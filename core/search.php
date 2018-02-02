<?php
$searchd = $arg1;
if(!$searchd) run404();
$searchd=htmlspecialchars($searchd);

if($arg2) $cur_page_num=intval($arg2);
if(!$cur_page_num) $cur_page_num=1;


if($searchkey)
{
	$articledb=array();
	//过滤及检测
	if(strlen($searchkey) < 2) 
	{
		message('关键字不能少于2个字节.', 'search.php');
	}
	if($host['search_post_space']>0)//时间间隔处理
	{
		$history=$DB->fetch_first("Select max(dateline) as time from {$dbprefix}search where `ip`='$onlineip'");
		if($history&&$timestamp-$history['time']<$host['search_post_space'])
		{
			message('对不起,您在 '.$host['search_post_space'].' 秒内只能进行一次搜索.', $searchurl);
		}
	}

	//写入搜索日志
	$DB->query("Insert into {$dbprefix}search (`dateline`,`ip`,`keywords`) values ('$timestamp','$onlineip','$searchkey')"); 
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
	$allarr=$DB->fetch_first("SELECT count(*) FROM {$dbprefix}article WHERE 1 AND ($sqltxtsrch)");
	$allcount=$allarr['count(*)'];
	$query_sql = "SELECT * FROM {$dbprefix}article WHERE 1 AND ($sqltxtsrch) ORDER BY dateline desc limit $start,{$host['list_shownum']}";
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
	$title='搜索页面';
}

doAction('search_before_view');
