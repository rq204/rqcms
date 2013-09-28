<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$tempView=$coreView;//不用再去加载模板了
$coredir=basename(RQ_CORE);//core目录
$datadir=basename(RQ_DATA);//data目录
$cssfile ='admin.php?file=css';//管理后台的css文件
$viewdir=$coredir.'/manager/view/';
$incfile=!empty($_GET['file'])?$_GET['file']:'main';
$do=isset($_POST['do'])?$_POST['do']:'';
if(!$do) $do=isset($_GET['do'])?$_GET['do']:'';
$action=!empty($_GET['action'])?$_GET['action']:(!empty($_POST['action'])?$_POST['action']:'');
$cssdir='/'.$coredir.'/manager/view/images/';
$editordir='/'.$coredir.'/manager/editor/';
$page=isset($_GET['page'])?intval($_GET['page']):'';
if($incfile!='css'&&$groupid<2)  $incfile='login';

//加载一些类
include RQ_CORE.'/library/func.image.php';
// 操作提示页面

function redirect($msg, $url = 'javascript:history.go(-1);', $min='2')
{
	global $cssdir,$cssfile;
	ob_end_clean();
	ob_start();
	include RQ_CORE.'/manager/view/redirect.php';
	$output=ob_get_contents();
	$output=adminRewrite($output);
	@ob_end_clean();
	exit($output);
}

$adminitem=array();
switch($groupid)
{
	case 2:
		$adminitem = array(
		'article' => '文章管理',
	);
	break;
	case 3:
		$adminitem = array(
		'configurate' => '系统设置',
		'article' => '文章管理',
		'comment' => '评论管理',
		'attachment' => '附件管理',
		'category' => '分类管理',
		'user' => '用户管理',
		'template' => '模板管理',
		'link' => '友情链接',
		'seo'=>'网站优化',
		'maintenance' => '系统维护',
	);
	break;
	case 4:
		$adminitem = array(
		'configurate' => '系统设置',
		'article' => '文章管理',
		'comment' => '评论管理',
		'attachment' => '附件管理',
		'category' => '分类管理',
		'user' => '用户管理',
		'template' => '模板管理',
		'link' => '友情链接',
		'seo'=>'网站优化',
		'plugin'=>'插件管理',
		'maintenance' => '系统维护' //这里要添加缓存更新和日志管理功能
		);
	break;
}

$other=array('css','login','special','main','xmlrpc','database','upload');

doAction('change_admin_item');

if(!in_array($incfile,$other)&&!array_key_exists($incfile,$adminitem)) redirect('未定义操作','admin.php?file=main');
if($groupid!=4&&$incfile=='special') redirect('您无权限访问多站点设置','admin.php?file=main');
if($groupid<3&&$incfile=='tag') redirect('您无权限编辑tag设置','admin.php?file=main');
if($groupid!=4&&$incfile=='database') redirect('您无权限操作数据库设置','admin.php?file=main');

$onlines=array();//在线后台用户
if($incfile!='css') include RQ_CORE.'/manager/view/header.php';
include RQ_CORE.'/manager/'.$incfile.'.php';
include RQ_CORE.'/manager/view/'.$incfile.'.php';
if($incfile!='css') include RQ_CORE.'/manager/view/footer.php';

// 检查链接URL是否符合逻辑
function checkurl($url,$allownull=1) 
{
	if($url) 
	{
		if (!preg_match("#^(http|news|https|ftp|ed2k|rtsp|mms)://#", $url)) 
		{
			$result .= '网站URL错误.<br />';
			return $result;
		}
		$key = array("\\",' ',"'",'"','*',',','<','>',"\r","\t","\n",'(',')','+',';');
		foreach($key as $value)
		{
			if (strpos($url,$value) !== false){ 
				$result .= '网站URL错误.<br />';
				return $result;
			}
		}
	} else {
		if (!$allownull) {
			$result .= '网站URL不允许为空.<br />';
			return $result;
		}
	}
}

//转换字符
function char_cv($string) {
	$string = htmlspecialchars(addslashes($string));
	return $string;
}


// 分页函数

function multi($num, $perpage, $curpage, $mpurl) {
	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	if($num > $perpage) {
		$page = 10;
		$offset = 5;
		$pages = @ceil($num / $perpage);
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $curpage + $page - $offset - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $curpage - $pages + $to;
				$to = $pages;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$from = $pages - $page + 1;
				}
			}
		}

		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1">第一页</a> ' : '').($curpage > 1 ? '<a href="'.$mpurl.'page='.($curpage - 1).'">上一页</a> ' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? $i.' ' : '<a href="'.$mpurl.'page='.$i.'">['.$i.']</a> ';
		}
		$multipage .= ($curpage < $pages ? '<a href="'.$mpurl.'page='.($curpage + 1).'">下一页</a>' : '').($to < $pages ? ' <a href="'.$mpurl.'page='.$pages.'">最后一页</a>' : '');
		$multipage = $multipage ? '页: '.$multipage : '';
	}
	return $multipage;
}

//目录的实际大小
function dirsize($dir) { 
	$dh = @opendir($dir);
	$size = 0;
	while($file = @readdir($dh)) {
		if ($file != '.' && $file != '..') {
			$path = $dir.'/'.$file;
			if (@is_dir($path)) {
				$size += dirsize($path);
			} else {
				$size += @filesize($path);
			}
		}
	}
	@closedir($dh);
	return $size;
}

//目录个数
function dircount($dir) { 
	$dh = @opendir($dir);
	$count = 0;
	while($file = @readdir($dh)) {
		if ($file != '.' && $file != '..') {
			$path = $dir.'/'.$file;
			if (@is_dir($path)) {
				$count++;
			}
		}
	}
	@closedir($dh);
	return $count;
}


function sizecount($filesize) {

	if($filesize >= 1073741824) {

		$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';

	} elseif($filesize >= 1048576) {

		$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';

	} elseif($filesize >= 1024) {

		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';

	} else {

		$filesize = $filesize . ' Byte';

	}
	return $filesize;
}

/**
 * 解压zip
 */
function rqUnZip ($zipfile, $path, $type = 'tpl') {
	if(class_exists('ZipArchive', FALSE)) {
		$zip = new ZipArchive();
		if (@$zip->open($zipfile) === TRUE) {
			$r = explode('/', $zip->getNameIndex(0), 2);
			$dir = isset($r[0]) ? $r[0].'/' : '';
			switch ($type) {
				case 'tpl':
					$re = $zip->getFromName($dir.'header.php');
					if (false === $re)
					return -2;
					break;
				case 'plugin':
					$plugin_name = substr($dir, 0, -1);
					$re = $zip->getFromName($dir.$plugin_name.'.php');
					if (false === $re)
						return '安装失败，插件安装包不符合标准';
					break;
			}

			if (true === $zip->extractTo($path)) {
				$zip->close();
				return true;
			} else {
				return '只支持zip压缩格式的插件包';
			}
		} else {
			return '只支持zip压缩格式的插件包';
		}
	} else {
		return "空间不支持zip模块，请按照提示手动安装插件";
	}
}