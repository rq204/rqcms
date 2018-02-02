<?php
print <<<EOT
<div class="mainbody">
  <table border="0"  cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
        <div class="tableheader">系统维护</div>
        <div class="leftmenubody">
          <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=cache&action=cache">缓存管理</a></div>
        </div>
      </div>
	  </td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top">
EOT;
print <<<EOT
	  <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	  <tr><td class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
EOT;
if(!$action || $action == 'cache') {print <<<EOT
<form action="{$admin_url}?file=cache"  method="POST">
    <tr class="tdbheader">
      <td width="50%"><b>缓存名称</b></td>
      <td width="25%"><b>生成时间</b></td>
      <td width="25%"><b>缓存大小</b></td>
    </tr>
EOT;
foreach($cachedb as $key => $cache){print <<<EOT
        <tr class="tablecell">
          <td nowrap="nowrap" style="line-height:20px;"><b>$cache[name]</b><br/>$cache[desc]</td>
          <td nowrap="nowrap">$cache[mtime]</td>
          <td nowrap="nowrap">$cache[size]</td>
        </tr>

EOT;
}print <<<EOT
    <input type="hidden" name="action" value="cache">
    <tr class="tablecell">
      <td colspan="5" align="center"><input type="submit" value="更新所有缓存" class="formbutton">
      </td>
    </tr>
  </form>
EOT;
} elseif ($action == 'log') {
if($do=='login'||$do=='dberror'){
$show=$do=='login'?'登录结果':'SQL语句';
print <<<EOT
<form action="{$admin_url}?file=cache"  method="POST">
    <tr class="tdbheader">
      <td width="10%"><b>用户名</b></td>
      <td width="10%"><b>时间</b></td>
      <td width="10%"><b>IP地址</b></td>
      <td width="50%"><b>浏览器</b></td>
	  <td width="20%"><b>$show</b></td>
    </tr>
EOT;
foreach($searchdb as $key => $search){print <<<EOT
        <tr class="tablecell">
          <td nowrap="nowrap">$search[user]</td>
          <td nowrap="nowrap">$search[dateline]</td>
          <td nowrap="nowrap">$search[ip]</td>
          <td>$search[useragent]</td>
          <td nowrap="nowrap">$search[content]</td>
        </tr>
EOT;
}
}else if($do=='search'){
print <<<EOT
<form action="{$admin_url}?file=cache"  method="POST">
    <tr class="tdbheader">
      <td width="20%" colspan="2"><b>时间</b></td>
      <td width="20%"><b>IP地址</b></td>
	  <td width="50%" colspan="2"><b>关键词</b></td>
    </tr>
EOT;
foreach($searchdb as $key => $search){print <<<EOT
        <tr class="tablecell">
          <td nowrap="nowrap" colspan="2">$search[dateline]</td>
          <td nowrap="nowrap">$search[ip]</td>
          <td nowrap="nowrap" colspan="2">$search[keywords]</td>
        </tr>
EOT;
}print <<<EOT
        <tr class="tablecell">
          <td colspan="6" nowrap="nowrap"><div class="records">记录:$total</div>
                  <div class="multipage">$multipage</div></td>
        </tr>
    <input type="hidden" name="action" value="log">
	<input type="hidden" name="do" value="$do">
    <tr class="tablecell">
      <td colspan="6" align="center"><input type="submit" value="保留最新500条日志记录" class="formbutton">
      </td>
    </tr>
  </form>
EOT;
}}print <<<EOT
    <tr>
      <td class="tablebottom" colspan="6"></td>
    </tr>
      </table></td>
    </tr>
  </table></td>
    </tr>
  </table>
</div>

EOT;
?>