CREATE TABLE IF NOT EXISTS `prefix_article` (
	`aid` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
	`cateid` SMALLINT(5) UNSIGNED NOT NULL COMMENT '分类id',
	`title` VARCHAR(100) NOT NULL COMMENT '标题',
	`tag` VARCHAR(100) NOT NULL COMMENT 'tag',
	`excerpt` VARCHAR(255) NOT NULL COMMENT '摘要',
	`dateline` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
	`modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
	`views` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '访问量',
	`comments` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论的个数',
	`userid` INT(11) NULL DEFAULT NULL,
	`username` VARCHAR(50) NULL DEFAULT NULL,
	`thumb` VARCHAR(200) NULL DEFAULT NULL,
	PRIMARY KEY (`aid`),
	INDEX `cateid` (`cateid`),
	INDEX `views` (`views`),
	INDEX `dateline` (`dateline`),
	INDEX `userid` (`userid`),
	INDEX `aid_cateid` (`cateid`, `aid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_category` (
 	`cid` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '栏目id',
	`name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '栏目名称',
	`url` CHAR(60) NOT NULL DEFAULT '' COMMENT '栏目友好网址',
	`description` VARCHAR(300) NOT NULL DEFAULT '' COMMENT '栏目描述',
	`visible` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '是否可见',
	`displayorder` SMALLINT(5) NOT NULL DEFAULT '0' COMMENT '显示次序',
	PRIMARY KEY (`cid`),
	UNIQUE INDEX `url` (`url`),
	INDEX `displayorder` (`displayorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `prefix_category` (`cid`,`name`, `description`, `displayorder`,`url`) VALUES (NULL,'默认栏目','', '0','hello');

CREATE TABLE IF NOT EXISTS `prefix_comment` (
  `cid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `articleid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `userid` smallint(5) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `dateline` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text NOT NULL,
  `ipaddress` varchar(16) NOT NULL DEFAULT '',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cid`),
  INDEX `articleid` (`articleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_link` (
  `lid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `displayorder` smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `note` varchar(200) NOT NULL DEFAULT '',
  `bak` varchar(200) NOT NULL DEFAULT '',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`lid`),
  INDEX `displayorder` (`displayorder`),
  INDEX `visible` (`visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `prefix_link` (`lid`, `displayorder`, `name`, `url`, `note`, `visible`) VALUES (NULL, '0', 'RQCMS', 'http://wwww.rqcms.com', 'RQCMS官方站点', '1');

CREATE TABLE IF NOT EXISTS `prefix_tag` (
  `tid` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(20) UNIQUE NOT NULL,
  `aids` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `prefix_tag` (`tag`,`aids`) values ('rqcms',',1,');

CREATE TABLE IF NOT EXISTS `prefix_user` (
	`uid` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(20) NOT NULL DEFAULT '',
	`password` VARCHAR(32) NOT NULL DEFAULT '',
	`groupid` TINYINT(4) NOT NULL DEFAULT '0',
	`email` VARCHAR(100) NOT NULL DEFAULT '',
	`regdate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`regip` VARCHAR(15) NOT NULL DEFAULT '',
	`logincount` MEDIUMINT(9) NOT NULL DEFAULT '0',
	`loginip` VARCHAR(15) NOT NULL DEFAULT '',
	`logintime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`useragent` VARCHAR(200) NOT NULL DEFAULT '',
	`lastpost` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`sessionid` VARCHAR(30) NULL DEFAULT NULL,
	`disabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`uid`),
	INDEX `sessionid` (`sessionid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_plugin` (
	`pid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`file` VARCHAR(50) NULL DEFAULT NULL,
	`name` VARCHAR(50) NOT NULL,
	`author` VARCHAR(50) NOT NULL,
	`version` VARCHAR(50) NOT NULL,
	`description` VARCHAR(255) NOT NULL,
	`url` VARCHAR(50) NULL,
	`active` TINYINT(1) NOT NULL,
	`config` TEXT NULL,
	PRIMARY KEY (`pid`),
	Index `file` (`file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_login` (
	`lid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`uid` INT(11) NOT NULL,
	`user` VARCHAR(12) NOT NULL,
	`dateline` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`useragent` VARCHAR(200) NOT NULL,
	`ip` VARCHAR(16) NOT NULL,
	`content` TEXT NOT NULL,
	PRIMARY KEY (`lid`),
	INDEX `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_search` (
	`sid` INT(12) unsigned NOT NULL AUTO_INCREMENT,
	`keywords` VARCHAR(50) NULL DEFAULT '',
	`ip` VARCHAR(15) NULL DEFAULT '',
	`dateline` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`sid`),
	Index `keywords` (`keywords`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_content` (
	`articleid` INT(12) unsigned NULL DEFAULT NULL,
	`content` MEDIUMTEXT NULL,
	UNIQUE INDEX `articleid` (`articleid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_option` (
	`name` VARCHAR(50) NULL DEFAULT NULL,
	`value` TEXT NULL,
	UNIQUE INDEX `name` (`name`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

Insert Into `prefix_option` (`name`,`value`) values ('admin','admin');
Insert Into `prefix_option` (`name`,`value`) values ('attachment','attachment');
Insert Into `prefix_option` (`name`,`value`) values ('category','category');
Insert Into `prefix_option` (`name`,`value`) values ('rss','rss');
Insert Into `prefix_option` (`name`,`value`) values ('search','search');
Insert Into `prefix_option` (`name`,`value`) values ('tag','tag');
Insert Into `prefix_option` (`name`,`value`) values ('article','article');
Insert Into `prefix_option` (`name`,`value`) values ('index','page');