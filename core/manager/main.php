<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
function get_real_size($size) 
{
	$kb = 1024; // Kilobyte
	$mb = 1024 * $kb; // Megabyte
	$gb = 1024 * $mb; // Gigabyte
	$tb = 1024 * $gb; // Terabyte
	if ($size < $kb) {
		return $size . ' Byte';
	} else if ($size < $mb) {
		return round ( $size / $kb, 2 ) . ' KB';
	} else if ($size < $gb) {
		return round ( $size / $mb, 2 ) . ' MB';
	} else if ($size < $tb) {
		return round ( $size / $gb, 2 ) . ' GB';
	} else {
		return round ( $size / $tb, 2 ) . ' TB';
	}
}

function getphpcfg($varArrname) 
{
	switch ($result = get_cfg_var ($varArrname)) {
		case 0 :
			return '关闭';
			break;
		case 1 :
			return '打开';
			break;
		default :
			return $result;
			break;
	}
}
// 返回GD函数版本号
function gd_version()
{
	if (function_exists ( 'gd_info' )) {
		$GDArray = gd_info ();
		$gd_version_number = $GDArray ['GD Version'] ? $GDArray ['GD Version'] : 0;
		unset ( $GDArray );
	} else {
		$gd_version_number = 0;
	}
	return $gd_version_number;
}
function getfun($funName) {
	return (function_exists ( $funName )) ? '支持' : '不支持';
}
if (@ini_get ( 'file_uploads' )) {
	$fileupload = '允许 ' . ini_get ('upload_max_filesize');
} else {
	$fileupload = '<font color="red">禁止</font>';
}
$globals = getphpcfg ('register_globals' );
$safemode = getphpcfg ('safe_mode');
$gd_version = gd_version ();
$gd_version = $gd_version ? '版本:' . $gd_version : '不支持';
// //查询数据信息
$server ['datetime'] = date ( 'Y-m-d H:i:s', time () );
$server ['software'] = $_SERVER ['SERVER_SOFTWARE'];
$server['mysql']=$DB->version();
if (function_exists ( 'memory_get_usage' )) {
	$server ['memory_info'] = get_real_size ( memory_get_usage () );
}
$aarr=$DB->fetch_first("SELECT count(*) FROM ".DB_PREFIX."article WHERE hostid=$hostid");
$atarr=$DB->fetch_first("SELECT count(*) FROM ".DB_PREFIX."attachment WHERE hostid=$hostid");
$carr=$DB->fetch_first("SELECT count(*) FROM ".DB_PREFIX."comment WHERE hostid=$hostid");

$server['article']=$aarr['count(*)'];
$server['attach']=$atarr['count(*)'];
$server['comment']= $carr['count(*)'];
?>