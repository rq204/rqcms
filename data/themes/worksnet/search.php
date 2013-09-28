<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$stickcache=getStickArticle(10);
$hotcache=getHotArticle(10);
include RQ_DATA."/themes/{$theme}/header.php";
?>
  <div id=main>
<?php
if (isset($articledb)&&$keywords) {?>
    <div id=left>
      <div class=leftbox>
        <h3>关键字&gt;&gt;<?php echo $keywords;?></h3>
<?php
require RQ_DATA."/themes/{$theme}/list.php";
?>
      </div>
    </div>
    <div id=right>
      <div class=rightbox>
        <h3>推荐文章</h3>
        <ul>
<?php
foreach($stickcache AS $data){
?>
          <li><a href="<?php echo $data['aurl'];?>" title="<?php echo $data['title'];?>"><?php echo $data['title'];?></a></li>
<?php
}?>
        </ul>
      </div>
      <div class=rightbox>
        <h3>热门文章</h3>
        <ul>
<?php
foreach($hotcache AS $data){
?>
        <li><a href="<?php echo $data['aurl'];?>" title="<?php echo $data['title'];?>,浏览<?php echo $data['views'];?>"><?php echo $data['title'];?></a></li>
<?php
}?>
        </ul>
      </div>
<?php
}else{?>
    <div id=fullbox>
      <div style=" margin:40px auto; width:880px; text-align:center;">
      <p style=" margin:10px auto; width:600px; text-align:center; font-weight:bold;color:#1c5E96;"><?php echo $host['name'];?>搜索</p>
      <form action="<?php echo $search_url;?>" method="post" >
      <span style="font-family:宋体; font-size:16px; font-weight:600; color:#1c5E96;">关键字:</span>
      <input type="text" name="keywords" id="keywords" type="text" value="" onmouseover="this.focus()"  autocomplete="off" style=" width:220px; height:22px; line-height:22px;"/> <input style="margin-left:8px; height:30px;" type="submit" id="go" value="搜 &nbsp; 索" /></form>
    </div>
<?php
}?>
  </div>
<?php
include RQ_DATA."/themes/{$theme}/footer.php";
?>