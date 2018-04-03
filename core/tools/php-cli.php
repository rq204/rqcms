<?php
/**
  由php命令行直接执行,RQ_CORE和RQ_DATA需要实际的一致
 */
set_time_limit(0);
//核心参数
define('RQ_VERS','3.0.1801');
define('RQ_ROOT',dirname(dirname(dirname(__file__))));
define('RQ_CORE',RQ_ROOT.'/core');
define('RQ_DATA',RQ_ROOT.'/data');

if(PHP_VERSION<7.0) die('require php 7.0 or higher , now is '.PHP_VERSION);
if(empty($argv)) die("olny run in php cli mode\r\n'");
if(count($argv)==1) die("argv length error\r\n");

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

switch($argv[1])
{
	case 'setting_cache':
		setting_recache();
		echo "setting_recache ok\r\n";
		break;
	case 'category_recache':
		category_recache();
		echo "category_recache ok\r\n";
		break;
	case 'all_cache':
		setting_recache();
		echo "setting_recache ok\r\n";
		category_recache();
		echo "category_recache ok\r\n";
		break;
	case 'rebuild_tag':
		$DB->query("TRUNCATE `{$dbprefix}tag`");
		$DB->query("DROP TABLE IF EXISTS `{$dbprefix}temp`");
		$sql="CREATE TABLE `{$dbprefix}temp` (`tag` varchar(50) NOT NULL,`aids` text NOT NULL) ENGINE=innodb DEFAULT CHARSET=utf8;";
		$DB->query($sql);

		$list=array();
		$tagquery=$DB->query("Select aid,tag from {$dbprefix}article order by aid asc");

		while($data=$DB->fetch_array($tagquery))
		{
		if(!$data['tag']) continue;
		$tagarr=explode(',',$data['tag']);
		$newtag=array();
		foreach($tagarr as $tagname)
		{
			$tagname=trim($tagname);
			if($tagname&&strlen($tagname)>1)
			{			
				$list[$tagname][]=$data['aid'];
				$newtag[]=$tagname;
			}
		}
		}

		$insertsql="insert into `{$dbprefix}temp` (`tag`,`aids`) values ";
		foreach($list as $tagname=>$data)
		{
		$aids=implode(',',$data);
		$tagname=addslashes($tagname);
		$insertsql.= "('$tagname','$aids'),";
		}
		$insertsql=trim($insertsql,',');
		$DB->query($insertsql);

		$tagquery=$DB->query("select tag,GROUP_CONCAT(`aids`) as aids from {$dbprefix}temp group by tag");
		$tagsql="insert into {$dbprefix}tag (`tag`,`aids`) values ";
		while($data=$DB->fetch_array($tagquery))
		{
		$tagsql.="('{$data['tag']}','{$data['aids']}'),";
		}
		$tagsql=trim($tagsql,',');
		$DB->query($tagsql);
		$DB->query("DROP TABLE `{$dbprefix}temp`");
		echo "rebuild tag sucess\r\n";
		break;
	default:
		echo '$argv[1] not suppert :'.$argv[1];
		break;
}




	