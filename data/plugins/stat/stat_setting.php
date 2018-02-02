<?php
!defined('RQ_DATA') && exit('access deined!');

//配置设置界面,需要做一个tr，界面代码参考core\manager\view\plugin.php
function stat_html_view()
{
	global $DB,$admin_url,$dbprefix;
	$arr=$DB->fetch_first("select * from {$dbprefix}plugin where `file`='stat'");
	$code=isset($arr['config'])?$arr['config']:'';
print <<<EOT
<form action="{$admin_url}?file=plugin&action=setting" method="post">
<input type="hidden" value="stat" name="plugin">
	<tr class="tdbheader">
    <td colspan="2">统计代码设置</td>
	</tr>
  <tr class="tablecell">
	<td width="20%"><b>统计代码:</b><td><textarea id="stat_code" class="formarea" type="text" name="stat_code" style="width:400px;height:80px;">$code</textarea></td>
  </tr>
    <tr class="tablecell">
	<td colspan="2" align="center"><input type="submit" value="提交" class="formbutton"></td>
  </tr>
  </form>
EOT;
}
addAction('admin_plugin_setting_view','stat_html_view');

//保存统计代码
function stat_code_save()
{
	global $DB,$admin_url,$dbprefix;
	$code=$_POST['stat_code'];
	$DB->query("update {$dbprefix}plugin set `config`='$code' where `file`='stat'");
	setting_recache();
	redirect('统计代码已成功更新',$admin_url.'?file=plugin&action=setting&plugin=stat');
}
addAction('admin_plugin_setting_save','stat_code_save');
