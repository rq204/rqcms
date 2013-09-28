<?php
//先查询数据库中的插件
$pluginsquery=$DB->query("Select * from ".DB_PREFIX."plugin where hostid='$hostid'");
$plugindb=array();
while($arr=$DB->fetch_array($pluginsquery))
{
	$pluginfile=RQ_DATA.'/plugins/'.$arr['file'].'/'.$arr['file'].'.php';
	$settingfile=RQ_DATA.'/plugins/'.$arr['file'].'/'.$arr['file'].'_setting.php';
	if(file_exists($pluginfile)&&file_exists($settingfile))
	{
		$plugindb[$arr['file']]=$arr;
	}
	else $DB->query('update '.DB_PREFIX."plugin set `active`=0 where hostid=$hostid and file='".$arr['file']."'");
}

//遍历目录
$pluginfile=getPlugins();
$needrecache=false;
foreach($pluginfile as $filename=>$fileinfo)
{
	if(!isset($plugindb[$filename])){
		$pluginarr=$DB->fetch_first('Select * from `'.DB_PREFIX."plugin` where `file`='$filename' and hostid=$hostid");
		if(empty($pluginarr)){
		$DB->query("Insert into `".DB_PREFIX."plugin` (`hostid`,`file`,`name`,`author`,`version`,`description`,`url`,`active`) values ('$hostid','$filename','$fileinfo[name]','$fileinfo[author]','$fileinfo[version]','$fileinfo[description]','$fileinfo[url]','0')");
		$fileinfo['active']=0;
		$fileinfo['pid']=$DB->insert_id();
		}else{
		$fileinfo=$pluginarr;
		}
		$plugindb[$filename]=$fileinfo;
		$needrecache=true;
	}
}

if($needrecache) plugins_recache();
$curentPlugin=isset($_GET['plugin'])?$_GET['plugin']:(isset($_POST['plugin'])?$_POST['plugin']:'');//当前设置的插件
if(!isset($plugindb[$curentPlugin])) $curentPlugin='';

if(RQ_POST)
{
	if($action=='upload')
	{//上传安装的
		$url='admin.php?file=plugin&action=install';
		$zipfile = isset($_FILES['pluzip']) ? $_FILES['pluzip'] : '';
		if (empty($zipfile)||$zipfile['error'] == 4){
			redirect('请选择一个zip插件安装包',$url);
		}
		if (!$zipfile || $zipfile['error'] >= 1 || empty($zipfile['tmp_name'])){
			redirect('插件上传失败',$url);
		}
		if (getFileSuffix($zipfile['name']) != 'zip') {
			redirect('只支持zip压缩格式的插件包',$url);
		}

		$ret = rqUnZip($zipfile['tmp_name'], RQ_DATA.'/plugins/', 'plugin');
		if($ret==true){
		redirect('插件安装成功','admin.php?file=plugin');
		}
		else redirect($ret,$url);
	}
	else if($action=='setting')
	{//插件的保存设置
		if($curentPlugin)
		{
			include RQ_DATA."/plugins/{$curentPlugin}/{$curentPlugin}_setting.php";
			doAction('admin_plugin_setting_save');
			exit;
		}
	}
}
else
{
	if(empty($action)) $action='list';

	if($action=='active'){
	$active=$_GET['active'];
	$active=$active?'0':'1';
	$pid=$_GET['pid'];
	$DB->query('update '.DB_PREFIX."plugin set active=$active where hostid=$hostid and pid=$pid");
	plugins_recache();
	redirect("插件状态更新成功",'admin.php?file=plugin');
	}
	else if($action=='delete')
	{
		if($groupid!=4) redirect("您无权删除插件",'admin.php?file=plugin');
		$pid=$_GET['pid'];
		$arr=$DB->fetch_first('select * from '.DB_PREFIX."plugin where hostid=$hostid and pid=$pid");
		if(!empty($arr))
		{
			$DB->query('delete from '.DB_PREFIX."plugin where hostid=$hostid and pid=$pid");
			if(rmdir(RQ_DATA.'/plugins/'.$arr['file'])){
			plugins_recache();
			redirect("插件删除成功",'admin.php?file=plugin');
			}
			redirect("删除删除失败",'admin.php?file=plugin');
		}
	}
	else if($action=='setting')
	{
		if($curentPlugin)
		{
			include RQ_DATA."/plugins/{$curentPlugin}/{$curentPlugin}_setting.php";
		}
		else
		{
			redirect("不存在的插件",'admin.php?file=plugin');
		}
	}
}

//插件菜单
$pluginitem=array();
doAction('admin_plugin_add_item');

/**
 * 获取所有插件列表，未定义插件名称的插件将不予获取
 * 插件目录：content\plugins
 * 仅识别 插件目录/插件/插件.php 目录结构的插件
 * @return array
 */
function getPlugins() {
	$emPlugins = array();
	$pluginFiles = array();
	$pluginPath = RQ_DATA . '/plugins';
	$pluginDir = @ dir($pluginPath);
	if ($pluginDir){
		while(($file = $pluginDir->read()) !== false){
			if (preg_match('|^\.+$|', $file)){
				continue;
			}
			if (is_dir($pluginPath . '/' . $file)){
				$pluginsSubDir = @ dir($pluginPath . '/' . $file);
				if ($pluginsSubDir){
					while(($subFile = $pluginsSubDir->read()) !== false){
						if (preg_match('|^\.+$|', $subFile)){
							continue;
						}
						if ($subFile == $file.'.php'){
							$pluginFiles[] = "$file/$subFile";
						}
					}
				}
			}
		}
	}
	if (!$pluginDir || !$pluginFiles){
		return $emPlugins;
	}
	sort($pluginFiles);
	foreach($pluginFiles as $pluginFile){
		$pluginData = getPluginData("$pluginPath/$pluginFile");
		if (empty($pluginData['name'])){
			continue;
		}
		$pfilename=explode(".",basename($pluginFile));
		$emPlugins[$pfilename[0]] = $pluginData;
	}
	return $emPlugins;
}

/**
 * 获取插件信息
 *
 * @param string $pluginFile
 * @return array
 */
function getPluginData($pluginFile) {
	$pluginData = implode('', file($pluginFile));
	preg_match("/Plugin Name:(.*)/i", $pluginData, $plugin_name);
	preg_match("/Version:(.*)/i", $pluginData, $version);
	preg_match("/Description:(.*)/i", $pluginData, $description);
	preg_match("/Author:(.*)/i", $pluginData, $author_name);
	preg_match("/Author URL:(.*)/i", $pluginData, $author_url);

	$plugin_name = isset($plugin_name[1]) ? trim($plugin_name[1]) : '';
	$version = isset($version[1]) ? $version[1] : '';
	$description = isset($description[1]) ? $description[1] : '';
	$plugin_url = isset($plugin_url[1]) ? trim($plugin_url[1]) : '';
	$author = isset($author_name[1]) ? trim($author_name[1]) : '';
	$author_url = isset($author_url[1]) ? trim($author_url[1]) : '';

	return array(
	'name' => $plugin_name,
	'version' => $version,
	'description' => $description,
	'author' => $author,
	'url' => $author_url,
	);
}

function dataFrom($plugin)
{
	$pluginName=isset($_GET['plugin'])?$_GET['plugin']:'';
	if(!$pluginName) $pluginName=isset($_POST['plugin'])?$_POST['plugin']:'';
	if($plugin==$pluginName) return true;
	return false;
}
