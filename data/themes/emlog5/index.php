<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$articledb=getLatestArticle(10);
$stickcache=getStickArticle(10);
$picscache=getPicArticle(5);
$commentdata=getLatestComment(10);
$linkarr=getLink();
$hotcache=getHotArticle(10);
$listcache=array();
$latestarray=@include RQ_DATA.'/cache/latest_'.$host['host'].'.php';

//得到最新的所有栏目的文章id
if($latestarray)
{
	unset($latestarray['cateids'][0]);
	$listcache=$latestarray['cateids'];
}

include RQ_DATA."/themes/$theme/header.php";


//获取置顶和前几篇文章
include RQ_DATA."/themes/$theme/list.php";

include RQ_DATA."/themes/$theme/sidebar.php";

include RQ_DATA."/themes/$theme/footer.php";
?>