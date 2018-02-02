<?php
/*
Plugin Name: 单页面
Version: 1.1.1801
Description: 该插件可以方便我们添加单个网页。
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
function page_add_item()
{
	global $pluginitem,$DB,$dbprefix;
	$pluginitem['单页管理']='page';
	
	//如果数据库不存在则创建添加数据库
	$table=$dbprefix.'page';
	$sql=<<<EOT
	 CREATE TABLE IF NOT EXISTS `{$table}` (
	`pid` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`userid` SMALLINT(5) UNSIGNED NOT NULL,
	`username` VARCHAR(50) NOT NULL,
	`title` VARCHAR(100) NOT NULL DEFAULT '',
	`keywords` VARCHAR(120) NOT NULL DEFAULT '',
	`excerpt` VARCHAR(255) NOT NULL,
	`content` LONGTEXT NOT NULL,
	`dateline` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`views` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`visible` TINYINT(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`pid`),
	INDEX `userid` (`userid`),
	INDEX `dateline` (`dateline`),
	INDEX `visible` (`visible`),
	INDEX `modified` (`modified`),
	INDEX `views` (`views`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
EOT;
	$DB->query($sql);
}
addAction('admin_plugin_add_item','page_add_item');

//找不到404页面时找谁，找单页面
function page_404_before_output()
{
	global $output,$setting,$DB,$contentType,$theme,$host,$host_url;
	if(RQ_FILE==$setting['plugin']['page'])
	{
		$url=$_GET['url1'];
		$article=$DB->fetch_first('select * from '.DB_PREFIX."page where url='{$url}'");
		if($article)
		{
			//如果启用了自动缓存，先判断是否超时的
			if(RQ_CACHE) cacheControl($article['modified']);
			//现在是对数据再做处理
			$title=$article['title'];
			$keywords=$article['keywords'];
			$description=str_replace("<p>","",$article['excerpt']);
			$description=str_replace("</p>","",$description);
			$pid=$article['pid'];
			$DB->query("UPDATE {$dbprefix}page SET views=views+1 WHERE pid=$pid");
			$tempView=RQ_DATA.'/themes/'.$theme.'/page.php';//风格模板文件
			include_once $tempView;
			//输出前处理,输出ContentType,网址重写，插件处理，网页压缩
			header($contentType);
			$output=ob_get_contents();
			ob_end_clean();
			doAction('before_output');
			ob_start();
			echo $output;
			ob_flush();//输出内容
			exit();
		}
	}
}

addAction('404_before_output','page_404_before_output');