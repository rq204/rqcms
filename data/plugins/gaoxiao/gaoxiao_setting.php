<?php
!defined('RQ_DATA') && exit('access deined!');
$urllink='admin.php?file=plugin&action=setting&plugin=gaoxiao';

//配置设置界面,需要做一个tr，界面代码参考core\manager\view\plugin.php
function gaoxiao_html_view()
{
	global $DB,$hostid,$urllink;
	$do=isset($_GET['do'])?$_GET['do']:'list';
	if($do=='add'||$do=='mod')
	{
		$config['title']=$config['listurl']=$config['listurl2']=$config['abouturl']=$config['bak']=$config['gid']='';
		if($do=='mod')
		{
			if(!isset($_GET['gid'])) redirect('缺少gid参数',$urllink);
			$config=$DB->fetch_first('select * from '.DB_PREFIX."gaoxiao where hostid=$hostid and gid=".intval($_GET['gid']));
			if(empty($config))  redirect('找不到学校',$urllink);
		}
		
		$tdtitle='添加学校';
		$visible_check='checked';

		print <<<EOT
<form action="$urllink&do=$do" method="post">
<input type="hidden" value="gaoxiao" name="plugin">
<input type="hidden" value="$config[gid]" name="gid">
 <tr class="tdbheader">
      <td colspan="2">$tdtitle</td>
    </tr>
    <tr class="tablecell">
      <td>校名:</td>
      <td><input class="formfield" type="text" name="title" id="title" size="50" value="{$config['title']}"></td>
    </tr>
	 <tr class="tablecell">
      <td>列表页1:</td>
      <td><input class="formfield" type="text" name="listurl" size="50" maxlength="150" value="{$config['listurl']}"></td>
    </tr>
    <tr class="tablecell">
      <td valign="top">列表页2:</td>
      <td><input class="formfield" type="text" name="listurl2" size="50" maxlength="150" value="{$config['listurl2']}"></td>
    </tr>
    <tr class="tablecell">
      <td valign="top">关于页面:
</td>
      <td><input class="formfield" type="text" name="abouturl" size="50" maxlength="150" value="$config[abouturl]"></td>
    </tr>
	 <tr class="tablecell">
      <td>备注内容:</td>
      <td><textarea name="bak" id="bak" style="width:100%; height:100px;">{$config['bak']}</textarea></td>
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
		$rs = $DB->fetch_first("SELECT count(*) AS pages FROM ".DB_PREFIX."gaoxiao where hostid=$hostid");
		$total = $rs['pages'];
		$multipage = multi($total, 30, $page, $urllink);
		$start=($page-1)*30;
		$query=$DB->query('Select * from '.DB_PREFIX."gaoxiao where hostid=$hostid limit $start,30");
		print <<<EOT
	 <tr class="tdbheader">
      <td class="tdbheader" colspan="8"><a href="$urllink&do=add">添加学校</a></td>
    </tr>
	<tr>
      <td class="tdbheader" colspan="8">&nbsp;</td>
    </tr>
			<tr class="tdbheader">
      <td width="20%">校名</td>
   	  <td width="17%" nowrap>列表网址1</td>
	  <td width="10%" nowrap>列表网址2</td>
      <td width="20%" nowrap>关于网址</td>
	  <td width="20%" nowrap>备注</td>
	  <td width="7%" nowrap>最后修改</td>
      <td width="3%" nowrap><input name="chkall" value="on" type="checkbox" onclick="checkall(this.form)"></td>
    </tr>   
EOT;
		while($pagedb=$DB->fetch_array($query)){
		$pagedb['dateline'] = date('Y-m-d H:i',$pagedb['dateline']);
		print <<<EOT
	<tr class="tablecell">
      <td><a href="$urllink&do=mod&gid={$pagedb['gid']}">{$pagedb['title']}</a></td>
	  <td nowrap>{$pagedb['listurl']}</td>
      <td nowrap>{$pagedb['listurl2']}</td>
	  <td nowrap>{$pagedb['abouturl']}</td>
	  <td nowrap>{$pagedb['bak']}</td>
	  <td nowrap>{$pagedb['modified']}</td>
      <td nowrap><input type="checkbox" name="aids[]" value="4"></td>
    </tr>
EOT;
}
print <<<EOT
	<tr class="tablecell">
          <td colspan="7" nowrap="nowrap"><div class="records">记录:$total</div>
                  <div class="multipage">$multipage</div></td>
        </tr>    <tr>
    </tr>
EOT;
}
}
addAction('admin_plugin_setting_view','gaoxiao_html_view');

//保存单页代码
function gaoxiao_content_save()
{
	global $DB,$hostid,$urllink,$userid,$username;
	//添加，编辑，删除的操作
	$do=isset($_GET['do'])?$_GET['do']:'';
	if(in_array($do,array('add','mod')))
	{
		$title       = trim($_POST['title']);
		$listurl       = trim($_POST['listurl']);
		$listurl2         =  $_POST['listurl2'];
		$abouturl     =  $_POST['abouturl'];
		$bak     = $_POST['bak'];
	
		if(empty($listurl)) redirect('列表网址不能为空',"$urllink&do=add");
		if(empty($title)) redirect('校名不得为空',"$urllink&do=add");
	}
	
	if($do=='add')
	{
		$DB->query('insert into '.DB_PREFIX."gaoxiao (`hostid`,`title`,`listurl`,`listurl2`,`abouturl`,`bak`,`dateline`,`modified`) values ('$hostid','$title','$listurl','$listurl2','$abouturl','$bak','$timestamp','$timestamp')");
		redirect('学校添加成功',$urllink);
	}
	else if($do=='mod')
	{
		$gid=$_POST['gid'];
		$DB->query('update '.DB_PREFIX."gaoxiao set `hostid`= '$hostid',`title`='$title',`listurl`='$listurl',`listurl2`='$listurl2',`abouturl`='$abouturl',`bak`='$bak',`modified`='$timestamp' where gid=$gid");
		redirect('学校编辑成功',$urllink);
	}
	else if ($do=='del'){
	
	}
	else redirect('未定义操作',$urllink);
}
addAction('admin_plugin_setting_save','gaoxiao_content_save');
