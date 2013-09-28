<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
?>
<ul id="sidebar">
	<li>
	<h3><span>日历</span></h3>
	<div id="calendar">
	</div>
	<script>sendinfo('http://live.emlog.net/?action=cal','calendar');</script>
	</li>
	<li>
	<h3><span>存档</span></h3>
	<ul id="record">
		<li><a href="http://live.emlog.net/record/201210">2012年10月(1)</a></li>
		<li><a href="http://live.emlog.net/record/201203">2012年3月(1)</a></li>
		</ul>
	</li>
	<li>
	<h3><span>搜索</span></h3>
	<ul id="logserch">
	<form name="keyform" method="get" action="http://live.emlog.net/index.php">
	<input name="keyword" class="search" type="text" />
	</form>
	</ul>
	</li>
	<li>
	<h3><span>最新日志</span></h3>
	<ul id="newlog">
	<?php
foreach($picscache as $k=>$v){
?>
<a href="<?php echo $v['url']; ?>" title="<?php echo $v['title']; ?>">$k</a>
<?php
}?>
		</ul>
	</li>
	<li>
	<h3><span>热门日志</span></h3>
	<ul id="hotlog">
	
	<?php
foreach($hotcache as $data){ ?>
          <li><a href="<?php echo $data['aurl']; ?>" title="<?php echo $data['title']; ?>"><?php echo $data['title'];;?></a></li>
<?php
}?>
		</ul>
	</li>
	<li>
	<h3><span>最新评论</span></h3>
	<ul id="newcomment">
	
	<?php
foreach($commentdata AS $data){
?>
         <li id="comment">emlog	<br /><a href="<?php echo $data['url']; ?>"><?php echo $data['content'];?></a></li>
<?php
}?>
		</ul>
	</li>
	<li>
	<h3><span>分类</span></h3>
	<ul id="blogsort">
		</ul>
	</li>
	<li>
	<h3><span>标签</span></h3>
	<ul id="blogtags">
			<span style="font-size:10pt; line-height:30px;">
		<a href="http://live.emlog.net/tag/emlog" title="2 篇日志">emlog</a></span>
		</ul>
	</li>
	<li>
	<h3><span>链接</span></h3>
	<ul id="link">
<?php
if($linkarr){
foreach($linkarr AS $link){
?>
<li><a href="<?php echo $link['url'];?>" target="_blank" title="<?php echo $link['note'];?>"><?php echo $link['name'];?></a></li>
<?php
}}?>
		</ul>
	</li>
<div class="rss">
<a href="http://live.emlog.net/rss.php" title="RSS订阅"><img src="http://live.emlog.net/content/templates/default/images/rss.gif" alt="订阅Rss"/></a>
</div>
</ul><!--end #siderbar-->