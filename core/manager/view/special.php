<?php
print <<<EOT
<div class="mainbody">
  <table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
          <div class="tableheader">站点管理</div>
          <div class="leftmenubody">
            <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=special&action=list">站点列表</a></div>
			 <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=special&action=add">添加站点</a></div>
          </div>
        </div><div class="tableborder">
          <div class="tableheader">其它操作</div>
          <div class="leftmenubody">
			<div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=database&action=backup">备份数据</a></div>
			<div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=special&action=cacheall">更新缓存</a></div>
          </div>
        </div></td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top">
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0"><form action="{$admin_url}?file=special" method="post"><input type="hidden" name="action" value="{$action}">
EOT;
if($action=='add'||$action=='edit') {print <<<EOT
          <tr>
            <td valign="top" class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                 <tr class="tdbheader">
				 <input type="hidden" name="setting[hid]" value="{$hostArr['hid']}">
                    <td colspan="2">基本设置</td>
                  </tr>
                  <tr class="tablecell">
                    <td width="200"><b>网站名称:</b></td>
                    <td><input class="formfield" type="text" name="setting[name]" size="35" maxlength="50" value="{$hostArr['name']}"></td>
                  </tr>
				  <tr class="tablecell">
					 <td width="200"><b>网站域名:</b></td>
                    <td><input class="formfield" type="text" name="setting[host]" size="35" maxlength="50" value="{$hostArr['host']}">&nbsp;示例如&nbsp;www.rqcms.com</td>
                  </tr>
				  <tr class="tablecell">
					 <td width="200"><b>网站别名:</b></td>
                    <td><input class="formfield" type="text" name="setting[host2]" size="35" maxlength="50" value="{$hostArr['host2']}">&nbsp;多个域名间以,号分隔</td>
                  </tr>
				    <tr class="tablecell">
					 <td width="200"><b>文件后缀:</b></td>
                    <td><input class="formfield" type="text" name="setting[url_ext]" size="35" maxlength="50" value="{$hostArr['url_ext']}">伪静态需要使用文件后缀，如php</td>
                  </tr>
EOT;
print <<<EOT
                  <td class="tablebottom" colspan="4"></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td height="20"></td>
          </tr>
          <tr>
            <td valign="top" class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr class="tdbheader">
                  <td width="200">原文件名</td>
				  <td>新文件名</td>
                </tr>
                <tr class="tablecell">
                  <td width="200">首页(index):</td>
                  <td><input class="formfield" type="text" name="filemap[index]" size="35" maxlength="50" value="{$info['index']}"></td>
                </tr>
                <tr class="tablecell">
                  <td width="200">列表页(category):</td>
                  <td><input class="formfield" type="text" name="filemap[category]" size="35" maxlength="50" value="{$info['category']}"></td>
                </tr>
				                <tr class="tablecell">
                  <td width="200">内容页(article):</td>
                  <td><input class="formfield" type="text" name="filemap[article]" size="35" maxlength="50" value="{$info['article']}"></td>
                </tr>
				                <tr class="tablecell">
                  <td width="200">附件页(attachment):</td>
                  <td><input class="formfield" type="text" name="filemap[attachment]" size="35" maxlength="50" value="{$info['attachment']}"></td>
                </tr>
				          </tr>
				                <tr class="tablecell">
                  <td width="200">搜索页(search):</td>
                  <td><input class="formfield" type="text" name="filemap[search]" size="35" maxlength="50" value="{$info['search']}"></td>
                </tr>
				          </tr>
				  <tr class="tablecell">
                  <td width="200">Tag页(tag):</td>
                  <td><input class="formfield" type="text" name="filemap[tag]" size="35" maxlength="50" value="{$info['tag']}"></td>
                </tr>
						  <tr class="tablecell">
                  <td width="200">管理页(admin):</td>
                  <td><input class="formfield" type="text" name="filemap[admin]" size="35" maxlength="50" value="{$info['admin']}"></td>
                </tr>		
				<tr class="tablecell">
                  <td width="200">RSS页(rss):</td>
                  <td><input class="formfield" type="text" name="filemap[rss]" size="35" maxlength="50" value="{$info['rss']}"></td>
                </tr>
				 <tr class="tablecell">
                    <td colspan="3" align="center"><input type="submit" value="提交" class="formbutton">
                      <input type="reset" value="重置" class="formbutton">
                    </td>
                  </tr>
              </form></table></td>
          </tr>
EOT;
}else if($action=='list') {
print <<<EOT
    <tr class="tdbheader">
      <td width="10%">ID</td>
      <td width="30%">网站名称</td>
      <td>网站域名</td>
	  <td width="10%">状态</td>
	  <td width="10%">编辑</td>
	  <td width="10%">切换</td>
      <td width="2%" nowrap><input name="chkall" type="checkbox" onclick="checkall(this.form)" value="on"></td>
    </tr>
EOT;
foreach($sitedb as $site) {print <<<EOT
		<tr class="tablecell">
		  <td>{$site['hid']}</td>
		  <td>{$site['name']}</td>
		  <td><a href="http://{$site['host']}" target='_blank'>{$site['host']}</a></td>
		  <td>{$site['status']}</td>
		  <td><a href="{$admin_url}?file=special&action=edit&hid={$site['hid']}">编辑</a></td>
		  <td><a href="{$admin_url}?file=special&action=go&hid={$site['hid']}">转到</a></td>
		  <td nowrap><input type="checkbox" name="hids" value="{$site['hid']}"></td>
		</tr>
EOT;
}}
print <<<EOT
  </form></table>
</div>
EOT;
?>
