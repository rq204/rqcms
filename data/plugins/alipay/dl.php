<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php 
require_once("alipay_config.php");
?>
<?php
$post_id   = $_GET['id'];
$trade_no  = $_GET['no'];

$time      = mysql_query("SELECT alipay_time FROM $wpdb->alipay WHERE alipay_num = '$trade_no'");
$row       = mysql_fetch_row($time);
$old_time  = $row[0];
$new       = mktime();
$old       = strtotime("$old_time");
if($new - $old > 900)
mysql_query("UPDATE $wpdb->alipay SET alipay_dl = 'no' WHERE alipay_num = '$trade_no'");

if(!empty($post_id)&&!empty($trade_no)){
$postid    = mysql_query("SELECT alipay_post FROM $wpdb->alipay WHERE alipay_num = '$trade_no'");
$row1      = mysql_fetch_row($postid);
$id        = $row1[0];
$dl        = mysql_query("SELECT alipay_dl FROM $wpdb->alipay WHERE alipay_num = '$trade_no'");
$row2      = mysql_fetch_row($dl);
$allow_dl  = $row2[0];

if($post_id == $id && $allow_dl=='y'){
$fileurl   = get_post_meta($id, 'ali_dl', true);

echo '<meta http-equiv="Refresh" content="0; url='.$fileurl.'"> ';
}
}
else{
	echo '请求失败！';
}
?>
</head>
    <body>
    </body>
</html>