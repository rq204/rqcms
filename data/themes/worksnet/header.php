<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
//在不同的页面，有不同的title,keywords,description

function pagination($count,$perlogs,$page,$file,$url){
	$pnums = @ceil($count / $perlogs);
	$re = '';
	for ($i = $page-5;$i <= $page+5 && $i <= $pnums; $i++){
		if ($i > 0){
			if ($i == $page){
				$re .= " <span>$i</span> ";
			} else {
				$curl=mkUrl($file,$url,$i);
				$re .= " <a href=\"$curl\">$i</a> ";
			}
		}
	}
	$u1=mkUrl($file,$url,1);
	$uend=mkUrl($file,$url,$pnums);
	if ($page > 6) $re = "<a href=\"<?php echo $u1}\" title=\"首页\">&laquo;</a><em>...</em>$re";
	if ($page + 5 < $pnums) $re .= "<em>...</em> <a href=\"<?php echo $uend}\" title=\"尾页\">&raquo;</a>";
	if ($pnums <= 1) $re = '';
	return $re;
}

if(!isset($keywords)) $keywords=$host['keywords'];
if(!isset($description)) $description=$host['description'];

$homeurl='/';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta content="漏洞EXP,技术文章,安全工具,源码赏析,资料下载,业界新闻" name="keywords" />
<meta content="<p>本站以 &ldquo;增强网络安全意识，提高网络安全能力，改善网络安全环境&rdquo; 为宗旨，致力于为广大网络安全爱好者和有志于开拓网络安全事业的有识之士提供一个免费、开放的学习交流环境。本站提供的全部资源皆为安全分析及实验之用，请勿用于非法用途，否则一切后果自负，特此声明。</p>
<p>&nbsp;</p>
<p>本站所有文章及工具均收集自互联网免费资源，文章版权问题、交换链接及其它站务请联系:</p>
<p>&nbsp;</p>
<p>QQ：530254&nbsp;<br />
MSN：ka0ru@live.cn <br />
欢迎赐稿：ka0ru@live.cn</p>
<p>&nbsp;</p>" name="description" />
<meta content="SaCMS" name="copyright" />
<meta content="angel,4ngel" name="author" />
<link rel="alternate" title="安全公会" href="/rss.php" type="application/rss+xml" />
<link rel="stylesheet" href="/images/style.css" type="text/css" media="all" />
<script type="text/javascript">
var blogurl = '';
</script>
<script type="text/javascript" src="/images/jquery.js?ver=1.3.2"></script>
<script type="text/javascript" src="/images/common.js"></script>
<title>安全公会</title>
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" media="all" href="http://web.archive.org/web/20100908222609/http://www.worksnet.net//templates/sacms-tem/ie6.css" />
<![endif]-->
</head>
<body>

<div id="outmain">

<div id="header">

<div id="logo"><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/"><h1>安全公会</h1></a></div>

<div id="search">
        <form method="post" action="http://web.archive.org/web/20100908222609/http://www.worksnet.net/post.php">
<input type="hidden" name="formhash" value="6ebaafc6" />
<input type="hidden" name="action" value="search" />
<div id="searchinput"><input name="keywords" type="text"  value="输入关键词..." onfocus="this.value=''" class="search-input" title="请输入关键字" /></div>
                <div id="searchbutton"><input type="submit" class="search-button" value="搜索" /></div>
</form>
</div>

</div>

<div id="pagemenu">
<ul class="clearfix">
<li class="current_page_item" ><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/">网站首页</a></li>
<li
><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/category/%E6%BC%8F%E6%B4%9EEXP-2/">漏洞EXP</a></li>
<li
><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/category/%E6%8A%80%E6%9C%AF%E6%96%87%E7%AB%A0-3/">技术文章</a></li>
<li
><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/category/%E5%AE%89%E5%85%A8%E5%B7%A5%E5%85%B7-4/">安全工具</a></li>
<li
><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/category/%E6%BA%90%E7%A0%81%E8%B5%8F%E6%9E%90-5/">源码赏析</a></li>
<li
><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/category/%E8%B5%84%E6%96%99%E4%B8%8B%E8%BD%BD-6/">资料下载</a></li>
<li
><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/category/%E4%B8%9A%E7%95%8C%E6%96%B0%E9%97%BB-7/">业界新闻</a></li>
<li ><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/archives/">文章归档</a></li>
<li ><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/search/">高级搜索</a></li>			
<li ><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/tagslist/">标签列表</a></li>
<li ><a href="http://web.archive.org/web/20100908222609/http://www.worksnet.net/links/">友情链接</a></li>
<li><a href="http://bbs.worksnet.net/" target="_blank">公会论坛</a></li>
<li><a href="/rss.php">RSS订阅</a></li>
</ul>
</div>