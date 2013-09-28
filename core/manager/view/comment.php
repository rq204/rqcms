<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
print <<<EOT
<div class="mainbody">
  <table border="0"  cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
          <div class="tableheader">评论管理</div>
          <div class="leftmenubody">
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=comment">所有评论</a></div>
		    <div class="leftmenuitem">&#8226; <a href="admin.php?file=comment&action=cmlist&kind=display">已审评论</a></div>
            <div class="leftmenuitem">&#8226; <a href="admin.php?file=comment&action=cmlist&kind=hidden"><font color="#FF0000">待审评论</font></a></div>
          </div>
        </div>
	</td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top"><form action="admin.php?file=comment" method="POST" name="form">
          <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">       
EOT;
if($action == 'cmlist'){print <<<EOT
                <tr class="tdbheader">
                  <td  width="6%">状态</td>
                  <td >作者</td>
                  <td >网站地址</td>
				  <td >电子邮件</td>
                  <td >IP</td>
                  <td >时间</td>
                  <td >内容</td>
                  <td  width="2%"><input name="chkall" value="on" type="checkbox" onClick="checkall(this.form)"></td>
                </tr>
EOT;
foreach($commentdb as $key => $comment){print <<<EOT

                <tr class="tablecell">
                  <td ><a href="admin.php?file=comment&action=cmvisible&cid=$comment[cid]">$comment[visible]</a></td>
                  <td >$comment[author]</td>
                  <td >$comment[url]</td>
				  <td >$comment[email]</td>
                  <td ><a href="admin.php?file=comment&action=cmlist&ip=$comment[ipaddress]" title="查看此IP同一C段发表的评论">$comment[ipaddress]</a></a></td>
                  <td >$comment[dateline]</td>
                  <td><a href="admin.php?file=comment&action=modcm&cid=$comment[cid]">$comment[content]</a></td>
                  <td ><input type="checkbox" name="comment[]" value="$comment[cid]"></td>
                </tr>
EOT;
}print <<<EOT

                <input type="hidden" name="articleid" value="$articleid">
                <tr class="tablecell">
                  <td colspan="9" nowrap="nowrap"><div class="records">记录:$total</div>
                    <div class="multipage">$multipage</div></td>
                </tr>
EOT;
} elseif ($action == 'modcm') {print <<<EOT
                <tr class="tdbheader">
                  <td colspan="2"><a name="编辑评论"></a>编辑评论</td>
                </tr>
                <tr class="tablecell">
                  <td>所在文章:</td>
                  <td><a href="admin.php?file=article&action=mod&aid=$comment[articleid]">$comment[title]</a></td>
                </tr>
                <tr class="tablecell">
                  <td>评论作者:</td>
                  <td><input class="formfield" type="text" name="username" size="50" value="$comment[username]"></td>
                </tr>
                <tr class="tablecell">
                  <td>电子邮件:</td>
                  <td><input class="formfield" type="text" name="email" size="50" value="$comment[email]"></td>
                </tr>
				 <tr class="tablecell">
                  <td>网站地址:</td>
                  <td><input class="formfield" type="text" name="url" size="50" value="$comment[url]"></td>
                </tr>
                <tr class="tablecell">
                  <td valign="top">评论内容:</td>
                  <td><textarea class="formarea" type="text" name="content" cols="75" rows="20">$comment[content]</textarea></td>
                </tr>
                <input type="hidden" name="cid" value="$comment[cid]">
                <input type="hidden" name="aid" value="$comment[articleid]">
				<input type="hidden" name="userid" value="$comment[userid]">
                <input type="hidden" name="action" value="domodcm">
                <tr class="tablecell">
                  <td colspan="2" align="center"><input type="submit" value="提交" class="formbutton">
                    <input type="reset" value="重置" class="formbutton">
                  </td>
                </tr>
EOT;
}
print <<<EOT
      <tr>
        <td class="tablebottom" colspan="9"></td>
      </tr>
    </table>
    </td>  
    </tr>
  </table>
EOT;
if (in_array($action, array('cmlist'))) {print <<<EOT
  <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" height="40">
    <tr>
      <td align="right"><select name="do">
          <option value="">= 管理操作 =</option>
          
EOT;
if ($action == 'cmlist' || $action == 'tblist') {print <<<EOT

          <option value="hidden">隐藏选定</option>
          <option value="display">显示选定</option>
          
EOT;
}print <<<EOT
          <option value="del">删除选定</option>
        </select>
        <input type="submit" value="确定" class="formbutton">
        <input type="hidden" name="action" value="domore{$action}"></td>
    </tr>
  </table>
EOT;
}print <<<EOT
  </form>
  </td>
  </tr>
  </table>
</div>
EOT;
?>

