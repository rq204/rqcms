<?php
if(!$arg1) run404();

$argArr=explode('.',$arg1);
if(count($argArr)!=2) run404();
if($argArr[1]!='html') run404();

$articleid=intval($argArr[0]);
if($articleid==0) run404();

$comment_username=isset($_COOKIE['comment_username'])?$_COOKIE['comment_username']:'';

$article=$DB->fetch_first("select a.*,c.content from {$dbprefix}article a left join {$dbprefix}content c on c.articleid={$articleid} where a.aid={$articleid}");
if(empty($article)) doAction('article_not_find');//插件可以处理找不到文章的结果

if(empty($article)) run404();

$article=fillArticle($article);

//缓存，先判断是否超时的
cacheControl($article['modified']);

//分类信息
$cateArr=$category[$article['cateid']];

doAction('article_before_view');