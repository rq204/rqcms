<?php
/*
Plugin Name: 高校采集
Version: 1.0
Description: 该插件可以方便我们添加学校。
Author: RQ204
Author URL: http://www.rqcms.com
*/

/*插件可以处理的位置和方法
doAction('before_router');在没有加载处理文件之前的处理，可以用来处理url
doAction('before_output',$output); 在输出之前对输出的内容进行处理
doAction('404_before_output');对出现404结果后的情况进行再处理
doAction('article_not_find');在没有找到文章时的处理方法
doAction('article_before_view');在程序处理完数据后显示前的处理
doAction('attachment_before_download');在下载前的处理，可以做下载页显示多次广告的效果
doAction('captcha_create_myself'); 创建自己的验证码图形，处理后注意要exit
doAction('comment_post_check'); 对回复保存时的检查用
doAction('comment_data_view',$commentdb);对回复显示的数据进行处理
doAction('index_before_view');首页显示内容前的处理工作
doAction('rss_before_output',$rssdb);输出rss结果前的处理工作
doAction('category_before_view');列表页显示前的处理
doAction('profile_reg_check');注册用户前的检查
doAction('search_before_featch');搜索页搜索前检查
doAction('search_before_view');搜索结果显示前的处理
doAction('tag_before_view');显示tag前的处理
doAction('js_before_view');输出js前的处理
doAction('admin_plugin_add_item');添加插件处理菜单，要处理数组$pluginitem
doAction('admin_plugin_setting_save');插件配置保存设置
doAction('admin_plugin_setting_view');插件设置界面
*/

!defined('RQ_DATA') && exit('access deined!');

//添加一个菜单在插件菜单中
function gaoxiao_add_item()
{
	global $pluginitem,$DB;
	$pluginitem['高校采集']='gaoxiao';
	
	//如果数据库不存在则创建添加数据库
	$table=DB_PREFIX.'gaoxiao';
	$sql=<<<EOT
	 CREATE TABLE IF NOT EXISTS `{$table}` (
	`gid` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`hostid` TINYINT(3) NOT NULL,
	`title` VARCHAR(100) NOT NULL DEFAULT '',
	`listurl` VARCHAR(150) NOT NULL DEFAULT '',
	`listurl2` VARCHAR(150) NOT NULL,
	`abouturl` VARCHAR(150) NOT NULL,
	`bak` VARCHAR(1500) NOT NULL,
	`dateline` INT(10) NOT NULL DEFAULT '0',
	`modified` INT(10) NOT NULL,
	PRIMARY KEY (`gid`),
	INDEX `hostid` (`hostid`),
	INDEX `modified` (`modified`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
EOT;
	$DB->query($sql);
}
addAction('admin_plugin_add_item','gaoxiao_add_item');