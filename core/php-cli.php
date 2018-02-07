<?php
/**
  由php命令行直接执行,RQ_CORE和RQ_DATA需要实际的一致
 */

//核心参数
define('RQ_VERS','3.0.1801');
define('RQ_ROOT',dirname(dirname(__file__)));
define('RQ_CORE',RQ_ROOT.'/core');
define('RQ_DATA',RQ_ROOT.'/data');

if(PHP_VERSION<7.0) die('require php 7.0 or higher , now is '.PHP_VERSION);
#if(empty($argv)) die('olny run in php cli mode');

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

//数据库实例化
$DB=new DB_MySQL();
$DB->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);

setting_recache();
echo "setting_recache ok\r\n";
category_recache();
echo "category_recache ok\r\n";