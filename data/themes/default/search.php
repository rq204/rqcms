<?php
include RQ_DATA."/themes/{$theme}/header.php";
?>

最近搜索列表：$searchlist=getLatestSearch(20);<br>
<?php 
$searchlist=getLatestSearch(20);
if(!empty($searchlist))
{
	foreach($searchlist as $data){ ?>
		  <li><a href="<?php echo $data['aurl']; ?>" title="<?php echo $data['title']; ?>"><?php echo $data['title'];;?></a></li>
	<?php
	}
}
?>
<hr>搜索的文件列表 $articledb;<br>
<hr>搜索的关键字  $searchd; <br>
<?php echo $searchd;?><br>
<?php
if($searchd)
{
	if(!empty($articledb)) 
	{
		require RQ_DATA."/themes/{$theme}/list.php";
	}
	else
	{
?>
没有搜索符合条件的内容
<?php
	}
}
else
{
?>
这里是默认的搜索页面
<?php
}
include RQ_DATA."/themes/{$theme}/footer.php";
?>