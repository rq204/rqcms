<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $title; ?></title>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<meta name="description" content="<?php echo $description; ?>" />
<meta name="generator" content="emlog" />
<link rel="alternate" type="application/rss+xml" title="RSS"  href="/rss.php" />
<link href="/images/main.css" rel="stylesheet" type="text/css" />
<script src="/images/common_tpl.js" type="text/javascript"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><a href="/"><?php echo $host['name']; ?></a></h1>
    <h3><?php echo $host['description']; ?></h3>
  </div>
  <div id="banner"><a href="<?php echo RQ_HTTP.$host['host']?>"><img src="/images/default.jpg" height="134" width="960" /></a></div>
  <div id="nav">	<ul>
			<li class="common"><a href="<?php echo RQ_HTTP.$host['host']?>" >首页</a></li>
			<?php
if ($uid) {
?>
			<li class="common"><a href="<?php echo $profile_url; ?>">帐户</a></li>
			<li class="common"><a href="<?php echo $admin_url,'?file=article&action=add'; ?>">写日志</a></li>
<?php
if ($groupid == 3 || $groupid == 4) {
?>
			<li class="common"><a href="<?php echo $admin_url; ?>">管理站点</a></li>
<?php
}?>
			<li class="common"><a href="<?php echo $logout_url; ?>">退出</a></li>
<?php
}else{
?>
      <li><a href="<?php echo $register_url; ?>">注册</a></li>
      <li><a href="<?php echo $login_url; ?>">登陆</a></li>
<?php
}
?>
				</ul>
</div>

<div id="content">