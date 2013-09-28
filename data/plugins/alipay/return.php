<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.2
 * 日期：2011-03-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 
 * TRADE_FINISHED(表示交易已经成功结束，为普通即时到帐的交易状态成功标识);
 * TRADE_SUCCESS(表示交易已经成功结束，为高级即时到帐的交易状态成功标识);
 */
 

require_once("alipay_config.php");
if(get_option('ali_api')){
	$api = get_option('ali_api');
}else{
	$api = 'direct';
}
require_once("lib-".$api."/alipay_notify.class.php");
?>


        <title><?php echo get_option(blogname);?></title>
        <style type="text/css">
<style> 
	html{background:#F2F2F2;}body{background:#fff;color:#333;font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;margin:2em auto;width:700px;padding:1em 2em;-moz-border-radius:11px;-khtml-border-radius:11px;-webkit-border-radius:11px;border-radius:14px;border:1px solid #dfdfdf;text-shadow: 1px 2px 3px rgba(0,0,0,0.5);}a{color:#2583ad;text-decoration:none;}a:hover{color:#d54e21;}h1{ text-align: center;border-bottom:1px solid #dadada;clear:both;font:26px Georgia,"Times New Roman",Times,serif;margin:5px 0 0 -4px;padding:0;padding-bottom:7px;font-weight:bold;}h2{color:#FF0080;font-size:16px;}p{padding-bottom:2px;font-size:14px;line-height:18px;}#logo{text-align:left;}.clear { clear:both; font-size:0; height:1px; }li{ background:url(images/li.gif) no-repeat 0 4px !important; padding:0 0 0 20px; ;list-style-type: none;}
h3{
    background: none repeat scroll 0 0 #F2F2F2;
    border: 1px solid #BFBFBF;
    color: #666666;
    font-size: 15px;
    margin: 15px 0 10px;
    padding: 2px 5px;
}
.time {
	float: left;
	margin: 0 10px 0 0;
	}

	div{font-size:13px;}
.header{padding: 0;text-align:left; }
.list{
    background: none repeat scroll 0 0 #F2F2F2;
    border: 1px solid #BFBFBF;
    color: #666666;
    font-size: 15px;
    height: 30px;
    margin: 15px 0 10px;
    padding: 10px 0 0 10px;
}
.sm{
    background: none repeat scroll 0 0 #F2F2F2;
    border: 1px solid #BFBFBF;
    color: #666666;
    font-size: 15px;
    margin: 15px 0 10px;
    padding: 10px 0 0 10px;
}
</style>
   </head>
    <body>
<div class="list">
<div style="text-align: center"><a href="<?php 
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($aliapy_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result){//验证成功
    $trade_no          = $_GET['out_trade_no'];    //获取订单号
    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS'|| $_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS'){
$postid    = mysql_query("SELECT alipay_post FROM $wpdb->alipay WHERE alipay_num = '$trade_no'");
$row       = mysql_fetch_row($postid);
$id        = $row[0];
echo "./dl.php?no=".$trade_no."&id=".$id;
    }
    else {
      echo "trade_status=".$_GET['trade_status'];
    }	
}
else {
    //验证失败
    echo "";
}
?>">下载</a></div>
</div>
<div style="text-align: center">Copyright © <?php echo date('Y'); ?> <a href="<?php echo get_option(siteurl);?>/"><?php echo get_option(blogname);?></a>
版权所有.
</div>
    </body>
</html>