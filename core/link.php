<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$page=isset($_GET['page'])?intval($_GET['page']):1;
$item=isset($_GET['url'])?$_GET['url']:'';
$articledb=array();
$multipage ='';
$title='';
$tagdb=array();
if ($item) 
{
	$shownum=$host['list_shownum'];
	$start_limit = ($page - 1) * $shownum;
	$query_sql = "SELECT articleid from ".DB_PREFIX."tag where tag='$item' and hostid='$hostid' order by tid desc limit $start_limit, $shownum";
	$query=$DB->query($query_sql);
	$selectnum=$DB->num_rows($query);
	if($selectnum)
	{
		$idarray=array();
		while($m=$DB->fetch_array($query))
		{
			$idarray[]=$m['articleid'];
		}
		$aids=implode_ids($idarray);
		$query_sql = "SELECT * FROM ".DB_PREFIX."article WHERE aid in ($aids)  and visible='1' and hostid=$hostid ORDER BY dateline desc";
		$query=$DB->query($query_sql);
		$articledb=array();
		while($adb=$DB->fetch_array($query))
		{
			$articledb[]=showArticle($adb);
		}
		$total=count($articledb);
	}
	else
	{
		message('记录不存在.', '/');
	}
	$title=$item;
	$DB->free_result($query);
}
else 
{
	$title='标签';
	$shownum = intval($host['tags_shownum']);
	$start_limit = ($page - 1) * $shownum;
	$multipage='';
	//$multipage = multi(100, $shownum, $page, 'tag.php');
	$query = $DB->query("SELECT count(*) as usenum,tag FROM ".DB_PREFIX."tag where hostid='$hostid' group by tag ORDER BY tid DESC LIMIT $start_limit, ".$shownum);
	while ($tag = $DB->fetch_array($query)) {
		$tag['fontsize'] = 12 + $tag['usenum'] / 2;
		$tag['url'] = $tag['tag'];
		$tag['usenum'] = intval($tag['usenum']);
		$tag['item'] = $tag['tag'];
		$tagdb[]=$tag;
	}
	unset($tag);
	$DB->free_result($query);
}

doAction('tag_before_view');