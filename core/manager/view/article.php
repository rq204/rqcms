<?php
print <<<EOT
<script type="text/javascript">

function checkform()
{
	if ($("#title").val()=='') 
	{
		alert("请输入标题");
		return false;
	}
	if ($("#cateid").val()==0)	
	{
		alert("请选择分类");
		return false;
	}
	if ($("#content").val()==0)	
	{
		alert("内容不得为空");
		return false;
	}
	return true;
}

$(document).ready(function(){
    $('#cid').change(function(){
		var upurl="{$admin_url}?file=upload&cid="+$(this).children('option:selected').val(); 
        editor.settings.upImgUrl=upurl;
		editor.settings.upFlashUrl=upurl;
		editor.settings.upMediaUrl=upurl;
    });
})

</script>

<div class="mainbody">
  <table border="0"  cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
        <div class="tableheader">文章管理</div>
        <div class="leftmenubody">
          <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=article&action=add">添加文章</a></div>
          <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=article&action=list">编辑文章</a></div>
          <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=article&action=search">搜索文章</a></div>
        </div>
      </div>
	  <div class="tableborder">
        <div class="tableheader">文章分类</div>
        <div class="leftmenubody">
EOT;
foreach($category as $key => $cate){print <<<EOT
          <div class="leftmenuitem">&#8226; <a href="{$admin_url}?file=article&action=list&cid={$cate['cid']}">{$cate['name']}</a></div>
EOT;
}print <<<EOT
        </div>
      </div></td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top">
	  <form action="{$admin_url}?file=article" enctype="multipart/form-data" method="POST" name="form1""><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	  <tr><td class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
EOT;
if($action == 'list'){print <<<EOT
    <tr class="tdbheader">
    <td width="7%">ID</td>
      <td width="45%">标题</td>
   	  <td width="12%" nowrap>时间</td>
	  <td width="8%" nowrap>分类</td>
      <td width="9%" nowrap>查看数</td>
      <td width="9%" nowrap>评论数</td>
	  <td width="7%" nowrap>作者</td>
      <td width="3%" nowrap><input name="chkall" value="on" type="checkbox" onclick="checkall(this.form)"></td>
    </tr>
EOT;
foreach($articledb as $key => $article){print <<<EOT
    <tr class="tablecell">
    <td>$article[aid]</td>
      <td><a href="{$admin_url}?file=article&action=mod&aid=$article[aid]">$article[title]</a></td>
	  <td nowrap>$article[dateline]</td>
      <td nowrap><a href="{$admin_url}?file=article&action=list&cid=$article[cateid]">$article[cname]</a></td>
	  <td nowrap>$article[views]</td>
      <td nowrap>$article[comments]</td>
	  <td nowrap>$article[username]</td>
      <td nowrap><input type="checkbox" name="aids[]" value="$article[aid]"></td>
    </tr>
EOT;
}print <<<EOT
        <tr class="tablecell">
          <td colspan="8" nowrap="nowrap"><div class="records">记录:$total</div>
                  <div class="multipage">$multipage</div></td>
        </tr>
EOT;
} elseif (in_array($action, array('add', 'mod'))) {print <<<EOT
    <tr class="tdbheader">
      <td colspan="2">$tdtitle</td>
    </tr>
    <tr class="tablecell">
      <td>文章标题:</td>
      <td><input class="formfield" type="text" name="article[title]" id="title" size="70" value="$article[title]"></td>
    </tr>
    <tr class="tablecell">
      <td valign="top">选择分类:</td>
      <td><select name="article[cateid]" id="cateid">
          <option value="">== 选择分类 ==</option>
EOT;
foreach($category as $key => $cate){
print <<<EOT
          <option value="{$cate['cid']}">$key. {$cate['name']}</option>
EOT;
}print <<<EOT
        </select></td>
    </tr>
    <tr class="tablecell">
      <td>标签(Tag):</td>
      <td><input class="formfield" type="text" name="article[tag]" size="80" maxlength="10000" value="$article[tag]">&nbsp;多个Tag用,分隔</td>
    </tr>
    <tr class="tablecell">
      <td valign="top">文章描述:</td>
      <td><textarea name="article[excerpt]" style="width:100%; height:100px;">{$article['excerpt']}</textarea></td>
    </tr>
    <tr class="tablecell">
      <td valign="top">文章内容:<br /></td>
      <td><textarea name="content[content]" id="content" style="width:100%; height:400px;">{$article['content']}</textarea></td>
    </tr>
	<tr class="tablecell">
      <td>缩略图片:</td>
      <td><input class="formfield" type="text" name="article[thumb]" size="50" maxlength="50" value="$article[thumb]"></td>
    </tr>
    <input type="hidden" name="action" value="$action">
    <input type="hidden" name="aid" value="$aid">
    <tr class="tablecell">
      <td colspan="2" align="center"><input type="submit" name="submit" id="submit" value="提交" class="formbutton" onclick="return checkform();">
        <input type="reset" value="重置" class="formbutton"></td>
    </tr>
EOT;
} elseif ($do == 'move') {print <<<EOT
    <tr class="tdbheader">
      <td colspan="1"><a name="移动文章"></a>移动文章</td>
    </tr>
    <tr>
      <td class="alertbox">
	  <p><ol>
        <br>
EOT;
foreach($articledb as $key => $article){print <<<EOT
        <li><a href="{$admin_url}?file=article&action=mod&aid=$article[aid]">$article[title]</a><input type="hidden" name="aids[]" value="$article[aid]"></li>
EOT;
}print <<<EOT
      </ol></p>
	  <p>将以上文章移动到
        <select name="cid">
            <option value="" selected>选择分类</option>
EOT;
foreach($category as $key => $cate){print <<<EOT
            <option value="{$cate['cid']}">{$cate['name']}</option>
EOT;
}print <<<EOT
          </select>
      </p>
      <p><input type="submit" name="submit" id="submit" value="确认" class="formbutton"></p>
      <input type="hidden" name="action" value="domore">
	  <input type="hidden" name="do" value="domove">
	  </td>
    </tr>
EOT;
} elseif ($do == 'delete') {print <<<EOT
    <tr class="alertheader">
      <td colspan="1"><a name="删除文章"></a>删除文章</td>
    </tr>
    <tr>
      <td class="alertbox">
	  <p><ol>
EOT;
foreach($articledb as $key => $article){print <<<EOT
        <li><a href="{$admin_url}?file=article&action=mod&aid=$article[aid]">$article[title]</a><input type="hidden" name="aids[]" value="$article[aid]"></li>
EOT;
}print <<<EOT
      </ol></p>
	  <p><b>注意: 删除以上文章将会连同相关评论、附件一起删除，确定吗？</b></p>
      <p><input type="submit" name="submit" id="submit" value="确认" class="formbutton"></p>
      <input type="hidden" name="action" value="domore">
	  	  <input type="hidden" name="do" value="dodelete">
	  </td>
    </tr>
EOT;
} elseif ($action == 'search') {print <<<EOT
    <tr class="tdbheader">
	  <td colspan="2">搜索文章</td>
    </tr>
    <tr class="tablecell">
      <td valign="top"><b>搜索分类:</b></td>
      <td><select name="cateid">
          <option value="">== 全部分类 ==</option>
EOT;
foreach($category as $key => $cate){
print <<<EOT
          <option value="$cate[cid]">$key. $cate[name]</option>
EOT;
}print <<<EOT
        </select></td>
    </tr>
    <tr class="tablecell">
	  <td><b>搜索关键字:</b></td>
	  <td><input class="formfield" type="text" name="keywords" size="35" maxlength="50" value=""></td>
    </tr>
    <tr class="tablecell">
	  <td><b>添加时间早于:</b><br />
	  yyyy-mm-dd</td>
	  <td><input class="formfield" type="text" name="startdate" size="35" maxlength="50" value=""></td>
    </tr>
    <tr class="tablecell">
	  <td><b>添加时间晚于:</b><br />
	  yyyy-mm-dd</td>
	  <td><input class="formfield" type="text" name="enddate" size="35" maxlength="255" value=""></td>
    </tr>
	<tr class="tablecell">
	  <td><b>阅读量判断:</b><br />
	  可以填写&gt;0或是&lt;100或是=这样的条件</td>
	  <td><input class="formfield" type="text" name="views" size="35" maxlength="50" value=""></td>
    </tr>
    <input type="hidden" name="action" value="list">
    <input type="hidden" name="do" value="search">
    <tr class="tablecell">
      <td colspan="2" align="center" class="tablecell"><input type="submit" name="submit" id="submit" value="提交" class="formbutton">
        <input type="reset" value="重置" class="formbutton"></td>
    </tr>
EOT;
}print <<<EOT
    <tr>
      <td class="tablebottom" colspan="8"></td>
    </tr>
      </table></td>
    </tr>
  </table>
EOT;
if ($action == 'list') {print <<<EOT
<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" height="40">
  <tr>
    <td align="right">
      <select name="do">
        <option value="">= 管理操作 =</option>
        <option value="delete">删除</option>
        <option value="move">移动</option>
      </select>
      <input type="submit" name="submit" id="submit" value="确定" class="formbutton"><input type="hidden" name="action" value="domore"></td>
  </tr>
</table>
EOT;
}print <<<EOT
</form></td>
    </tr>
  </table>
</div>
EOT;
?>
