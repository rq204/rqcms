<?php
!defined('RQ_DATA') && exit('access deined!');
$urllink='admin.php?file=plugin&action=setting&plugin=page';

//配置设置界面,需要做一个tr，界面代码参考core\manager\view\plugin.php
function page_html_view()
{
	global $DB,$hostid,$urllink;
	$do=isset($_GET['do'])?$_GET['do']:'list';
	if($do=='add'||$do=='mod')
	{
		$article['title']=$article['keywords']=$article['url']=$article['excerpt']=$article['content']=$article['pid']='';
		if($do=='mod')
		{
			if(!isset($_GET['pid'])) redirect('缺少pid参数',$urllink);
			$article=$DB->fetch_first('select * from '.DB_PREFIX."page where hostid=$hostid and pid=".intval($_GET['pid']));
			if(empty($article))  redirect('找不到文章',$urllink);
		}
		
		$tdtitle='添加单页';
		$visible_check='checked';

		print <<<EOT
<form action="$urllink&do=$do" method="post">
<input type="hidden" value="page" name="plugin">
<input type="hidden" value="$article[pid]" name="pid">
 <tr class="tdbheader">
      <td colspan="2">$tdtitle</td>
    </tr>
    <tr class="tablecell">
      <td>文章标题:</td>
      <td><input class="formfield" type="text" name="title" id="title" size="35" value="$article[title]"></td>
    </tr>
	 <tr class="tablecell">
      <td>关键字:</td>
      <td><input class="formfield" type="text" name="keywords" size="50" maxlength="110" value="$article[keywords]">多个关键字用,分隔</td>
    </tr>
    <tr class="tablecell">
      <td valign="top">文章描述:</td>
      <td><textarea name="excerpt" style="width:100%; height:100px;">{$article['excerpt']}</textarea></td>
    </tr>
    <tr class="tablecell">
      <td valign="top">文章内容:
</td>
      <td><textarea name="content" id="content" style="width:100%; height:400px;">{$article['content']}</textarea></td>
    </tr>
	 <tr class="tablecell">
      <td>友好网址:</td>
      <td><input class="formfield" type="text" name="url" size="20" maxlength="20" value="$article[url]"> 20个字符以内</td>
    </tr>
	<tr class="tablecell">
      <td valign="top">发布本文:
	</td>
      <td> <input name="visible" type="checkbox" value="1" $visible_check>不选则为隐藏文章</td>
    </tr>
    <tr class="tablecell">
	<td colspan="2" align="center"><input type="submit" value="提交" class="formbutton"></td>
  </tr>
  </form>
EOT;
	}
	else if($do=='list')
	{
		$page=isset($_GET['page'])?intval($_GET['page']):1;
		$rs = $DB->fetch_first("SELECT count(*) AS pages FROM ".DB_PREFIX."page where hostid=$hostid");
		$total = $rs['pages'];
		$multipage = multi($total, 30, $page, $urllink);
		$start=($page-1)*30;
		$query=$DB->query('Select * from '.DB_PREFIX."page where hostid=$hostid limit $start,30");
		print <<<EOT
	 <tr class="tdbheader">
      <td class="tdbheader" colspan="8"><a href="$urllink&do=add">添加单页</a></td>
    </tr>
	<tr>
      <td class="tdbheader" colspan="8">&nbsp;</td>
    </tr>
			<tr class="tdbheader">
      <td width="45%">标题</td>
   	  <td width="12%" nowrap>时间</td>
	  <td width="10%" nowrap>网址</td>
      <td width="6%" nowrap>查看</td>
	  <td width="7%" nowrap>作者</td>
	  <td width="7%" nowrap>显示</td>
      <td width="3%" nowrap><input name="chkall" value="on" type="checkbox" onclick="checkall(this.form)"></td>
    </tr>   
EOT;
		while($pagedb=$DB->fetch_array($query)){
		$pagedb['dateline'] = date('Y-m-d H:i',$pagedb['dateline']);
		$pagedb['visible']=$pagedb['visible']?'是':'否';
		print <<<EOT
	<tr class="tablecell">
      <td><a href="$urllink&do=mod&pid={$pagedb['pid']}">{$pagedb['title']}</a></td>
	  <td nowrap>{$pagedb['dateline']}</td>
      <td nowrap>{$pagedb['url']}</td>
	  <td nowrap>{$pagedb['views']}</td>
	  <td nowrap>{$pagedb['username']}</td>
	  <td nowrap>{$pagedb['visible']}</td>
      <td nowrap><input type="checkbox" name="aids[]" value="4"></td>
    </tr>
	<tr class="tablecell">
          <td colspan="8" nowrap="nowrap"><div class="records">记录:$total</div>
                  <div class="multipage">$multipage</div></td>
        </tr>    <tr>
    </tr>
EOT;
}}
}
addAction('admin_plugin_setting_view','page_html_view');

//保存单页代码
function page_content_save()
{
	global $DB,$hostid,$urllink,$userid,$username;
	//添加，编辑，删除的操作
	$do=isset($_GET['do'])?$_GET['do']:'';
	if(in_array($do,array('add','mod')))
	{
		$title       = trim($_POST['title']);
		$url         =  $_POST['url'];
		$excerpt     =  $_POST['excerpt'];
		$content     = $_POST['content'];
		$keywords    = trim($_POST['keywords']);
		$visible     = isset($_POST['visible'])?intval($_POST['visible']):0;
		$dateline    = isset($_POST['edittime'])?getDateLine():time();
		
		if(empty($title)) redirect('标题不得为空',"$urllink&do=add");
		if(empty($content)) redirect('内容不得为空',"$urllink&do=add");
		if(empty($url)) redirect('网址不得为空',"$urllink&do=add");
	}
	
	if($do=='add')
	{
		$DB->query('insert into '.DB_PREFIX."page (`hostid`,`userid`,`username`,`title`,`keywords`,`url`,`excerpt`,`content`,`dateline`,`modified`,`visible`) values ('$hostid','$userid','$username','$title','$keywords','$url','$excerpt','$content','$timestamp','$timestamp','1')");
		redirect('单页添加成功',$urllink);
	}
	else if($do=='mod')
	{
		$pid=$_POST['pid'];
		$DB->query('update '.DB_PREFIX."page set `hostid`= '$hostid', `userid`='$userid',`username`='$username',`title`='$title',`keywords`='$keywords',`url`='$url',`excerpt`='$excerpt',`content`='$content',`dateline`='$timestamp',`modified`='$timestamp',`visible`=1 where pid=$pid");
		redirect('单页编辑成功',$urllink);
	}
	else if ($do=='del'){
	
	}
	else redirect('未定义操作',$urllink);
}
addAction('admin_plugin_setting_save','page_content_save');
