<?php
$lockfile=RQ_DATA.'/install.lock';
$sqlfile=RQ_CORE.'/resource/install.sql';
$rqcms_coredir=basename(RQ_CORE);
$rqcms_datadir= basename(RQ_DATA);
$rqcms_version=RQ_VERS;
include RQ_CORE.'/library/func.convert.php';
header('Content-Type: text/html; charset=UTF-8');
echo <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RQCMS $rqcms_version 安装脚本</title>
<script type="text/javascript" src="$rqcms_coredir/manager/editor/jquery-1.4.4.min.js"></script>
<script type="text/javascript">
function checkNull()
{
	var str="";
	if($("#username").val()=='')
	{
		 alert("用户名不能为空!");
		 return false;
	}
	$("input[type$='password']").each(function(n)
	{
		if($(this).val()=="")
		{
			alert($(this).attr("title")+"不能为空!");
			return false;
		}
	});
	
	if($("#password").val()!==$("#comfirpassword").val())
	{
		alert("两次输入的密码必须一样!");
		return false;
	}
	return true;
}
</script>
<style>
body {
	margin: 20px;
	line-height: 140%;
	color: #000000;
	font: 14px "Georgia", "Verdana", "Tahoma", "sans-serif", "宋体";
	background-color: #cdd6dd;
	text-align: center;
}
a {
	color: #333399; 
	text-decoration: none;
}
a:hover {
	color: #CC0000; 
}
td {
	font: 14px "Georgia", "Verdana", "Tahoma", "sans-serif", "宋体";
	line-height: 160%;
	color: #000000;
}
div {
	font: 14px "Georgia", "Verdana", "Tahoma", "sans-serif", "宋体";
	line-height: 160%;
	color: #000000;
}
#main {
	background-color: #fff;
	text-align: left;
	padding: 20px;
	width: 600px;
	border: 1px solid #ccc;
	margin-bottom: 20px;
	margin:auto;
}
.title {
	font-size: 18px;
	font-weight: bold;
}
form {
	margin: 0px;
	padding: 0px;
}
.formfield {
	font: 14px "Georgia", "Verdana", "Tahoma", "sans-serif", "宋体";
	font-weight: bold;
	background-color: #fff;
	padding: 3px;
	border: 1px solid #BFBFBF;
	margin-right: 10px;
}
.formbutton {
	font: 14px "Georgia", "Verdana", "Tahoma", "sans-serif", "宋体";
	font-weight: bold;
	padding: 3px;
}
.install_main {
	width: 500px;
	margin-right: auto;
	margin-left: auto;
	background-color: #fff;
	padding: 30px;
	margin-top: 10%;
	border: 1px solid #333333;
	text-align: center;
}
.install_logo{
	border: 1px solid #333333;
}
.p2 {
	text-align: left;
	font-weight: bold;
	color: #666666;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.copyright {
	font-size: 12px;
	text-align: center;
	line-height: 30px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
</style>
</head>
<body>
<div id="main">
EOT;
if(file_exists($lockfile)) 
{	
	echo "<p>您已经安装过RQCMS $rqcms_version,如果您需要重新安装,请删除{$rqcms_datadir}目录下的install.lock文件并刷新本页面</p>";
}
else
{
	$cacheDir=RQ_DATA.'/caches/';
	if(TestWrite($cacheDir))
	{
		$sql=file_get_contents($sqlfile);
		if(!empty($_POST))
		{
			$username=$_POST['username'];
			$password=$_POST['password'];
			$comfirpassword=$_POST['comfirpassword'];
			if(empty($username)) exit('用户名不得为空');
			if(empty($password)) exit('密码不得为空');
			if($password!=$comfirpassword) exit('两次输入的密码必须一样!');
			$password=md5(md5($password));
			$tablenum=0;
			runquery($sql,$dbprefix);
			echo "成功创建{$tablenum}个表<br />";
			$DB->query("Insert into {$dbprefix}user (`username`,`password`,`groupid`) values ('$username','$password','1')");
			echo "成功添加管理员帐号{$username}<br />";
			$DB->query("INSERT INTO `{$dbprefix}article` (`aid`,  `cateid`, `title`, `tag`, `excerpt`, `views` ) VALUES (NULL,'1', '感谢您使用RQCMS', 'rqcms', '', '1')");
			$DB->query("INSERT INTO `{$dbprefix}comment` (`cid`,  `articleid`, `userid`, `username`,`content`, `ipaddress`) VALUES (1, 1, 1, '$username', '测试评论', '$onlineip')");
			$DB->query("Insert into `{$dbprefix}content` (`articleid`,`content`) values ('1','感谢您使用RQCMS')");
			$DB->query("Insert Into `{$dbprefix}option` (`name`,`value`) values ('name','又一个RQCMS'),('theme','default'),('per_page_articles','10'),('search_list_pages',50),('keywords',''),('description',''),('article_list_pages',100)");
			setting_recache();
			category_recache();
			file_put_contents($lockfile,'installed');
			exit("成功更新系统缓存<br />安装完毕,点击这里进入<a href='/admin/'>管理后台</a>");
		}
		else
		{
			if(!file_exists($sqlfile))
			{
				echo "不存在的安装文件install.sql.请检查安装文件";
			}
			else
			{
				//preg_match_all("/CREATE TABLE `([a-z0-9_]+)`/",$sql,$dataarr);
				//$dbarrs=$dataarr[1];
				$tables=$DB->query("show tables");
				$dbtables=GetTables();
				$info='';
				if(in_array('rqcms_host',$dbtables)) $info='<font color=\'red\'>程序检测到数据库中已经安装过RQCMS,如果继续,原来的数据将会被全部清空,请慎重操作!</font>';
				//$same=array_intersect($dbarrs,$dbtables);
echo <<<EOT
	<form method="post" action="install.php">
	<p class="title">设置管理员账号</p>
	<hr noshade="noshade" />
	<p>{$info}</p>
	<table width="100%" border="0" cellspacing="0" cellpadding="4">
	  <tr>
		<td width="30%" nowrap>用户名:</td>
		<td><input type="text" value="" name="username" id="username" class="formfield" style="width:150px" title="用户名"></td>
	  </tr>
	  <tr>
		<td width="30%" nowrap>密码:</td>
		<td><input type="password" value="" name="password" id="password" class="formfield" style="width:150px" title="密码"></td>
	  </tr>
	  <tr>
		<td width="30%" nowrap>确认密码:</td>
		<td><input type="password" value="" name="comfirpassword" id="comfirpassword" class="formfield" style="width:150px" title="确认密码"></td>
	  </tr>
	</table>
	<p>&nbsp;</p>
	<hr noshade="noshade" />
	<p align="right">
	  <input type="hidden" name="step" value="4" />
	  <input class="formbutton" type="submit" value="开始安装" onclick="return checkNull()"/>
	</p>
	</form>	
EOT;
			}
		}
	}
	else
	{
		echo "您的网站缓存目录{$rqcms_datadir}/caches不可写,请修改其权限为777";
	}
}

echo <<<EOT
</div>
<strong>Powered by RQCMS $rqcms_version (C) 2010-2018 RQCMS.COM</strong>
</body>
</html>
EOT;

function TestWrite($d)
{
	$tfile = '_test.txt';
	$fp = @fopen($d.'/'.$tfile,'w');
	if(!$fp) return false;
	else
	{
		fclose($fp);
		$rs = @unlink($d.'/'.$tfile);
		if($rs) return true;
		else return false;
	}
}
//获取表中的所有字段
function GetTableField($table)
{
	global $DB,$dbprefix;
	$arrlist=array();
	$sqlColumns = $DB->query("SHOW COLUMNS FROM ".$dbprefix."$table");
	while($re=$DB->fetch_array($sqlColumns))
	{
		$arrlist[]=$re['Field'];
	}
	return $arrlist;
}

///获取所有表名
function GetTables()
{
	global $DB;
	$dbtables=array();
	$tables=$DB->query("show tables");
	while($dbs=$DB->fetch_array($tables))
	{
		$temp=array_values($dbs);
		$dbtables[]=$temp[0];
	}
	return $dbtables;
}