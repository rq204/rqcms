<?php
$tempView=$coreView;
$rssfile = RQ_DATA.'/cache/rss_'.$host['host'].'.php';
$rssdb=@include($rssfile);
if(!$rssdb) $rssdb=array();//rss数据

if(isset($_GET['url'])&&$_GET['url'])
{
	$rssdb=array();
	$cate=array();
	foreach($cateArr as $ct)
	{
		if($ct['url']==$_GET['url']) $cate=$ct;
	}
	if(!empty($cate)) 
	{
		$rquery= $DB->query('SELECT * FROM `'.DB_PREFIX.'article` where hostid='.$hostid.' and cateid='.$cate['cid'].' ORDER BY aid DESC limit '.$host['rss_num']);
		while($article=$DB->fetch_array($rquery))
		{
			$rssdb[]=showArticle($article);
		}
	}
}

doAction('rss_before_output',$rssdb);
$contentType="Content-Type: application/xml";
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<rss version=\"2.0\">\n";
echo "\t<channel>\n";
echo "\t\t<title>".htmlspecialchars($host['name'])."</title>\n";
echo "\t\t<link>http://".$host['host']."</link>\n";
echo "\t\t<description>".htmlspecialchars($host['description'])."</description>\n";
echo "\t\t<lastBuildDate>".date('r', $timestamp)."</lastBuildDate>\n";
echo "\t\t<ttl>".$host['rss_ttl']."</ttl>\n";
if ($rssdb&&is_array($rssdb)) {
	foreach ($rssdb AS $article) {
		$articleurl = RQ_HTTP.RQ_HOST.'/'.$article['aurl'];
		echo "\t\t<item>\n";
		echo "\t\t\t<guid>".$articleurl."</guid>\n";
		echo "\t\t\t<title>".$article['title']."</title>\n";
		if ($article['password']) {
			echo "\t\t\t<description>文章需要输入密码才能浏览.</description>\n";
		} else {
			echo "\t\t\t<description><![CDATA[".$article['excerpt']."]]></description>\n";
		}
		echo "\t\t\t<link>".$articleurl."</link>\n";
		echo "\t\t\t<pubDate>".$article['dateline']."</pubDate>\n";
		echo "\t\t</item>\n";
	}
}
echo "\t</channel>\n";
echo "</rss>";