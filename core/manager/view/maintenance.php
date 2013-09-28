<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
print <<<EOT
<div class="mainbody">
  <table border="0"  cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
        <div class="tableheader">系统维护</div>
        <div class="leftmenubody">
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=maintenance&action=cache">缓存管理</a></div>
        </div>
      </div>
	  <div class="tableborder">
        <div class="tableheader">日志管理</div>
        <div class="leftmenubody">
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=maintenance&action=log&do=login">登陆日志</a></div>
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=maintenance&action=log&do=search">站内搜索</a></div>
EOT;
if($groupid==4){print <<<EOT
		  <div class="leftmenuitem">&#8226; <a href="admin.php?file=maintenance&action=log&do=dberror">MySql</a></div>
EOT;
}print <<<EOT
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
<form action="admin.php?file=maintenance"  method="POST">
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
} elseif ($action == 'log') {print <<<EOT
<form action="admin.php?file=maintenance"  method="POST">
    <tr class="tdbheader">
      <td width="10%"><b>用户名</b></td>
      <td width="10%"><b>时间</b></td>
      <td width="10%"><b>IP地址</b></td>
      <td width="50%"><b>$browser</b></td>
	  <td width="20%"><b>$result</b></td>
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
}print <<<EOT
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
