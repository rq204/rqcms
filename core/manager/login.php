<?php
$loginerr='';
if($action=='logout'&&$adminid)//退出系统
{
	$adminitem=array();
	setcookie('sessionid',null);
	$DB->query("update {$dbprefix}user set `sessionid`=null where uid='$adminid'");
	ob_end_clean();
	ob_start();
	include(RQ_CORE.'/manager/view/header.php');
}
$lusername=$lpassword='';
if(RQ_POST)
{
	$lusername=$_POST['username'];
	$lpassword=$_POST['password'];
	$sql="Select * from {$dbprefix}user where `username`='$lusername' and groupid=1";
	$result=$DB->fetch_first($sql);
	if($result)
	{
		if($result['password']==md5(md5($lpassword)))
		{
			$adminid=$result['uid'];
			$sessionid=getRandStr(30,false);//生成那个登陆信息
			$expire=isset($_POST['rememberme'])?$timestamp+31536000:0;//过期时间设置，记住我为最长时间，否则为浏览器关闭则无效
			setcookie('sessionid',$sessionid,$expire,'/');
			$date=date("Y-m-d h:i:s", time());
			$DB->query("update {$dbprefix}user set `logincount`=`logincount`+1,`loginip`='$onlineip',`logintime`='$date',`sessionid`='$sessionid',`useragent`='$useragent' where uid='$adminid'");
			$DB->query("insert into {$dbprefix}login (`user`,`useragent`,`ip`,`content`,`uid`) values ('$lusername','$useragent','$onlineip','后台登录成功',$adminid)");
			redirect('登陆成功', $admin_url);
		}
		else $loginerr='密码错误';
	}
	else $loginerr='不存在的用户名';
	$DB->query("insert into {$dbprefix}login (`user`,`dateline`,`useragent`,`ip`,`content`) values ('$lusername','$timestamp','$useragent','$onlineip','$loginerr')");
	$file='login';
}
if($loginerr) $loginerr='<font color="red">'.$loginerr.'</font>';