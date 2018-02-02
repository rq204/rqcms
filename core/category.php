<?php
$cur_page=1;
if($arg2) $cur_page=intval($arg2);
if(!$cur_page) $cur_page=1;

//当前分类
$cateArr=$category[$arg1];

//总页数
$all_page=@ceil($cateArr['count']/$setting['option']['per_page_articles']);
if($all_page>$setting['option']['article_list_pages']) $all_page=$setting['option']['article_list_pages'];

//超过总数时，只显示最后页
if($cur_page>$all_page) $cur_page=$all_page;
if($cur_page==0) $cur_page=1;

$start=($cur_page-1)*$setting['option']['per_page_articles'];
//$query=$DB->query("select * from {$dbprefix}article where  cateid={$cateArr['cid']} order by aid desc limit {$start},{$setting['option']['per_page_articles']}");
$query=$DB->query("select * from {$dbprefix}article a where a.aid in (select b.i as id from (select aid as i from {$dbprefix}article c where c.cateid={$cateArr['cid']} order by c.aid desc limit {$start},{$setting['option']['per_page_articles']}) b)");
$articledb=array();
while($row=$DB->fetch_array($query))
{
	$articledb[$row['aid']]=fillArticle($row);
}

doAction('category_before_view');