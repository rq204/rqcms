<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
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
		//print_r($info);exit;
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

