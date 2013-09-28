<?php
if(!defined('RQ_ROOT')) exit('Access Denied');

print <<<EOT
<div class="mainbody">
<p class="p_nav"><a href="admin.php?file=user&action=add">添加用户</a> [ 降序排列: <a href="admin.php?file=user&action=list&order=username">用户名</a> | <a href="admin.php?file=user&action=list&order=logincount">登陆次数</a> | <a href="admin.php?file=user&action=list&order=regdateline">注册时间</a> ] [ 发表与否: <a href="admin.php?file=user&action=list&lastpost=already">发表过评论</a> | <a href="admin.php?file=user&action=list&lastpost=never">从未发表过评论</a> ] [ 组别: <a href="admin.php?file=user&action=list&groupid=3">管理组</a> | <a href="admin.php?file=user&action=list&groupid=2">撰写组</a> | <a href="admin.php?file=user&action=list&groupid=1">注册组</a> ]</p>
<div class="box">
<div class="alert">搜索用户</div>
<div class="alertmsg"><form method="post" action="admin.php?file=user&action=list">
<input class="formfield" type="text" size="15" name="srhname" value="" /> <input class="formbutton" type="submit" value="确定" />
</form></div>
</div>
	  <form action="admin.php?file=user" method="POST"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	  <tr><td class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
EOT;
if($action == 'list'){print <<<EOT
  <tr class="tdbheader">
    <td nowrap><b>用户名</b></td>
    <td nowrap><b>用户组</b></td>
    <td nowrap><b>电子邮件</b></td>
	 <td nowrap><b>网站地址</b></td>
    <td nowrap><b>注册时间</b></td>
    <td nowrap><b>登陆次数</b></td>
    <td nowrap><b>上次登陆IP</b></td>
    <td nowrap><b>上次登陆时间</b></td>
    <td nowrap><b>最后发表评论时间</b></td>
    <td width="2%" nowrap><input name="chkall" value="on" type="checkbox" onclick="checkall(this.form)" /></td>
  </tr>
EOT;
foreach($userdb as $key => $user){print <<<EOT
  <tr class="tablecell">
    <td nowrap><a href="admin.php?file=user&action=mod&userid=$user[uid]">$user[username]</a></td>
    <td>$user[group]</td>
	<td>$user[email]</td>
    <td>$user[url]</td>
    <td>$user[regdateline]</td>
    <td>$user[logincount]</td>
    <td><a href="admin.php?file=user&action=list&ip=$user[loginip]">$user[loginip]</a></td>
    <td>$user[logintime]</td>
    <td>$user[lastpost]</td>
    <td><input type="checkbox" name="user[]" value="$user[uid]" $user[disabled] /></td>
  </tr>
EOT;
}print <<<EOT
  <tr class="tablecell">
    <td colspan="10" nowrap="nowrap"><div class="records">记录:$total</div>
                  <div class="multipage">$multipage</div></td>
  </tr>
  <tr class="tablecell">
    <td colspan="10" align="center"><input type="hidden" name="action" value="del" /><input type="submit" class="formbutton" value="删除所选用户" /></td>
  </tr>
EOT;
} elseif (in_array($action, array('add', 'mod'))){print <<<EOT
    <tr class="tdbheader">
      <td colspan="2"><b>必填资料</b></td>
    </tr>
    <tr class="tablecell">
      <td><b>登陆名:</b><br>
        登陆后台的登陆名</td>
      <td><input class="formfield" type="text" name="username" size="35" value="$info[username]" style="width:150px" $readonly></td>
    </tr>
    <tr class="tablecell">
      <td><b>新密码:</b><br>
        不改请留空,</td>
      <td><input class="formfield" type="password" name="newpassword" size="35" value="" style="width:150px"></td>
    </tr>
    <tr class="tablecell">
      <td><b>确认新密码:</b><br>
        请再输入一次密码</td>
      <td><input class="formfield" type="password" name="comfirpassword" size="35" value="" style="width:150px"></td>
    </tr>
    <tr class="tablecell">
      <td><b>用户组:</b></td>
	  <td><select name="groupid">
EOT;
if($showgid!=$groupid){print <<<EOT
	   <option value="1" $groupselect[1]>注册组</option>
EOT;
}if($showgid<$groupid&&$groupid>2||$showgid==2&&$groupid==2){print <<<EOT
          <option value="2" $groupselect[2]>撰写组</option>
EOT;
}if($showgid<$groupid&&$groupid>3||$showgid==3&&$groupid==3){print <<<EOT
<option value="3" $groupselect[3]>管理员</option>
EOT;
}if($showgid==4&&$groupid==4){print <<<EOT
<option value="4" $groupselect[4]>创始人</option>
EOT;
}
print <<<EOT
        </select></td>
    </tr>
    <tr class="tdbheader">
      <td colspan="2"><b>备选资料</b></td>
    </tr>
    <tr class="tablecell">
      <td><b>电子邮件:</b></td>
      <td><input class="formfield" type="text" name="email" size="35" value="$info[email]" ></td>
    </tr>
	  <tr class="tablecell">
      <td><b>网站主页:</b></td>
      <td><input class="formfield" type="text" name="url" size="35" value="$info[url]" ></td>
    </tr>
	  <tr class="tablecell">
      <td><b>QQ:</b></td>
      <td><input class="formfield" type="text" name="qq" size="35" value="$info[qq]" ></td>
    </tr>
	</tr>
	  <tr class="tablecell">
      <td><b>Msn:</b></td>
      <td><input class="formfield" type="text" name="msn" size="35" value="$info[msn]" ></td>
    </tr>
    <input type="hidden" name="userid" value="$info[uid]">
    <input type="hidden" name="action" value="$do">
    <tr class="tablecell">
      <td colspan="2" align="center"><input type="submit" value="提交" class="formbutton">
        <input type="reset" value="重置" class="formbutton">
      </td>
    </tr>

EOT;
} elseif ($action == 'del'){print <<<EOT

    <tr class="alertheader">
      <td><a name="删除用户"></a>删除用户</td>
    </tr>
    <tr>
      <td class="alertbox">
	  <p><b>注意:<br />创始人和管理员不能删除,要删除其他管理员请先把用户组改成其他组.<br />删除用户并不会删除用户发表过的评论.</b></p>
	  <p><ol>
EOT;
foreach($userdb as $user){print <<<EOT
        <li><a href="admin.php?file=user&action=mod&userid=$user[uid]">$user[username]</a><input type="hidden" name="user[]" value="$user[uid]"></li>
EOT;
}print <<<EOT
      </ol></p>
	  <p><b>你确实要删除以上用户吗? 此操作一旦执行, 将无法撤销!</b></p>
	  <p>如果有撰写组的用户,删除其所发表的文章:<br><select name="deluserarticle">
          <option value="1">是</option>
          <option value="0" selected>否</option>
        </select></p>
      <p><input type="submit" name="submit" id="submit" value="确认" class="formbutton"></p>
      <input type="hidden" name="action" value="delusers">
	  </td>
    </tr>
EOT;
}print <<<EOT
    <tr>
      <td class="tablebottom" colspan="10"></td>
    </tr>
      </table></td>
    </tr>
  </table></form>
</div>

EOT;
?>