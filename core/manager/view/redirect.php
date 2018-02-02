<?php
print <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>系统消息</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="{$css_url}" type="text/css">
<meta HTTP-EQUIV="REFRESH" content="$min;url=$url">
<style type="text/css">
.alert {
	color: #990000;
	font-size: 14px;
	margin-bottom: 25px;
}
.box {
	border: #B1B6D2 1px solid;
	width: 500px;
	margin: 100px auto;
	background-color: #F7F8FA;
	background-image: url({$cssdir}box_bg.jpg);
	background-repeat: repeat-x;
	background-position: left top;
	text-align: center;
	padding: 30px;
}
.alertmsg {
	background-color: transparent;
	margin-top: 25px;
}
</style>
</head>
<body style="text-align:center">
<div class="box">
  <h2 class="alert">$msg</h2>
  <div class="alertmsg"><a href="$url">如果你不想等待或浏览器没有自动跳转请点击这里跳转</a></div>
</div>
</body>
</html>
EOT;
