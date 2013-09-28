<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
print <<<EOT
<div class="mainbody">
  <table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
          <div class="tableheader">快捷链接</div>
          <div class="leftmenubody">
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=article&action=add">添加文章</a></div>
			 <div class="leftmenuitem">&#8226; <a href="admin.php?file=article&action=list">编辑文章</a></div>
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=article&action=search">搜索文章</a></div>
EOT;
if ($groupid>2) 
{
	print <<<EOT
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=category&action=addcate">添加分类</a></div>
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=link&action=add">添加链接</a></div>
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=attachment&action=repair">附件修复</a></div>
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=attachment&action=clear">附件清理</a></div>
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=category&action=tagclear">标签整理</a></div>
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=cache&action=rebuild">重建数据</a></div>
EOT;
}
if($groupid==4){
print <<<EOT
  <div class="leftmenuitem">&#8226; <a href="admin.php?file=database&action=backup">数据库备份</a></div>
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=database&action=tools">数据库维护</a></div>
			<div class="leftmenuitem">&#8226; <a href="admin.php?file=special">多站点管理</a></div>
EOT;
}
print <<<EOT
          </div>
        </div></td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top">
        <div id="news_box" class="box" style="display:none;">
          <div id="news_title" class="alert">读取中...</div>
          <div id="news_content" class="alertmsg">读取中...</div>
        </div>
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr class="tdbheader">
                  <td colspan="2">系统信息</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">服务器时间:</td>
                  <td width="50%">$server[datetime]</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">服务器解译引擎:</td>
                  <td width="50%">$server[software]</td>
                </tr>
				 <tr class="tablecell">
                  <td width="50%">MySql版本:</td>
                  <td width="50%">$server[mysql]</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">文件上传:</td>
                  <td width="50%">$fileupload</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">全局变量 register_globals:</td>
                  <td width="50%">$globals</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">安全模式 safe_mode:</td>
                  <td width="50%">$safemode</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">图形处理 GD Library:</td>
                  <td width="50%">$gd_version</td>
                </tr>
EOT;
if ($server['memory_info']) {print <<<EOT
                <tr class="tablecell">
                  <td width="50%">内存占用:</td>
                  <td width="50%">$server[memory_info]</td>
                </tr>
                <tr>
EOT;
}print <<<EOT
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
                  <td width="50%">{$host['name']}</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">文章数量:</td>
                  <td width="50%">{$server['article']}</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">附件数量:</td>
                  <td width="50%">{$server['attach']}</td>
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
          <tr>
            <td valign="top" class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr class="tdbheader">
                  <td colspan="2">程序相关信息</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">当前版本:</td>
                  <td width="50%">{$constant['RQ_VERSION']} Build {$constant['RQ_RELEASE']}</td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">最新版本:</td>
                  <td width="50%"><span id="newest_version">读取中...</span></td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">开发人员:</td>
                  <td width="50%"><a href="mailto:{$constant['RQ_EMAIL']}" target="_blank">{$constant['RQ_AUTHOR']}</a></td>
                </tr>
                <tr class="tablecell">
                  <td width="50%">官方主页:</td>
                  <td width="50%"><a href="{$constant['RQ_WEBSITE']}" target="_blank">{$constant['RQ_WEBSITE']}</a></td>
                </tr>
                <tr>
                  <td class="tablebottom" colspan="2"></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>
EOT;
?>
