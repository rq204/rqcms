<?php
$settingsmenu = array(
	'basic' => '基本设置',
	'display' => '显示设置',
	'comment' => '评论设置',
	'search' => '搜索设置',
	'attach' => '附件设置',
	'watermark' => '水印设置',
	'dateline' => '时间设置',
	'user' => '用户设置',
	'ban' => '限制设置',
	'js' => 'JS调用设置',
	'rss' => 'RSS订阅设置',
);
$type=isset($_GET['type'])?$_GET['type']:(isset($_POST['type'])?$_POST['type']:'');
if(RQ_POST&&isset($_POST['action'])&&$_POST['action'] == 'updatesetting')
{
	if(isset($_POST['setting']['search_field_allow'])&&!$_POST['setting']['search_field_allow']) redirect('搜索字段不得为空', 'admin.php?file=configurate&type='.$type);
	if(isset($_POST['host'])) unset($_POST['host']);
	$sql='Update '.DB_PREFIX.'host set ';
	foreach($_POST['setting'] AS $key => $val)
	{
		$sql.="`$key`='$val',";
	}
	$sql=substr($sql,0,strlen($sql)-1);
	$sql.=' where `hid`='.$hostid;
	$DB->query($sql);
	hosts_recache();
	//如果更新了显示方式，这些缓存文件得更新
	if(isset($_POST['setting']['friend_url']))
	{
		$host['friend_url']=$_POST['setting']['friend_url'];
		filemaps_recache();
		rss_recache();
		stick_recache();
		pics_recache();
		latest_recache();
		cates_recache();
	}
	redirect('更新系统配置成功', 'admin.php?file=configurate&type='.$type);
}
else
{
	$query=$DB->query("Select * from `".DB_PREFIX."host` where `hid`='$hostid'");
	$settings=$DB->fetch_array($query);
	
	//基本设置
	ifselected('close');
	ifselected('gzipcompress');
	
	//显示设置
	$article_order['dateline'] =$article_order['articleid']= '';
	$article_order[$settings['article_order']] = 'selected';
	$friend_url['aid'] =$friend_url['oid']=$friend_url['url']= '';
	$friend_url[$settings['friend_url']] = 'selected';
	$related_order['dateline']=$related_order['views']=$related_order['comments']='';
	$related_order[$settings['related_order']] = 'selected';
	
	//评论设置
	ifselected('guest_comment');
	ifselected('audit_comment');
	ifselected('comment_order');
	
	//搜索设置
	ifselected('allow_search_content');
	
	//附件设置
	$attach_save_dir[0]=$attach_save_dir[1]=$attach_save_dir[2]=$attach_save_dir[3]='';
	$attach_save_dir[$settings['attach_save_dir']]='selected';
	$attach_display[0]=$attach_display[1]=$attach_display[2]='';
	$attach_display[$settings['attach_display']]='selected';
	ifselected('attach_thumbs');
	ifselected('attach_remote_open');

	//水印设置
	ifselected('watermark');
	for($i=1;$i<10;$i++) $watermark_pos[$i]='';
	$watermark_pos[$settings['watermark_pos']] = 'selected';
	
	//时间设置
	for($i=0;$i<13;$i++) ${'zone_0'.$i}=${'zone_'.$i}='';
	$zone_03_5=$zone_111=$zone_3_5=$zone_4_5=$zone_5_5=$zone_9_5='';
	$settings['server_timezone'] < 0 ? ${'zone_0'.str_replace('.','_',abs($settings['server_timezone']))}='selected' : ${'zone_'.str_replace('.','_',$settings['server_timezone'])}='selected';
	
	//用户设置
	ifselected('closereg');
	
	//WAP设置
	ifselected('wap_enable');
	
	//限制设置
	ifselected('banip_enable');
	ifselected('spam_enable');
	
	//JS调用设置
	ifselected('js_enable');
		
	//RSS订阅设置
	ifselected('rss_enable');
	
	//是否远程查看附件
	ifselected('attachments_remote_open');
}

function ifselected($varArr) {
	global $settings,${$varArr.'_Y'},${$varArr.'_N'};
	if(isset($settings[$varArr])&&$settings[$varArr]) {
		${$varArr.'_Y'} = 'selected';
	} else {
		${$varArr.'_N'} = 'selected';
	}
}
?>