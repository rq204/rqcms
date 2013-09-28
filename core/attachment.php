<?php
if(!defined('RQ_ROOT')) exit('Access Denied');

if ($host['attachments_remote_open']&&!$host['attach_display']) //禁止从非本站下载
{	
	if(strpos($refer_url,RQ_HTTP.RQ_HOST)!=0) message('附件禁止从地址栏直接输入或从其他站点链接访问', './');
}

//在检查下载id前的处理
doAction('attachment_before_checkaid');

// 查询文章

$aid = intval($_GET['url']);
if (!$aid)
{
	message('缺少附件参数', './');
} 
else 
{
	$attachinfo = $DB->fetch_first("select * from ".DB_PREFIX."attachment where aid='$aid' and hostid=$hostid");
	if (!$attachinfo)
	{
		message('附件不存在', '/');
	}
	else
	{
		$DB->unbuffered_query("UPDATE ".DB_PREFIX."attachment SET downloads=downloads+1 WHERE aid='$aid'");
	}
}

//验证下载验证
$downid='dk'.$aid;
$sendkey=isset($_COOKIE[$downid])?$_COOKIE[$downid]:'';
$downkey=md5(md5(DB_USER.$aid));
$downkey=substr($downkey,0,4);

/*
@文件下载
*/

if($host['attach_display']&&($sendkey!=$downkey||$refer_url!=$page_url)&&!$attachinfo['isimage'])//显示下载页面后再下载
{
	$title=$attachinfo['filename'].' 下载';
	setcookie($downid,$downkey,$timestamp+3600);
}
else
{
	$filepath = RQ_DATA.'/files/'.$attachinfo['filepath'];
	$filepath=str_replace('//','/',$filepath);

	$attachment = $attachinfo['isimage'] ? 'inline' : 'attachment';
	$attachinfo['filetype'] = $attachinfo['filetype'] ? $attachinfo['filetype'] : 'unknown/unknown';

	doAction('attachment_before_download');

	if(is_readable($filepath)) 
	{
		ob_end_clean();
		$ua = $_SERVER["HTTP_USER_AGENT"];
		$filename=$attachinfo['filename'];
		$encoded_filename = urlencode($filename);
		$encoded_filename = str_replace("+", "%20", $encoded_filename);
		//参考 http://www.fising.cn/2012/05/php-%E6%8F%90%E4%BE%9B%E6%96%87%E4%BB%B6%E4%B8%8B%E8%BD%BD%E4%B8%AD%E6%96%87%E6%96%87%E4%BB%B6%E5%90%8D.shtml
		if (preg_match("/MSIE/", $ua)) {
		 header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
		} else if (preg_match("/Firefox/", $ua)) {
		 header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
		} else {
		 header('Content-Disposition: attachment; filename="' . $filename . '"');
		} 
		
		header('Cache-control: max-age=31536000');
		header('Expires: ' . gmdate('D, d M Y H:i:s',$timestamp+31536000) . ' GMT');
		header('Content-Encoding: none');
		header('Content-type: '.$attachinfo['filetype']);
		header('Content-Length: '.filesize($filepath));
		$fp = fopen($filepath, 'rb'); 
		fpassthru($fp);
		fclose($fp);
		exit;
	}
	else 
	{
		message('读取附件失败', '/');
	}
}
?>