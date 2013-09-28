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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
<title><?php echo $title; ?></title>
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="description" content="<?php echo $description; ?>">
<meta content="text/html; charset=utf-8" http-equiv=Content-Type>
<base href="<?php echo $host_url; ?>">
<link title="<?php echo $host['name']; ?>" rel=alternate type=application/rss+xml href="<?php echo $rss_url; ?>">
<link rel=stylesheet type=text/css href="images/common.css">
<script type="text/javascript">
//收藏本站
function AddFavorite(title, url) {try {window.external.addFavorite(url,title);}catch (e) { try {window.sidebar.addPanel(title,url,"");}catch (e) {alert("抱歉，您所使用的浏览器无法完成此操作。加入收藏失败，请使用Ctrl+D进行添加");}}}
</script>
</head>
<body>
<div class=wrap>
  <div class=top-nav>
    <div class=top-menu>
      <ul>
		<li><a href="http://hanhan.qq.com/hanhan/one/" target=_blank>一个-韩寒</a></li>
		<li><a href="http://www.infzm.com/" target=_blank>南方周末</a></li>
		<li><a href="http://focus.news.163.com/" target=_blank>网易深度</a></li>
		<li><a href="http://view.news.qq.com/" target=_blank>腾讯话题</a></li>
      </ul>
    </div>
    <div class=top-nav-logo></div>
  </div>
  <div class=top>
    <div class=top640-480><a href="http://www.locoy.com" target=_blank><IMG src="/images/welcome.png" width=468 height=60></a></div>
    <div class=ad-text>
      <ul>
        <li><a href="javascript:void(0);" onClick="AddFavorite('<?php echo $host['name']; ?> <?php echo $host['keywords']; ?>','<?php echo $constant['RQ_HTTP']; ?><?php echo $host['host']; ?>');">收藏本站</a></li>
        <li><a href="<?php echo $rss_url; ?>" target=_blank>RSS订阅</a></li>
      </ul>
    </div>
  </div>
  <div class=menu>
    <ul id=menu-left>
      <li><a href="<?php echo $homeurl; ?>">首页</a></li>
      <li><a href="<?php echo $search_url; ?>">搜索</a></li>
      <li><a href="<?php echo $tag_url; ?>">标签</a></li>
      <li><a href="<?php echo $comment_url; ?>">评论</a></li>
	</ul>
<?php
if ($uid) {
?>
    <ul id=menu-right>
      <li><a href="<?php echo $profile_url; ?>">资料</a></li>
      <li><a href="<?php echo $logout_url; ?>">注销</a></li>
<?php
if ($groupid == 3 || $groupid == 4) {
?>
      <li><a href="<?php echo $admin_url; ?>" target="_blank">管理</a></li>
<?php
}}else{
?>
      <li><a href="<?php echo $register_url; ?>">注册</a></li>
      <li><a href="<?php echo $login_url; ?>">登陆</a></li>
<?php
}
?>
    </ul>
  </div>