<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="keywords" content=”<?php echo $host['keywords'];?>" />
<meta name="description" content=”<?php echo $host['description'];?>" />
<link rel="stylesheet" href="/images/common.css" type="text/css" media="all"  />
<?php
if ($returnurl) {
?>
<meta http-equiv="REFRESH" content="3;URL=<?php echo $returnurl;?>">
<?php
}?>
<title>系统消息 <?php echo $host['name'];?></title>
</head>
<body>
<div id="message">
  <h2><?php echo $host['name'];?></h2>
  <p style="margin-bottom:20px;"><strong><?php echo $msg;?></strong></p>
<?php
if ($returnurl) {?>
  <p>2秒后将自动跳转<br /><a href="<?php echo $returnurl;?>">如果不想等待或浏览器没有自动跳转请点击这里跳转</a></p>
<?php
}?>
</div>
</body>
</html>
<?php
?>