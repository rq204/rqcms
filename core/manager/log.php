<?php
if(!in_array($do,array('login','search','dberror'))) redirect('未定义操作',$url.'&action=log');
$url.='&action=log&do='.$do;

if(RQ_POST)
{
	$delnum=500;
	if($do=='dberror')
	{
		$logfilename = RQ_DATA.'/logs/dberror.php';
		if(file_exists($logfilename))
		{
			$logs = @file($logfilename);
			if(count($logs)>$delnum)//超过500的删除掉
			{
				$logs=array_slice($logs,0-$delnum);
				$data=implode("\t",$logs);
				@$fp = fopen($logfilename, 'w');
				@fwrite($fp, $data);
				@fclose($fp);
			}
			else redirect("日志不足$delnum,不需要执行删除操作",$url);
		}
		redirect('删除数据库日志成功',$url);
	}
	else
	{
		$num=$DB->fetch_first("SELECT count(*) FROM {$dbprefix}login");
		if($num['count(*)']>$delnum)
		{
			$delnum2=$num['count(*)']-$delnum;
			$DB->fetch_first("Delete FROM {$dbprefix}login order by lid limit $delnum2");
			redirect('日志删除成功'.$delnum.'条',$url);
		}
		else
		{
			redirect("日志不足$delnum,不需要执行删除操作",$url);
		}
	}

}
else
{
	if($page) 
	{
		$start_limit = ($page - 1) * 30;
	}
	else 
	{
		$start_limit = 0;
		$page = 1;
	}
	if($do=='dberror')
	{
		//todo查询文本文件
		$logfilename = RQ_DATA.'/logs/dberror.php';
		$searchdb = array();
		$total     = 0;
		if(file_exists($logfilename))
		{
			$logs = @file($logfilename);
			$total     = count($logs);
			if($total>0)
			{
				if($page>$total) $page=$total;
				$logs=array_slice($logs,($page-1)*30,30);
				foreach($logs as $log)
				{
					$arr=explode("\t",$log);
					$dateline = date('Y-m-d H:i',$arr[1]);
					$searchdb[]=array('user'=>$arr[0],'dateline'=>$dateline,'ip'=>$arr[2],'useragent'=>$arr[4],'content'=>$arr[3]);
				}
			}
		}
		else
		{
			$logs=array();
		}

		$multipage = multi($total, 30, $page, "{$admin_url}?file=maintenance&action=log&do=$do");
	}
	else if($do=='search')
	{
		$searchs  = $DB->query("SELECT * FROM {$dbprefix}$do");
		$total     = $DB->num_rows($searchs);
		$multipage = multi($total, 30, $page, "{$admin_url}?file=maintenance&action=log&do=$do");
		$searchdb = array();
		$query = $DB->query("SELECT * FROM {$dbprefix}$do ORDER BY sid DESC LIMIT $start_limit, 30");
		while ($search = $DB->fetch_array($query)) {
			$search['dateline'] = date('Y-m-d H:i',$search['dateline']);
			$searchdb[] = $search;
		}//end while
		unset($search);
		$DB->free_result($query);
	}
	else if($do=='login')
	{	
		$searchs  = $DB->query("SELECT * FROM {$dbprefix}$do");
		$total     = $DB->num_rows($searchs);
		$multipage = multi($total, 30, $page, "{$admin_url}?file=maintenance&action=log&do=$do");
		$searchdb = array();
		$query = $DB->query("SELECT * FROM {$dbprefix}$do ORDER BY lid DESC LIMIT $start_limit, 30");
		while ($search = $DB->fetch_array($query)) {
			$search['dateline'] = date('Y-m-d H:i',$search['dateline']);
			$searchdb[] = $search;
		}//end while
		unset($search);
		$DB->free_result($query);
	}
}