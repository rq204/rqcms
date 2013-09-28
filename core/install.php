<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$lockfile=RQ_DATA.'/install.lock';
$sqlfile=RQ_CORE.'/resource/install.sql';
$configfile=RQ_CORE.'/resource/conig.sample.php';
$rqcms_coredir=basename(RQ_CORE);
$rqcms_datadir= basename(RQ_DATA);
$rqcms_version=RQ_VERSION;
$dbcharset='Utf-8';
echo <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RQCMS $rqcms_version 安装脚本</title>
<link href="$rqcms_coredir/resource/install.css" rel="stylesheet" type="text/css" />
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
	$cacheDir=RQ_DATA.'/cache/';
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
			$password=md5($password);
			$tablenum=0;
			runquery($sql);
			echo "成功创建{$tablenum}个表<br />";
			$DB->query("Insert into ".DB_PREFIX."user (`hostid`,`username`,`password`,`groupid`,`regdateline`,`regip`,`qq`) values ('1','$username','$password','4','$timestamp','$onlineip','285576545')");
			echo "成功添加管理员帐号{$username}<br />";
			file_put_contents($lockfile,md5(RQ_HOST));
			$DB->query("update ".DB_PREFIX."host set `host`='".RQ_HOST."'");
			$DB->query("INSERT INTO `".DB_PREFIX."article` (`aid`, `hostid`, `cateid`, `userid`, `title`, `keywords`, `tag`, `url`, `excerpt`, `dateline`, `modified`, `views`, `comments`, `attachments`, `closed`, `visible`, `stick`, `score`, `password`, `ban`) VALUES (NULL, '1', '1', '1', '感谢您使用RQCMS', '', 'rqcms', 'welcome', '','$timestamp', '$timestamp', '1', '0', '0',  '0', '1', '1', '0', '', '0')");
			$DB->query("INSERT INTO `".DB_PREFIX."comment` (`cid`, `hostid`, `articleid`, `userid`, `username`, `dateline`, `content`, `ipaddress`, `score`, `visible`, `ban`) VALUES (1, 1, 1, 1, '$username', '$timestamp', '测试评论', '$onlineip', 0, 1, 0)");
			$DB->query('Insert into `'.DB_PREFIX."content` (`articleid`,`content`) values ('1','感谢您使用RQCMS')");
			hosts_recache();
			$hosts=include RQ_DATA.'/cache/hosts.php';
			$host=$hosts[RQ_HOST];
			$hostid=1;
			filemaps_recache();
			plugins_recache();
			links_recache();
			cates_recache();
			vars_recache();
			$mapArr= @include RQ_DATA.'/cache/map_'.$host['host'].'.php';
			$cateArr=@include RQ_DATA.'/cache/cate_'.$host['host'].'.php';
			rss_recache();
			stick_recache();
			pics_recache();
			latest_recache();
			comments_recache();
			redirect_recache();
			hot_recache();
			search_recache();
			echo "成功更新系统缓存<br />安装完毕,点击这里进入<a href='admin.php'>管理后台</a>";
		}
		else
		{
			if(!file_exists($sqlfile))
			{
				echo "不存在的安装文件install.sql.请检查安装文件";
			}
			else
			{
				preg_match_all("/CREATE TABLE `([a-z0-9_]+)`/",$sql,$dataarr);
				$dbarrs=$dataarr[1];
				$tables=$DB->query("show tables");
				$dbtables=array();
				while($dbs=$DB->fetch_array($tables))
				{
					$temp=array_values($dbs);
					$dbtables[]=$temp[0];
				}
				$same=array_intersect($dbarrs,$dbtables);
				$info='';
				if(count($same)>0)
				{
					$info='<font color=\'red\'>程序检测到数据库中已经安装过RQCMS,如果继续,原来的数据将会被全部清空,请慎重操作!</font>';
				}
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
		echo "您的网站缓存目录{$rqcms_datadir}/cache不可写,请修改其权限为777";
	}
}

echo <<<EOT
</div>
<strong>Powered by RQCMS $rqcms_version (C) 2010-2012 RQCMS.COM</strong>
</body>
</html>
EOT;

function runquery($sql) {
	global $dbcharset, $DB, $tablenum;
	$sql = str_replace("\r", "\n", str_replace('`rqcms_', '`'.DB_PREFIX, $sql));
	$ret = explode(";\n", trim($sql));
	unset($sql);
	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE `([a-z0-9_]+)` \(.*/is", "\\1", $query);
				$DB->query($query);
				echo '创建表 '.$name.' ... <font color="#0000EE">成功</font><br />';
				$tablenum++;
			} else {
				$DB->query($query);
			}
		}
	}
}

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
exit();
?>