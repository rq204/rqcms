<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
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
require RQ_DATA."/themes/{$theme}/list.php";
}else if($tagdb){
foreach($tagdb as $key => $tag){
$tagurl=mkUrl('tag.php',$tag['url']);
?>
<span style="line-height:160%;font-size:$tag[fontsize]px;margin-right:10px;"><a href="<?php echo $tagurl;?>" title="使用次数: <?php echo $tag['usenum'];?>"><?php echo $tag['item'];?></a></span>
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