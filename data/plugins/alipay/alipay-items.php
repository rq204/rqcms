<?php
$base_name = plugin_basename( __FILE__ );

// Get trade Logs Data
$total_trade   = $wpdb->get_var("SELECT COUNT(alipay_id)     FROM $wpdb->alipay");
$total_success = $wpdb->get_var("SELECT COUNT(alipay_status) FROM $wpdb->alipay WHERE alipay_status = '交易成功'");
$total_money   = $wpdb->get_var("SELECT SUM(alipay_price)    FROM $wpdb->alipay WHERE alipay_status = '交易成功'");

// Checking $alipay_page and $offset
$alipay_log_perpage = 20;
$pages = intval($total_trade / $alipay_log_perpage);
if($total_trade%$alipay_log_perpage)
$pages++;
if(isset($_GET['alipage'])){
	$page = intval($_GET['alipage']);
}else{
	$page = 1;
}
$offset = $alipay_log_perpage*($page - 1);

// Get The Logs
$alipay_logs = $wpdb->get_results("SELECT * FROM $wpdb->alipay  WHERE 1=1 order by alipay_time DESC limit $offset,$alipay_log_perpage");
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
	.pages{
		display:block;
		text-align:center;
		margin-top:10px;
	}
	.pages span{
		margin-right:3px;
	}
	.wrap h2{padding-top:30px;}
</style>
	<div id="icon-wp-alipay" class="ali_icon"></div>
	<h2>订单查询</h2>
	<p><?php printf(('共有<strong>%s</strong>笔交易, 其中<strong>%s</strong>笔交易完成了付款, 收入￥<strong>%s</strong>'), number_format_i18n($total_trade), number_format_i18n($total_success),$total_money); ?></p>
	<table class="widefat">
		<thead>
			<tr>
				<th width="15%">订单号</th>
				<th width="15%">商品名称</th>
				<th width="5%">价格</th>
				<th width="17%">支付宝</th>
				<th width="10%">QQ</th>	
				<th width="15%">网站</th>
				<th width="15%">交易时间</th>			
				<th width="8%">交易状态</th>			
			</tr>
		</thead>
		<tbody>
	<?php
		if($alipay_logs) {
			$i = 0;
			foreach($alipay_logs as $alipay_log) {
				if($i%2 == 0) {
					$style = 'class="alternate"';
				}  else {
					$style = '';
				}
				$ali_num    = trim($alipay_log->alipay_num);
				$ali_title  = $alipay_log->alipay_title;
				$ali_price  = trim($alipay_log->alipay_price);
				$ali_email  = trim($alipay_log->alipay_email);
				$ali_qq     = trim($alipay_log->alipay_qq);
				$ali_site   = trim($alipay_log->alipay_site);
				$ali_time   = $alipay_log->alipay_time;
				$ali_status = trim($alipay_log->alipay_status);

				echo "<tr $style>\n";
				echo "<td>$ali_num</td>\n";
				echo "<td>$ali_title</td>\n";
				echo "<td>$ali_price</td>\n";
				echo "<td>$ali_email</td>\n";
				echo "<td>$ali_qq</td>\n";
				echo "<td>$ali_site</td>\n";
				echo "<td>$ali_time</td>\n";
				echo "<td>$ali_status</td>\n";
				echo "</tr>";
				$i++;
			}
		} else {
			echo '<tr><td colspan="7" align="center"><strong>没有交易记录</strong></td></tr>';
		}
	?>
	</tbody>
	</table>
    <div class="pages"><a class=page>共有<?php echo $pages ?>页  当前页：<?php echo $page ?>/<?php echo $pages ?></a> <span><a href='?page=<?php echo $base_name.'&alipage=1' ?>'>第一页</a></span>
     <?php if(!empty($_GET['alipage'])&&$_GET['alipage']!=1){?><span><a href="?page=<?php echo $base_name?>&alipage=<?php echo $page-1 ?>">上一页</a></span><?php } ?> 
	 <?php for($i=$page-4;$i<$_GET['alipage']&&$i>0;$i++){?><span><a href='?page=<?php echo $base_name.'&alipage='.$i ?>'><?php echo $i ?></a> <?php } ?></span> 
     <a>[<?php echo $page ?>]</a> 
	 <?php for ($i=$page+1;$i<=$_GET['alipage']+4&&$i<=$pages;$i++) {?><span><a href="?page=<?php echo $base_name?>&alipage=<?php echo $i ?>"><?php echo $i ?></a></span> 
	 <?php } ?>
	 <?php if($page!=$pages){?><span><a href="?page=<?php echo $base_name?>&alipage=<?php echo $page+1 ?>">下一页</a></span><?php } ?>
     <?php if($page!=$pages){?><span><a href="?page=<?php echo $base_name?>&alipage=<?php echo $pages ?>">最后一页</a></span><?php } ?>
　　</div>
<br />
						<div style="text-align:center;background:#eee;margin:10px;padding:5px;">
			            <a style="padding:5px;" target="_blank" href="http://www.iztwp.com/">爱主题</a> >
			            <a style="padding:5px;" target="_blank" href="http://www.iztwp.com/wordpress-alipay.html">插件主页</a> >
			            <a style="padding:5px;" target="_blank" href="http://wordpress.org/extend/plugins/wp-alipay/">WordPress官方目录</a> >
			            <a style="padding:5px;" target="_blank" href="http://www.iztwp.com/">WordPress主题</a> >
			            <a style="padding:5px;" target="_blank" href="http://weibo.com/iztme">爱主题官方微博</a> >
						<a style="padding:5px;" target="_blank" href="http://www.iztwp.com/wordpress-alipay.html">报告BUG</a> >
						<a style="padding:5px;" target="_blank" href="https://me.alipay.com/iztme">捐赠我们</a>
                        </div>