<?php
$admin_url=$setting['option']['admin'];
$rqcms_version=RQ_VERS;
$username='';$adminid=0;
$sessionid=isset($_COOKIE['sessionid'])?$_COOKIE['sessionid']:'';

//判断登录
if(!empty($sessionid)&&strlen($sessionid)==30)
{
	$userinfo=$DB->fetch_first("Select * from  {$dbprefix}user where `sessionid`='$sessionid' and groupid=1");
	if($userinfo)
	{
		$nowips=explode('.',$onlineip);
		$oldips=explode('.',$userinfo['loginip']);
		$diffip=array_diff_assoc($nowips,$oldips);
		if(count($diffip)<2&&!isset($diffip[2])&&$useragent==$userinfo['useragent'])//当最后一位不同时认为是同一地点
		{
			$adminid=$userinfo['uid'];
			$username=$userinfo['username'];
		}
	}
}

$tempView=$coreView;//不用再去加载模板了
$coredir=basename(RQ_CORE);//core目录
$datadir=basename(RQ_DATA);//data目录

$css_url =$admin_url.'?file=css';//管理后台的css文件
$viewdir=$coredir.'/manager/view/';
$incfile=!empty($_GET['file'])?$_GET['file']:'main';
$do=isset($_POST['do'])?$_POST['do']:'';
if(!$do) $do=isset($_GET['do'])?$_GET['do']:'';
$action=!empty($_GET['action'])?$_GET['action']:(!empty($_POST['action'])?$_POST['action']:'');
$cssdir='/'.$coredir.'/manager/view/images/';
$editordir='/'.$coredir.'/manager/editor/';

$page=isset($_GET['page'])?intval($_GET['page']):'';
if($incfile!='css'&&!$adminid)  $incfile='login';

//加载一些类
include RQ_CORE.'/library/func.admin.php';
// 操作提示页面

if(!function_exists('redirect'))
{
function redirect($msg, $url = 'javascript:history.go(-1);', $min='2')
{
	global $cssdir,$css_url;
	ob_end_clean();
	ob_start();
	include RQ_CORE.'/manager/view/redirect.php';
	$output=ob_get_contents();
	@ob_end_clean();
	exit($output);
}
}

$adminitem = array(
		'configurate' => '系统设置',
		'article' => '文章管理',
		'category' => '分类管理',
		'user' => '用户管理',
		'comment'=>'查看评论',
		'template' => '模板管理',
		'link' => '友情链接',
		'tag'=>'TAG优化',
		'plugin'=>'插件管理',
		'cache' => '更新缓存' //这里要添加缓存更新和日志管理功能
		);

$other=array('css','login','special','main','xmlrpc','database','upload');

doAction('change_admin_item');

if(!in_array($incfile,$other)&&!array_key_exists($incfile,$adminitem)) redirect('未定义操作',$admin_url.'?file=main');

// 检查提交Tag是否符合逻辑
function checktag($tag) {
	$tag = str_replace('，', ',', $tag);
	if (strrpos($tag, ',')) {
		$result .= '关键字中不能含有“,”或“，”字符<br />';
		return $result;
	}
	if(strlen($tag) > 15) {
		$result .= '关键字不能超过15个字符<br />';
		return $result;
	}
}

//更改tag
function modtag($oldtag,$newtag,$aid)
{
	global $DB,$dbprefix;
	$oldarr=array();
	$newarr=array();
	if($oldtag) $oldarr=explode(',',$oldtag);
	if($newtag) $newarr=explode(',',$newtag);
	$delold=array_diff($oldarr,$newarr);
	$addnew=array_diff($newarr,$oldarr);
	if($delold)
	{
		foreach($delold as $tag)
		{
			$aids=gettagids($tag);
			if($aids)
			{
				$aidsarr=explode(',',$aids);
				foreach($aidsarr as $k=>$temp)
				{
					if($temp==$aid||!$temp) unset($aidsarr[$k]);
				}
				$aidsnew='';
				if($aidsarr) $aidsnew=implode(',',$aidsarr);
				$DB->query("update {$dbprefix}tag set aids='$aidsnew' where tag='$tag'");
			}
		}
	}
	
	if($addnew)
	{
		foreach($addnew as $tag)
		{
			$aids=gettagids($tag);
			if(!$aids) $aids=$aid;
			else
			{
				$aidsarr=explode(',',$aids);
				$aidsarr[]=$aid;
				$aids=implode(',',$aidsarr);
			}

			$DB->query("replace into `{$dbprefix}tag` (`tag`,`aids`) values ('$tag','$aids')");
		}
	}

	//注释 20150711 ，$DB->query("delete from {$dbprefix}tag where aids=''");
	
}
	
//得到ids
function gettagids($tagname)
{
	global $DB,$dbprefix;
	$tagsql="SELECT aids FROM {$dbprefix}tag WHERE tag='$tagname' ";
	$aids=null;
	$tagarr=$DB->fetch_first($tagsql);
	if($tagarr)
	{
		$aids= $tagarr['aids'];
	}
	return $aids;
}

if($incfile!='css') include RQ_CORE.'/manager/view/header.php';
include RQ_CORE.'/manager/'.$incfile.'.php';
include RQ_CORE.'/manager/view/'.$incfile.'.php';
if($incfile!='css') include RQ_CORE.'/manager/view/footer.php';