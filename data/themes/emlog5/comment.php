<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$top10cache=getLatestArticle(10);
$host10comments=getHotComment(10);

include RQ_DATA."/themes/$theme/header.php";
?>
<div id=main>
<div id=left>
<div class=leftbox>
<h3>当前位置&gt;&gt;查看评论</h3>
<?php
if ($total) {
foreach($commentdb as $key => $comment){?>
<p class="art-title"><a href="<?php echo $comment['aurl'];?>"><?php echo $comment['title'];?></a></p><p class="lesscontent"><?php echo $comment['content'];?></p>
<p class="lessdate">Post by <?php echo $comment['username'];?>;} on <?php echo $comment['commentdate'];?></p>
<?php
}
echo $multipage;
} else {?>
<p><strong>没有任何评论</strong></p>
<?php
}?>
</div></div>
<div id=right>
<div class=rightbox>
<h3>热评文章</h3>
<ul>
<?php
foreach($host10comments AS $data){
?>
          <li><a href="<?php echo $data['aurl'];?>" title="<?php echo $data['title'];?>"><?php echo $data['title'];?></a></li>
<?php
}?>
</ul></div>
<div class=rightbox>
<h3>热门文章</h3>
<ul>
<?php
foreach($top10cache AS $data){
?>
        <li><a href="<?php echo $data['aurl'];?>" title="<?php echo $data['title'];?>,浏览<?php echo $data['views'];?>"><?php echo $data['title'];?></a></li>
<?php
}?>
</ul></div>
</div></div>
<?php
include RQ_DATA."/themes/$theme/footer.php";
?>
