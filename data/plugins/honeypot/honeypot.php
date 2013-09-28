<?php
/*
Plugin Name: 简易蜜罐系统
Version: 1.0
Description: 该插件可以使黑客无法判断网站程序。
Author: RQ204
Author URL: http://www.rqcms.com
*/

!defined('RQ_DATA') && exit('access deined!');

function honeypot_404_before_output()
{
	$ziparr=array('zip','rar','mdb','db','asa','bak','exe');
	$head=$_SERVER['REQUEST_METHOD']=='HEAD';
	foreach($ziparr as $ext)
	{
		if(endsWith(REQUEST_URI,'.'.$ext))
		{
			if($head)
			{
				header('Content-Type: application/'.$ext);
				header('HTTP/1.0 200 OK');
			}
			else
			{
				header("HTTP/1.1 302 Moved Permanently");
				header("Location: http://www.rqcms.com/rqcms.zip");
			}
			exit;
		}
	}

	$filearr=array('upload','ewebeditor','editor','login','upfile','config','datebase','version.php','blog-space-uid-');
	foreach($filearr as $file)
	{
		if(strpos(REQUEST_URI,$file)!==false)
		{
			if($head)
			{
				header('Content-Type: text/html'); 
				header('HTTP/1.0 200 OK');
			}
			else
			{
			header('Content-Type: text/html; charset=UTF-8'); 
			print <<<EOT
<style type="text/css">
input {font:11px Verdana;BACKGROUND: #FFFFFF;height: 18px;border: 1px solid #666666;}
</style>
<form method="POST" action="">
<span style="font:11px Verdana;">Password: </span><input name="password" type="password" size="20" value='www.rqcms.com'>
<input type="hidden" name="action" value="login">
<input type="submit" value="Login">
</form>
EOT;
			}
			exit;
		}
	}
	
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}


addAction('404_before_output','honeypot_404_before_output');
