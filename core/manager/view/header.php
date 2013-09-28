<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$uploadurl=mkUrl('admin.php','').'?file=upload';
print <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="author" content="RQ204" />
<title>{$host['name']} - RQCMS {$constant['RQ_VERSION']}</title>
<link rel="stylesheet" href="{$cssfile}" type="text/css">
<script type="text/javascript" src="{$editordir}jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="{$editordir}xheditor-1.1.14-zh-cn.min.js"></script>

<style type="text/css">
<!--
.btnCode {
	background:transparent url({$editordir}/xheditor_plugins/prettify/code.gif) no-repeat 16px 16px;
	background-position:2px 2px;
}
-->
</style>
<script type="text/javascript">

function checkall(form) {
	for (var i=0;i<form.elements.length;i++) {
		var e = form.elements[i];
		if (e.name != 'chkall')
		e.checked = form.chkall.checked;
    }
}

var editor;
$(pageInit);
function pageInit()
{
	var allPlugin={
	Code:{c:'btnCode',t:'插入代码',h:1,e:function(){
		var _this=this;
		var htmlCode='<div><select id="xheCodeType"><option value="html">HTML/XML</option><option value="js">Javascript</option><option value="css">CSS</option><option value="php">PHP</option><option value="java">Java</option><option value="py">Python</option><option value="pl">Perl</option><option value="rb">Ruby</option><option value="cs">C#</option><option value="c">C++/C</option><option value="vb">VB/ASP</option><option value="">其它</option></select></div><div><textarea id="xheCodeValue" wrap="soft" spellcheck="false" style="width:300px;height:100px;" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';			var jCode=$(htmlCode),jType=$('#xheCodeType',jCode),jValue=$('#xheCodeValue',jCode),jSave=$('#xheSave',jCode);
		jSave.click(function(){
			_this.loadBookmark();
			_this.pasteHTML('<pre class="prettyprint lang-'+jType.val()+'">'+_this.domEncode(jValue.val())+'</pre>');
			_this.hidePanel();
			return false;	
		});
		_this.saveBookmark();
		_this.showDialog(jCode);
	}}
	};
	editor=$('#content').xheditor({plugins:allPlugin,tools:'Cut,Copy,Paste,Pastetext,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,Align,List,Outdent,Indent,Link,Unlink,Anchor,Img,Flash,Media,Hr,Table,Code,|,Source,Preview,Print,Fullscreen,About',upLinkExt:"zip,rar,txt",upImgUrl:"{$uploadurl}",upImgExt:"jpg,jpeg,gif,png",upFlashUrl:"{$uploadurl}",upFlashExt:"swf",upMediaUrl:"{$uploadurl}",upMediaExt:"avi,rmvb,mkv,mp4,wmv,wma,mid"});
}
</script>

</head>
<body>
<a name="TOP" id="TOP"></a>
<table width="100%" border="0" cellpadding="0" cellspacing="0" background="{$cssdir}page_bg.jpg">
  <tr>
    <td><div class="topBar">
      <table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
        <tr>
          <td class="topLinksLeft"></td>
EOT;
if ($groupid) {
print <<<EOT
          <td class="topLinks">欢迎您 $username [<a href="admin.php?file=login&action=logout">注销身份</a>] 
EOT;
if($groupid==4) echo ' [<a href="admin.php?file=special">站点管理</a>]';
if ($groupid) print <<<EOT
  [<a href="./" target="_blank">站点首页</a>]
</td>
        </tr>
      </table>
    </div>
EOT;
}if (isset($adminitem) && $adminitem) {print <<<EOT
    <table width="100%" height="25" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
EOT;
foreach ($adminitem AS $link => $title)	{
print <<<EOT
         <td width="9%" class="navcell" onMouseover="$('$link').className='cpnavmenuHover'" onMouseout="$('{$link}').className='cpnavmenu'"><div class="cpnavmenu" id="{$link}"><a href="admin.php?file={$link}">{$title}</a></div></td>
EOT;
}print <<<EOT
          <td>&nbsp;</td>
        </tr>
      </table>
EOT;
}print <<<EOT
</td>
  </tr>
</table>
EOT;
?>