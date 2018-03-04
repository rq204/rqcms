<?php
$tag=$arg1;
$is404=false;
if(!$tag) $is404=true;

if(!$is404)
{
    if($arg2) $cur_page=intval($arg2);
    if(!$cur_page) $cur_page=1;
}

if (!$is404)
{
    $tag=urldecode($tag);
	$tag=htmlspecialchars($tag);
    $tagdata = $DB->fetch_first("SELECT * FROM {$dbprefix}tag where tag='$tag'");
    if($tagdata)
    {
        $aids=$tagdata['aids'];
        $aidsarr=explode(',',$aids);
        $aidsarr=array_reverse($aidsarr);

        $all_record=count($aidsarr);

        $pagenum=@ceil($all_record/$setting['option']['per_page_articles']);

        if($cur_page>$pagenum) $cur_page=$pagenum;

        $start = ($cur_page - 1) * $setting['option']['per_page_articles'];
        $selectnum=$setting['option']['per_page_articles'];
        if($selectnum+$start>$all_record) $selectnum=$all_record-$start;

        $listaids=array_slice($aidsarr,$start,$selectnum);

        $aidstr=implode_ids($listaids);
        $query_sql = "SELECT * FROM {$dbprefix}article WHERE aid in ($aidstr) ORDER BY aid desc";
        $query=$DB->query($query_sql);
        while($adb=$DB->fetch_array($query))
        {
            $articledb[]=fillArticle($adb);
        }
        $DB->free_result($query);
        $next_url='/'.$setting['option']['tag'].'/'.urlencode($tag).'/'. ($cur_page+1);
    }
    else
    {
        $is404=true;
    }
}

doAction('tag_before_view');

if($is404)
{
    $views='404';
    $tempView=RQ_DATA.'/themes/'.$theme.'/'.$views.'.php';//风格模板文件
    header('HTTP/1.1 404 Not Found');
}