<?php
/**
 *  该升级程序可以将以前的所有旧版本升级到 RQCMS 1.3 
 *  注意要 RQ_CORE 和 RQ_DATA 要和网站一致
 *  升级完成后删除这个文件.升级完后后台更新一下缓存
 */

//核心参数
define('RQ_ROOT',dirname(__file__));
define('RQ_CORE',RQ_ROOT.'/core');
define('RQ_DATA',RQ_ROOT.'/data');
define('RQ_HOST',$_SERVER['HTTP_HOST']);
define('RQ_POST',$_SERVER['REQUEST_METHOD'] == 'GET' ? false : true);
define('RQ_HTTP',(isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'],'off')!=0) ? 'https://' : 'http://');


//加载公共类和配置文件
include RQ_CORE.'/library/class.mysql.php';
include RQ_CORE.'/library/func.base.php';
include RQ_CORE.'/library/func.agile.php';
include RQ_CORE.'/library/func.cache.php';
include RQ_CORE.'/library/func.data.php';
include RQ_DATA.'/config.php';

//数据库实例化
$DB=new DB_MySQL();
$DB->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE,0);

header('Content-Type: text/html; charset=UTF-8');
print <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="author" content="RQ204" />
<title>RQCMS自动升级程序</title></head><body>
EOT;


//获取表的所有字段
function GetField($table)
{
	global $DB;
	$arrlist=array();
	$sqlColumns = $DB->query("SHOW COLUMNS FROM ".DB_PREFIX."$table");
	while($re=$DB->fetch_array($sqlColumns))
	{
		$arrlist[]=$re['Field'];
	}
	return $arrlist;
}

$varhost=GetField('host');
if(!in_array('host2',$varhost))//1.2的升级
{
	$DB->query("ALTER TABLE `".DB_PREFIX."host` ADD COLUMN `host2` VARCHAR(100) NULL DEFAULT ''");
	echo '升级host字段host2成功<br />';
}
if(!in_array('search_field_allow',$varhost))//1.2的升级
{
	$DB->query("ALTER TABLE `".DB_PREFIX."host` ADD COLUMN `search_field_allow` VARCHAR(100) NULL DEFAULT 'tag,keywords,title,excerpt'");
	echo '升级host字段search_field_allow成功<br />';
}
if(!in_array('search_max_num',$varhost))//1.2的升级
{
	$DB->query("ALTER TABLE `".DB_PREFIX."host` ADD COLUMN `search_max_num` mediumint(8) NOT NULL DEFAULT 0");
	echo '升级host字段search_max_num成功<br />';
}

$varplugin=GetField('plugin');
if(!in_array('mapname',$varplugin))//1.3的升级
{
	$DB->query("ALTER TABLE `".DB_PREFIX."plugin` ADD COLUMN `mapname` VARCHAR(15) NULL DEFAULT ''");
	echo '升级plugin字段mapname成功<br />';
}
if(!in_array('mapvars',$varplugin))//1.3的升级
{
	$DB->query("ALTER TABLE `".DB_PREFIX."plugin` ADD COLUMN `mapvars` VARCHAR(1000) NULL DEFAULT ''");
	echo '升级plugin字段mapvars成功<br />';
}

$hostquery=$DB->query('Select * from '.DB_PREFIX.'host');//1.2
while($arr=$DB->fetch_array($hostquery))
{
	$varhid=$arr['hid'];
	$varfilemap=$DB->fetch_first('Select * from '.DB_PREFIX."filemap where original='archive.php' and hostid=$varhid");
	if(!isset($varfilemap['original'])) $DB->query('insert into '.DB_PREFIX."filemap (`original`,`filename`,`hostid`) values ('archive.php','archive','$varhid')");
	$varfilemap=$DB->fetch_first('Select * from '.DB_PREFIX."filemap where original='link.php' and hostid=$varhid");
	if(!isset($varfilemap['original'])) $DB->query('insert into '.DB_PREFIX."filemap (`original`,`filename`,`hostid`) values ('link.php','link','$varhid')");
}

$varcate=GetField('category');
if(!in_array('keywords',$varcate))//1.3的升级
{
	$DB->query("ALTER TABLE `".DB_PREFIX."category` ADD COLUMN `keywords` VARCHAR(100) NULL DEFAULT ''");
	echo '升级category字段keywords成功<br />';
}
if(!in_array('description',$varcate))//1.3的升级
{
	$DB->query("ALTER TABLE `".DB_PREFIX."category` ADD COLUMN `description` VARCHAR(100) NULL DEFAULT ''");
	echo '升级category字段description成功<br />';//1.3的升级
}

$vararticle=GetField('article');
if(!in_array('search',$vararticle))//1.4的升级
{
	$DB->query("ALTER TABLE `".DB_PREFIX."article` ADD COLUMN `search` VARCHAR(1500) NULL DEFAULT ''");
	echo '升级article字段search成功<br />';//1.3的升级
}

//1.3增加搜索表
$createsearch=<<<EOT
CREATE TABLE IF NOT EXISTS `rqcms_search` (
  `sid` int(10) NOT NULL AUTO_INCREMENT,
  `hostid` tinyint(6) DEFAULT '0',
  `keywords` varchar(50) DEFAULT '',
  `ip` varchar(15) DEFAULT '',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`sid`),
  KEY `hostid` (`hostid`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;
$createsearch=str_replace('rqcms_',DB_PREFIX,$createsearch);
$DB->query($createsearch);

exit('升级完成<body></html>');