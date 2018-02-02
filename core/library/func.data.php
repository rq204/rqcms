<?php
///获得指定分类下文章，0为所有
function getCateArticle($page=1,$cateid=0,$limit='')
{
	global $DB,$dbprefix,$setting;
	$articledb=array();
	$sqladd=$cateid==0?'':"where cateid=$cateid";
	if(!$limit) $limit=$setting['option']['per_page_articles'];
	$start=($page-1)*$limit;

	$sql="select * from {$dbprefix}article order by aid desc limit $start,$limit";
	if($cateid)
	{
		$sql="select * from {$dbprefix}article a where a.aid in (select b.i as id from (select aid as i from {$dbprefix}article c where c.cateid={$cateid} order by c.aid desc limit {$start},{$limit}) b)";
	}
	$files= $DB->query($sql);
	while ($fs = $DB->fetch_array($files)) 
	{
		$articledb[$fs['aid']]=fillArticle($fs);
	}
	return $articledb;
}

///得到tag文章
function getTagArticle($tag,$page,$aid='',$limit='')
{
	global $DB,$dbprefix;
	$articledb=array();
	$tagarr=$DB->fetch_first("Select * from `{$dbprefix}tag` where tag ='$tag'");

	if(!$tagarr) return $articledb;
	
	$aidarr=array();
	$dbaids=explode(',',$tagarr['aids']);
	$aidarr=array_merge($aidarr,$dbaids);
	if(!$aidarr) return $articledb;

	if(!$limit)	$limit=$setting['option']['per_page_articles'];
	$aidcount=count($aidarr);
	$tatolpage=ceil($aidcount/$limit);
	if($page>$tatolpage) $page=$tatolpage;

	$start=($page-1)*$limit;
	if($start+$limit>$aidcount) $limt=$aidcount-$start;

	$aidarr=array_unique($aidarr);
	if($aid) unset($aidarr[$aid]);
	arsort($aidarr);
	//
	$aidarr=array_slice($aidarr,$start,$limit);
	$aids=implode_ids($aidarr);
	$query=$DB->query("Select * from `{$dbprefix}article` where aid in ($aids) order by aid desc");
	while($article=$DB->fetch_array($query))
	{
		$articledb[$article['aid']]=fillArticle($article);
	}
		
	return $articledb;
}


//得到热门文章
function getHotArticle($page,$cateid=0,$limit='')
{
	global $dbprefix,$DB,$setting;
	$articledb=array();
	$cateadd=$cateid?" where cateid in ({$cateid})":'';
	if(!$limit)	$limit=$setting['option']['per_page_articles'];
	$start=($page-1)*$limit;
	$sql="SELECT * FROM `{$dbprefix}article` $cateadd ORDER BY views DESC limit $start,$limit";
	$query=$DB->query($sql);
	while($article=$DB->fetch_array($query))
	{
		$articledb[$article['aid']]=fillArticle($article);
	}	
	return $articledb;
}


//得到上一篇文章
function getPreArticle($aid)
{
	global $DB,$dbprefix;
	$preArr=$DB->fetch_first("Select * from {$dbprefix}article where aid<$aid limit 1");
	if($preArr) $preArr=fillArticle($preArr);
	return $preArr;
}

//得到下一篇文章
function getNextArticle($aid)
{
	global $DB,$dbprefix;
	$nextArr=$DB->fetch_first("Select * from {$dbprefix}article where aid>$aid limit 1");
	if($nextArr) $nextArr=fillArticle($nextArr);
 	return $nextArr;
}

function fillArticle($article)
{
	global $setting,$category;
	$article['url']='/'.$setting['option']['article'].'/'.$article['aid'].'.html';
	$article['cateurl']='/'.$category[$article['cateid']]['url'];
	$article['catename']=$category[$article['cateid']]['name'];
	return $article;
}