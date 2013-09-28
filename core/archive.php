<?php
if(!defined('RQ_ROOT')) exit('Access Denied');

$date=isset($_GET['date'])?intval($_GET['date']):'';
if($date)//有日期就查找对应日期的
{
	$articledb=array();
	$start_limit=0;
	$shownum=$host['list_shownum'];
	if(isset($_GET['page'])) $start_limit=(intval($_GET['page'])-1)*$shownum;
	if($start_limit<0) $start_limit=0;
	$timestart=strtotime($date);
	$timeend=$timestart+3600*24;
	$query_sql = "SELECT * from ".DB_PREFIX."article where hostid='$hostid' and dateline>$timestart and dateline<$timeend order by aid desc limit $start_limit, $shownum";

	$query=$DB->query($query_sql);
	$selectnum=$DB->num_rows($query);
	if($selectnum)
	{
		while($m=$DB->fetch_array($query))
		{
			$articledb[]=showArticle($m);
		}
	}
}
else//这里直接获取archive的缓存生成列表
{

}

doAction('archive_before_view');