<?php
print <<<EOT
<div class="mainbody">
  <table border="0"  cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
          <div class="tableheader">系统设置</div>
          <div class="leftmenubody">          
EOT;
foreach ($settingmenu as $key => $value) {print <<<EOT
            <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=configurate&amp;type=$key">$value</a></div>       
EOT;
}print <<<EOT
          </div>
        </div></td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top"><form action="{$admin_url}?file=configurate" method="post">
          <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">               
EOT;
if(!$type || $type=='basic'){print <<<EOT
                  <tr class="tdbheader">
                    <td colspan="2">基本设置</td>
                  </tr>
                  <tr class="tablecell">
                    <td width="60%"><b>网站名称:</b></td>
                    <td><input class="formfield" type="text" name="option[name]" size="35" maxlength="50" value="{$setting['option']['name']}"></td>
                  </tr>
                  <tr class="tablecell">
                    <td width="60%"><b>网站关键字:</b></td>
                    <td><input class="formfield" type="text" name="option[keywords]" size="35" maxlength="255" value="{$setting['option']['keywords']}"></td>
                  </tr>
				  <tr class="tablecell">
                    <td width="60%"><b>网站描述:</b></td>
                    <td><input class="formfield" type="text" name="option[description]" size="35" maxlength="255" value="{$setting['option']['description']}"></td>
                  </tr>
EOT;
}
if(!$type || $type=='display'){print <<<EOT
                  <tr class="tdbheader">
                    <td colspan="2">显示设置</td>
                  </tr>
                  <tr class="tablecell">
                    <td width="60%"><b>列表每页显示文章的数量:</b><br />
                      指文章列表页每页有多少文章</td>
                    <td><input class="formfield" type="text" name="option[per_page_articles]" size="15" maxlength="50" value="{$setting['option']['per_page_articles']}"></td>
                  </tr>
                  <tr class="tablecell">
                  <td width="60%"><b>列表最多显示分页数:</b><br />
                    显示列表太多会增加查询负载，建议为50-100</td>
                  <td><input class="formfield" type="text" name="option[article_list_pages]" size="15" maxlength="50" value="{$setting['option']['article_list_pages']}"></td>
                </tr>
EOT;
}
if(!$type || $type=='search'){print <<<EOT
                  <tr class="tdbheader">
                    <td colspan="2">搜索设置</td>
                  </tr>
				<tr class="tablecell">
                    <td width="60%"><b>搜索结果最多显示分页数:</b><br />
                      默认是0，即全部显示</td>
                    <td><input class="formfield" type="text" name="option[search_list_pages]" size="15" maxlength="50" value="{$setting['option']['search_list_pages']}"></td>
                  </tr> 				  
EOT;
}
if(!$type || $type=='filemap'){print <<<EOT
  <tr class="tdbheader">
    <td colspan="2">链接映射</td>
  </tr>
<tr class="tablecell">
    <td width="60%"><b>index:</b></td>
    <td><input class="formfield" type="text" name="option[index]" size="15" maxlength="50" value="{$setting['option']['index']}"></td>
  </tr> 		
  <tr class="tablecell">
    <td width="60%"><b>tag:</b></td>
    <td><input class="formfield" type="text" name="option[tag]" size="15" maxlength="50" value="{$setting['option']['tag']}"></td>
  </tr> 
  <tr class="tablecell">
    <td width="60%"><b>search:</b></td>
    <td><input class="formfield" type="text" name="option[search]" size="15" maxlength="50" value="{$setting['option']['search']}"></td>
  </tr> 
  <tr class="tablecell">
  <td width="60%"><b>article:</b></td>
  <td><input class="formfield" type="text" name="option[article]" size="15" maxlength="50" value="{$setting['option']['article']}"></td>
</tr> 	
  <tr class="tablecell">
    <td width="60%"><b>admin:</b></td>
    <td><input class="formfield" type="text" name="option[admin]" size="15" maxlength="50" value="{$setting['option']['admin']}"></td>
  </tr> 		  
EOT;
}print <<<EOT
                  <input type="hidden" name="action" value="updatesetting" />
                  <input type="hidden" name="type" value="$type" />
                  <tr class="tablecell">
                    <td colspan="2" align="center"><input type="submit" value="提交" class="formbutton">
                      <input type="reset" value="重置" class="formbutton">
                    </td>
                  </tr>
                  <tr>
                    <td class="tablebottom" colspan="2"></td>
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

