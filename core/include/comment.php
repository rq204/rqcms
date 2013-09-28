<?php
function SaveComment($aid,$content,$username,$uid)
{
	global $DB,$timestamp,$onlineip,$hostid;
	$sql="Insert into ".DB_PREFIX."comment (`hostid`,`articleid`,`userid`,`username`,`dateline`,`content`,`ipaddress`) values ('$hostid','$aid','$uid','$username','$timestamp','$content','$onlineip')";
	$DB->query($sql);
	$DB->query("update ".DB_PREFIX."article set comments=comments+1,dateline=dateline+1 where aid=$aid and hostid=$hostid");
}

function getAllComment($page)
{
	global $hostid,$host,$DB;
	$pagenum=$host['article_comment_num'];
	$start_limit = ($page - 1) * $pagenum;
	$cmtorder=$host['comment_order'] ? 'ASC' : 'DESC';
	$sql="SELECT c.dateline as commentdate ,c.username,c.userid,c.content,a.* FROM ".DB_PREFIX."comment c,".DB_PREFIX."article a WHERE c.visible='1' and c.articleid=a.aid and a.hostid=$hostid ORDER BY cid desc limit $start_limit,$pagenum";
	$commentdb=array();
	$query=$DB->query($sql);
	while($comment=$DB->fetch_array($query))
	{
		$comment['commentdate']=date($host['time_comment_format'], $comment['commentdate']);
		$comment=showArticle($comment);
		$commentdb[]=$comment;
	}
	return $commentdb;
}