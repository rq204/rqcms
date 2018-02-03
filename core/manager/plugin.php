<?php
//先查询数据库中的插件
$pluginsquery=$DB->query("Select * from {$dbprefix}plugin");
$plugindb=array();
while($arr=$DB->fetch_array($pluginsquery))
{
	$pluginfile=RQ_DATA.'/plugins/'.$arr['file'].'/'.$arr['file'].'.php';
	if(file_exists($pluginfile))
	{
		$plugindb[$arr['file']]=$arr;
	}
	else $DB->query("update {$dbprefix}plugin set `active`=0 where  file='".$arr['file']."'");
}

//遍历目录
$pluginfile=getPlugins();
$needrecache=false;
foreach($pluginfile as $filename=>$fileinfo)
{
	if(!isset($plugindb[$filename])){
		$pluginarr=$DB->fetch_first("Select * from `{$dbprefix}plugin` where `file`='$filename'");
		if(empty($pluginarr)){
		$DB->query("Insert into `{$dbprefix}plugin` (`file`,`name`,`author`,`version`,`description`,`url`,`active`) values ('$filename','$fileinfo[name]','$fileinfo[author]','$fileinfo[version]','$fileinfo[description]','$fileinfo[url]','0')");
		$fileinfo['active']=0;
		$fileinfo['pid']=$DB->insert_id();
		}else{
		$fileinfo=$pluginarr;
		}
		$plugindb[$filename]=$fileinfo;
		$needrecache=true;
	}
}

if($needrecache) setting_recache();
$curentPlugin=isset($_GET['plugin'])?$_GET['plugin']:(isset($_POST['plugin'])?$_POST['plugin']:'');//当前设置的插件
if(!isset($plugindb[$curentPlugin])) $curentPlugin='';

if(RQ_POST)
{
	if($action=='setting')
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

	if($action=='active')
	{
		$active=$_GET['active'];
		$active=$active?'0':'1';
		$stats=$active?'启用':'禁用';
		$pid=$_GET['pid'];
		$DB->query("update {$dbprefix}plugin set active=$active where pid=$pid");
		setting_recache();
		redirect("插件状态更新为$stats",$admin_url.'?file=plugin');
	}
	else if($action=='setting')
	{
		if($curentPlugin)
		{
			$plugin_setting_file=RQ_DATA."/plugins/{$curentPlugin}/{$curentPlugin}_setting.php";
			if(file_exists($plugin_setting_file)) include $plugin_setting_file;
			else redirect("不存在的插件文件",$admin_url.'?file=plugin');
		}
		else
		{
			redirect("不存在的插件",$admin_url.'?file=plugin');
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
