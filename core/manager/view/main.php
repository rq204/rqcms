<?php
print <<<EOT
<div class="mainbody">
  <table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
          <div class="tableheader">快捷链接</div>
          <div class="leftmenubody">
            <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=article&action=add">添加文章</a></div>
			 <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=article&action=list">编辑文章</a></div>
            <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=article&action=search">搜索文章</a></div>
            <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=category&action=addcate">添加分类</a></div>
            <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=link&action=add">添加链接</a></div>
            <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=category&action=tagclear">标签整理</a></div>
            <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=cache&action=rebuild">重建数据</a></div>
            <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=database&action=backup">数据库备份</a></div>
            <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=database&action=tools">数据库维护</a></div>
          </div>
        </div></td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top">
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr class="tdbheader">
                  <td colspan="2">系统信息</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">服务器时间:</td>
                  <td width="50%">{$server ['datetime'] }</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">服务器解译引擎:</td>
                  <td width="50%">{$server ['software']}</td>
                </tr>
				 <tr class="tablecell">
                  <td width="50%">MySql版本:</td>
                  <td width="50%">{$server['mysql']}</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">内存占用:</td>
                  <td width="50%">{$server ['memory_info']}</td>
                </tr>
                <tr>
                  <td class="tablebottom" colspan="2"></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td height="20"></td>
          </tr>
          <tr>
            <td valign="top" class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr class="tdbheader">
                  <td colspan="2">数据统计</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">站点信息:</td>
                  <td width="50%"></td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">文章数量:</td>
                  <td width="50%">{$server['article']}</td>
                </tr>
			        	<tr class="tablecell">
                  <td width="50%">评论数量:</td>
                  <td width="50%">{$server['comment']}</td>
                </tr>
                <tr>
                  <td class="tablebottom" colspan="2"></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td height="20"></td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>
EOT;
?>
