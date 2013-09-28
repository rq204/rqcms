<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$hotdata=getHotArticle(10,$article['cateid']);
if(is_array($article['tag'])) $likedata=getRelatedArticle($article['aid'],$article['tag'],10);

if($pagecount>0)
{
	for ($i = 1;$i <=$pagecount;$i++)
	{
		if ($i == $page){
			$multipage .= " <span>$i</span> ";
		} else {
			$curl=mkUrl('article.php',$_GET['url'],$i);
			$multipage .= " <a href=\"$curl\">$i</a> ";
		}
	}
}

include RQ_DATA."/themes/$theme/header.php";
?>
  <div id=main>
    <div id=left>
      <div class=leftbox>
        <h3><a href="<?php echo $article['curl'];?>"><?php echo $article['cname'];?></a>&gt;&gt;<?php echo $article['title'];?></h3>
        <H2><?php echo $article['title'];?>;</H2>
        <div id=info>发布:<?php echo $article['dateline'];?>     浏览:<span id=spn1><?php echo $article['views'];?></span></div>
        <div id=contents>
<?php
if (!$article['allowread']) {?>
<div class="needpwd"><form action="<?php echo $article['aurl'];?>" method="post">这篇日志被加密了。请输入密码后查看。<br /><input class="formfield" type="password" name="readpassword" style="margin-right:5px;" /> <button class="formbutton" type="submit">提交</button></form></div>
<?php
} 
else
{
	echo $article['content'];
if($multipage){?>
<div id="ArtPLink"><?php echo $multipage;?></div>
<?php
}if($article['attachments'])
{
	foreach($article['attachments'] as $image)
	{
		if($image['isimage'])
		{
			?>
			<p class="attach"><?php echo $image['filename'];?><br /><a href="<?php echo $image['aurl'];?>" target="_blank"><img src="<?php echo $image['aurl'];?>" border="0" alt="大小: <?php echo $image['filesize'];?>KB&#13;浏览: <?php echo $image['downloads'];?> 次" /></a></p>
<?php
		}
	}
	foreach($article['attachments'] as $attach)
	{
		if(!$attach['isimage']) 
		{
			?>
			<p class="attach"><strong>附件: </strong><a href="<?php echo $image['aurl'];?>" target="_blank"><?php echo $attach['filename'];?></a> (<?php echo $attach['filesize'];?>KB, 下载次数:<?php echo $attach['downloads'];?>)</p>
<?php
		}
	}
}
}
?>
        </div>
        <div class=pagebreak></div>
      </div><!--end leftbox-->
      <div id=comments>
        <h3>相关信息</h3>
        <ul id=like>
         <li>上下一篇</a> &raquo;</p></li>
          <li>Tag：
<?php
if($article['tag'])
{foreach($article['tag'] as $tag){
$tagurl=mkUrl('tag.php',$tag);
?>
<a href='<?php echo $tagurl;?>'><?php echo $tag;?></a>&nbsp;
<?php
}
?>
</li>
          <li>原文链接：<a href="<?php echo $article['aurl'];?>"><?php echo $article['aurl'];?></a></li>
          <li><B>将本文收藏到网摘：</B></li>
        </ul>
      <div class=pagebreak></div>
	  </div><!--end leftbox-->
      <div id=comments>
<?php
}
if ($article['comments']) {?>
<span style="FLOAT:right;padding-bottom: 2px;font-size: 12px;"><?php echo $article['comments'];?>条记录</span>访客评论
<?php
foreach($commentdb as $key => $comment){?>
<div class=cbox><a name="cm<?php echo $comment['cid'];?>"></a><p class="lesscontent" id="comm_<?php echo $comment['cid'];?>"><?php echo $comment['content'];?></p>
<p class="lessdate">Post by <?php echo $comment['username'];?> on <?php echo $comment['dateline'];?> <img style="cursor: hand" onclick="addquote('comm_<?php echo $comment['cid'];?>','<?php echo $comment['userid'];?>')" src="/images/quote.gif" border="0" alt="引用此文发表评论" /> <font color="#000000">#<strong><?php echo $comment['cid'];?></strong></font></p></div>
<?php
}?>
$multipage
<br />
<?php
}
if (!$article['closed']) {
?>
  <a name="addcomment"></a>
  <form method="post" name="form" id="form" action="comment.php" onsubmit="return checkform();">
    <input type="hidden" name="url" value="<?php echo $article['url'];?>" />
    <div class="formbox">
<?php
if ($uid) {
?>
  <p>已经登陆为 <b><?php echo $username;?></b> [<a href="<?php echo $logout_url;?>">注销</a>]</p>
<?php
} else {?>
  <p>
    <label for="username">
    名字 (必填):<br /><input name="username" id="username" type="text" value="<?php echo $comment_username;?>" tabindex="1" class="formfield" style="width: 210px;" /></label>
  </p>
  <p>
    <label for="password">
    密码 (游客不需要密码):<br /><input name="password" id="password" type="password" value="" tabindex="2" class="formfield" style="width: 210px;" /></label>
  </p>
  <p>
    <label for="url">
    网址或电子邮件 (选填):<br /><input type="text" name="url" id="url" value="<?php echo $comment_url;?>" tabindex="3" class="formfield" style="width: 210px;" /></label>
  </p>
<?php
}?>
  <p>评论内容 (必填):<br />
	<textarea name="content" cols="84" rows="6" tabindex="4" onkeydown="ctlent(event);" class="formfield" id="content"><?php echo $cmcontent;?></textarea>
  </p>
<?php
if ($host['audit_comment'] && $groupid < 2) {?>
  <p>
    <label for="clientcode">
    验证码(*):<br /><input name="clientcode" id="clientcode" value="" tabindex="5" class="formfield" size="6" maxlength="6" /> <img id="seccode" class="codeimg" src="captcha.php" alt="单击图片换张图片" border="0" onclick="this.src='captcha.php?update=' + Math.random()" /></label>(*请输入图片后三位数字)
  </p>
<?php
}?>
      <p><input type="hidden" name="action" value="addcomment" />
          <button type="submit" name="submit" class="formbutton">提交</button></p>
	</div>
  </form>
<?php
} else {?>
<p align="center"><strong>本文因为某种原因此时不允许访客进行评论</strong></p>
<?php
}?>
    </div><!--end comments-->
	</div><!--end left-->
    <div id=right>
      <div class=rightbox>
        <h3>相关文章</h3>
        <ul>
<?php
if(isset($likedata)){
foreach($likedata as $key => $title){
?>
          <li><a href="<?php echo $title['aurl'];?>" title="<?php echo $title['title'];?>,浏览<?php echo $title['views'];?>"><?php echo $title['title'];?></a></li>
<?php
}}?>
        </ul>
      </div>
      <div class=rightbox>
        <h3>阅读排行</h3>
        <ul>
<?php
foreach($hotdata AS $data){
?>
        <li><a href="<?php echo $data['aurl'];?>" title="<?php echo $data['title'];?>,浏览<?php echo $data['views'];?>"><?php echo $data['title'];?></a></li>
<?php
}?>
        </ul>
      </div>
    </div><!--end right-->
  </div><!--end main-->
<?php
include RQ_DATA."/themes/$theme/footer.php";
?>
