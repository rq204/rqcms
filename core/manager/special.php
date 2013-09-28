<?php
if(empty($action)) $action='list';
$sitedb=array();
$setting['name']=$setting['host']=$setting['host2']=$setting['hid']='';
$setting['url_ext']='php';
$files=array('index.php','category.php','article.php','attachment.php','search.php','comment.php','tag.php','profile.php','admin.php','captcha.php','rss.php','js.php','archive.php','link.php');
foreach($files as $f) $info[$f]='';
foreach($files as $f) $args[$f]='';
$hid=isset($_GET['hid'])?intval($_GET['hid']):'';
$curhostid=$hostid;
$curhost=$host;
$curmapArr=$mapArr;
$url_html1=$url_html0='';

if(RQ_POST)
{
	if($action=='add'||$action=='edit')
	{	
		if(!$hid) $hid=isset($_POST['setting']['hid'])?intval($_POST['setting']['hid']):'';
		$hidadd=!$hid?'':'&action=edit&hid='.$hid;
		$test=array();
		foreach($files as $k)
		{
			$postvalue=$_POST['maps'][$k];
			if(!$postvalue) redirect($k.'参数不能为空','admin.php?file=special'.$hidadd);
			if(!preg_match("/^\w*?$/i",$postvalue)) redirect($k.'参数不符合条件，只能是字母或数字','admin.php?file=special'.$hidadd);
			if(isset($test[$postvalue])) redirect($k.'参数和参数'.$test[$postvalue].'新文件名重复，请修改','admin.php?file=special'.$hidadd);
			$test[$postvalue]=$k;
		}
		$url_html=$_POST['setting']['url_html'];
		$url_ext=$_POST['setting']['url_ext'];
		if($url_ext&&!preg_match("/^\w*?$/i",$url_ext)) redirect('文件后缀只能是字母或数字','admin.php?file=special'.$hidadd);
		if(!$url_html&&!$url_ext) redirect('当使用动态网址后，必须指定文件后缀','admin.php?file=special'.$hidadd);
	}
	switch($action)
	{
		case 'add':
			if(!isset($_POST['setting'])) redirect('Setting参数不足','admin.php?file=special');
			if(!isset($_POST['maps'])) redirect('maps参数不足','admin.php?file=special');
			$hostname=$_POST['setting']['name'];
			$hosturl=$_POST['setting']['host'];
			$host2=$_POST['setting']['host2'];
			//先检查网址是否存在
			$exsits=$DB->fetch_first('Select * from '.DB_PREFIX."host where host='$hosturl'");
			if($exsits) redirect('该站点已经存在','admin.php?file=special');

			$sql="INSERT INTO `".DB_PREFIX."host` (`name`, `host`,`host2`, `gzipcompress`, `theme`, `password`, `keywords`, `description`, `icp`, `close`, `close_note`, `list_shownum`, `article_order`, `title_limit`, `tags_shownum`, `related_shownum`, `related_title_limit`, `related_order`, `audit_comment`, `comment_order`, `article_comment_num`, `comment_min_len`, `comment_max_len`, `commentlist_num`, `comment_post_space`, `allow_search_content`, `search_post_space`, `search_keywords_min_len`, `attach_save_dir`, `attach_thumbs`, `attach_display`, `attach_thumbs_size`, `attachments_remote_open`, `watermark`, `watermark_size`, `watermark_pos`, `watermark_trans`, `watermark_padding`, `server_timezone`, `time_article_format`, `time_comment_format`,`closereg`, `censoruser`, `banip_enable`, `ban_ip`, `spam_enable`, `spam_words`, `spam_url_num`, `js_enable`, `js_cache_life`, `js_lock_url`, `rss_enable`, `rss_num`, `rss_ttl`,`status`,`friend_url`,`listcachenum`) VALUES ('$hostname', '$hosturl','$host2', 0, 'default', '', 'CMS,RQCMS', '又一个RQCMS', '1234567890', 0, '服务器检修中,稍后开放', 10, 'articleid', 0, 10, 10, 0, 'dateline', 1, 0, 10, 10, 3000, 20, 10, 1, 10, 2, 2, 0, 2, '200x200', 1, 0, 150, 4, 10, 5, '8', 'Y-m-d', 'Y-m-d',0, 'admin', 0, '', 0,'', 0, 0, 3600, '', 1, 20, 3600,1,'aid',20)";
			$DB->query($sql);
			$insertid=$DB->insert_id();
			foreach($files as $k)
			{
				$filename=$_POST['maps'][$k];
				$mapArr=$_POST['args'][$k];
				$DB->query('Insert into '.DB_PREFIX."filemap (`hostid`,`original`,`filename`,`maps`) values ('$insertid','$k','$filename','$mapArr')");
			}
			$hostid=$insertid;
			$host=$DB->fetch_first('Select * from '.DB_PREFIX."host where hid='$insertid'");
			$mapArr=getFiles($hostid);
			hosts_recache();
			filemaps_recache();
			cates_recache();
			plugins_recache();
			links_recache();
			rss_recache();
			latest_recache();
			vars_recache();
			stick_recache();
			comments_recache();
			pics_recache();
			hot_recache();
			search_recache();
			$host=$curhost;
			$hostid=$curhostid;
			$mapArr=$curmapArr;
			redirect('新站点添加成功','admin.php?file=special');
		break;
		case 'edit':
			if(!$hid) redirect('缺少站点Id参数','admin.php?file=special');
			if(!isset($_POST['setting'])) redirect('Setting参数不足','admin.php?file=special');
			if(!isset($_POST['maps'])) redirect('maps参数不足','admin.php?file=special');
			$result=$DB->fetch_first('Select * from '.DB_PREFIX."host where hid=$hid");
			if(!$result) redirect('不存在的站点','admin.php?file=special');
			$hostname=$_POST['setting']['name'];
			$hosturl=$_POST['setting']['host'];
			$host2=$_POST['setting']['host2'];
			$url_html=$_POST['setting']['url_html'];
			$url_ext=$_POST['setting']['url_ext'];
			$DB->query('update '.DB_PREFIX."host set `host`='$hosturl',`host2`='$host2',`name`='$hostname',`url_html`=$url_html,`url_ext`='$url_ext' where hid=$hid");
			foreach($files as $k)
			{
				$filename=$_POST['maps'][$k];
				$mapArr=$_POST['args'][$k];
				$DB->query('Update '.DB_PREFIX."filemap set `filename`='$filename',`maps`='$mapArr' where `original`='$k' and `hostid`='$hid'");
			}
			$hostid=$hid;
			$host=$DB->fetch_first('Select * from '.DB_PREFIX."host where hid='$hid'");
			$mapArr=getFiles($hid);
			if($curhostid==$hid)
			{
				$curhost=$host;
				$curmapArr=$mapArr;
			}
			hosts_recache();
			filemaps_recache();
			cates_recache();
			rss_recache();
			latest_recache();
			comments_recache();
			pics_recache();
			stick_recache();
			hot_recache();
			search_recache();
			if($curhostid!=$hid)
			{
				$host=$curhost;
				$hostid=$curhostid;
				$mapArr=$curmapArr;
			}
			redirect('站点更新成功',mkUrl('admin.php','').'?file=special');
		break;
	}
}
else
{
	//先加载所有站点信息
	switch($action)
	{
		case "cacheall";
			$hquery=$DB->query('select * from '.DB_PREFIX."host");
			while($host=$DB->fetch_array($hquery))
			{
				$hostid=$host['hid'];
				$mapArr=getFiles($hostid);
				if($curhostid==$hid)
				{
					$curhost=$host;
					$curmapArr=$mapArr;
				}
				vars_recache();
				pics_recache();
				latest_recache();
				redirect_recache();
				hosts_recache();
				cates_recache();
				filemaps_recache();
				plugins_recache();
				comments_recache();
				links_recache();
				rss_recache();
				search_recache();
				stick_recache();
				hot_recache();
			}
			$host=$curhost;
			$hostid=$curhostid;
			$mapArr=$curmapArr;
			redirect('更新所有站点缓存成功',mkUrl('admin.php','').'?file=special');
			break;
		case 'list':
			$query=$DB->query('Select * from '.DB_PREFIX.'host');
			while($res=$DB->fetch_array($query))
			{
				if($res['status']) $res['status']='正常';
				else $res['status']='关闭';
				$sitedb[]=$res;
			}
			break;		
		case 'edit':
			$info=$args=array();
			if(!$hid) redirct('缺少站点Id参数');
			$setting=$DB->fetch_first('Select * from '.DB_PREFIX.'host where hid='.$hid);
			${'url_html'.$setting['url_html']}='selected';
			if(empty($setting)) redirct('不存在的站点id');
			$query=$DB->query('Select * from '.DB_PREFIX.'filemap where hostid='.$hid);
			while($fname=$DB->fetch_array($query))
			{
				$info[$fname['original']]=$fname['filename'];
				$args[$fname['original']]=$fname['maps'];
			}
			break;
		case 'bakup':
			break;
		case 'add':
			foreach($files as $f) $info[$f]=substr($f,0,-4);
			break;
		case 'go':
			if(!$hid) redirct('缺少站点Id参数');
			$nsessionid=urlencode($sessionid);
			$host=$DB->fetch_first('Select * from '.DB_PREFIX.'host where hid='.$hid);
			$rhost=$host['host'];
			$mapArr=getFiles($hid);
			$admin_url=mkUrl('admin.php','');
			if($host)
			{
				redirect('正在转向转站点'.$host['name'],RQ_HTTP."{$rhost}/{$admin_url}?sessionid={$nsessionid}");
				break;
			}
	}
}

function getFiles($hostid)
{
	global $DB,$host;
	$files= $DB->query('SELECT f.*,h.host,h.hid FROM `'.DB_PREFIX.'filemap` f,`'.DB_PREFIX.'host` h where h.hid=f.hostid and f.hostid='.$hostid);
	$arrfiles=array();
	while ($fs = $DB->fetch_array($files)) 
	{
		$args=array();
		if($fs['maps'])
		{
			$arr=explode(',',$fs['maps']);
			foreach($arr as $arg)
			{
				$ag=explode('=',$arg);
				if(count($ag)==2&&$ag[0]&&$ag[1]) $args[$ag[0]]=$ag[1];
			}
		}
		if(!$host['url_html']) $fs['filename']=$fs['filename'].'.'.$host['url_ext'];
		$arrfiles['file'][$fs['filename']]=$fs['original'];
		$arrfiles['arg'][$fs['filename']]=$args;
	}
	return $arrfiles;
}

?>