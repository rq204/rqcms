<?php
/**
 * 基础函数库
 * @copyright (c) Emlog All Rights Reserved
 * @version emlog-3.5.0
 * $Id: func.base.php 1698 2010-05-03 03:57:40Z emloog@gmail.com $
 */

/**
 * 增加转义字符
 *
 */
function doStripslashes(){
	if (!get_magic_quotes_gpc()){
		$_GET = addslashesDeep($_GET);
		$_POST = addslashesDeep($_POST);
		$_COOKIE = addslashesDeep($_COOKIE);
		$_REQUEST = addslashesDeep($_REQUEST);
	}
}

/**
 * 递归增加转义字符
 *
 * @param unknown_type $value
 * @return unknown
 */
function addslashesDeep($value){
	$value = is_array($value) ? array_map('addslashesDeep', $value) : addslashes($value);
	return $value;
}

/**
 * 转换HTML代码函数
 *
 * @param unknown_type $content
 * @param unknown_type $wrap 是否换行
 * @return unknown
 */
function htmlClean($content, $wrap=true){
	$content = htmlspecialchars($content);
	if($wrap){
		$content = str_replace("\n", '<br>', $content);
	}
	$content = str_replace('  ', '&nbsp;&nbsp;', $content);
	$content = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $content);
	return $content;
}

/**
 * 获取用户ip地址
 *
 * @return string
 */
function getIp(){
	$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	if(!preg_match("/^\d+\.\d+\.\d+\.\d+$/", $ip)){
		$ip = '';
	}
	return $ip;
}

/**
 * 验证email地址格式
 *
 * @param unknown_type $email
 * @return unknown
 */
function checkMail($email){
	if (preg_match("/^[\w\.\-]+@\w+([\.\-]\w+)*\.\w+$/", $email) && strlen($email) <= 60){
		return true;
	} else {
		return false;
	}
}

/**
 * 截取编码为utf8的字符串
 *
 * @param string $strings 预处理字符串
 * @param int $start 开始处 eg:0
 * @param int $length 截取长度
 * @return unknown
 */
function subString($strings,$start,$length){
	$str = substr($strings, $start, $length);
	$char = 0;
	for ($i = 0; $i < strlen($str); $i++){
		if (ord($str[$i]) >= 128)
		$char++;
	}
	$str2 = substr($strings, $start, $length+1);
	$str3 = substr($strings, $start, $length+2);
	if ($char % 3 == 1){
		if ($length <= strlen($strings)){
			$str3 = $str3 .= '...';
		}
		return $str3;
	}
	if ($char%3 == 2){
		if ($length <= strlen($strings)){
			$str2 = $str2 .= '...';
		}
		return $str2;
	}
	if ($char%3 == 0){
		if ($length <= strlen($strings)){
			$str = $str .= '...';
		}
		return $str;
	}
}

/**
 * 转换附件大小单位
 *
 * @param string $fileSize 文件大小 kb
 * @return unknown
 */
function changeFileSize($fileSize){
	if($fileSize >= 1073741824){
		$fileSize = round($fileSize / 1073741824  ,2) . 'GB';
	} elseif($fileSize >= 1048576){
		$fileSize = round($fileSize / 1048576 ,2) . 'MB';
	} elseif($fileSize >= 1024){
		$fileSize = round($fileSize / 1024, 2) . 'KB';
	} else{
		$fileSize = $fileSize . '字节';
	}
	return $fileSize;
}

/**
 * 该函数在插件中调用,挂载插件函数到预留的钩子上
 *
 * @param string $hook
 * @param string $actionFunc
 * @return boolearn
 */
function addAction($hook, $actionFunc){
	global $hookArr;
	if (!isset($hookArr[$hook])||!in_array($actionFunc, $hookArr[$hook])){
		$hookArr[$hook][] = $actionFunc;
}
	return true;
}

/**
 * 执行挂在钩子上的函数,支持多参数 eg:doAction('post_comment', $author, $email, $url, $comment);
 *
 * @param string $hook
 */
function doAction($hook){
	global $hookArr;
	$args = array_slice(func_get_args(), 1);
	if (isset($hookArr[$hook])){
		foreach ($hookArr[$hook] as $function){
			$string = call_user_func_array($function, $args);
		}
	}
}

/**
 * 获取远程文件内容
 *
 * @param 文件http地址 $url
 * @return unknown
 */
function fopen_url($url){
	if (function_exists('file_get_contents')) {
		$file_content = @file_get_contents($url);
	} elseif (ini_get('allow_url_fopen') && ($file = @fopen($url, 'rb'))){
		$i = 0;
		while (!feof($file) && $i++ < 1000) {
			$file_content .= strtolower(fread($file, 4096));
		}
		fclose($file);
	} elseif (function_exists('curl_init')) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl_handle, CURLOPT_FAILONERROR,1);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Trackback Spam Check');
		$file_content = curl_exec($curl_handle);
		curl_close($curl_handle);
	} else {
		$file_content = '';
	}
	return $file_content;
}
/**
 * 时间转化函数
 *
 * @param $now
 * @param $datetemp
 * @param $dstr
 * @return string
 */
function smartDate($datetemp, $dstr='Y-m-d H:i'){
	global $utctimestamp, $timezone;
	$op = '';
	$sec = $utctimestamp - $datetemp;
	$hover = floor($sec / 3600);
	if ($hover == 0){
		$min = floor($sec / 60);
		if ( $min == 0) {
			$op = $sec.' 秒前';
		} else {
			$op = "$min 分钟前";
		}
	} elseif ($hover < 24){
		$op = "约 {$hover} 小时前";
	} else {
		$op = gmdate($dstr, $datetemp + $timezone * 3600);
	}
	return $op;
}

/**
 * 生成一个随机的字符串
 *
 * @param int $length
 * @param boolean $special_chars
 * @return string
 */
function getRandStr($length = 12, $special_chars = true){
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	if ( $special_chars ){
		$chars .= '!@#$%^&*()';
	}
	$randStr = '';
	for ( $i = 0; $i < $length; $i++ ){
		$randStr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $randStr;
}

/**
 * 寻找两数组所有不同元素
 *
 * @param array $array1
 * @param array $array2
 * @return array
 */
function findArray($array1,$array2){
    $r1 = array_diff($array1, $array2);
    $r2 = array_diff($array2, $array1);
    $r = array_merge($r1, $r2);
    return $r;
}


/**
 * 图片生成缩略图
 *
 * @param string $img 预缩略的图片
 * @param unknown_type $imgType 上传文件的类型 eg:image/jpeg
 * @param string $thumPatch 生成缩略图路径
 * @param int $max_w 缩略图最大宽度 px
 * @param int $max_h 缩略图最大高度 px
 * @return unknown
 */
function resizeImage($img, $imgType, $thumPatch, $max_w, $max_h){
	$size = chImageSize($img,$max_w,$max_h);
    $newwidth = $size['w'];
	$newheight = $size['h'];
	$w =$size['rc_w'];
	$h = $size['rc_h'];
	if ($w <= $max_w && $h <= $max_h){
		return false;
	}
	if ($imgType == 'image/pjpeg' || $imgType == 'image/jpeg'){
		if(function_exists('imagecreatefromjpeg')){
			$img = imagecreatefromjpeg($img);
		}else{
			return false;
		}
	} elseif ($imgType == 'image/x-png' || $imgType == 'image/png') {
		if (function_exists('imagecreatefrompng')){
			$img = imagecreatefrompng($img);
		}else{
			return false;
		}
	}
	if (function_exists('imagecopyresampled')){
		$newim = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresampled($newim, $img, 0, 0, 0, 0, $newwidth, $newheight, $w, $h);
	} else {
		$newim = imagecreate($newwidth, $newheight);
		imagecopyresized($newim, $img, 0, 0, 0, 0, $newwidth, $newheight, $w, $h);
	}
	if ($imgType == 'image/pjpeg' || $imgType == 'image/jpeg'){
		if(!imagejpeg($newim,$thumPatch)){
			return false;
		}
	} elseif ($imgType == 'image/x-png' || $imgType == 'image/png') {
		if (!imagepng($newim,$thumPatch)){
			return false;
		}
	}
	ImageDestroy ($newim);
	return true;
}

/**
 * 按照比例改变图片大小(非生成缩略图)
 *
 * @param string $img 图片路径
 * @param int $max_w 最大缩放宽
 * @param int $max_h 最大缩放高
 * @return unknown
 */
function chImageSize ($img,$max_w,$max_h){
	$size = @getimagesize($img);
	$w = $size[0];
	$h = $size[1];
	//计算缩放比例
	@$w_ratio = $max_w / $w;
	@$h_ratio =	$max_h / $h;
	//决定处理后的图片宽和高
	if( ($w <= $max_w) && ($h <= $max_h) ){
		$tn['w'] = $w;
		$tn['h'] = $h;
	} else if(($w_ratio * $h) < $max_h){
		$tn['h'] = ceil($w_ratio * $h);
		$tn['w'] = $max_w;
	} else {
		$tn['w'] = ceil($h_ratio * $w);
		$tn['h'] = $max_h;
	}
	$tn['rc_w'] = $w;
	$tn['rc_h'] = $h;
	return $tn ;
}

/**
 * 计算时区的时差
 * @param string $remote_tz 远程时区
 * @param string $origin_tz 标准时区
 *
 */
function getTimeZoneOffset($remote_tz, $origin_tz = 'UTC') {
    if($origin_tz === null) {
        if(!is_string($origin_tz = date_default_timezone_get())) {
            return false; // A UTC timestamp was returned -- bail out!
        }
    }
    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);
    $origin_dt = new DateTime('now', $origin_dtz);
    $remote_dt = new DateTime('now', $remote_dtz);
    $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
    return $offset;
}

/**
 * 显示调试信息
 * @param string $errno 错误号
 * @param string $errstr 出错信息
 * @param string $errfile 出错的文件
 * @param string $errline 出错的行
 *
 */
function debug($errno, $errstr, $errfile, $errline)
{
	switch ($errno) {
		case E_USER_ERROR:
			echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
			echo "  Fatal error on line $errline in file $errfile";
			echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			echo "Aborting...<br />\n";
			exit(1);
			break;

		case E_USER_WARNING:
			echo "<b>My WARNING</b> [$errno] $errstr on line $errline in file $errfile <br />\n";
			break;

		case E_USER_NOTICE:
			echo "<b>My NOTICE</b> [$errno] $errstr on line $errline in file $errfile<br />\n";
			break;

		case E_ERROR:
			echo "<b>PHP ERROR</b> [$errno] $errstr<br />\n";
			echo "  Fatal error on line $errline in file $errfile";
			echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			echo "Aborting...<br />\n";
			exit(1);
			break;
			
		case E_WARNING:
			echo "<b>PHP WARNING</b> [$errno] $errstr on line $errline in file $errfile<br />\n";
			break;
			
		default:
			echo "Unknown error type: [$errno] $errstr line:$errline in file $errfile<br />\n";
			break;
    }

    /* Don't execute PHP internal error handler */
    return true;	
}

// 连接多个ID
function implode_ids($array){
	$ids = $comma = '';
	if (is_array($array) && count($array)){
		foreach($array as $id) {
			$ids .= "$comma'".intval($id)."'";
			$comma = ', ';
		}
	}
	return $ids;
}

/**
 * 获取文件后缀
 * @param string $fileName
 */
function getFileSuffix($fileName) { 
	return strtolower(substr(strrchr($fileName, "."),1));
}

function getGetArr($string)
{
	$getArr=array();
	if(strpos($string,'?')>1)
	{
		$string=explode('?',$string);
		foreach(explode('&',$string[1]) as $tget)
		{
			$gets=explode('=',$tget);
			$getArr[$gets[0]]=isset($gets[1])?$gets[1]:'';
		}
	}
	return $getArr;
}

//获取最下级的分类id
function getChildCate($cateid,$cateArr)
{
	$has=array();
	$has[]=$cateid;
	foreach($cateArr as $key => $cate)
	{
		if($cate['pid']==$cateid)
		{
			$has[]=getChildCate($cate['cid'],$cateArr);
		}
	}
	return implode(',',$has);
}
