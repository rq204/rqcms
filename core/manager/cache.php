<?php
if(!$action) $action='cache';

//系统管理包含一个 缓存生成 , 重新统计,日志查看
$cachedb=array();
$url = $admin_url.'?file=cache';

if(RQ_POST)
{
	if ($action == 'cache') {
		setting_recache();
		category_recache();
		redirect('所有缓存已经更新', $url);
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
		$cachefile=array(
		'category'=>'分类栏目',
		'setting'=>'配置文件');
		foreach($cachefile as $cfile=>$desc)
		{
			$filepath = RQ_DATA.'/caches/'.$cfile.'.php';
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