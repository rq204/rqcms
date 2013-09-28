<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
print <<<EOT
<script type="text/javascript">

function checkform()
{
	if ($("#title").val()=='') 
	{
		alert("请输入标题");
		return false;
	}
	if ($("#cid").val()==0)	
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
		var upurl="admin.php?file=upload&cid="+$(this).children('option:selected').val(); 
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
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=article&action=add">添加文章</a></div>
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=article&action=list">编辑文章</a></div>
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=article&action=search">搜索文章</a></div>
		  <div class="leftmenuitem">&#8226; <a href="admin.php?file=article&action=list&view=hidden">草稿箱($hiddenCount)</a></div>
        </div>
      </div>
	  <div class="tableborder">
        <div class="tableheader">文章分类</div>
        <div class="leftmenubody">
		  <div class="leftmenuitem">&#8226; <a href="admin.php?file=article&action=list&view=stick">置顶文章</a></div>
EOT;
foreach($cateArr as $key => $cate){print <<<EOT
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=article&action=list&cid={$cate['cid']}">{$cate['name']}</a></div>
EOT;
}print <<<EOT
        </div>
      </div></td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top">
	  <form action="admin.php?file=article" enctype="multipart/form-data" method="POST" name="form1""><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	  <tr><td class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
EOT;
if($action == 'list'){print <<<EOT
    <tr class="tdbheader">
      <td width="45%">标题</td>
   	  <td width="12%" nowrap>时间</td>
	  <td width="10%" nowrap>分类</td>
      <td width="6%" nowrap>查看</td>
      <td width="7%" nowrap>回复</td>
      <td width="10%" nowrap>附件</td>
	  <td width="7%" nowrap>作者</td>
      <td width="3%" nowrap><input name="chkall" value="on" type="checkbox" onclick="checkall(this.form)"></td>
    </tr>
EOT;
foreach($articledb as $key => $article){print <<<EOT
    <tr class="tablecell">
      <td><a href="admin.php?file=article&action=mod&aid=$article[aid]">$article[title]</a></td>
	  <td nowrap>$article[dateline]</td>
      <td nowrap><a href="admin.php?file=article&action=list&cid=$article[cateid]">$article[cname]</a></td>
	  <td nowrap>$article[views]</td>
      <td nowrap>$article[comments]</td>
      <td nowrap>$article[attachment]</td>
	  <td nowrap>$article[userid]</td>
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
      <td><input class="formfield" type="text" name="title" id="title" size="70" value="$article[title]"></td>
    </tr>
    <tr class="tablecell">
      <td valign="top">选择分类:</td>
      <td><select name="cid" id="cid">
          <option value="">== 选择分类 ==</option>
EOT;
$i=0;
foreach($cateArr as $key => $cate){
$i++;
$selected = ($cate['cid'] == $article['cateid']) ? "selected" : "";
print <<<EOT
          <option value="{$cate['cid']}" $selected>$i. {$cate['name']}</option>
EOT;
}print <<<EOT
        </select></td>
    </tr>
    <tr class="tablecell">
      <td>标签(Tag):</td>
      <td><input class="formfield" type="text" name="tag" size="80" maxlength="10000" value="$article[tag]">&nbsp;多个Tag用,分隔</td>
    </tr>
	 <tr class="tablecell">
      <td>关键字:</td>
      <td><input class="formfield" type="text" name="keywords" size="80" maxlength="10000" value="$article[keywords]">&nbsp;多个关键字用,分隔</td>
    </tr>
    <tr class="tablecell">
      <td valign="top">文章描述:</td>
      <td><textarea name="excerpt" style="width:100%; height:100px;">{$article['excerpt']}</textarea></td>
    </tr>
    <tr class="tablecell">
      <td valign="top">文章内容:<br /><br />手动分页符<br /><a href="javascript:void(0);" onClick="editor.pasteHTML('[page]');">[page]</a></td>
      <td><textarea name="content" id="content" style="width:100%; height:400px;">{$article['content']}</textarea></td>
    </tr>
	 <tr class="tablecell">
      <td>友好网址:</td>
      <td><input class="formfield" type="text" name="url" size="50" maxlength="255" value="$article[url]"> 255个字符以内</td>
    </tr>
	<tr class="tablecell">
      <td>缩略图片:</td>
      <td><input class="formfield" type="text" name="thumb" size="50" maxlength="20" value="$article[thumb]"> 255个字符以内</td>
    </tr>
EOT;
print <<<EOT
    <tr class="tablecell">
      <td valign="top">更多选项:</td>
      <td> <input name="visible" type="checkbox" value="1" $visible_check>
        发布本文,不选则为草稿 <input name="stick" type="checkbox" value="1" $stick_check>
        置顶本文<br />
		<input name="closed" type="checkbox" value="1" $closecomment_check><input class="formfield" type="hidden" name="password" size="50" maxlength="20" value="$article[password]"> 
        禁止评论
		<input name='edittime' type="checkbox" value="1">
		更改发布时间 <input class="formfield" name="newyear" type="text" value="$newyear" maxlength="4" style="width:40px"> 年 <input class="formfield" name="newmonth" type="text" value="$newmonth" maxlength="2" style="width:20px"> 月 <input class="formfield" name="newday" type="text" value="$newday" maxlength="2" style="width:20px"> 日 <input class="formfield" name="newhour" type="text" value="$newhour" maxlength="2" style="width:20px"> 时 <input class="formfield" name="newmin" type="text" value="$newmin" maxlength="2" style="width:20px"> 分 <input class="formfield" name="newsec" type="text" value="$newsec" maxlength="2" style="width:20px"> 秒 <input class="formbutton" type="button" onclick="alert('有效的时间戳典型范围是从格林威治时间 1901 年 12 月 13 日 星期五 20:45:54 到 2038年 1 月 19 日 星期二 03:14:07\\n\\n该日期根据 32 位有符号整数的最小值和最大值而来\\n\\n取值说明: 日取 01 到 30 之间, 时取 0 到 24 之间, 分和秒取 0 到 60 之间!\\n\\n系统会自动检查时间有效性,如果不在有效范围内,将不会执行更改时间操作\\n\\n注意:如果系统是按照时间而不是提交次序排列文章,修改时间可以改变文章的顺序.');" value="时间说明">		
		</td>
    </tr>
EOT;
if(count($attachdb) > 0){print <<<EOT
    <tr class="tablecell">
      <td valign="top">已上传的附件:</td>
      <td>
EOT;
foreach($attachdb as $key => $attach){
$atturl=mkUrl('attachment.php',$attach['aid']);
print <<<EOT
<input type="checkbox" name="keep[]" value="{$attach['aid']}" checked> 保留 <a href="$atturl" target="_blank"><b>$attach[filename]</b></a> ($attach[dateline], $attach[filesize]) <b> <a href="javascript:void(0);" onClick="editor.pasteHTML('[attach={$attach['aid']}]');">插入文章</a></b><br />
EOT;
}print <<<EOT
</td>
    </tr>
EOT;
}print <<<EOT
    <tr class="tablecell">
      <td valign="top">上传新附件:</td>
      <td style="padding-top:5px;">
<table cellspacing="0" cellpadding="6" border="0" class="celltable">
  <tbody id="attach"><tr><td><input type="file" name="attach[0]" class="formfield"/><b> <a href="javascript:void(0);" onClick="editor.pasteHTML('[localfile=0]');">插入文章</a></b><input type="file" name="attach[1]" class="formfield"/><b> <a href="javascript:void(0);" onClick="editor.pasteHTML('[localfile=1]');">插入文章</a></b><input type="file" name="attach[2]" class="formfield"/><b> <a href="javascript:void(0);" onClick="editor.pasteHTML('[localfile=2]');">插入文章</a></b><input type="file" name="attach[3]" class="formfield"/><b> <a href="javascript:void(0);" onClick="editor.pasteHTML('[localfile=3]');">插入文章</a></b></td></tr></tbody>
  <tbody id="attachbody"></tbody>
</table>
  </td>
    </tr>
    <input type="hidden" name="action" value="$action">
    <input type="hidden" name="aid" value="$aid">
    <input type="hidden" name="oldtags" value="$article[keywords]">
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
        <li><a href="admin.php?file=article&action=mod&aid=$article[aid]">$article[title]</a><input type="hidden" name="aids[]" value="$article[aid]"></li>
EOT;
}print <<<EOT
      </ol></p>
	  <p>将以上文章移动到
        <select name="cid">
            <option value="" selected>选择分类</option>
EOT;
foreach($cateArr as $key => $cate){print <<<EOT
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
        <li><a href="admin.php?file=article&action=mod&aid=$article[aid]">$article[title]</a><input type="hidden" name="aids[]" value="$article[aid]"></li>
EOT;
}print <<<EOT
      </ol></p>
	  <p><b>注意: 删除以上文章将会连同相关评论、附件一起删除，确定吗？</b></p>
      <p><input type="submit" name="submit" id="submit" value="确认" class="formbutton"></p>
      <input type="hidden" name="action" value="domore">
	  	  <input type="hidden" name="do" value="dodelete">
		  <input type="hidden" name="view" value="$view">
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
$i=0;
foreach($cateArr as $key => $cate){
$i++;
$selected = ($cate['cid'] == $article['cid']) ? 'selected' : '';
print <<<EOT
          <option value="$cate[cid]" $selected>$i. $cate[name]</option>
EOT;
}print <<<EOT
        </select></td>
    </tr>
    <tr class="tablecell">
	  <td><b>标题、作者、描述、内容内的关键字:</b></td>
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
	   <input type="hidden" name="view" value="$view">
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
