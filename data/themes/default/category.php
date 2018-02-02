<?php
$title=$cateArr['name'];
include RQ_DATA."/themes/$theme/header.php";
?>
分类名称 $cateArr['name'];<br>
<?php echo $cateArr['name'];?>
<hr>分类链接 $cateArr['url'];<br>
<?php echo $cateArr['url'];?>
<hr>分页函数在header中，可自己修改 pagination($count,$perlogs,$page,$url);<br>
文章列表 $articledb  ,包含分页<br>
<?php
$multipage=pagination($all_page,$cur_page,$cateArr['url']);
include RQ_DATA."/themes/$theme/list.php";
include RQ_DATA."/themes/$theme/footer.php";
?>
