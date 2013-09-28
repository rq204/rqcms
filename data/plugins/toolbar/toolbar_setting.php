<?php
!defined('RQ_DATA') && exit('access deined!');

//配置设置界面,需要做一个tr，界面代码参考core\manager\view\plugin.php
function toolbar_html_view()
{
	global $DB,$hostid;
	$arr=$DB->fetch_first('select * from '.DB_PREFIX."plugin where `hostid`=$hostid and `file`='toolbar'");
	$code=isset($arr['config'])?$arr['config']:'';
print <<<EOT
<form action="admin.php?file=plugin&action=setting" method="post">
<input type="hidden" value="toolbar" name="plugin">
	<tr class="tdbheader">
    <td colspan="2">导航代码设置</td>
	</tr>
  <tr class="tablecell">
	<td width="20%"><b>导航代码:</b><td><textarea id="toolbar_code" class="formarea" type="text" name="toolbar_code" style="width:400px;height:80px;">$code</textarea></td>
  </tr>
    <tr class="tablecell">
	<td colspan="2" align="center"><input type="submit" value="提交" class="formbutton"></td>
  </tr>
  </form>
EOT;
}
addAction('admin_plugin_setting_view','toolbar_html_view');

//保存统计代码
function toolbar_code_save()
{
	global $DB,$hostid;
	$code=$_POST['toolbar_code'];
	$DB->query('update '.DB_PREFIX."plugin set `config`='$code' where hostid=$hostid and `file`='toolbar'");
	plugins_recache();
	redirect('工具栏代码已成功更新','admin.php?file=plugin&action=setting&plugin=toolbar');
}
addAction('admin_plugin_setting_save','toolbar_code_save');
