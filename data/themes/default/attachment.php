<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$top10cache=getLatestArticle(10);
include RQ_DATA."/themes/$theme/header.php";
?>
<div id=main>
<div id=left>
<div class=leftbox>
<h3>当前位置&gt;&gt;下载文件</h3>
<p>点击这里下载文件：<a href="<?php echo $page_url;?>"><?php echo $attachinfo['filename'];?></a></p>
</div></div>
<div id=right>
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
