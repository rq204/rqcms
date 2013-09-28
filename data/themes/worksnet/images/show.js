




/*
     FILE ARCHIVED ON 7:51:11 十一月 30, 2010 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 14:03:08 十一月 27, 2012.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
function checkform() {
	if ($('#username').length > 0 && $('#username').val() == "") {
		alert("请输入您的名字.");
		return false;
	}
	if ($('#email').length > 0 && $('#email').val() == "") {
		alert("请输入您的电子邮件.");
		return false;
	}
	if ($('#clientcode').length > 0 && $('#clientcode').val() == "")	{
		alert("请输入验证码.");
		return false;
	}
	if ($('#content').val() == "")	{
		alert("请输入内容.");
		return false;
	}
	if (((postminchars != 0 && $('#content').val().length < postminchars) || (postmaxchars != 0 && $('#content').val().length > postmaxchars))) {
		alert("您的评论内容长度不符合要求。\n\n当前长度: "+$('#content').val().length+" 字节\n系统限制: "+postminchars+" 到 "+postmaxchars+" 字节");
		return false;
	}
	$('#submit').disabled = true;
	return true;
}

function ctlent(event) {
	if((event.ctrlKey && event.keyCode == 13) || (event.altKey && event.keyCode == 83)) {
		$("#submit").click();
	}
}

function addquote(obj,strAuthor){
	var text = $('#'+obj).html();
	text = text.replace(/alt\=(\"|)([^\"\s]*)(\"|)/g,"> $2 <");
	text = text.replace(/\<[^\<\>]+\>/g,"\n");
	text = text.replace(/ +/g," ");
	text = text.replace(/\n+/g,"\n");
	text = text.replace(/^\n*/gm,"");
	text = text.replace(/^\s*/gm,"");
	text = text.replace(/\n*$/gm,"");
	text = text.replace(/\s*$/gm,"");
	text = text.replace(/&lt;/g,"<");
	text = text.replace(/&gt;/g,">");
	text = text.replace(/&nbsp;&nbsp;/g,"  ");
	text = text.replace(/&amp;/g,"&");
	$("#content").val($("#content").val() + "[quote="+strAuthor+"]"+text+"[/quote]");
	$("#content").focus();
}

function setCopy(content){
	if(navigator.userAgent.toLowerCase().indexOf('ie') > -1) {
		clipboardData.setData('Text',content);
		alert ("该地址已经复制到剪切板");
	} else {
		prompt("请复制网站地址:",content);
	}
}

function showajaxdiv(url) {
	var x = new Ajax('statusid', 'XML');
	x.get(url, showxml);
}

function tagshow(tag) {
	var x = new Ajax('statusid', 'XML');
	x.get(blogurl + 'getxml.php?action=tag&tag=' + encodeURIComponent(tag), showxml);
}