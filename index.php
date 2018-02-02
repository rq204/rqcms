<?php
/**
 * RQCMS       A simple,personal,fast cms 
 * @env        Nginx Apache PHP MySql,No IIS
 * @copyright  Copyright (c) 2010-2018 RQ204
 * @license    GNU General Public License 2.0
 * @t          http://t.qq.com/winslow
 */

//核心参数
define('RQ_VERS','3.0.1801');
define('RQ_ROOT',dirname(__file__));
define('RQ_CORE',RQ_ROOT.'/core');
define('RQ_DATA',RQ_ROOT.'/data');
define('RQ_HOST',$_SERVER['HTTP_HOST']);
define('RQ_POST',$_SERVER['REQUEST_METHOD'] == 'GET' ? false : true);
define('RQ_HTTP',(isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'],'off')!=0) ? 'https://' : 'http://');
define('RQ_ISIE',isset($_SERVER['HTTP_USER_AGENT'])&&strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')); 

if(PHP_VERSION<7.0) die('require php 7.0 or higher , now is '.PHP_VERSION);

//加载公共类和配置文件
include RQ_CORE.'/library/class.mysql.php';
include RQ_CORE.'/library/func.base.php';
include RQ_CORE.'/library/func.agile.php';
include RQ_CORE.'/library/func.cache.php';
include RQ_CORE.'/library/func.data.php';
include RQ_DATA.'/config.php';

//错误提示设置和参数过滤
if(RQ_DEBUG) 
{
	error_reporting(E_ALL | E_WARNING);
	set_error_handler("debug");
}
else error_reporting(0);

//禁止自动转反斜杠
if(get_magic_quotes_runtime()) set_magic_quotes_runtime(false);
doStripslashes();

//数据库实例化
$DB=new DB_MySQL();
$DB->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);

//开启缓冲区
ob_start();

if(empty($_POST)&&isset($HTTP_RAW_POST_DATA)) $_POST=$HTTP_RAW_POST_DATA;

//IP地址和User-Agent
$onlineip=getIp();
$useragent=htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
$timestamp=time();
$hookArr = array();//当前站点的插件方法列表
$headArr=array();//头部需要显示的数据，调用viewhead()
$footArr=array();//尾部需要显示的数据,调用viewfoot();

//读取缓存数据
$setting=array();//配置信息，包含option,plugin,link
$setting=@include RQ_DATA.'/caches/setting.php';
if(!$setting)
{
	include RQ_CORE.'/install.php';
	exit();
}
$category=array();//当前站点的分类数据
$category=@include RQ_DATA.'/caches/category.php';
if(!$category) $category=array();

//加载插件，插件目录和插件文件名应保持一致
if (isset($setting['plugin']) && is_array($setting['plugin']))
{
	foreach($setting['plugin'] as $pluginName=>$pluginData)
	{
		if(file_exists(RQ_DATA.'/plugins/'.$pluginName.'/'.$pluginName.'.php'))
		{
			include RQ_DATA.'/plugins/'.$pluginName.'/'.$pluginName.'.php';
		}
	}
}

doAction('init');

//获取当前要执行的文件，先tag,search,article,admin,再分类.没有就是404
$request_url=trim($_SERVER['REQUEST_URI'],'/');
$markurl=strpos($request_url,'?');
if($markurl!==false) 
{
	if($markurl===0) $request_url='';
	else $request_url=substr($request_url,0,$markurl);
}
$request_arr=explode('/',$request_url);
$fix_filemap=array('tag','search','article','admin','index');
$views='';$arg1='';$arg2='';
if($request_url=='') $views='index';
if(!$views)
{
	foreach($fix_filemap as $filemap)
	{
		if($setting['option'][$filemap]==$request_arr[0])
		{
			$views=$filemap;
			break;
		}
	}
}
if(!$views)
{
	foreach($category as $cate)
	{
		if($cate['url']==$request_arr[0])
		{
			$views='category';
			$arg1=$cate['cid'];
			if(count($request_arr)>=2) $arg2=$request_arr[1];
			break;
		}
	}
}

if(!$views) $views='404';

doAction('change_views');

if(count($request_arr)>=2&&!$arg1) $arg1=$request_arr[1];
if(count($request_arr)>=3&&!$arg2) $arg2=$request_arr[2];

//加载模板
$theme=$setting['option']['theme'];//站点模板
if(!isset($theme)) $theme='default';

//加载执行文件和模板
$coreView=RQ_CORE.'/'.$views.'.php';//核心处理文件
$tempView=RQ_DATA.'/themes/'.$theme.'/'.$views.'.php';//风格模板文件
$contentType='Content-Type: text/html; charset=UTF-8';

//特别几个网址的处理
$host_url=RQ_HTTP.RQ_HOST;
$page_url=RQ_HTTP.RQ_HOST.$_SERVER['REQUEST_URI'];
$refer_url=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';

doAction('before_router');
include_once $coreView;
include_once $tempView;

//输出前处理,输出ContentType,网址重写，插件处理，网页压缩
header($contentType);
header('Cache-Control:max-age=0');//缓存的处理http://blog.csdn.net/nashuiliang/article/details/7854633
$output=ob_get_contents();
ob_end_clean();
doAction('before_output');
ob_start();//这里不使用压缩，服务器端的压缩比php效率更高
echo $output;
ob_flush();//输出内容