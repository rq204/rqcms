<?php
$title=$setting['option']['name'];
include RQ_DATA."/themes/$theme/header.php";
?>
显示所有栏目<br />
<?php
foreach($category as $cate)
{
?>
<a href="/<?php echo $cate['url']; ?>" title="<?php echo $cate['name']; ?>"><?php echo $cate['name'];?></a>
<?php
}
?>
<hr>
最新文章调用<br>
<?php
//
$allarticle=0;
if($arg1) $cur_page=intval($arg1);
if($cur_page==0) $cur_page=1;
foreach($category as $cate) $allarticle+=$cate['count'];
$all_page=ceil($allarticle/$setting['option']['per_page_articles']);
if($all_page>$setting['option']['article_list_pages']) $all_page=$setting['option']['article_list_pages'];
$articledb=getCateArticle($cur_page);
$multipage=pagination($all_page,$cur_page,$setting['option']['index']);
include RQ_DATA."/themes/$theme/list.php";
include RQ_DATA."/themes/$theme/footer.php";
?>