<?php
$hotcache=getHotArticle(10);
include RQ_DATA."/themes/$theme/header.php";

?>
<div id=main>
<div id=left>
<div class=leftbox>
<h3>下面是一些简单，却很神奇的东东</h3>
<div id=contents>
<?php
if($articledb){
$multipage=pagination($total,$host['list_shownum'],$page,'tag',$item);
require RQ_DATA."/themes/{$theme}/list.php";
}else if($tagdb){
foreach($tagdb as $tag){
?>
<span><a href="<?php echo $tagurl;?>"><?php echo $tag;?></a></span>
<?php
}}
echo $multipage
?>
</div>
</div></div>
<div id=right>
<div class=rightbox>
<h3>XXX</h3>
      <ul>
        <li><a href="#" title="">test</a></li>
      </ul>
</div>
<div class=rightbox>
<h3>XXX</h3>
      <ul>
        <li><a href="#" title="">test</a></li>
      </ul>
</div>
<div class=rightbox>
<h3>XXX</h3>
      <ul>
        <li><a href="#" title="">test</a></li>
      </ul>
</div>
</div></div>
<?php
include RQ_DATA."/themes/$theme/footer.php";
?>