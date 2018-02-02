<?php
$title=$article['title'];
include RQ_DATA."/themes/$theme/header.php";
?>

文章标题：$article['title'];<br>
<?php echo $article['title'];?>
<hr>发布时间:$article['dateline'];<br>
<?php echo $article['dateline'];?>
<hr>浏览量：$article['views'];<br>
<?php echo $article['views'];?>
<hr>分类链接 $article['cateurl'];<br>
<a href="<?php echo $article['cateurl'];?>"><?php echo $article['cateurl'];?></a>
<hr>分类名称：$article['catename'];<br>
<?php echo $article['catename'];?>
<hr>文章内容：echo $article['content'];<br>
<?php echo $article['content']; ?>
<hr>Tags:$article['tag']<br>
<?php echo $article['tag']; ?>
<hr>原文链接:$article['url'];<br>
<?php echo $article['url'];?>
<hr>阅读排行 $hotdata=getHotArticle(1); <br>
<ul>
<?php
$hotdata=getHotArticle(1);
foreach($hotdata AS $data){
?>
        <li><a href="<?php echo $data['url'];?>" title="<?php echo $data['title'];?>,浏览<?php echo $data['views'];?>"><?php echo $data['title'];?></a></li>
<?php
}?>
</ul>
</ul>
<?php
include RQ_DATA."/themes/$theme/footer.php";
?>
