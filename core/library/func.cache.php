<?php
// 站点缓存更新
function hosts_recache()
{
	global $DB;
	$contents=array();
	$hosts = $DB->query('SELECT * FROM `'.DB_PREFIX.'host`');
	while ($arrhosts = $DB->fetch_array($hosts)) 
	{
		$contents[$arrhosts['host']]=$arrhosts;
		if(isset($arrhosts['host2'])&&$arrhosts['host2']!='')
		{
			$hostarr=explode(',',$arrhosts['host2']);
			foreach($hostarr as $ha)
			{
				if($ha&&!isset($contents[$ha])) $contents[$ha]=$arrhosts;
			}
		}
	}
	writeCache('hosts',$contents);
}

// 插件缓存
function plugins_recache() {
	global $DB;
	$query= $DB->query('SELECT p.*,h.host FROM `'.DB_PREFIX.'plugin` p,`'.DB_PREFIX.'host` h where p.hostid=h.hid and p.active=1');
	$plugins=array();
	while ($ps = $DB->fetch_array($query)) 
	{
		$plugins[$ps['host']][$ps['file']]=$ps['config'];
	}
	writeCache('plugins',$plugins);
}

// 链接缓存
function links_recache()
{
	global $DB;
	$links = $DB->query('SELECT l.*,h.host,h.hid FROM `'.DB_PREFIX.'link` l,`'.DB_PREFIX.'host` h WHERE l.visible = 1 and l.hostid=h.hid ORDER BY l.displayorder ASC, l.name ASC');
	$linkdb = array();
	while ($link = $DB->fetch_array($links))
	{
		$linkdb[$link['host']][] = $link;
	}
	unset($link);
	writeCache('links',$linkdb);
}

// 更新映射文件
function filemaps_recache()
{
	global $DB,$host,$hostid;
	$files= $DB->query('SELECT f.*,h.host,h.hid FROM `'.DB_PREFIX.'filemap` f,`'.DB_PREFIX.'host` h where h.hid=f.hostid and f.hostid='.$hostid);
	$arrfiles=array();
	$hostname='';
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
		$hostname=$fs['host'];
	}
	writeCache('map_'.$hostname,$arrfiles);
}


// 评论缓存
function comments_recache()
{
	global $DB,$hostid,$host;
	$comments = $DB->query('SELECT * from `'.DB_PREFIX."comment` WHERE visible = 1 and hostid='$hostid' ORDER BY cid desc limit 100");
	$commentdb = array();
	while ($comment= $DB->fetch_array($comments))
	{
		$comment['url']=mkUrl('comment.php', $comment['cid']);
		$commentdb[] = $comment;
	}
	writeCache('comment_'.$host['host'],$commentdb);
}



// rss缓存
function rss_recache()
{
	global $DB,$host,$hostid;
	$rquery= $DB->query('SELECT aid FROM `'.DB_PREFIX.'article` where hostid='.$hostid.' ORDER BY aid DESC limit '.$host['rss_num']);
	$aids=array();
	$arrfiles=array();
	while($rss=$DB->fetch_array($rquery))
	{
		$aids[]=$rss['aid'];
	}
	if(count($aids)>0)
	{
		$aid=implode_ids($aids);
		$rquery= $DB->query('SELECT * FROM `'.DB_PREFIX."article` where aid in ($aid) order by aid desc");
		while($rss=$DB->fetch_array($rquery))
		{
			$arrfiles[]=showArticle($rss);
		}
	}
	writeCache('rss_'.$host['host'],$arrfiles);
}

// 置顶
function stick_recache()
{
	global $DB,$host,$hostid;
	$arrfiles=array();
	$files= $DB->query('SELECT * FROM `'.DB_PREFIX.'article` where stick=1 and hostid='.$hostid.' and visible=1 ORDER BY aid DESC limit 200');
	while ($fs = $DB->fetch_array($files)) 
	{
		unset($fs['content']);
		$arrfiles[]=showArticle($fs);
	}
	writeCache('stick_'.$host['host'],$arrfiles);
}

//分类及系统设置参数
function cates_recache()
{
	global $DB,$host,$hostid;
	$cquery= $DB->query('SELECT * FROM `'.DB_PREFIX."category` where hostid='$hostid' order by displayorder asc");
	$arrcates=array();
	while($cate=$DB->fetch_array($cquery))
	{
		$cate['curl']=mkUrl('category.php',$cate['url'],0);
		$arrcates[$cate['cid']]=$cate;
	}
	
	foreach($arrcates as $k=>$v)
	{
		$count='0';
		$countarr=$DB->fetch_first('SELECT count(*) as ct FROM `'.DB_PREFIX."article` where visible=1 and cateid='{$cate['cid']}'");
		if(is_array($countarr)) $count=$countarr['ct'];
		$arrcates[$k]['count']=$count;
		$arrcates[$k]['child']=getChildCate($k,$arrcates);
	}
	
	writeCache('cate_'.$host['host'],$arrcates);
}

//全局变量参数
function vars_recache()
{
	global $DB,$host,$hostid;
	$varArr= $DB->query('SELECT * FROM `'.DB_PREFIX."var` where visible=1 and hostid=$hostid");
	$arrvars=array();
	while ($fs = $DB->fetch_array($varArr)) 
	{
		$arrvars[$fs['title']]=$fs['value'];
	}
	writeCache('var_'.$host['host'],$arrvars);
}

//图片文章
function pics_recache()
{
	global $DB,$host,$hostid;
	$varArr= $DB->query('SELECT a.*,d.* FROM `'.DB_PREFIX.'article` a,'.DB_PREFIX."attachment d where a.thumb>0 and a.thumb=d.aid and a.hostid=d.hostid and a.visible=1 and a.hostid=$hostid order by a.aid desc limit 20");
	$arrvars=array();
	while ($fs = $DB->fetch_array($varArr)) 
	{
		$arrvars[]=showArticle($fs);
	}
	writeCache('pic_'.$host['host'],$arrvars);
}

function latest_recache()
{
	global $DB,$host,$hostid;
	$query= $DB->query('SELECT cid,name,url,hostid from '.DB_PREFIX."category where hostid=$hostid and visible=1");
	$cache=array();
	$cate0=array();
	while($catearr=$DB->fetch_array($query))
	{
		$cid=$catearr['cid'];
		$artquery=$DB->query('Select * from '.DB_PREFIX."article where hostid=$hostid and visible=1 and cateid=$cid order by aid desc limit {$host['listcachenum']}");
		while($artarr=$DB->fetch_array($artquery))
		{
			unset($artarr['content']);
			$cache['article'][$artarr['aid']]=showArticle($artarr);
			$cache['cateids'][$cid][]=$artarr['aid'];
			$cate0[]=$artarr['aid'];
		}
		if($DB->num_rows($artquery)==0)
		{
			$cache['cateids'][$cid]=array();
		}
	}
	sort($cate0);
	$cache['cateids'][0]=array_reverse($cate0);
	writeCache('latest_'.$host['host'],$cache);
}

// 更新自动转向
function redirect_recache()
{
	global $DB,$host,$hostid;
	$varArr= $DB->query('SELECT * FROM `'.DB_PREFIX."redirect` where hostid=$hostid");
	$arrvars=array();
	while ($fs = $DB->fetch_array($varArr)) 
	{
		$arrvars[$fs['old']]=array($fs['new'],$fs['status']);
	}
	writeCache('redirect_'.$host['host'],$arrvars);
}

//阅读排行的文章
function hot_recache()
{
	global $DB,$host,$hostid;
	$query=$DB->query('Select * from '.DB_PREFIX."article where hostid=$hostid and visible=1 order by views desc limit 100");
	$cache=array();
	while($article=$DB->fetch_array($query))
	{
		$cache[]=showArticle($article);
	}
	writeCache('hot_'.$host['host'],$cache);
}

//最新100条搜索内容
function search_recache()
{
	global $DB,$host,$hostid;
	$query=$DB->query('Select distinct keywords from '.DB_PREFIX."search where hostid=$hostid order by dateline desc limit 100");
	$cache=array();
	while($data=$DB->fetch_array($query))
	{
		$cache[]=$data[keywords];
	}
	writeCache('search_'.$host['host'],$cache);
}