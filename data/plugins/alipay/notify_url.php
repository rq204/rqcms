<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.2
 * 日期：2011-03-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 
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

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($aliapy_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代
    $out_trade_no	= $_POST['out_trade_no'];	    //获取订单号
	if($api=='direct'){
    	$total			= $_POST['total_fee'];				//获取总价格(即时到账)
	}else{
		$total			= $_POST['price'];				//获取总价格(担保或双功能)
	}
	$trade_status = $_POST['trade_status'];
	$buyer_email = $_POST['buyer_email'];
	$gmt_payment = $_POST['gmt_payment'];
	if($api=='direct'){
    if($trade_status == 'TRADE_FINISHED' ||$trade_status == 'TRADE_SUCCESS') {    //交易成功结束
		//判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$wpdb->update( $wpdb->alipay, array( 'alipay_price' => $total, 'alipay_email' => $buyer_email ,'alipay_time' => $gmt_payment, 'alipay_status' => '交易成功','alipay_dl' => 'y'), array( 'alipay_num' => $out_trade_no ) , array( '%f', '%s', '%s', '%s', '%s' ), array( '%s' )) ;

		///////////////////////////////////////////////////
	
	$alipay_title  = mysql_query("SELECT alipay_title FROM $wpdb->alipay WHERE alipay_num = '$out_trade_no'");
	$row_title    = mysql_fetch_row($alipay_title);
	$item_name  = $row_title[0];
	$admin_email = get_bloginfo ('admin_email'); // $admin_email 可改為你指定的 e-mail.
    $to = $admin_email;
	$to_buyer = $buyer_email;
    $subject = get_option("blogname") . '有商品售出';
	$subject_buyer = '您在['.get_option("blogname").']的交易已完成';
    $message = '
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p>您好!</p>
	  <p>您的网站[<a href="'.get_option("siteurl").'">'.get_option("blogname").'</a>]有商品售出，售出的商品是：'.$item_name.'，收入：'.$total.'，交易时间：'.$gmt_payment.'</p>
      <p>(此邮件由系统自动发出，请勿回复.)</p>
    </div>';
	$message_buyer ='
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p>您好!</p>
	  <p>感谢您在[<a href="'.get_option("siteurl").'">'.get_option("blogname").'</a>]购买商品，您购买的商品是：'.$item_name.'，消费金额：'.$total.'，交易时间：'.$gmt_payment.'</p>
	  <p>再次感谢您的支持，如有任何疑问，欢迎与我们联系，邮箱：'.$admin_email.'</p>
      <p>(此邮件由系统自动发出，请勿回复.)</p>
    </div>';
    $headers = "Content-Type: text/html; charset=" . get_option('blog_charset');
    wp_mail( $to_buyer, $subject_buyer, $message_buyer, $headers );
    wp_mail( $to, $subject, $message, $headers );
///////////////////////////////////////////////////////////////////////////////////////////


		echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //log_result("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
    else {
        echo "success";		//其他状态判断。普通即时到帐中，其他状态不用判断，直接打印success。

        //调试用，写文本函数记录程序运行情况是否正常
        //log_result ("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}else{
	if($trade_status == 'WAIT_BUYER_PAY') {
	//该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
		$wpdb->update( $wpdb->alipay, array( 'alipay_price' => $total, 'alipay_email' => $buyer_email ,'alipay_time' => $gmt_payment, 'alipay_status' => '等待买家付款','alipay_dl' => 'n'), array( 'alipay_num' => $out_trade_no ) , array( '%f', '%s', '%s', '%s', '%s' ), array( '%s' )) ;
		//判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
			
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
	//该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
		$wpdb->update( $wpdb->alipay, array( 'alipay_price' => $total, 'alipay_email' => $buyer_email ,'alipay_time' => $gmt_payment, 'alipay_status' => '等待卖家发货','alipay_dl' => 'y'), array( 'alipay_num' => $out_trade_no ) , array( '%f', '%s', '%s', '%s', '%s' ), array( '%s' )) ;
		//判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
			
        echo "success";		//请不要修改或删除
	$alipay_title  = mysql_query("SELECT alipay_title FROM $wpdb->alipay WHERE alipay_num = '$out_trade_no'");
	$row_title    = mysql_fetch_row($alipay_title);
	$item_name  = $row_title[0];
	$admin_email = get_bloginfo ('admin_email'); // $admin_email 可改為你指定的 e-mail.
    $to = $admin_email;
	$to_buyer = $buyer_email;
    $subject = get_option("blogname") . '有商品售出';
	$subject_buyer = '您在['.get_option("blogname").']的交易已付款成功';
    $message = '
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p>您好!</p>
	  <p>您的网站[<a href="'.get_option("siteurl").'">'.get_option("blogname").'</a>]有商品售出，请及时发货！售出的商品是：'.$item_name.'，收入：'.$total.'，交易时间：'.$gmt_payment.'</p>
      <p>(此邮件由系统自动发出，请勿回复.)</p>
    </div>';
	$message_buyer ='
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p>您好!</p>
	  <p>感谢您在[<a href="'.get_option("siteurl").'">'.get_option("blogname").'</a>]购买商品，我们将很快给您发货！您购买的商品是：'.$item_name.'，消费金额：'.$total.'，交易时间：'.$gmt_payment.'</p>
	  <p>再次感谢您的支持，如有任何疑问，欢迎与我们联系，邮箱：'.$admin_email.'</p>
      <p>(此邮件由系统自动发出，请勿回复.)</p>
    </div>';
    $headers = "Content-Type: text/html; charset=" . get_option('blog_charset');
    wp_mail( $to_buyer, $subject_buyer, $message_buyer, $headers );
    wp_mail( $to, $subject, $message, $headers );

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
	//该判断表示卖家已经发了货，但买家还没有做确认收货的操作
		$wpdb->update( $wpdb->alipay, array( 'alipay_price' => $total, 'alipay_email' => $buyer_email ,'alipay_time' => $gmt_payment, 'alipay_status' => '等待买家确认','alipay_dl' => 'y'), array( 'alipay_num' => $out_trade_no ) , array( '%f', '%s', '%s', '%s', '%s' ), array( '%s' )) ;
		//判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
			
        echo "success";		//请不要修改或删除
	$alipay_title  = mysql_query("SELECT alipay_title FROM $wpdb->alipay WHERE alipay_num = '$out_trade_no'");
	$row_title    = mysql_fetch_row($alipay_title);
	$item_name  = $row_title[0];
	$admin_email = get_bloginfo ('admin_email'); // $admin_email 可改為你指定的 e-mail.
	$to_buyer = $buyer_email;
	$subject_buyer = '您在['.get_option("blogname").']的交易已发货';
	$message_buyer ='
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p>您好!</p>
	  <p>感谢您在[<a href="'.get_option("siteurl").'">'.get_option("blogname").'</a>]购买商品，我们已经发货，请注意查收！您购买的商品是：'.$item_name.'，消费金额：'.$total.'，发货时间：'.$gmt_payment.'</p>
	  <p>再次感谢您的支持，如有任何疑问，欢迎与我们联系，邮箱：'.$admin_email.'</p>
      <p>(此邮件由系统自动发出，请勿回复.)</p>
    </div>';
    $headers = "Content-Type: text/html; charset=" . get_option('blog_charset');
    wp_mail( $to_buyer, $subject_buyer, $message_buyer, $headers );

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($trade_status == 'TRADE_FINISHED') {
	//该判断表示买家已经确认收货，这笔交易完成
		$wpdb->update( $wpdb->alipay, array( 'alipay_price' => $total, 'alipay_email' => $buyer_email ,'alipay_time' => $gmt_payment, 'alipay_status' => '交易成功','alipay_dl' => 'n'), array( 'alipay_num' => $out_trade_no ) , array( '%f', '%s', '%s', '%s', '%s' ), array( '%s' )) ;
		//判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
			
        echo "success";		//请不要修改或删除
	$alipay_title  = mysql_query("SELECT alipay_title FROM $wpdb->alipay WHERE alipay_num = '$out_trade_no'");
	$row_title    = mysql_fetch_row($alipay_title);
	$item_name  = $row_title[0];
	$admin_email = get_bloginfo ('admin_email'); // $admin_email 可改為你指定的 e-mail.
    $to = $admin_email;
	$to_buyer = $buyer_email;
    $subject = get_option("blogname") . '有商品买家已经确认收货';
	$subject_buyer = '您在['.get_option("blogname").']的交易已完成';
    $message = '
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p>您好!</p>
	  <p>您的网站[<a href="'.get_option("siteurl").'">'.get_option("blogname").'</a>]售出的商品买家已经确认收货，售出的商品是：'.$item_name.'，收入：'.$total.'，确认时间：'.$gmt_payment.'</p>
      <p>(此邮件由系统自动发出，请勿回复.)</p>
    </div>';
	$message_buyer ='
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p>您好!</p>
	  <p>感谢您在[<a href="'.get_option("siteurl").'">'.get_option("blogname").'</a>]购买商品，您购买的商品是：'.$item_name.'，消费金额：'.$total.'，交易完成时间：'.$gmt_payment.'</p>
	  <p>再次感谢您的支持，如有任何疑问，欢迎与我们联系，邮箱：'.$admin_email.'</p>
      <p>(此邮件由系统自动发出，请勿回复.)</p>
    </div>';
    $headers = "Content-Type: text/html; charset=" . get_option('blog_charset');
    wp_mail( $to_buyer, $subject_buyer, $message_buyer, $headers );
    wp_mail( $to, $subject, $message, $headers );

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
    else {
		//其他状态判断
        echo "success";

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult ("这里写入想要调试的代码变量值，或其他运行的结果记录");
	}
	
	}
}
else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //log_result ("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>