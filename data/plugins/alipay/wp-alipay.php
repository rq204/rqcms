<?php
/*
Plugin Name: WordPress支付宝插件
Plugin URI: http://www.iztwp.com/wordpress-alipay.html
Description: 专为wordpress开发的一款支付宝付款接口的插件，集成了即时到帐、担保交易和双功能接口
Version: 2.1
Author: 爱主题
Author URI: http://www.iztwp.com/
*/
/*  Copyright 2012  爱主题  (Homepage:http://www.iztwp.com/  E-Mail:whyun@vip.qq.com)  */

### Load WP-Config File If This File Is Called Directly
if (!function_exists('add_action')) {
	$wp_root = '../../..';
	if (file_exists($wp_root.'/wp-load.php')) {
		require_once($wp_root.'/wp-load.php');
	} else {
		require_once($wp_root.'/wp-config.php');
	}
}
### Alipay Logs Table Name
global $wpdb;
$wpdb->alipay = $wpdb->prefix.'alipay';

### Function: Alipay Administration Menu
add_action('admin_menu', 'alipay_menu');
function alipay_menu() {
	if(function_exists('add_menu_page')) {
		add_menu_page('alipay', '支付宝', 'administrator', plugin_dir_path(__FILE__).'/alipay-settings.php', '', plugins_url('images/ali_ico.gif', __FILE__ ));
	}
	if(function_exists('add_submenu_page')) {
		add_submenu_page(plugin_dir_path(__FILE__).'/alipay-settings.php', '账号设置','账号设置', 'administrator', plugin_dir_path(__FILE__).'/alipay-settings.php');
		add_submenu_page(plugin_dir_path(__FILE__).'/alipay-settings.php', '订单查询', '订单查询',  'administrator', plugin_dir_path(__FILE__).'/alipay-items.php');
	}
}
?>
<?php
function alipay_style() {
	echo'<link rel="stylesheet" href="'.plugins_url('css/alipay.css', __FILE__ ).'" type="text/css" />';
}
add_action('wp_head', 'alipay_style');

### Function: Create Logs Table
add_action('activate_wp-alipay/wp-alipay.php', 'create_alipay_table');
function create_alipay_table() {
	global $wpdb;
	if(@is_file(ABSPATH.'/wp-admin/upgrade-functions.php')) {
		include_once(ABSPATH.'/wp-admin/upgrade-functions.php');
	} elseif(@is_file(ABSPATH.'/wp-admin/includes/upgrade.php')) {
		include_once(ABSPATH.'/wp-admin/includes/upgrade.php');
	} else {
		die('We have problem finding your \'/wp-admin/upgrade-functions.php\' and \'/wp-admin/includes/upgrade.php\'');
	}
	$charset_collate = '';
	if($wpdb->supports_collation()) {
		if(!empty($wpdb->charset)) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if(!empty($wpdb->collate)) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
	}
	// Create alipay Table
	$create_alipaylogs_sql = "CREATE TABLE $wpdb->alipay (".
			"alipay_id INT(11) NOT NULL auto_increment,".
			"alipay_num BIGINT(18) NOT NULL,".
			"alipay_post INT(11) NOT NULL,".
			"alipay_title TinyText NOT NULL,".
			"alipay_price double(10,2) NOT NULL,".
			"alipay_qq INT(15),".
			"alipay_email VARCHAR(50),".
			"alipay_site VARCHAR(100),".
			"alipay_time datetime NOT NULL ,".
			"alipay_status VARCHAR(25),".
			"alipay_dl VARCHAR(10),".
			"PRIMARY KEY (alipay_id)) $charset_collate;";
	maybe_create_table($wpdb->alipay, $create_alipaylogs_sql);
}
function alipay_form(){
	$id = get_the_ID();
	$money = get_post_meta($id, 'ali_price', true);
	$description = get_post_meta($id, 'ali_description', true);
	$link = get_permalink($id);
	$date = date(Ymdhms);
	$actionurl= plugins_url('alipayto.php', __FILE__ );
	
 $ali_display = get_post_meta($id, 'ali_display', true);
  if($ali_display == 'yes')             
echo<<<ALIPAY
  <div id="alipay">
    <form name="alipayment" action="$actionurl" method="post" target="_blank">
	<div class="ali-form">
	<div class="ali-info">
      <ul>
        <li>
          <p>QQ</p>
          <input type="text" name="qq">
        </li>
        <li>
          <p>网址</p>
          <input type="text" name="site">
        </li>
        <li>
          <p>支付宝</p>
          <input type="text" name="alipay">
        </li>
      </ul></div>
<div class="ali-buy"> <span>￥<em>$money</em></span>
<input type="submit" class="btn" value="" />
        </div>
      <input type="hidden" name="from_url" value="$link">
      <input type="hidden" maxLength=10 size=30 name="id"  value="$id"/>
      <input type="hidden" name="alibody" value="$description">
      <input type="hidden" name="time" value="$date">
      <input type="hidden" name="pay_bank" value="directPay">
	  </div>
    </form>
    <div class="ali-desc">
	<strong>说明:</strong><br>$description</div>
    <div style=" clear:both"></div>
</div>
<!--alipay end-->
ALIPAY;
}

function alipay($atts) {
	 extract(shortcode_atts(array(
	      'id' => get_the_ID(),
     ), $atts));
return '
  <div id="alipay">
    <form name="alipayment" action="'.plugins_url('alipayto.php', __FILE__ ).'" method="post" target="_blank">
	<div class="ali-form">
	<div class="ali-info">
      <ul>
        <li>
          <p>QQ</p>
          <input type="text" name="qq">
        </li>
        <li>
          <p>网址</p>
          <input type="text" name="site">
        </li>
        <li>
          <p>支付宝</p>
          <input type="text" name="alipay">
        </li>
      </ul></div>
<div class="ali-buy"> <span>￥<em>'.get_post_meta($id, 'ali_price', true).'</em></span>
<input type="submit" class="btn" value="" />
        </div>
      <input type="hidden" name="from_url" value="'.get_permalink($post->ID).'">
      <input type="hidden" maxLength=10 size=30 name="id"  value="'.$id.'"/>
      <input type="hidden" name="alibody" value="'.get_post_meta($id, 'ali_description', true).'">
      <input type="hidden" name="time" value="'.date(Ymdhms).'">
      <input type="hidden" name="pay_bank" value="directPay">
	  </div>
    </form>
    <div class="ali-desc">
	<strong>说明:</strong><br>
'.get_post_meta($id, 'ali_description', true).'</div>
    <div style=" clear:both"></div>
</div>
<!--alipay end-->';
}
add_shortcode("alipay", "alipay");
?>
<?php include('meta-box.php'); ?>