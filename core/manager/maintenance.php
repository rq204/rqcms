<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
if(!$action) $action='cache';

//系统管理包含一个 缓存生成 , 重新统计,日志查看
$cachedb=array();
$url = 'admin.php?file=maintenance';

if(RQ_POST)
{
	if ($action == 'cache') {
		filemaps_recache();
		plugins_recache();
		links_recache();
		stick_recache();
		comments_recache();
		rss_recache();
		cates_recache();
		vars_recache();
		pics_recache();
		latest_recache();
		hot_recache();
		search_recache();
		redirect('所有缓存已经更新', $url);
	}
	else if($action == 'log') 
	{
		include RQ_CORE.'/manager/log.php';
	}
}
else
{
	if($action=='log'&&!in_array($do,array('login','search','dberror'))) $do='login';
	if($action == 'log') 
	{
		include RQ_CORE.'/manager/log.php';
	}
	else if($action=='cache')
	{
		$cachefile=array('rss_'.$host['host']=>'Rss文件',
		'var_'.$host['host']=>'自定义模板变量',
		'map_'.$host['host']=>'映射文件',
		'comments_'.$host['host']=>'最新评论',
		'stick_'.$host['host']=>'置顶文章',
		'tag_'.$host['host']=>'热门Tag文件',
		'pic_'.$host['host']=>'包含图片的文章',
		'redirect_'.$host['host']=>'自动跳转设置',
		'latest_'.$host['host']=>'栏目最新文件',
		'search_'.$host['host']=>'最新搜索的100条记录',
		'hot_'.$host['host']=>'阅读排行文件');
		foreach($cachefile as $cfile=>$desc)
		{
			$filepath = RQ_DATA.'/cache/'.$cfile.'.php';
			if(is_file($filepath))
			{
				$cachefile['name'] = $cfile.'.php';
				$cachefile['desc'] = $desc;
				$cachefile['size'] = sizecount(filesize($filepath));
				$cachefile['mtime'] = date('Y-m-d H:i',@filemtime($filepath));
				$cachedb[] = $cachefile;
			}
		}
	}
}