<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
if(!$action) $action = 'template';
include RQ_CORE.'/include/template.php';
$refile='admin.php?file=template&action=template';
//读取模板套系(目录)
$template_dir = RQ_DATA.'/themes/';

if(RQ_POST)
{
	switch($action)
	{
		case 'addstylevar':
			//添加自定义模板变量
			$title = strtolower(addslashes($_POST['title']));
			$value = addslashes($_POST['value']);
			if (!$title || !$value) {
				redirect('请填写完整');
			}
			$query = $DB->query("SELECT COUNT(*) FROM ".DB_PREFIX."var WHERE title='$title' and `hostid`='$hostid'");
			if($DB->result($query, 0)) {
				redirect('变量名已经存在,请返回修改');
			} elseif(!preg_match("/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/", $title)) {
				redirect('变量名称不合法,请返回修改');
			}
			$DB->query("INSERT INTO ".DB_PREFIX."var (hostid,title, value) VALUES ('$hostid','$title', '$value')");
			vars_recache();
			redirect('自定义变量添加成功','admin.php?file=template&action=stylevar');
			break;
		case 'domorestylevar':
			//批量处理自定义模板变量
			if($ids = implode_ids($_POST['delete'])) {
				$DB->query("DELETE FROM	".DB_PREFIX."var WHERE vid IN ($ids) and hostid='$hostid'");
			}
			if(is_array($_POST['stylevar'])) {
				foreach($_POST['stylevar'] as $stylevarid => $value) {
					$DB->unbuffered_query("UPDATE ".DB_PREFIX."var SET value='".addslashes(trim($_POST['stylevar'][$stylevarid]))."', visible='".intval($_POST['visible'][$stylevarid])."' WHERE vid='".intval($stylevarid)."' and hostid='$hostid'");
				}
			}
			vars_recache();
			redirect('自定义模板变量已成功更新', 'admin.php?file=template&action=stylevar');
			break;
		default:
			redirect('未定义操作', $refile);
	}
}
else
{
	switch($action)
	{
		//设置模板
		case 'settemplate':
			$name = $_GET['name'];
			if (file_exists($template_dir.$name) && strpos($name,'..')===false) 
			{
				$DB->query("update ".DB_PREFIX."host set theme='$name' where hid='$hostid'");
				hosts_recache();
				redirect('模板已经更新', $refile);
			} 
			else 
			{
				redirect('模板不存在',$refile);
			}
			break;
		//自定义模板变量
		case 'stylevar':
			if($page) {
				$start_limit = ($page - 1) * 30;
			} else {
				$start_limit = 0;
				$page = 1;
			}
			$total = $DB->num_rows($DB->query("SELECT vid FROM ".DB_PREFIX."var where hostid='$hostid' "));
			$multipage = multi($total, 30, $page, 'admin.php?file=template&action=stylevar');
			$query = $DB->query("SELECT * FROM ".DB_PREFIX."var where hostid='$hostid'  ORDER BY vid DESC LIMIT $start_limit, 30");

			$stylevardb = array();
			while ($stylevar = $DB->fetch_array($query)) {
				if ($stylevar['visible']) {
					$stylevar['visible'] = '<option value="1" selected>启用</option><option value="0">禁用</option>';
				} else {
					$stylevar['visible'] = '<option value="1">启用</option><option value="0" selected>禁用</option>';
				}
				$stylevardb[] = $stylevar;
			}
			unset($stylevar);
			$DB->free_result($query);
			$subnav = '自定义模板变量管理';
			break;
		default:
			$current_infofile = $theme.'/info.txt';
			if (file_exists($template_dir.$current_infofile)) {
				$current_template_info = get_template_info($current_infofile);
			} else {
				$current_template_info = '';
			}
			$dir1 = opendir($template_dir);
			$available_template_db = array();
			while($file1 = readdir($dir1)){
				if ($file1 != '' && $file1 != '.' && $file1 != '..' && $file1 != 'admin' && $file1 != $theme){
					if (is_dir($template_dir.'/'.$file1)){
						$dir2 = opendir($template_dir.'/'.$file1);
						while($file2 = readdir($dir2)){
							if (is_file($template_dir.'/'.$file1.'/'.$file2) && $file2 == 'info.txt'){
								$available_template_db[] = get_template_info($file1.'/'.$file2);
							}
						}
						closedir($dir2);
					}
				}
			}
			closedir($dir1);
			unset($file1);
			$subnav = '选择模板';
	}


	

			
	
	// $path = isset($_GET['path']) ? $_GET['path'] : (isset($_POST['path'])?$_POST['path']:'');
	// $file = isset($_GET['file']) ? $_GET['file'] : (isset($_POST['file'])?$_POST['file']:'');
	// $ext = isset($_GET['ext']) ? $_GET['ext'] : (isset($_POST['ext'])?$_POST['ext']:'');

	// $opened = @opendir($template_dir);
	// $dirdb = array();
	// while($dir = @readdir($opened)){
		// if(($dir != '.') && ($dir != '..')) {
			// if (@is_dir($template_dir.$dir)){
				// $dirdb[] = $dir;
			// }
		// }
	// }
	// asort($dirdb);
	// unset($dir);
	// @closedir($opened);
	// $path = in_array($path,$dirdb) ? $path : 'default';
	// if (strstr($file,'.') || strstr($path,'.')) {
		// redirect('模板无效', 'admin.php?file=template&action=filelist');
	// }
}