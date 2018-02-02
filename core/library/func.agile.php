<?php
/**
 * Seo相关的，如Url处理，缓存等
 */

/**
 * 将数组写入缓存文件
 *
 * @param string $cacheFile 要保存的文件路径
 * @param array $cacheArray 需要保存的数组
 * @return false or strlen
 */
function writeCache($cacheFile,$cacheArray)
{
	if(!is_array($cacheArray)) return false;
	$array = "<?php\nreturn ".var_export($cacheArray, true).";\n?>";
	$wirteFile = RQ_DATA.'/caches/'.$cacheFile.'.php';
	$strlen = file_put_contents($wirteFile, $array);
	@chmod($wirteFile, 0777);
	return $strlen;
}

function message($msg,$returnurl='')
{
	global $theme,$host;
	if(!$returnurl) $returnurl='http://'.$host['host'];
	include RQ_DATA."/themes/$theme/message.php";
	exit();
}

///缓存处理
function cacheControl($lastmodified,$ETag='')
{
	$lastmodified=strtotime($lastmodified);
	$lastmodified=gmdate('D, d M Y H:i:s',$lastmodified).' GMT';
	if(array_key_exists('HTTP_IF_MODIFIED_SINCE',$_SERVER))
	{
		if($_SERVER['HTTP_IF_MODIFIED_SINCE']==$lastmodified)
		{
			header('HTTP/1.0 304 Not Modified');
			exit;
		}
	}
	else if(array_key_exists('HTTP_IF_NONE_MATCH',$_SERVER)&&$Etag)
	{
		if($_SERVER['HTTP_IF_NONE_MATCH']==$ETag)
		{
			header('HTTP/1.0 304 Not Modified');
			exit;
		}
	}
	else
	{
		header("Cache-Control: max-age=259200");
		header("Last-Modified: ".$lastmodified); //Fri, 31 Oct 2008 02:14:04 GMT
		if($ETag) header("ETag: $ETag");
	}
}

function run404()
{
	global $theme;
	header('HTTP/1.1 404 Not Found');
	header('Content-Type: text/html; charset=UTF-8');
	include RQ_DATA.'/themes/'.$theme.'/404.php';
	ob_flush();
	exit;
}

//显示头部增加的信息
function viewhead()
{
	global $headArr;
	if($headArr)
	{
		$data=implode("\r\n",$headArr);
		echo $data,"\r\n";
	}
}

//显示尾部增加的信息
function viewfoot()
{
	global $footArr;
	if($footArr)
	{
		$data=implode("\r\n",$footArr);
		echo $data,"\r\n";
	}
}