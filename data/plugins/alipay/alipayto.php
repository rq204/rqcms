<?php
/* *
 * 功能：即时到帐接口接入页
 * 版本：3.2
 * 修改日期：2011-03-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */

require_once("alipay_config.php");
if(get_option('ali_api')){
	$api = get_option('ali_api');
}else{
	$api = 'direct';
}
require_once("lib-".$api."/alipay_service.class.php");

/**************************请求参数**************************/

//必填参数//
$postid    =  $_POST['id'];
$total_fee = get_post_meta($postid, 'ali_price', true); //即时到账接口价格
$price     = get_post_meta($postid, 'ali_price', true); //担保交易和双功能接口价格
$subject   = get_post_meta($postid, 'ali_name', true);  //订单名称，显示在支付宝收银台里的"商品名称"里，显示在支付宝的交易管理的"商品名称"的列表里。
$out_trade_no = $_POST['time'];		//请与贵网站订单系统中的唯一订单号匹配

$body         = $_POST['alibody'];	//订单描述、订单详细、订单备注，显示在支付宝收银台里的"商品描述"里
if($_POST['qq']){
$qq           = $_POST['qq'];
}else{
	$qq = 0;
}
$alipay       = $_POST['alipay'];
$site         = $_POST['site'];
$time         = date('Y-m-d H:i:s');

$logistics_fee		= "0.00";				//物流费用，即运费。
$logistics_type		= "EXPRESS";			//物流类型，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
$logistics_payment	= "SELLER_PAY";			//物流支付方式，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）

$quantity			= "1";					//商品数量，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品。

// 将传入的数据写入数据库
	if(get_post_meta($postid, 'ali_price', true)){
		$wpdb->update( $wpdb->alipay, array( 'alipay_post' => $postid, 'alipay_price' => $total_fee , 'alipay_qq' => $qq, 'alipay_email' => $alipay, 'alipay_site' => $site, 'alipay_time' => $time), array( 'alipay_num' => $out_trade_no ) , array( '%s', '%s', '%s', '%s', '%s' ), array( '%s' )) ;
		$affected = mysql_affected_rows();
			if($affected == 0)
				{
					 $wpdb->insert( $wpdb->alipay, array( 'alipay_num' => $out_trade_no, 'alipay_post' => $postid , 'alipay_title' => $subject , 'alipay_price' => $total_fee , 'alipay_qq' => $qq , 'alipay_email' => $alipay ,'alipay_site' => $site , 'alipay_time' => $time , 'alipay_status' => '未付款' ,'alipay_dl' => ''), array( '%s', '%s', '%s', '%f', '%s', '%s', '%s','%s', '%s', '%s' ) );
				}
		}

//扩展功能参数——默认支付方式//

//默认支付方式，取值见"即时到帐接口"技术文档中的请求参数列表
$paymethod    = 'directPay';
//默认网银代号，代号列表见"即时到帐接口"技术文档"附录"→"银行列表"
$defaultbank  = '';


//扩展功能参数——防钓鱼//

//防钓鱼时间戳
$anti_phishing_key  = '';
//获取客户端的IP地址，建议：编写获取客户端IP地址的程序
$exter_invoke_ip = '';
//注意：
//1.请慎重选择是否开启防钓鱼功能
//2.exter_invoke_ip、anti_phishing_key一旦被使用过，那么它们就会成为必填参数
//3.开启防钓鱼功能后，服务器、本机电脑必须支持SSL，请配置好该环境。
//示例：
//$exter_invoke_ip = '202.1.1.1';
//$ali_service_timestamp = new AlipayService($aliapy_config);
//$anti_phishing_key = $ali_service_timestamp->query_timestamp();//获取防钓鱼时间戳函数


//扩展功能参数——其他//

//商品展示地址，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
$show_url			= get_permalink($postid);
//自定义参数，可存放任何内容（除=、&等特殊字符外），不会显示在页面上
$extra_common_param = '';

//扩展功能参数——分润(若要使用，请按照注释要求的格式赋值)
$royalty_type		= "";			//提成类型，该值为固定值：10，不需要修改
$royalty_parameters	= "";
//注意：
//提成信息集，与需要结合商户网站自身情况动态获取每笔交易的各分润收款账号、各分润金额、各分润说明。最多只能设置10条
//各分润金额的总和须小于等于total_fee
//提成信息集格式为：收款方Email_1^金额1^备注1|收款方Email_2^金额2^备注2
//示例：
//royalty_type 		= "10"
//royalty_parameters= "111@126.com^0.01^分润备注一|222@126.com^0.01^分润备注二"

/************************************************************/
//构造要请求的参数数组
if($api=='direct'){
$parameter = array(
		"service"			=> "create_direct_pay_by_user",
		"payment_type"		=> "1",
		
		"partner"			=> trim($aliapy_config['partner']),
		"_input_charset"	=> trim(strtolower($aliapy_config['input_charset'])),
        "seller_email"		=> trim($aliapy_config['seller_email']),
        "return_url"		=> trim($aliapy_config['return_url']),
        "notify_url"		=> trim($aliapy_config['notify_url']),
		
		"out_trade_no"		=> $out_trade_no,
		"subject"			=> $subject,
		"body"				=> $body,
		"total_fee"			=> $total_fee,
		
		"paymethod"			=> $paymethod,
		"defaultbank"		=> $defaultbank,
		
		"anti_phishing_key"	=> $anti_phishing_key,
		"exter_invoke_ip"	=> $exter_invoke_ip,
		
		"show_url"			=> $show_url,
		"extra_common_param"=> $extra_common_param,
		
		"royalty_type"		=> $royalty_type,
		"royalty_parameters"=> $royalty_parameters
);
//构造即时到帐接口
$alipayService = new AlipayService($aliapy_config);
$html_text = $alipayService->create_direct_pay_by_user($parameter);
}elseif($api=='escow'){
	$parameter = array(
		"service"			=> "create_partner_trade_by_buyer",
		"payment_type"		=> "1",
		
		"partner"			=> trim($aliapy_config['partner']),
		"_input_charset"	=> trim(strtolower($aliapy_config['input_charset'])),
        "seller_email"		=> trim($aliapy_config['seller_email']),
        "return_url"		=> trim($aliapy_config['return_url']),
        "notify_url"		=> trim($aliapy_config['notify_url']),

        "out_trade_no"		=> $out_trade_no,
        "subject"			=> $subject,
        "body"				=> $body,
        "price"				=> $price,
		"quantity"			=> $quantity,
		
		"logistics_fee"		=> $logistics_fee,
		"logistics_type"	=> $logistics_type,
		"logistics_payment"	=> $logistics_payment,
		
		"receive_name"		=> $receive_name,
		"receive_address"	=> $receive_address,
		"receive_zip"		=> $receive_zip,
		"receive_phone"		=> $receive_phone,
		"receive_mobile"	=> $receive_mobile,
		
        "show_url"			=> $show_url
	);
//构造担保交易接口
$alipayService = new AlipayService($aliapy_config);
$html_text = $alipayService->create_partner_trade_by_buyer($parameter);
}else{
	$parameter = array(
		"service"		=> "trade_create_by_buyer",
		"payment_type"	=> "1",
		
		"partner"		=> trim($aliapy_config['partner']),
		"_input_charset"=> trim(strtolower($aliapy_config['input_charset'])),
		"seller_email"	=> trim($aliapy_config['seller_email']),
		"return_url"	=> trim($aliapy_config['return_url']),
		"notify_url"	=> trim($aliapy_config['notify_url']),

		"out_trade_no"	=> $out_trade_no,
		"subject"		=> $subject,
		"body"			=> $body,
		"price"			=> $price,
		"quantity"		=> $quantity,
		
		"logistics_fee"		=> $logistics_fee,
		"logistics_type"	=> $logistics_type,
		"logistics_payment"	=> $logistics_payment,
		
		"receive_name"		=> $receive_name,
		"receive_address"	=> $receive_address,
		"receive_zip"		=> $receive_zip,
		"receive_phone"		=> $receive_phone,
		"receive_mobile"	=> $receive_mobile,
		
		"show_url"		=> $show_url
);

//构造标准双接口
$alipayService = new AlipayService($aliapy_config);
$html_text = $alipayService->trade_create_by_buyer($parameter);
}

?>
<?php 
if (empty($total_fee)){
echo '<script language="javascript"> alert("价格获取错误，请联系站长或尝试刷新并重新提交！") </script> ';
}else{
?>
<html>
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>正在前往支付宝...</title>
    </head>
    <body>
<?php
	echo '<div  style="display:none">'.$html_text.'</div>'; 
}
?>
    </body>
</html>