<?php
$tag=$arg1;
if(!$tag) run404();

if($arg2) $cur_page_num=intval($arg2);
if(!$cur_page_num) $cur_page_num=1;

$articledb=array();
$tagdb=array();

if ($tag)
{
	$tag=htmlspecialchars($tag);
    $tagdata = $DB->fetch_first("SELECT * FROM {$dbprefix}tag where tag='$tag'");
    if($tagdata)
    {
        $aids=$tagdata['aids'];
        $aidsarr=explode(',',$aids);
        $aidsarr=array_reverse($aidsarr);

        $all_tag_total=count($aidsarr);

        $pagenum=@ceil($all_tag_total/$per_page_articles);

        if($cur_page_num>$pagenum) $cur_page_num=$pagenum;

        $start = ($cur_page_num - 1) * $per_page_articles;
        $selectnum=$per_page_articles;
        if($selectnum+$start>$all_tag_total) $selectnum=$all_tag_total-$start;

        $listaids=array_slice($aidsarr,$start,$selectnum);

        $aidstr=implode_ids($listaids);
        $query_sql = "SELECT * FROM {$dbprefix}article WHERE aid in ($aidstr) ORDER BY aid desc";
        $query=$DB->query($query_sql);
        $articledb=array();
        while($adb=$DB->fetch_array($query))
        {
            $articledb[]=$adb;
        }
    }
    else
    {
        run404();
    }
    $DB->free_result($query);
}
else
{
    run404();
}
 
doAction('tag_before_view');