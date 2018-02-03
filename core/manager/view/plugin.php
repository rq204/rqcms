<?php
print <<<EOT
<div class="mainbody">
  <table border="0"  cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
        <div class="tableheader">插件管理</div>
        <div class="leftmenubody">
          <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=plugin&action=list">插件列表</a></div>
        </div>
      </div><div class="tableborder">
        <div class="tableheader">插件菜单</div>
EOT;
if(count($pluginitem)>0){ print <<<EOT
        <div class="leftmenubody">
EOT;
foreach($pluginitem as $itemk=>$itemv){print <<<EOT
          <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=plugin&action=setting&plugin=$itemv">$itemk</a></div>
EOT;
}print <<<EOT
        </div>
EOT;
}print <<<EOT
      </div></td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top">
	  <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	  <tr><td class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
EOT;
if($action == 'list'){print <<<EOT
    <tr class="tdbheader">
      <td width="20%">插件名称</td>
   	  <td width="12%" nowrap>状态</td>
	  <td width="10%" nowrap>版本</td>
      <td width="6%" nowrap>作者</td>
      <td width="32%" nowrap>描述</td>
      <td width="10%" nowrap>操作</td>
    </tr>
EOT;
foreach($plugindb as $key => $plugin){print <<<EOT
    <tr class="tablecell">
      <td>$plugin[name]</td>
	  <td nowrap>
EOT;
$stats=$plugin['active']?'正常':'禁用';
$opt=$plugin['active']?'禁用':'启用';
print <<<EOT
	  $stats</td>
      <td nowrap>$plugin[version]</td>
	  <td nowrap><a href='$plugin[url]' target='_blank'>$plugin[author]</td>
      <td nowrap>$plugin[description]</td>
      <td nowrap><a href="{$admin_url}?file=plugin&action=active&active=$plugin[active]&pid=$plugin[pid]">{$opt}</a></td>
    </tr>
EOT;
}
}elseif ($action == 'setting') {
doAction('admin_plugin_setting_view');
}print <<<EOT
    <tr>
      <td class="tablebottom" colspan="6"></td>
    </tr>
      </table></td>
    </tr>
  </table>
EOT;
print <<<EOT
</td>
    </tr>
  </table>
</div>
EOT;
?>