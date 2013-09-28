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

<div id="contentleft">
	<h2><img src="comment_files/import.gif" title="置顶日志"> 欢迎使用emlog</h2>
	<p class="date">作者：<a href="http://live.emlog.net/author/1">emlog</a> 发布于：2012-3-29 0:34 Thursday 
		 	</p>
	<p><b>功能介绍</b></p>
<div>支持日志url自定义，对搜索引擎更为友好</div>
<div>独有的碎语(微博)功能，让你用简单的文字记录生活</div>
<div>一键式更换模板，方便快捷打造个性博客</div>
<div>清爽的日志撰写页面、配以自动保存，书写博文更加舒适无忧</div>
<div>日志草稿箱功能，方便保存你未完成的日志</div>
<div>完美支持手机访问，随时随地记录你的生活</div>
<div>支持离线写作，你可以使用Windows Live Write等软件撰写博文</div>
<div>灵活的侧边栏组件(widgets)管理，轻松组合、自定义你喜欢的组件</div>
<div>支持强大的插件扩展功能，随意选择实用的插件，让你的博客无限可能</div>
<div>自定义页面，轻松创建留言板、导航条、博主介绍等个性页面</div>
<div>多人联合撰写，后台轻松管理多个撰写人</div>
<div>支持灵活的标签(tag)分类，以及传统分类方式</div>
<div>方便的附件（图片、文件）上传和管理</div>
<div>上传的图片可以随意直观的嵌入到日志内容里，让你的日志图文并茂</div>
<div>首页日历方式查阅日志，方便、直观、快捷</div>
<div>数据缓存技术，博客访问速度更快</div>
<div>整体使用UTF-8编码方式，让你的博客和世界接轨。</div>
<div>使用跨浏览器可视化日志编辑器，轻松编辑文章格式</div>
<div>支持引用通告(trackback) , 并配有强大的垃圾引用防御功能</div>
<div>支持RSS日志输出功能 ，方便朋友订阅关注你的博客</div>
<div>数据库备份/恢复功能</div>	<p class="tag">标签:	<a href="http://live.emlog.net/tag/emlog">emlog</a></p>
		<div class="nextlog">		« <a href="http://live.emlog.net/post-2001.html">emlog美国主机</a>
			</div>
				    <div id="pagenavi">
	        </div>
		<div id="comment-place">
	<div class="comment-post" id="comment-post">
		<div class="cancel-reply" id="cancel-reply" style="display:none"><a href="javascript:void(0);" onclick="cancelReply()">取消回复</a></div>
		<p class="comment-header"><b>发表评论：</b><a name="respond"></a></p>
		<form method="post" name="commentform" action="http://live.emlog.net/index.php?action=addcom" id="commentform">
			<input name="gid" value="2000" type="hidden">
						<p>
				<input name="comname" maxlength="49" value="emlog" size="22" tabindex="1" type="text">
				<label for="author"><small>昵称</small></label>
			</p>
			<p>
				<input name="commail" maxlength="128" size="22" tabindex="2" type="text">
				<label for="email"><small>邮件地址 (选填)</small></label>
			</p>
			<p>
				<input name="comurl" maxlength="128" value="http://live.emlog.net/" size="22" tabindex="3" type="text">
				<label for="url"><small>个人主页 (选填)</small></label>
			</p>
						<p><textarea name="comment" id="comment" rows="10" tabindex="4"></textarea></p>
			<p><img src="comment_files/checkcode.png" align="absmiddle"><input name="imgcode" class="input" size="5" tabindex="5" type="text"> <input id="comment_submit" value="发表评论" tabindex="6" type="submit"></p>
			<input name="pid" id="comment-pid" value="0" size="22" tabindex="1" type="hidden">
		</form>
	</div>
	</div>
		<div style="clear:both;"></div>
</div><!--end #contentleft-->

<?php
include RQ_DATA."/themes/$theme/footer.php";
?>