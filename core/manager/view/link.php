<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
print <<<EOT
<div class="mainbody">
  <table border="0"  cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
        <div class="tableheader">链接管理</div>
        <div class="leftmenubody">
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=link&action=add">添加链接</a></div>
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=link&action=list">编辑链接</a></div>
        </div>
      </div></td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top">
	  <form action="admin.php?file=link" method="POST" name="form"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	  <tr><td class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
EOT;
if($action == 'add'){print <<<EOT
    <input type="hidden" name="action" value="addlink">
    <tr class="tdbheader">
      <td colspan="2">添加链接</td>
    </tr>
    <tr class="tablecell">
      <td>状态:</td>
      <td><select name="visible"><option value="1" selected>显示</option><option value="0">隐藏</option></select></td>
    </tr>
    <tr class="tablecell">
      <td>名称:</td>
      <td><input class="formfield" name="name" type="text" value="" size="18"></td>
    </tr>
    <tr class="tablecell">
      <td>地址:</td>
      <td><input class="formfield" name="url" type="text" value="" size="35"></td>
    </tr>
    <tr class="tablecell">
      <td>描述:</td>
      <td><input class="formfield" name="note" type="text" value="" size="35"></td>
    </tr>
	<tr class="tablecell">
      <td>备注:</td>
      <td><input class="formfield" name="bak" type="text" value="" size="35"></td>
    </tr>
    <tr class="tablecell">
      <td colspan="2" align="center"><input type="submit" value="提交" class="formbutton"> <input type="reset" value="重置" class="formbutton"></td>
    </tr>
EOT;
} elseif($action == 'list'){print <<<EOT
    <input type="hidden" name="action" value="domorelink">
    <tr class="tdbheader">
      <td width="6%" nowrap>排序</td>
      <td width="8%">状态</td>
      <td width="14%">名称</td>
      <td width="22%">地址</td>
      <td width="22%">描述</td>
	   <td width="26%">备注</td>
      <td width="2%" nowrap><input name="chkall" value="on" type="checkbox" onclick="checkall(this.form)"></td>
    </tr>
EOT;
foreach($linkdb as $key => $link){print <<<EOT
    <tr class="tablecell">
      <td><input class="formfield" style="text-align:center;font-size:11px;" type="text" value="$link[displayorder]" name="displayorder[$link[lid]]" size="1"></td>
      <td><select name="visible[$link[lid]]">$link[visible]</select></td>
      <td><input class="formfield" name="name[$link[lid]]" type="text" value="$link[name]" size="15"></td>
      <td><input class="formfield" name="url[$link[lid]]" type="text" value="$link[url]" size="25"></td>
      <td><input class="formfield" name="note[$link[lid]]" type="text" value="$link[note]" size="25"></td>
	  <td><input class="formfield" name="bak[$link[lid]]" type="text" value="$link[bak]" size="25"></td>
      <td nowrap><input type="checkbox" name="delete[]" value="$link[lid]"></td>
    </tr>
EOT;
}print <<<EOT
    <tr class="tablecell">
      <td colspan="7" align="center"><input type="submit" value="更新 / 删除(所选)" class="formbutton"></td>
    </tr>
EOT;
}print <<<EOT
    <tr>
      <td class="tablebottom" colspan="7"></td>
    </tr>
      </table></td>
    </tr>
  </table>
</form></td>
    </tr>
  </table>
</div>
EOT;
?>
