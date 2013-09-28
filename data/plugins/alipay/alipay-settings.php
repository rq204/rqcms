<?php
### Alipay Variables
$base_name = plugin_basename( __FILE__ );
$base_page = 'admin.php?page='.$base_name;

### If Form Is Submitted
if($_POST['Submit']) {
	$ali_partner        = trim($_POST['ali_partner']);
	$ali_security_code  = trim($_POST['ali_security_code']);
	$ali_seller_email   = trim($_POST['ali_seller_email']);
	$ali_api            = trim($_POST['ali_api']);
	$ali_return_url    = $_POST['ali_return_url'];
	$update_ali_queries = array();
	$update_ali_text    = array();
	$update_ali_queries[] = update_option('ali_partner', $ali_partner);
	$update_ali_queries[] = update_option('ali_security_code', $ali_security_code);
	$update_ali_queries[] = update_option('ali_seller_email', $ali_seller_email);
	$update_ali_queries[] = update_option('ali_api', $ali_api);
	$update_ali_queries[] = update_option('ali_return_url', $ali_return_url);
	$update_ali_text[] = '合作者身份(Partner ID)';
	$update_ali_text[] = '安全校验码(Key)';
	$update_ali_text[] = '支付宝收款账号';
	$update_ali_text[] = '接口类型';
	$update_ali_text[] = '交易完成返回页面';
	$i = 0;
	$text = '';
	foreach($update_ali_queries as $update_ali_query) {
		if($update_ali_query) {
			$text .= '<font color="green">'.$update_ali_text[$i].' 更新成功！</font><br />';
		}
		$i++;
	}
	if(empty($text)) {
		$text = '<font color="red">您对设置没有做出任何改动...</font>';
	}

}
### Needed Variables
$ali_partner       = get_option('ali_partner');
$ali_security_code = get_option('ali_security_code');
$ali_seller_email  = get_option('ali_seller_email');
$ali_api           = get_option('ali_api');
$ali_return_url    = get_option('ali_return_url');
?>
<div class="wrap">
<style>
#icon-wp-alipay {
	background: transparent url(<?php echo plugins_url( 'images/admin_icon.png', __FILE__ ); ?>) no-repeat;
	}
	.ali_icon{
		float: left;
		height: 45px;
		margin: 14px 6px 0 0;
		width: 125px;
	}
	.wrap h2{margin-top:20px;}
</style>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>" style="width:70%;float:left;">

	<div id="icon-wp-alipay" class="ali_icon"><br /></div>
        <h2>支付宝设置</h2>
        <h3>设置选项</h3>
        <table class="form-table">
            <tr>
                <td valign="top" width="30%"><strong>合作者身份(Partner ID)</strong><br />
                </td>
                <td>
                <input type="text" id="ali_partner" name="ali_partner" value="<?php echo $ali_partner ; ?>" maxlength="50" size="50" />
                </td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>安全校验码(Key)</strong><br />
                </td>
                <td>
                <input type="text" id="ali_security_code" name="ali_security_code" value="<?php echo $ali_security_code; ?>" maxlength="50" size="50" />
                </td>
            </tr>
                        <tr>
                <td valign="top" width="30%"><strong>支付宝收款账号</strong><br />
                </td>
                <td>
                <input type="text" id="ali_seller_email" name="ali_seller_email" value="<?php echo $ali_seller_email; ?>" maxlength="100" size="50" />
                </td>
            </tr>
                        <tr>
                <td valign="top" width="30%"><strong>支付宝接口类型</strong><br />
                </td>
                <td>
                <select id="ali_api" name="ali_api">
                  <option value ="direct" <?php if($ali_api=='direct') echo 'selected="selected"';?>>即时到帐</option>
                  <option value ="escow" <?php if($ali_api=='escow') echo 'selected="selected"';?>>担保交易</option>
                  <option value ="dualfun" <?php if($ali_api=='dualfun') echo 'selected="selected"';?>>双功能</option>
                </select>
                </td>
            </tr>                           <tr>
                <td valign="top" width="30%"><strong>交易完成跳转页面</strong><br />
                </td>
                <td>
                <input type="text" id="ali_return_url" name="ali_return_url" value="<?php echo $ali_return_url; ?>" maxlength="200" size="50" />
                不允许加?id=123这类自定义参数</td>
            </tr>
    </table>
        <br /> <br />
        <table> <tr>
        <td><p class="submit">
            <input type="submit" name="Submit" value="保存设置" class="button-primary"/>
            </p>
        </td>

        </tr> </table>

</form>
		<div class="postbox-container" style="width:21%;float:right;margin-top:80px;">
			<div class="metabox-holder">	
				<div class="meta-box-sortables">			
	     <div class="postbox">
			<h3 class="hndle"><span >关于支付宝插件</span></h3>
			  <div class="inside">
			            <a style="display:block;padding:5px;" target="_blank" href="http://www.iztwp.com/">爱主题</a>
			            <a style="display:block;padding:5px;" target="_blank" href="http://www.iztwp.com/wordpress-alipay.html">插件主页</a>
			            <a style="display:block;padding:5px;" target="_blank" href="http://wordpress.org/extend/plugins/wp-alipay/">WordPress官方目录</a>
			            <a style="display:block;padding:5px;" target="_blank" href="http://www.iztwp.com/">WordPress主题</a>
			            <a style="display:block;padding:5px;" target="_blank" href="http://weibo.com/iztme">爱主题官方微博</a>
						<a style="display:block;padding:5px;" target="_blank" href="http://www.iztwp.com/wordpress-alipay.html">报告BUG</a>
						<a style="display:block;padding:5px;" target="_blank" href="https://me.alipay.com/iztme">捐赠我们</a>
				</div>
			</div>
			</div>
			</div>
			</div>
			</div>