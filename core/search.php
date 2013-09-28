<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$searchd = isset($_POST['keywords'])?$_POST['keywords']:'';
if(!$searchd&&isset($_GET['url'])) $searchd = $_GET['url'];
$searchkey=$searchd;

$articledb=array();
$searchurl='search.php';
$total = $allcount = $ids = 0; 
$pagenums=1;
$page=isset($_GET['page'])?intval($_GET['page']):1;

if($searchkey)
{
	//过滤及检测
	if(strlen($searchkey) < $host['search_keywords_min_len']) 
	{
		message('关键字不能少于'.$host['search_keywords_min_len'].'个字节.', 'search.php');
	}
	if($groupid<2&&$host['search_post_space']>0)//时间间隔处理
	{
		$history=$DB->fetch_first('Select max(dateline) as time from '.DB_PREFIX."search where `ip`='$onlineip'");
		if($history&&$timestamp-$history['time']<$host['search_post_space'])
		{
			message('对不起,您在 '.$options['search_post_space'].' 秒内只能进行一次搜索.', $searchurl);
		}
	}

	//写入搜索日志
	$DB->query('Insert into '.DB_PREFIX."search (`hostid`,`dateline`,`ip`,`keywords`) values ('$hostid','$timestamp','$onlineip','$searchkey')"); 
	$searchkey = str_replace("_","\_",$searchkey);
	$searchkey = str_replace("%","\%",$searchkey);

	doAction('search_before_featch');
	
	if(preg_match("(AND|\+|&|\s)", $searchkey) && !preg_match("(OR|\|)", $searchkey)) {
		$andor = ' AND ';
		$sqltxtsrch = '1';
		$searchkey = preg_replace("/( AND |&| )/is", "+", $searchkey);
	} else {
		$andor = ' OR ';
		$sqltxtsrch = '0';
		$searchkey = preg_replace("/( OR |\|)/is", "+", $searchkey);
	}
	$searchkey = str_replace('*', '%', addcslashes($searchkey, '%_'));
	foreach(explode("+", $searchkey) AS $text) {
		$text = trim($text);
		$searchfield=explode(',',$host['search_field_allow']);
		if($text) {
			$sqltxtsrch .= $andor.'(';
			foreach($searchfield as $sfield)
			{
				$sqltxtsrch .= "`$sfield` LIKE '%".str_replace('_', '\_', $text)."%' OR ";//title LIKE '%".$text."%' OR excerpt LIKE '%".$text."%' OR tag LIKE '%".$text."%')" ;
			}
			$sqltxtsrch=substr($sqltxtsrch,0,strlen($sqltxtsrch)-4).')';
		}
	}
	//搜索文章

	$sortby = 'dateline';
	$orderby = 'desc';
	$start=($page-1)*$host['list_shownum'];
	$allarr=$DB->fetch_first("SELECT count(*) FROM ".DB_PREFIX."article WHERE visible='1' and hostid=$hostid AND ($sqltxtsrch)");
	$allcount=$allarr['count(*)'];
	$query_sql = "SELECT * FROM ".DB_PREFIX."article WHERE visible='1' and hostid=$hostid AND ($sqltxtsrch) ORDER BY dateline desc limit $start,{$host['list_shownum']}";
	$query = $DB->query($query_sql);
	while($article = $DB->fetch_array($query)) 
	{
		$articledb[]=showArticle($article);
	}
	$total=count($articledb);
	
	if($total) $pagenums=@ceil($allcount/$host['list_shownum']);
	
	$multipage='';
	$title=$searchd;
}
else
{
	$searchfrom = 'article';
	$searchurl = 'search.php';
	$articledb=array();
	$title='搜索文章';
}

doAction('search_before_view');
