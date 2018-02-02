<?php
if(!$action) $action = 'template';
$refile=$admin_url.'?file=template&action=template';
//读取模板套系(目录)
$template_dir = RQ_DATA.'/themes/';

switch($action)
{
	//设置模板
	case 'settemplate':
		$name = $_GET['name'];
		if (file_exists($template_dir.$name) && strpos($name,'..')===false) 
		{
			$themetype='theme';
			$DB->query("replace into {$dbprefix}option (`name`,`value`) values ('theme','$name')");
			setting_recache();
			redirect("{$themename}模板已经更新", $refile);
		} 
		else 
		{
			redirect('模板不存在',$refile);
		}
		break;
	default:
		$current_infofile = $theme.'/info.txt';
		if (file_exists($template_dir.$current_infofile)) {
			$current_template_info = get_template_info($current_infofile);
		} else {
		$current_template_info['name']= $current_template_info['author']=$current_template_info['version']=$current_template_info['description']=$current_template_info['templatedir']= '';
		}

		$dir1 = opendir($template_dir);
		$available_template_db = array();
		while($file1 = readdir($dir1)){
			if ($file1 != '' && $file1 != '.' && $file1 != '..'){
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

//获取模板信息
function get_template_info($infofile) {
	global $template_dir,$datadir;
	$themedir=dirname($infofile);
	$infofile = str_replace(array('..',':/'),array('',''),$infofile);
	$template_info = @file($template_dir.$infofile);
	if ($template_info) {
		$cssdata = array();
		foreach ($template_info AS $data) {
			$data = str_replace('://','=//',$data);
			$info = explode(':', $data);
			$info[1] = trim(str_replace('=//','://',$info[1]));
			$cssdata[] = $info[1];
		}
		//判断制作者是否有网站
		if ($cssdata[4]) {
			$cssdata[3] = '<a href="'.trim($cssdata[4]).'" title="访问模板作者的网站" target="_blank">'.trim($cssdata[3]).'</a>';
		}
		//判断缩略图是否存在
		$templatedir=$datadir.'/themes/'.$themedir;
		if (file_exists($template_dir.$themedir.'/screenshot.png')) {
			$screenshot = $templatedir.'/screenshot.png';
		} else {
			$screenshot = $datadir.'/themes/no.png';
		}
		$info = array(
			'name' => $cssdata[0],
			'dirurl' => urlencode($themedir),
			'version' => $cssdata[1],
			'description' => $cssdata[2],
			'author' => $cssdata[3],
			'templatedir' => $templatedir,
			'screenshot' => $screenshot
		);

		return $info;
	} else {
		return false;
	}
}

//复制目录
function copydir($source, $target) {
	if (substr($source, -1) != '/') {
		$source = $source.'/';
	}
	if (substr($target, -1) != '/') {
		$target = $target.'/';
	}
	if (!@mkdir($target, 0777)) {
		return false;
	} else {
		@chmod($target, 0777);
	}
	$result = true;
	$handle = @opendir($source);
	while(($file = @readdir($handle)) !== false) {
		if($file != '.' && $file != '..') {
			if(@is_dir($source.$file)) {
				copydir($source.$file, $target.$file);
			} else {
				if(!@copy($source.$file, $target.$file)) {
					$result = false;
					break;
				}
			}
		}
	}

//删除目录
function removedir($dirname){
	$result = false;
	if (substr($dirname, -1) != '/') {
		$dirname = $dirname.'/';
	}
	$handle = @opendir($dirname);
	while(($file = @readdir($handle)) !== false) {
		$delfile = $dirname.$file;
		if ($file != '.' && $file != '..') {
			if(@is_dir($delfile)) { 
				@chmod($delfile,0777);
				removedir($delfile);
			} else {
				@chmod($delfile,0777);
				@unlink($delfile);
			}
		}
	}
	@closedir($handle);
	@chmod($dirname,0777);
	@rmdir($dirname);
}
	@closedir($handle);
	return $result;
}
