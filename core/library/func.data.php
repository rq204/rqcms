<?php
if(!defined('RQ_ROOT')) exit('Access Denied');

//得到友情链接$num条
function getLink($num=null)
{
	global $host;
	$linkarr=array();
	$linkarray=@include RQ_DATA.'/cache/links.php';
	if($linkarray&&is_array($linkarray)&&isset($linkarray[$host['host']])) $linkarr=$linkarray[$host['host']];
	if($num>0&&count($linkarr)>$num) $linkarr=array_slice($linkarr, 0, $num); 
	return $linkarr;
}

//得到最新$num条$cateid分类的文章
function getLatestArticle($num,$cateid=0)
{
	global $host;
	$articledb=$ids=array();
	$latestarray=@include RQ_DATA.'/cache/latest_'.$host['host'].'.php';
	if(!empty($latestarray)&&isset($latestarray['cateids'][$cateid]))
	{
		$aids=$latestarray['cateids'][$cateid];
		if(!empty($aids))
		{
			if(count($aids)>$num) $aids=array_slice($aids, 0, $num); 
			foreach($aids as $aid) $articledb[]=$latestarray['article'][$aid];
		}
	}
	return $articledb;
}

//得到图片文章
function getPicArticle($num)
{
	global $host;
	$picarray=@include RQ_DATA.'/cache/pic_'.$host['host'].'.php';
	if(!$picarray) $picarray=array();
	if($num>0&&count($picarray)>$num) $picarray=array_slice($picarray, 0, $num); 
	return $picarray;
}

//error_log("You messed up!", 3, RQ_DATA."\logs\dd.txt");

//得到置顶的$num条$cateid分类文章
function getStickArticle($num,$cateid=null)
{
	global $host,$DB;
	$stickdata=@include RQ_DATA.'/cache/stick_'.$host['host'].'.php';
	if(!$stickdata) $stickdata=array();
	$arrdata=array();
	if($cateid==null)
	{
		$arrdata=$stickdata;
	}
	else
	{
		foreach($stickdata as $sdata)
		{
			if($sdata['cateid']==$cateid) $arrdata[]=$sdata;
		}
		if(count($arrdata)<$num) //少于的话还是查询一下数据库为好
		{	
			$arrdata=array();
			$files= $DB->query('SELECT * FROM `'.DB_PREFIX.'article` where stick=1 and hostid='.$host['hid']." and cateid=$cateid and visible=1 ORDER BY aid DESC limit $num");
			while ($fs = $DB->fetch_array($files)) 
			{
				unset($fs['content']);
				$arrdata[]=showArticle($fs);
			}
		}
	}
	
	if(count($arrdata)>$num) 
	{
		$arrdata=array_slice($arrdata, 0, $num);
	}
	
	return $arrdata;
}

//得到最新的$num条$cateid分类文章评论
function getLatestComment($num,$cateid=null)
{
	global $host;
	$commentdata=@include RQ_DATA.'/cache/comment_'.$host['host'].'.php';
	if(!$commentdata) $commentdata=array();
	if(count($commentdata)>$num) $commentdata=array_slice($commentdata, 0, $num); 
	return $commentdata;
}

//得到热门文章
function getHotArticle($num,$cateid=null)
{
	global $host,$DB;
	$hotdata=@include RQ_DATA.'/cache/hot_'.$host['host'].'.php';
	if(!$hotdata) $hotdata=array();
	$arrdata=array();
	if($cateid==null)
	{
		$arrdata=$hotdata;
	}
	else
	{
		foreach($hotdata as $sdata)
		{
			if($sdata['cateid']==$cateid) $arrdata[]=$sdata;
		}
		if(count($arrdata)<$num) //少于的话还是查询一下数据库为好
		{	
			$arrdata=array();
			$files= $DB->query('SELECT * FROM `'.DB_PREFIX.'article` where hostid='.$host['hid']." and cateid=$cateid and visible=1 ORDER BY views desc  limit $num");
			while ($fs = $DB->fetch_array($files)) 
			{
				unset($fs['content']);
				$arrdata[]=showArticle($fs);
			}
		}
	}
	if(count($arrdata)>$num) 
	{
		$arrdata=array_slice($arrdata, 0, $num);
	}
	return $arrdata;
}

//得到相关文章
function getRelatedArticle($aid,$tagarr,$num)
{
	global $DB,$hostid,$host;
	$articledb=array();
	$tag="'".implode("','",$tagarr)."'";
	$query=$DB->query('Select distinct articleid from '.DB_PREFIX."tag where tag in ($tag) and articleid!=$aid");
	$aidarr=array();
	while($aq=$DB->fetch_array($query))
	{
		$aidarr[]=$aq['articleid'];
	}
	if(!empty($aidarr))
	{
		$aids=implode_ids($aidarr);
		$query=$DB->query('Select * from '.DB_PREFIX."article where hostid=$hostid and aid in ($aids) and visible=1 order by rand() limit $num");
		while($article=$DB->fetch_array($query))
		{
			$articledb[]=showArticle($article);
		}
	}	
	return $articledb;
}

//得到某个分类的文章列表
function getCateArticle($cateids,$page)
{
	global $DB,$hostid,$host,$cateArr;
	$pagenum = intval($host['list_shownum']);
	$start_limit = ($page - 1) * $pagenum;
	$catesql=$cateids==0?'':" and `cateid` in ($cateids)";
	$sql = "SELECT * FROM ".DB_PREFIX."article WHERE hostid=$hostid $catesql and visible=1 ORDER BY aid DESC LIMIT $start_limit, ".$pagenum;//exit($sql);
	$articledb=array();
	$query=$DB->query($sql);
	while($article=$DB->fetch_array($query))
	{
		$articledb[]=showArticle($article);
	}
	return $articledb;
}

//得到符合条件的文章，包含附件
function getArticle($url)
{
	global $DB,$hostid,$host;
	$sql = "SELECT * FROM ".DB_PREFIX."article a,".DB_PREFIX."content c WHERE url='$url' and visible='1' and hostid=$hostid and a.aid=c.articleid limit 1";
	$article=$DB->fetch_first($sql);
	if(!empty($article))
	{
		$article=showArticle($article);
		$articleid=$article['aid'];
		//处理附件
		if ($article['attachments']) 
		{
			$attachs=getAttachById($articleid);
			if (isset($attachs[$articleid])&&is_array($attachs[$articleid])) 
			{
				$article['attachments']=array();
				foreach($attachs[$articleid] as $aid=>$attach)
				{
					$article['attachments'][$aid]=$attach;
					$article['attachments'][$aid]['downloads']=$attach['downloads'];
					$article['attachments'][$aid]['filesize']=(int)($attach['filesize']/1024);
					$argurl=mkUrl('attachment.php',$aid);
					if($attach['isimage'])
					{
						$file="<a href='{$argurl}' target='_blank'><img src='{$argurl}' alt='{$attach['filename']}'></a>";
					}
					else
					{
						$file="<a href='{$argurl}' target='_blank'>{$attach['filename']}</a>";
					}

					if(strpos($article['content'],"[attach=$aid]")!==false)
					{
						$article['content']=str_replace("[attach=$aid]",$file,$article['content']);
						unset($article['attachments'][$aid]);//加在文章中后就不用在后边显示了.
					}
					else
					{
						$article['attachments'][$aid]['aurl']=$argurl;
					}
				}
				//print_r($article['attachments']);exit;
			}
		}
		if(!empty($article['tag'])) $article['tag']=explode(',',$article['tag']);
	}
	return $article;
}

//按文章的aid，当前页码和每页的条数得到符合条件的评论
function getComment($aid,$page,$pagenum)
{
	global $DB,$hostid,$host;
	$start_limit = ($page - 1) * $pagenum;
	$cmtorder=$host['comment_order'] ? 'ASC' : 'DESC';
	$sql="SELECT * FROM ".DB_PREFIX."comment WHERE articleid='$aid' AND visible='1' ORDER BY cid $cmtorder limit $start_limit,$pagenum";
	
	$commentdb=array();
	$query=$DB->query($sql);
	while($comment=$DB->fetch_array($query))
	{
		$comment['dateline']=date($host['time_comment_format'], $comment['dateline']);
		$commentdb[]=$comment;
	}
	return $commentdb;
}

//得到热门评论文章todo
function getHotComment($num,$cateid=null)
{
	global $DB,$host,$hostid;
	if($cateid==null) $cate='';
	else $cate=' and cateid='.$cateid;
	$query=$DB->query('Select * from '.DB_PREFIX."article where visible=1 and hostid=$hostid $cate order by views desc limit $num");
	return getArticleByAid($query);
}

//按id得到附件
function getAttachById($aids)
{
	global $DB,$host;
	$attacharr=array();
	$downloads=$DB->query('select * from '.DB_PREFIX."attachment where articleid in (".$aids.')');
	while($dds=$DB->fetch_array($downloads))
	{
		$attacharr[$dds['articleid']][$dds['aid']]=$dds;
	}
	return $attacharr;
}

//得到上一篇文章和下一篇文章
function getPreNextArticle($aid)
{
	global $DB,$host,$hostid;
	$data=array();
	$preArr=$DB->fetch_first('Select max(aid) from'.DB_PREFIX."article where aid<$aid and hostid=$hostid limit 1");
	$nextArr=$DB->fetch_first('Select min(aid) from'.DB_PREFIX."article where aid>$aid and hostid=$hostid limit 1");
	if(empty($perArr)&&empty($nextArr)) return $data;
	if(!empty($preArr))
	{	
		$preid=$preArr['max(aid)'];
		$data['Pre']=$DB->fetch_first('Select * from '.DB_PREFIX.'article where aid=$perid');
		$data['Pre']=showArticle($data['Pre']);
	}
	if(!empty($nextArr))
	{	
		$nextid=$nextArr['max(aid)'];
		$data['Next']=$DB->fetch_first('Select * from '.DB_PREFIX.'article where aid=$nextid');
		$data['Next']=showArticle($data['Next']);
	}
	return data;
}


function getArticleByAid($query)
{
	global $DB;
	$articledb=array();
	$aidarr=array();
	while($aid=$DB->fetch_array($query))
	{
		$aidarr[]=$aid['aid'];
	}
	if(count($aidarr)>0)
	{
		$aids=implode_ids($aidarr);
		$query=$DB->query('Select * from '.DB_PREFIX."article where aid in ($aids)");
		while($article=$DB->fetch_array($query))
		{
			$articledb[]=showArticle($article);
		}
	}
	return $articledb;
}

//得到最新$num条搜索的记录
function getLatestSearch($num)
{
	global $host;
	$latestarray=@include RQ_DATA.'/cache/search_'.$host['host'].'.php';
	if(!empty($latestarray))
	{
		if(count($latestarray)>$num) $latestarray=array_slice($latestarray, 0, $num); 
	}
	return $latestarray;
}
