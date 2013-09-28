<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
if(!isset($_GET['url'])) message('未定义参数');
$page=isset($_GET['page'])?intval($_GET['page']):1;//这个是文章的页数
$catepage=isset($_GET['catepage'])?intval($_GET['catepage']):1;//这个是评论的页数

$comment_username=isset($_COOKIE['comment_username'])?$_COOKIE['comment_username']:'';
$comment_url=isset($_COOKIE['comment_url'])?$_COOKIE['comment_url']:'';

$article=getArticle($_GET['url']);
if(empty($article))
{
	doAction('article_not_find');
	message('该文章不存在或已被删除',RQ_HTTP.$host['host']);
}
//如果启用了自动缓存，先判断是否超时的
if(RQ_CACHE) cacheControl($article['lastmodified']);

//现在是对数据再做处理
$title=$article['title'];
$keywords=$article['keywords'];
$description=str_replace("<p>","",$article['excerpt']);
$description=str_replace("</p>","",$description);
$aid=$article['aid'];

//内容分页的处理
$pagecount=0;
if(strpos($article['content'],'[page]'))
{
	$articleArr=explode('[page]',$article['content']);
	$pagecount=count($articleArr);
	if($pagecount>=$page&&$page>0) $article['content']=$articleArr[$page-1];
	else
	{
		$page=0;
		$article['content']=$articleArr[0];
	}
}

//加密处理
$article['allowread'] = true;
if(!empty($article['password']))
{
	if(isset($_COOKIE['readpassword_'.$aid])&& $_COOKIE['readpassword_'.$aid] == $article['password']) $article['allowread'] = true;
	else if(isset($_POST['readpassword'])&&$_POST['readpassword']==$article['password'])
	{
		$article['allowread'] = true;
		setcookie('readpassword_'.$aid,$article['password']);
	}
	else $article['allowread'] = false;
}

$DB->unbuffered_query("UPDATE ".DB_PREFIX."article SET views=views+1 WHERE aid=$aid");

//处理PHP高亮
$article['content'] = preg_replace("/\s*\[php\](.+?)\[\/php\]\s*/ies", "phphighlite('\\1')", $article['content']);
if($article['cateid']=='0')
{
	$article['cname']=$article['curl']='';
}
else
{
	$article['cname'] = $cateArr[$article['cateid']]['name'];
}
// 评论	
$commentdb=array();
if ($article['comments'])
{
	$commentdb=getComment($aid,$catepage,$host['article_comment_num']);
}

$cmcontent=isset($_COOKIE['cmcontent'])?$_COOKIE['cmcontent']:'';
$multipage ='';

doAction('article_before_view');