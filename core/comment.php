<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
include RQ_CORE.'/include/comment.php';
if(RQ_POST)
{
	if(!isset($_POST['url'])) message('未定义参数', './');
	$article=getArticle($_POST['url']);
	if(empty($article)) message('不存在的文章', './');
	doAction('comment_post_check');
	$redirct= mkUrl('article.php',$_POST['url'],0);
	if($article['closed'])  message('该文章禁止评论', $redirct);
	$content=$_POST['content'];
	if(empty($content)) message('评论内容不能为空', $redirct);
	if($username) $commentuser=$username;
	else
	{	
		if(!$host['guest_comment']) message('游客禁止发表评论',$redirct);
		$commentuser=$POST_['username'];
	}
	SaveComment($article['aid'],$content,$commentuser,$uid);
	message('成功添加一条评论',$redirct);
}
else
{
	$page=1;
	if(isset($_GET['page'])) $page=(int)$_GET['page'];
	$commentdb =getAllComment($page);//print_r($commentdb);exit;
	doAction('comment_data_view',$commentdb);
	$total=count($commentdb);
	$multipage='';
	$title='评论';
}