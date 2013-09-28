<?php
if(!defined('RQ_ROOT')) exit('Access Denied');

?>
<div id="contentleft">
<?php
foreach($articledb as $key => $article){
?>
	<h2><img src="http://live.emlog.net/content/templates/default/images/import.gif" title="置顶日志" /> <a href="<?php echo $article['aurl'];;?>"><?php echo $article['title'];?></a></h2>
	<p class="date">作者：<?php echo $article['userid'];?> 发布于：<?php echo $article['dateline'];?>
		 
	<a href="<?php echo $admin_url,'?file=article&action=mod&aid=',$article['aid']; ?>">编辑</a>	</p>
    <?php echo $article['excerpt'];?>
	<p class="tag">标签:	<?php echo $article['tag'];?></p>
	<p class="count">
	<a href="http://live.emlog.net/post-2000.html#comments">评论(<?php echo $article['comments'];?>)</a>
	<a href="http://live.emlog.net/post-2000.html">浏览(<?php echo $article['views'];?>)</a>
	</p>
	<div style="clear:both;"></div>
<?php
}?>
<div id="pagenavi">

</div>

</div><!-- end #contentleft-->