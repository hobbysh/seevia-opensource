-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014-10-27 09:12:25
-- 服务器版本: 5.5.40-0ubuntu0.14.04.1
-- PHP 版本: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `o2o`
--

-- --------------------------------------------------------
--
-- 表的结构 `svcms_advertisements`
--

CREATE TABLE IF NOT EXISTS `svcms_advertisements` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `advertisement_position_id` int(11) NOT NULL DEFAULT '0' COMMENT '0站外广告 从1开始代表的是该广告所处的广告位 同表svcart_advertisement_positions 中的字段id的值',
  `code` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告位置标识符',
  `media_type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '\r\n\r\n广告类型，0，图片；1，flash;2,代码；3，文字',
  `contact_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告联系人',
  `contact_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告联系人的邮箱',
  `contact_tele` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告联系人的电话',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `click_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `advertisement_position_id` (`advertisement_position_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_advertisement_effects`
--

CREATE TABLE IF NOT EXISTS `svcms_advertisement_effects` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `advertisements_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级id',
  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言',
  `type` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `configs` text COLLATE utf8_unicode_ci NOT NULL COMMENT '配置',
  `images` varchar(800) COLLATE utf8_unicode_ci NOT NULL COMMENT '图片',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '创建',
  `modified` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '修改',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_advertisement_effects_defaults`
--

CREATE TABLE IF NOT EXISTS `svcms_advertisement_effects_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `locale` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言',
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '特效名称',
  `show_link` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '展示链\r\n\r\n接',
  `configs` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '配置',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '2008-01-01 00:00:00 	' COMMENT '创建',
  `modified` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '2008-01-01 00:00:00 	' COMMENT '修改',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_advertisement_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_advertisement_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `advertisement_id` int(11) NOT NULL DEFAULT '0' COMMENT '广告编号',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '该条广告记录的广告名称',
  `description` text COLLATE utf8_unicode_ci COMMENT '广告描述',
  `url` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告链接地址',
  `url_type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '链接类型：0直接连接，1间接链接',
  `start_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '广告开始时间',
  `end_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '广告结束时间',
  `code` text COLLATE utf8_unicode_ci COMMENT '广告链接的表现，文字广告就是文字或图片和flash就是它们的地址，代码广告就是代码内容',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`advertisement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_advertisement_positions`
--

CREATE TABLE IF NOT EXISTS `svcms_advertisement_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '广告位编号',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告位名称',
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告位置',
  `template_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '对应哪个模板name',
  `ad_width` smallint(5) NOT NULL DEFAULT '0' COMMENT '广告位宽度',
  `ad_height` smallint(5) NOT NULL DEFAULT '0' COMMENT '广告位高度',
  `position_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告位描述',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------




--
-- 表的结构 `svedi_sms_send_histories`
--

CREATE TABLE IF NOT EXISTS `svedi_sms_send_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号码',
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '短信内容',
  `send_date` datetime NOT NULL COMMENT '发送时间',
  `flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0;未发送',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_sms_send_queues`
--

CREATE TABLE IF NOT EXISTS `svedi_sms_send_queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号码',
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '短信内容',
  `send_date` datetime NOT NULL COMMENT '发送时间',
  `flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0;未发送',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_sms_words`
--

CREATE TABLE IF NOT EXISTS `svedi_sms_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `word` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '敏感字',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


--
-- 表的结构 `svcms_articles`
--

CREATE TABLE IF NOT EXISTS `svcms_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商店编号',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '主分类ID',
  `upload_file_id` int(11) DEFAULT NULL COMMENT '关联上传文件id',
  `upload_video` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传视频',
  `author_email` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章作者的email',
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '文章类型',
  `file_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '外部链接',
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传的文件',
  `video` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章视频',
  `video_competence` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '对应user_rank表id',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;2:删除',
  `front` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '首页显示 1:显示 0:不显示',
  `importance` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '文章重要性[0:普通;1:置顶;2:滚动显示;3:置顶且滚动显示]',
  `recommand` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否推荐[0:否,1:是]',
  `comment` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否可\r\n\r\n评论[0:否,1:是]',
  `displayed_title` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '文章标题是否显示',
  `displayed_add_time` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '添加文章日期是否显示',
  `clicked` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文章被点击数',
  `template` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default' COMMENT '选择模板',
  `layout` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default' COMMENT '布局',
  `operator_id` int(11) NOT NULL COMMENT '操作员id',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  `showtime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `type` (`type`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_article_categories`
--

CREATE TABLE IF NOT EXISTS `svcms_article_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类编号',
  `article_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章编号',
  `orderby` smallint(4) NOT NULL DEFAULT '500' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_article_galleries`
--

CREATE TABLE IF NOT EXISTS `svcms_article_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章编号',
  `article_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章编号',
  `img_original` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '原始图',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_article_gallery_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_article_gallery_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章相册多语言编号',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `article_gallery_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章相册编号',
  `description` text COLLATE utf8_unicode_ci COMMENT '文章相册描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`article_gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_article_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_article_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `article_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章编号 取文章article主表自增ID',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文章题目',
  `subtitle` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '副标题',
  `meta_keywords` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '文章的关键字',
  `meta_description` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '文章描述',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '文章内容',
  `content2` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '手机的文章内容',
  `author` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文章作者',
  `img01` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片1',
  `img02` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`article_id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `content` (`content`),
  FULLTEXT KEY `author` (`author`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_category_articles`
--

CREATE TABLE IF NOT EXISTS `svcms_category_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类编号',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类编号(0是根目录)',
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'P' COMMENT '分类类型[A:文章,P:商品]',
  `sub_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'G' COMMENT '系统参数',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;2:删除',
  `code` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '参数名称',
  `template` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模\r\n\r\n版',
  `layout` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '框架',
  `new_show` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '1显\r\n\r\n示0隐藏',
  `home_show` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `home_cat_orderby` tinyint(4) DEFAULT NULL COMMENT '首页分类排序',
  `home_show_num` int(11) DEFAULT NULL COMMENT '首页显示数量',
  `home_show_order` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new_arrival' COMMENT '首页分类商品排序方式',
  `link` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '超级链接',
  `img01` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片1',
  `img01_link` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '分类图超链接01',
  `img02` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片2',
  `img02_link` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '分类图超链接02',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`,`status`),
  KEY `parent_id_2` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章分类' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_category_article_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_category_article_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类多语言编号',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类编号',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `home_show_keywords` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '首页显示过滤关键字',
  `meta_keywords` tinytext COLLATE utf8_unicode_ci COMMENT 'SEO分类关键字',
  `meta_description` tinytext COLLATE utf8_unicode_ci COMMENT 'SEO分类描述',
  `detail` text COLLATE utf8_unicode_ci COMMENT '分类详细',
  `top_detail` text COLLATE utf8_unicode_ci NOT NULL COMMENT '顶部描述',
  `foot_detail` text COLLATE utf8_unicode_ci NOT NULL COMMENT '底部描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章分类多语言表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_contacts`
--

CREATE TABLE IF NOT EXISTS `svcms_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '公司名',
  `company_url` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '域名',
  `company_type` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '行业',
  `contact_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系人',
  `contact_type` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '联系的类型 0：电话 1：email',
  `is_send` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '需要我们结您邮寄样本',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '地址',
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Email地址',
  `mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'qq',
  `msn` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'msn',
  `skype` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'skype',
  `from` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '您是如何获知我们的',
  `content` text COLLATE utf8_unicode_ci COMMENT '内容',
  `ip_address` varchar(15) COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'ip地址',
  `browser` varchar(100) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '用户使用浏览器',
  `locale` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '语言',
  `resolution` varchar(100) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '分辨率',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='留言' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_documents`
--

CREATE TABLE IF NOT EXISTS `svcms_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文件编号',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名称',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件类型',
  `file_size` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件大小',
  `file_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件路径',
  `file_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件物理路径',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文件' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_flashes`
--

CREATE TABLE IF NOT EXISTS `svcms_flashes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `page` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '页面[H:首页;PC:分类;B:品牌;AC:文章分类]',
  `page_id` int(11) NOT NULL DEFAULT '0' COMMENT 'image类型ID',
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型0:普通1:手机',
  `roundcorner` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '9' COMMENT '圆角',
  `autoplaytime` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '8' COMMENT '自动播放时间',
  `isheightquality` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false' COMMENT '高品质',
  `blendmode` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '混合模式',
  `transduration` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '跨期',
  `windowopen` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '_self' COMMENT '窗口打开',
  `btnsetmargin` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'auto 5 5 auto' COMMENT 'btnsetmargin',
  `btndistance` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '20' COMMENT '距离',
  `titlebgcolor` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0xff6600' COMMENT '标题颜色',
  `titletextcolor` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0xffffff' COMMENT '标题文本颜色',
  `titlebgalpha` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '.75' COMMENT '标题透明度',
  `titlemoveduration` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '标题移动时间',
  `btnalpha` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '.7' COMMENT '按钮透明度',
  `btntextcolor` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0xffffff' COMMENT '按钮文本颜色',
  `btndefaultcolor` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0x1B3433' COMMENT '按钮默认颜色',
  `btnhovercolor` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#41b73d' COMMENT '按钮悬停颜色',
  `btnfocuscolor` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#41b73d' COMMENT '按钮重点颜色',
  `changimagemode` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'click' COMMENT '图像模式',
  `isshowbtn` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'true' COMMENT '显示按钮',
  `isshowtitle` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'true' COMMENT '显示标题',
  `scalemode` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'noBorder' COMMENT '缩放模式',
  `transform` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'blur' COMMENT '变换',
  `isshowabout` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'true' COMMENT '是否显示关于',
  `titlefont` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '微软雅黑' COMMENT '标题字体',
  `height` int(11) NOT NULL DEFAULT '314' COMMENT '长',
  `width` int(11) NOT NULL DEFAULT '741' COMMENT '宽',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_2` (`page`,`page_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='轮播' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_flash_images`
--

CREATE TABLE IF NOT EXISTS `svcms_flash_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图片编号',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言代码',
  `flash_id` int(10) NOT NULL DEFAULT '0' COMMENT 'svcart_flashes表ＩＤ',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `image` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题文字',
  `description` text COLLATE utf8_unicode_ci COMMENT '描述',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '链接地址',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `locale` (`locale`),
  KEY `flash_id` (`flash_id`),
  KEY `locale_2` (`locale`,`flash_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='轮播图片' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_jobs`
--

CREATE TABLE IF NOT EXISTS `svcms_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '职位id',
  `type_id` int(11) NOT NULL COMMENT '类型',
  `education_id` int(11) DEFAULT NULL COMMENT '学历id',
  `experience_id` int(11) DEFAULT NULL COMMENT '工作年限ID',
  `department_id` int(11) NOT NULL COMMENT '部门ID',
  `number` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '招聘人数',
  `orderby` tinyint(4) DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '撞人',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `id` (`type_id`,`education_id`,`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='职位信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_job_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_job_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `job_id` int(11) NOT NULL COMMENT 'job id',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '语言',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '职位名称',
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '工作地点',
  `detail` text COLLATE utf8_unicode_ci NOT NULL COMMENT '职位描述',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='职位信息多语言表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_links`
--

CREATE TABLE IF NOT EXISTS `svcms_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '友情链接编号',
  `type` smallint(1) NOT NULL DEFAULT '1' COMMENT '1.友情链接 2.赞助商 3.合作伙伴',
  `contact_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系人',
  `contact_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Email地址',
  `contact_tele` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `target` enum('_self','_blank') COLLATE utf8_unicode_ci NOT NULL DEFAULT '_self' COMMENT '打开位置',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='网址连接' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_link_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_link_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '友情链接编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `link_id` int(11) NOT NULL DEFAULT '0' COMMENT '友情链接编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '友情链接名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '友情链接描述',
  `url` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '友情链接地址',
  `click_stat` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `img01` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `img02` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`link_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_mobile_operator_tokens`
--

CREATE TABLE IF NOT EXISTS `svcms_mobile_operator_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `operator_id` int(11) NOT NULL COMMENT '操作员ID',
  `token` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Token码',
  `device` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '设备',
  `geolocation` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '坐标',
  `connection` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上网方式',
  `app_version` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '应用版本',
  `login_time` datetime NOT NULL COMMENT '登陆时间',
  `remote_ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'IP地址',
  `last_visit_time` datetime NOT NULL COMMENT '上次访问时间',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_navigations`
--

CREATE TABLE IF NOT EXISTS `svcms_navigations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '导航编号',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级导航',
  `type` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '导航类型[H;T;M;B...]',
  `orderby` tinyint(4) NOT NULL DEFAULT '10' COMMENT '排序',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `icon` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `target` enum('_self','_blank') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '_self' COMMENT '打开位置',
  `controller` enum('pages','categories','brands','products','articles','cars','static_pages') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pages' COMMENT '系统内容',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_navigation_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_navigation_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '导航多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `navigation_id` mediumint(9) NOT NULL DEFAULT '0' COMMENT '导航编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '导航栏名称',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'URL链接',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '描述',
  `img01` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片1',
  `img02` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`navigation_id`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_newsletter_lists`
--

CREATE TABLE IF NOT EXISTS `svcms_newsletter_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '邮件订阅编号',
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '邮件地址',
  `mobile` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '会员手机号',
  `group_id` int(11) DEFAULT NULL COMMENT '分组id',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='订阅用户' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_pages`
--

CREATE TABLE IF NOT EXISTS `svcms_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;2:删除',
  `operator_id` int(11) NOT NULL COMMENT '操作员id',
  `showtime` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_page_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_page_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '页面多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `page_id` int(11) NOT NULL DEFAULT '0' COMMENT '页面编号 取页面page主表自增ID',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '页面标题',
  `subtitle` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '副标题',
  `meta_keywords` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '页面的关键字',
  `meta_description` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '页面描述',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '页面内容',
  `img01` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片1',
  `img02` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`page_id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_photo_categories`
--

CREATE TABLE IF NOT EXISTS `svcms_photo_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '相册分类编号',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `custom` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：系统尺寸，1：自定义尺寸',
  `cat_small_img_height` int(11) NOT NULL DEFAULT '140' COMMENT '分类下小图默认高度',
  `cat_small_img_width` int(11) NOT NULL DEFAULT '140' COMMENT '分类下小图默认宽度',
  `cat_mid_img_height` int(11) NOT NULL DEFAULT '400' COMMENT '分类下中图默认高度',
  `cat_mid_img_width` int(11) NOT NULL DEFAULT '400' COMMENT '分类下中图默认宽度',
  `cat_big_img_height` int(11) NOT NULL DEFAULT '800' COMMENT '分类下大图默认高度',
  `cat_big_img_width` int(11) NOT NULL DEFAULT '800' COMMENT '分类下大图默认宽度',
  `img01` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '图片1',
  `img02` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '图片2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_photo_category_galleries`
--

CREATE TABLE IF NOT EXISTS `svcms_photo_category_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图片编号',
  `photo_category_id` int(11) NOT NULL DEFAULT '0' COMMENT '相册分类编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片名称',
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '图片类型',
  `original_size` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '原图大小',
  `original_pixel` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '原图尺寸',
  `img_small` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '小图',
  `img_detail` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '相册中图',
  `img_big` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '相册大图',
  `img_original` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '原始图',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `photo_category_id` (`photo_category_id`),
  KEY `name` (`name`),
  KEY `created` (`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_photo_category_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_photo_category_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '相册分类多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `photo_category_id` int(11) NOT NULL DEFAULT '0' COMMENT '相册分类编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '相册分类名称',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`photo_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_resumes`
--

CREATE TABLE IF NOT EXISTS `svcms_resumes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '简历ID',
  `job_id` int(11) NOT NULL COMMENT 'job表id',
  `experience_id` int(11) NOT NULL COMMENT '工作年限ID',
  `current_salary` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '目前薪资',
  `certificate_id` int(11) NOT NULL COMMENT '证件类型',
  `certificate_num` varchar(45) COLLATE utf8_unicode_ci NOT NULL COMMENT '证件编号',
  `apartments` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '居住地点',
  `registers` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '户籍所在地',
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '姓名',
  `birthday` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '生日',
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '头像',
  `sex` tinyint(1) NOT NULL COMMENT '0 男 1 女',
  `email` varchar(45) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮件地址',
  `mobile` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机号',
  `telephone` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '电话号码',
  `introduce` text COLLATE utf8_unicode_ci COMMENT '自我介绍',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='简历信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_resume_educations`
--

CREATE TABLE IF NOT EXISTS `svcms_resume_educations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '教育经历ID',
  `resume_id` int(11) NOT NULL COMMENT '简历ID',
  `start_time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '开始时间',
  `end_time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '结束时间',
  `school_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '学校名称',
  `major_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '专业类型',
  `education_id` int(11) NOT NULL COMMENT '学历',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT '专业描述',
  `abroad` tinyint(1) NOT NULL COMMENT '是否海外求学 0 不是 1是',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`resume_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='简历教育经历信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_resume_experiences`
--

CREATE TABLE IF NOT EXISTS `svcms_resume_experiences` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '工作经历ID',
  `resume_id` int(11) NOT NULL COMMENT '简历ID',
  `start_time` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '开始时间',
  `end_time` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '结束时间',
  `company_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '公司名称',
  `company_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '公司类型',
  `department` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '部门',
  `position` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '职位',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT '工作描述',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`resume_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='简历工作经历信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_resume_languages`
--

CREATE TABLE IF NOT EXISTS `svcms_resume_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '语言类型ID',
  `resume_id` int(11) NOT NULL COMMENT '简历ID',
  `language_id` int(11) NOT NULL COMMENT '语言ID',
  `master_id` int(11) NOT NULL COMMENT '掌握能力',
  `rw_id` int(11) NOT NULL COMMENT '读写能力',
  `hs_id` int(11) NOT NULL COMMENT '听说能力',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `resume_id` (`resume_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='简历语言相关表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_seo_keywords`
--

CREATE TABLE IF NOT EXISTS `svcms_seo_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '关键字名称',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `lasthittime` datetime DEFAULT NULL COMMENT '最后访问',
  `usetimes` int(11) NOT NULL DEFAULT '0' COMMENT '引用次数',
  `lastusetime` datetime DEFAULT NULL COMMENT '最后引用',
  `orderby` int(11) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1有效0无效',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_tags`
--

CREATE TABLE IF NOT EXISTS `svcms_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `type_id` int(11) NOT NULL COMMENT '相关id',
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '类型 P：商品  A：文章',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否有效 0:无效 1:有效',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='标签' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_tag_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_tag_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `tag_id` int(11) NOT NULL COMMENT '标签id',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '标签名',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='标签语言' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_templates`
--

CREATE TABLE IF NOT EXISTS `svcms_templates` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '模板名',
  `description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模版的名称',
  `template_style` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '模版的颜色样式',
  `template_img` varchar(900) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'http://www.seevia.cn/' COMMENT '作者地址',
  `show_css` text COLLATE utf8_unicode_ci COMMENT '模板样式',
  `mobile_css` text COLLATE utf8_unicode_ci COMMENT '手机模板样式',
  `mobile_status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否启用手机版',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否有效',
  `is_default` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否默认',
  `author` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '版本',
  `style` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '模板样式',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_topics`
--

CREATE TABLE IF NOT EXISTS `svcms_topics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `start_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '促销开始时间',
  `end_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '促销结束时间',
  `template` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '模板',
  `css` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '主题样式',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否有效 \r\n\r\n1有效0无效',
  `front` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否前台显\r\n\r\n示 1.显示0.不显示',
  `front_num` int(11) DEFAULT NULL COMMENT '首页显示数量',
  `orderby` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '排序方式',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_topic_articles`
--

CREATE TABLE IF NOT EXISTS `svcms_topic_articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `article_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章编号',
  `topic_id` int(11) NOT NULL DEFAULT '0' COMMENT '专题编号',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_topic_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_topic_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `topic_id` int(11) NOT NULL DEFAULT '0' COMMENT '专题编号',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '专题名称',
  `img01` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `img02` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机图片',
  `intro` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '专题介绍',
  `mobile_intro` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '手机\r\n\r\n专题介绍',
  `meta_keywords` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT 'SEO分类关键字',
  `meta_description` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT 'SEO分类描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`topic_id`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_topic_products`
--

CREATE TABLE IF NOT EXISTS `svcms_topic_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商店编号',
  `topic_id` int(11) NOT NULL DEFAULT '0' COMMENT '促销编号',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品编号',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;2:删除',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `store_id_3` (`store_id`,`topic_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_upload_files`
--

CREATE TABLE IF NOT EXISTS `svcms_upload_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文件编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名称',
  `type` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文件类型',
  `file_size` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文件大小',
  `file_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文件路径',
  `file_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文件物理路径',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `created` (`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_votes`
--

CREATE TABLE IF NOT EXISTS `svcms_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '在线调查自增id',
  `start_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '在线调查开始时间',
  `end_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '在线调查结束时间',
  `can_multi` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '能否多选，0，可以；1，不可以',
  `vote_count` int(11) NOT NULL DEFAULT '0' COMMENT '投票人数',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1:有效,0:无效',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='在线调查' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_vote_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_vote_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '语言编码',
  `vote_id` int(11) NOT NULL DEFAULT '0' COMMENT '投票主题ID',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '在线调查主题',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '主题描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`vote_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='在线调查多语言表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_vote_logs`
--

CREATE TABLE IF NOT EXISTS `svcms_vote_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '投票记录自增id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID,0:匿名用户',
  `vote_id` int(11) NOT NULL DEFAULT '0' COMMENT '投票主题id',
  `ip_address` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '投票的ip地址',
  `system` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户所用的操作系统',
  `browser` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户所用的浏览器',
  `resolution` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户所用的分辨率',
  `vote_option_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '多选时逗号分割',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '前台是否显示1:显示,0:不显示',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `vote_id` (`vote_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='投票记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_vote_options`
--

CREATE TABLE IF NOT EXISTS `svcms_vote_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '投票选项自增id',
  `vote_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联的投票主题id，取值表svcms_votes',
  `option_count` int(8) NOT NULL DEFAULT '0' COMMENT '该选项的票数',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1:有效,0:无效',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `vote_id` (`vote_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='投票的选项表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_vote_option_i18ns`
--

CREATE TABLE IF NOT EXISTS `svcms_vote_option_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '语言编码',
  `vote_option_id` int(11) NOT NULL DEFAULT '0' COMMENT '选项表ID',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '投票选项的名字',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '选项描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`vote_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='在线调查选项多语言表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_weibo_teams`
--

CREATE TABLE IF NOT EXISTS `svedi_weibo_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_affiliate_logs`
--

CREATE TABLE IF NOT EXISTS `svoms_affiliate_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `user_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现金分成',
  `point` int(10) NOT NULL COMMENT '积分分成',
  `separate_type` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '分成类型 0：注册分成，1：订单分成',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='推荐分成日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_brands`
--

CREATE TABLE IF NOT EXISTS `svoms_brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '品牌编号',
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '品牌code',
  `category_type_id` int(11) NOT NULL DEFAULT '0' COMMENT '类目id',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `img01` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `img02` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片2',
  `flash_config` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'flash参数',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌网址',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='品牌' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_brand_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_brand_i18ns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `brand_id` int(11) NOT NULL DEFAULT '0' COMMENT '品牌编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '品牌描述',
  `meta_keywords` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT 'SEO品牌关键字',
  `meta_description` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT 'SEO品牌描述',
  `img01` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片1',
  `img02` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片2',
  `img03` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片3',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`brand_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_category_filters`
--

CREATE TABLE IF NOT EXISTS `svoms_category_filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类筛选',
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  `product_attribute` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品属性',
  `filter_price` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品价格区间',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='分类过滤' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_category_products`
--

CREATE TABLE IF NOT EXISTS `svoms_category_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类编号',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类编号(0是根目录)',
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'P' COMMENT '分类类型[A:文章,P:商品]',
  `sub_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'G' COMMENT '系统参数',
  `show_info` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '前台可显示内容',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;2:删除',
  `code` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '参数名称',
  `template` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模\r\n\r\n版',
  `layout` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '框架',
  `new_show` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '1显\r\n\r\n示0隐藏',
  `home_show` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `home_cat_orderby` tinyint(4) DEFAULT NULL COMMENT '首页分类排序',
  `home_show_num` int(11) DEFAULT NULL COMMENT '首页显示数量',
  `home_show_order` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new_arrival' COMMENT '首页分类商品排序方式',
  `link` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '超级链接',
  `img01` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片1',
  `img01_link` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '分类图超链接01',
  `img02` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片2',
  `img02_link` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '分类图超链接02',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`,`status`),
  KEY `parent_id_2` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='产品分类' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_category_product_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_category_product_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类多语言编号',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类编号',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `home_show_keywords` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '首页显示过滤关键字',
  `meta_keywords` tinytext COLLATE utf8_unicode_ci COMMENT 'SEO分类关键字',
  `meta_description` tinytext COLLATE utf8_unicode_ci COMMENT 'SEO分类描述',
  `detail` text COLLATE utf8_unicode_ci COMMENT '分类详细',
  `top_detail` text COLLATE utf8_unicode_ci NOT NULL COMMENT '顶部描述',
  `foot_detail` text COLLATE utf8_unicode_ci NOT NULL COMMENT '底部描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='产品分类多语言表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_category_types`
--

CREATE TABLE IF NOT EXISTS `svoms_category_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '类目ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `code` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '类目code',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '类目排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '类目状态',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='分类类型' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_category_type_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_category_type_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '类目多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `category_type_id` int(11) NOT NULL DEFAULT '0' COMMENT '类目编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '类目名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '类目描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`category_type_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_category_type_relations`
--

CREATE TABLE IF NOT EXISTS `svoms_category_type_relations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `category_type_id` int(11) NOT NULL DEFAULT '0' COMMENT '类目编号',
  `related_brand_id` int(11) NOT NULL DEFAULT '0' COMMENT '品牌编号',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `is_double` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否是双向关联',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `related_brand_id` (`related_brand_id`),
  KEY `category_type_id` (`category_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='分类类型关联' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_comments`
--

CREATE TABLE IF NOT EXISTS `svoms_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论编号',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '评论类型[商品，分类，品牌，文章，商店]',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '类型编号',
  `email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '评论时提交的email地址，默认取的users的email',
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `parent_id` mediumint(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复的评论ID',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '评论状态[0-不显示，1-显示，2-删除]',
  `is_public` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:公开1:匿名',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '内容',
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片',
  `rank` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '评论等级',
  `ipaddr` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip地址',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户编号',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_currencies`
--

CREATE TABLE IF NOT EXISTS `svoms_currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '货币代码',
  `rate` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '比率',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:无效1：有效',
  `is_default` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否默认',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='货币管理' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_currency_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_currency_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `currency_id` int(11) NOT NULL COMMENT '货币ID',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `format` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '货币格式',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '0：无效 1：有效',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `currency_id` (`currency_id`,`locale`),
  KEY `locale` (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='多货币多语言表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_package_products`
--

CREATE TABLE IF NOT EXISTS `svoms_package_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `package_product_id` int(11) NOT NULL COMMENT '套装子商品id',
  `package_product_code` varchar(45) COLLATE utf8_unicode_ci NOT NULL COMMENT '套装商品code',
  `product_id` int(11) NOT NULL COMMENT '套装id',
  `product_code` varchar(45) COLLATE utf8_unicode_ci NOT NULL COMMENT '套装code',
  `package_product_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '套装子商品显示名称',
  `package_product_qty` int(11) NOT NULL DEFAULT '1' COMMENT '套装子商品数量',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='套装商品表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_packagings`
--

CREATE TABLE IF NOT EXISTS `svoms_packagings` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '包装编号',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `img01` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '包装图纸',
  `img02` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '包装图纸2',
  `fee` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '包装的费用',
  `free_money` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单达到此金额可以免除该包装费用',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='包装' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_packaging_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_packaging_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '包装多语言编号自增id',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `packaging_id` int(11) NOT NULL DEFAULT '0' COMMENT '包装编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '包装名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '包装描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`packaging_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_payments`
--

CREATE TABLE IF NOT EXISTS `svoms_payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '已安装的支付方式自增id',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商店编号',
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '支付方式的英文缩写，其实就是该支付方式处理插件的不带后缀的文件名部分',
  `fee` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '支付费用',
  `orderby` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式在页面的显示顺序',
  `config` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '支付方式的配置信息，包括商户号和密钥什么的',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否可用，0，否；1，是',
  `is_cod` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否货到付款，0，否；1，是',
  `is_getinshop` int(11) NOT NULL DEFAULT '0' COMMENT '是否门店取货',
  `is_online` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否在线支付，0，否；1，是',
  `supply_use_flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '充值可用标志',
  `order_use_flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '订单可用标志',
  `php_code` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '接口代码',
  `version` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '插件版本',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `store_id` (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_payment_api_logs`
--

CREATE TABLE IF NOT EXISTS `svoms_payment_api_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '支付记录自增id',
  `payment_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '支付代码',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '支付类型(购买/充值)',
  `type_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单编号',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `is_paid` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否已支付，0，否；1，是',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `payment_code` (`payment_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_payment_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_payment_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '支付方式多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `payment_id` int(11) NOT NULL DEFAULT '0' COMMENT '支付编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '支付方式名称',
  `payment_values` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '值',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '支付方式描述',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效 1:有效]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时\r\n\r\n间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时\r\n\r\n间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`payment_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_payment_logs`
--

CREATE TABLE IF NOT EXISTS `svoms_payment_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '订单号',
  `amount` decimal(10,2) NOT NULL COMMENT '费用',
  `operator_id` int(11) NOT NULL COMMENT '操作的管理员',
  `system_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '系统注释',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '日志类型0：付款，1：还款，默认0',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1有效0无效 默认1  付款取消用 ',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='付款日志表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_products`
--

CREATE TABLE IF NOT EXISTS `svoms_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品编号',
  `coupon_type_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券关联',
  `brand_id` int(11) NOT NULL DEFAULT '0' COMMENT '品牌编号',
  `product_public_template_id` int(11) NOT NULL DEFAULT '0' COMMENT '公共模板',
  `provider_id` int(11) NOT NULL DEFAULT '0' COMMENT '供应商编号',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类ID',
  `category_type_id` int(11) DEFAULT NULL COMMENT '类目ID',
  `option_type_id` int(11) NOT NULL COMMENT '商品类型：0.普通;1.套装;2.销售属性',
  `code` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '商品编号',
  `style_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '款号',
  `is_colors_gallery` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否显示相同款号下的颜色图片',
  `product_name_style` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '+' COMMENT '商品名称样式',
  `img_thumb` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `img_detail` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '详细图',
  `img_big` varchar(255) NOT NULL DEFAULT '' COMMENT '大图',
  `img_original` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '原图',
  `colors_gallery` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '款号颜色图',
  `recommand_flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '推荐标志位',
  `min_buy` int(11) NOT NULL DEFAULT '1' COMMENT '最小购买数',
  `max_buy` int(11) NOT NULL DEFAULT '100' COMMENT '最大购买数',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '添加管理员',
  `alone` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效 1:有效]',
  `forsale` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效 1:有效]',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效 1:有效 2:进回收站]',
  `bestbefore` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '过\r\n\r\n往精品(0:不是，1：是)',
  `weight` decimal(10,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '商品重量',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本店价',
  `custom_price` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '自定义价格',
  `promotion_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价',
  `purchase_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '进货价',
  `promotion_start` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '促销开始时间',
  `promotion_end` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '促销结束时间',
  `promotion_status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '促销标志[0:无效;1:有效;2:自动]',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '赠送积分数',
  `point_fee` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '积分购买额度',
  `view_stat` int(11) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `sale_stat` int(11) NOT NULL DEFAULT '0' COMMENT '销售次数',
  `like_stat` int(11) NOT NULL COMMENT '喜欢的人数',
  `product_type_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品类型',
  `product_rank_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品会员价',
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `warn_quantity` int(11) NOT NULL DEFAULT '0' COMMENT '商品报警数量',
  `warn_style` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:系统默认1:自己设定',
  `frozen_quantity` int(11) NOT NULL DEFAULT '0' COMMENT '冻结库存',
  `extension_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'virtual_card：虚拟商品',
  `operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '最后编辑人ID',
  `operator_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '最后编辑人名称',
  `last_update_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '最后修改时间',
  `online_time` datetime NOT NULL COMMENT '最后上架时间',
  `file_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '上传的图片',
  `video` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商品视频',
  `wholesale` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `freeshopping` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `modify_status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:未修改 1：已修改',
  `weithm` int(11) NOT NULL DEFAULT '0' COMMENT 'weibo_thm_id',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_2` (`code`),
  KEY `brand_id` (`brand_id`),
  KEY `provider_id` (`provider_id`),
  KEY `recommand_flag` (`recommand_flag`),
  KEY `status` (`status`),
  KEY `forsale` (`forsale`),
  KEY `category_id` (`category_id`),
  FULLTEXT KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_products_categories`
--

CREATE TABLE IF NOT EXISTS `svoms_products_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长编号',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '类型编号',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品编号',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`category_id`,`product_id`),
  KEY `category_id` (`category_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_alsoboughts`
--

CREATE TABLE IF NOT EXISTS `svoms_product_alsoboughts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `alsobought_product_id` int(11) NOT NULL DEFAULT '0' COMMENT '其他购买的商品ID',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户购买商品关联表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_articles`
--

CREATE TABLE IF NOT EXISTS `svoms_product_articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `article_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章编号',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品编号',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `is_double` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否是双向关联',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_attributes`
--

CREATE TABLE IF NOT EXISTS `svoms_product_attributes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `locale` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `orderby` tinyint(4) DEFAULT NULL COMMENT '排序',
  `product_type_attribute_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `product_type_attribute_value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '属性值',
  `product_type_attribute_price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '属性价格',
  `product_type_attribute_image_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性图片',
  `product_type_attribute_back_image_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性背面图片',
  `product_type_attribute_related_image_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性自定义图片',
  `product_type_attribute_related_back_image_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性自定义背面图片',
  `product_type_attribute_color_css` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性颜色css',
  `product_type_attribute_shell_num` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性外壳拼图个数',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`product_id`),
  KEY `attr_id` (`product_type_attribute_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_bookings`
--

CREATE TABLE IF NOT EXISTS `svoms_product_bookings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增Ｉｄ',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登记该缺货记录的用户的id存svoms_users用户表自增ID',
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '页面填的用户的email，默认取值于svoms_users的email',
  `contact_man` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '页面填的用户的姓名，默认取值于svoms_users的name',
  `telephone` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '页面填的用户的电话，默认取值于svoms_users的tel',
  `product_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '登记缺货的商品ID',
  `product_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '缺货登记时留的订购描述',
  `product_number` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '订购数量',
  `booking_time` datetime DEFAULT NULL COMMENT '缺货登记的时间',
  `is_dispose` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否已经被处理',
  `dispose_operation_id` int(11) NOT NULL DEFAULT '0' COMMENT '处理操作员编号',
  `dispose_time` datetime DEFAULT NULL COMMENT '处理时间',
  `dispose_note` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '处理时管理员留的备注',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='产品订购' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_downloads`
--

CREATE TABLE IF NOT EXISTS `svoms_product_downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `start_time` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '开始时间',
  `end_time` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '结束时间',
  `product_id` int(11) NOT NULL COMMENT '商品ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '开启状态',
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '连接地址',
  `download_count` int(11) NOT NULL DEFAULT '0' COMMENT '下载总数',
  `allow_downloadtimes` int(11) NOT NULL COMMENT '允许下载次数',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_download_logs`
--

CREATE TABLE IF NOT EXISTS `svoms_product_download_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单号',
  `download_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT '用户ip',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_galleries`
--

CREATE TABLE IF NOT EXISTS `svoms_product_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图片编号',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品编号',
  `img_thumb` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `img_detail` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '详细图',
  `img_big` varchar(255) NOT NULL DEFAULT '' COMMENT '大图',
  `img_original` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '原始图',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_gallery_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_product_gallery_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品相册多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `product_gallery_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品相册编号',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '商品相册描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`product_gallery_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_product_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '商品名称',
  `style_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '款号名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '详细描述',
  `description02` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '描述2',
  `seller_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商家备注',
  `delivery_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '发货备注',
  `market_price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `shop_price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '本店价',
  `meta_keywords` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT 'SEO关键字',
  `meta_description` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT 'SEO描述',
  `api_site_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '商品网站网址',
  `api_cart_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '购物车快捷网址',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`product_id`),
  KEY `locale` (`locale`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_locale_prices`
--

CREATE TABLE IF NOT EXISTS `svoms_product_locale_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否有效0:无效,1:有效',
  `product_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价',
  `param01` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '属性1',
  `param02` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '\r\n\r\n属性2',
  `param03` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '\r\n\r\n属性3',
  `param04` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '\r\n\r\n属性4',
  `param05` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '\r\n\r\n属性5',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`,`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_public_templates`
--

CREATE TABLE IF NOT EXISTS `svoms_product_public_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `code` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '公用模板编号',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1有效0无效',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_public_template_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_product_public_template_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '语言',
  `product_public_template_id` int(11) NOT NULL COMMENT '商品公用模板Id   取 svoms_product_public_templates ID',
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '模板标题',
  `description01` text COLLATE utf8_unicode_ci NOT NULL COMMENT '公用模板描述01',
  `description02` text COLLATE utf8_unicode_ci NOT NULL COMMENT '公用模板描述02',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`product_public_template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_ranks`
--

CREATE TABLE IF NOT EXISTS `svoms_product_ranks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `rank_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级ID',
  `is_default_rank` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否使用会员初始的比例 0:禁用 1:使用',
  `product_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品会员价',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `rank_id` (`rank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_relations`
--

CREATE TABLE IF NOT EXISTS `svoms_product_relations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品编号',
  `related_product_id` int(11) NOT NULL DEFAULT '0' COMMENT '相关商品编号',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `is_double` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否是双向关联',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_services`
--

CREATE TABLE IF NOT EXISTS `svoms_product_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `start_time` datetime DEFAULT NULL COMMENT '开始时间',
  `end_time` datetime DEFAULT NULL COMMENT '结束时间',
  `product_id` int(11) NOT NULL COMMENT '商品ID',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '开启状态',
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '连接地址',
  `service_cycle` int(11) NOT NULL COMMENT '服务期限 （天）',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '观看总数',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_service_logs`
--

CREATE TABLE IF NOT EXISTS `svoms_product_service_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `product_id` int(11) NOT NULL COMMENT '商品ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `order_id` int(11) NOT NULL COMMENT '订单号',
  `user_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户ip',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_types`
--

CREATE TABLE IF NOT EXISTS `svoms_product_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类编号',
  `code` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '编码',
  `group_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '分组',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;2:删除',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `cat_id` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_type_attributes`
--

CREATE TABLE IF NOT EXISTS `svoms_product_type_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '属性编号',
  `code` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '编码',
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'basic' COMMENT '属性\r\n\r\n类型',
  `product_type_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品类型编号',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;2:删除',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `attr_input_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '属性输入类型',
  `attr_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '属性是否可选',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `cat_id` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_type_attribute_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_product_type_attribute_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '属性多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `product_type_attribute_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '属性名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '属性描述',
  `attr_value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '属性值',
  `default_value` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '默认值',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`product_type_attribute_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_type_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_product_type_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '类型多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_product_volumes`
--

CREATE TABLE IF NOT EXISTS `svoms_product_volumes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品数量优惠ID',
  `product_id` int(11) NOT NULL COMMENT '分类ID',
  `volume_number` int(11) NOT NULL COMMENT '购买数量',
  `volume_price` decimal(10,2) NOT NULL COMMENT '商品优惠价',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_regions`
--

CREATE TABLE IF NOT EXISTS `svoms_regions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父地区ID',
  `level` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '2' COMMENT '等级',
  `agency_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '目前暂未用到',
  `abbreviated` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '简写',
  `param01` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '参\r\n\r\n数1',
  `param02` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '参\r\n\r\n数2',
  `param03` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '参\r\n\r\n数3',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `level` (`level`),
  KEY `agency_id` (`agency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='区域' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_region_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_region_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '区域多语言编号',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `region_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章编号',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `description` text COLLATE utf8_unicode_ci COMMENT '描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`region_id`),
  KEY `locale` (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='区域语言' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_scores`
--

CREATE TABLE IF NOT EXISTS `svoms_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '评分类型',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '0无效1有效',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='评分表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_score_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_score_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '语言类型',
  `score_id` int(11) NOT NULL COMMENT '评分表id',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '评分名',
  `value` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '评分可选值',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='评分多语言表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_score_logs`
--

CREATE TABLE IF NOT EXISTS `svoms_score_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `type_id` int(11) NOT NULL COMMENT '类型id',
  `score_id` int(11) NOT NULL COMMENT '评分表id',
  `user_id` int(11) NOT NULL COMMENT '用户表id',
  `value` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '评论值',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='评分日志表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_shops`
--

CREATE TABLE IF NOT EXISTS `svoms_shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0虚拟店1实体店2淘宝3京东',
  `type_id` int(11) NOT NULL COMMENT '关联ID',
  `shop_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '店铺名',
  `shop_nick` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '店铺昵称',
  `shop_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '店铺地址',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否有效1有效0无效',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='店铺表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_sku_products`
--

CREATE TABLE IF NOT EXISTS `svoms_sku_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `sku_product_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '销售属性货号',
  `product_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '主货号',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='销售属性商品表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_stores`
--

CREATE TABLE IF NOT EXISTS `svoms_stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店铺编号',
  `store_sn` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '店铺编号',
  `operator_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '操作员id',
  `store_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0.虚拟店，1.实体店',
  `contact_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '联系人名称',
  `contact_email` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '联系人EMAIL',
  `contact_tele` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '联系人电话',
  `contact_mobile` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '联系人手机',
  `contact_fax` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '传真',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '网址',
  `X` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'x坐标',
  `Y` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'y坐标',
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '备注',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `workday_open_time` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '工作日营业时间',
  `wenkend_open_time` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '周末营业时间',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_sn` (`store_sn`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_store_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_store_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店铺多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '店铺名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '店铺描述',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '地址',
  `img01` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '图片',
  `zipcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '邮编',
  `transport` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '交通',
  `map` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '地图',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '链接',
  `meta_keywords` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT 'SEO关键字',
  `meta_description` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT 'SEO描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `locale` (`locale`),
  KEY `locale_2` (`locale`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_store_operators`
--

CREATE TABLE IF NOT EXISTS `svoms_store_operators` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '代理商ID',
  `operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作员ID',
  `orderby` smallint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_store_products`
--

CREATE TABLE IF NOT EXISTS `svoms_store_products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺编号',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品编号',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `start_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '有效时间',
  `end_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '结束时间',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_users`
--

CREATE TABLE IF NOT EXISTS `svoms_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员资料自增id',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '用户类型 0. 普通会员 1.分销商',
  `user_sn` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '会员卡号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '注册语言',
  `domain` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '注册域名',
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称',
  `first_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'first_name',
  `last_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'last_name',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
  `email` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '会员邮箱',
  `mobile` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '会员手机号',
  `admin_note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '管理员注释',
  `admin_note2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '折扣',
  `address_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '收货信息id，取值表\r\n\r\nuser_address',
  `payment_id` int(11) NOT NULL COMMENT '默认付款方式',
  `shipping_id` int(11) NOT NULL COMMENT '默认配送方式',
  `question` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '安全问题答案',
  `answer` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '安全问题',
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '用户现有资金',
  `frozen` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '用户冻结资金',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '消费积分',
  `user_point` int(11) DEFAULT NULL COMMENT '会员等级积分',
  `login_times` int(11) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `login_ipaddr` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '最后一次登录ip',
  `last_login_time` datetime DEFAULT '2008-01-01 00:00:00' COMMENT '最后一次登录时间',
  `rank` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级id，取值user_rank',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效 1:有效 2:冻结 3:注销 ]',
  `verify_status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '会员认证状态[0 未认证 1已认证 2 取消认证]',
  `unvalidate_note` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '会员认证备注',
  `img01` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户图片1',
  `img02` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户图片2',
  `img03` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户图片3',
  `birthday` date DEFAULT NULL COMMENT '生日日期',
  `mail_pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '找回邮件',
  `mail_pass_expire_time` datetime DEFAULT NULL COMMENT '重置密码过期时间',
  `sex` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '性别，0，保密；1，男；2，女',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人会员id',
  `email_flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0：未订阅 1：订阅 2：已订阅',
  `user_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ioco',
  `user_type_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '网站',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_sn` (`user_sn`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_accounts`
--

CREATE TABLE IF NOT EXISTS `svoms_user_accounts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户登录后保存在session中的id号，跟users表中的user_id对应',
  `user_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户注释',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '资金的数目，正数为增加，负数为减少',
  `paid_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '支付时间',
  `admin_user` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '管理员\r\n\r\n名称',
  `admin_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '管理员\r\n\r\n注释',
  `process_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '操作类型，1，退款；0，预付费，其实就是充值',
  `payment` varchar(90) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '支付渠道的名称\r\n\r\n，取自payment的pay_name字段',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否已经\r\n\r\n付款，０，未付；１，已付',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_addresses`
--

CREATE TABLE IF NOT EXISTS `svoms_user_addresses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名称',
  `first_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '名',
  `last_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '姓',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户表中的流水号',
  `consignee` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '收货人的名字',
  `email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '收货人的email',
  `address` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '收货人的详细地址',
  `address_type` int(11) NOT NULL DEFAULT '0' COMMENT '0家庭地址1公司地址2学校地址3其他',
  `zipcode` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '收货人的邮编',
  `telephone` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '收货人的电话',
  `mobile` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '收货人的手机',
  `sign_building` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '收货地址的标志性建筑名',
  `best_time` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '收货人的最佳收货时间',
  `regions` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '区域集',
  `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '国家',
  `province` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '省',
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '市',
  `district` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '区',
  `region_param01` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地区参数1',
  `region_param02` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地区参数2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_apps`
--

CREATE TABLE IF NOT EXISTS `svoms_user_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `app_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `app_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_balance_logs`
--

CREATE TABLE IF NOT EXISTS `svoms_user_balance_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `admin_user` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员名称',
  `admin_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员注释',
  `system_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '系统注释',
  `log_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '日志\r\n\r\n类型[O:订单;B:充值;R:退款]',
  `type_id` varchar(90) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '关\r\n\r\n联编号',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_configs`
--

CREATE TABLE IF NOT EXISTS `svoms_user_configs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_rank` int(11) NOT NULL DEFAULT '0' COMMENT '用户等级',
  `code` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户设置代码',
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `value` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '参数值\r\n\r\n',
  `orderby` tinyint(4) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_rank` (`user_rank`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_config_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_user_config_i18ns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `user_config_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户参数号',
  `code` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '配置编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '配送名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '配送描\r\n\r\n述',
  `user_config_values` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '可选值',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`user_config_id`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_favorites`
--

CREATE TABLE IF NOT EXISTS `svoms_user_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '收藏编号',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户编号',
  `type` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '收藏类型[商品(p)，商品分类(pc)，文章分类(ac)，品牌(b)，文章(a)]',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '收藏id号',
  `file_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '拼图链接',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效 1:有效]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `type_2` (`type`,`type_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_friends`
--

CREATE TABLE IF NOT EXISTS `svoms_user_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `cat_id` int(11) NOT NULL DEFAULT '0' COMMENT '好友分组ID',
  `contact_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '联系人姓名',
  `contact_mobile` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '联系人电话',
  `contact_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '联系人email',
  `contact_user_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '联系人用户名',
  `birthday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '好友生日',
  `birthday_wishes` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '祝福语',
  `remark` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '备注',
  `last_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '最后登入时间',
  `address` varchar(220) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '好友地址',
  `constellation` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '好友星座',
  `personality` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '个性',
  `sex` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '性别',
  `contact_other_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '好友备用邮箱',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_friend_cats`
--

CREATE TABLE IF NOT EXISTS `svoms_user_friend_cats` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `cat_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '好友分组名称',
  `cat_desc` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '好友分组描述',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级分组 ',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ＩＤ',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_groups`
--

CREATE TABLE IF NOT EXISTS `svoms_user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '分组名称',
  `description` tinytext COLLATE utf8_unicode_ci COMMENT '分组描述',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;2:删除',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_infos`
--

CREATE TABLE IF NOT EXISTS `svoms_user_infos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'html类型',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效 1:有效]',
  `front` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '前台显示',
  `backend` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '后台显示',
  `section` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '版本标识',
  `orderby` smallint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_info_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_user_info_i18ns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `user_info_id` int(11) NOT NULL DEFAULT '0' COMMENT '项目编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `user_info_values` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '可选值',
  `message` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '提示信息',
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '备注',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`user_info_id`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_info_values`
--

CREATE TABLE IF NOT EXISTS `svoms_user_info_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户编号',
  `user_info_id` int(11) NOT NULL DEFAULT '0' COMMENT '信息项目编号',
  `value` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '项目值',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_2` (`user_id`,`user_info_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_messages`
--

CREATE TABLE IF NOT EXISTS `svoms_user_messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复ID 0为留言',
  `from_id` int(11) NOT NULL DEFAULT '0' COMMENT '发送者(0:管理员)',
  `to_id` int(11) NOT NULL DEFAULT '0' COMMENT '接收者(0:管理员)',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `user_email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '留言者\r\n\r\nEMAIL',
  `msg_title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '留言标\r\n\r\n题',
  `msg_type` tinyint(5) unsigned NOT NULL DEFAULT '0' COMMENT '5.商家留言',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '留言对象类型',
  `msg_content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '留言内\r\n\r\n容 或 回复内容',
  `message_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '留言图片',
  `value_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '类型编号',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '状态0无\r\n\r\n效，1有效',
  `is_read` int(11) NOT NULL DEFAULT '0' COMMENT '0:未读，1:已读',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_oauths`
--

CREATE TABLE IF NOT EXISTS `svoms_user_oauths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '关联users表',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `account` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `oauth_token` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `oauth_token_secret` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_point_logs`
--

CREATE TABLE IF NOT EXISTS `svoms_user_point_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '金额',
  `admin_user` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员名称',
  `admin_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员注释',
  `system_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '系统注释',
  `log_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'R.注\r\n\r\n册赠送 B.购买赠送 O.购买消费 A.管理员操作',
  `type_id` varchar(90) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '关\r\n\r\n联编号',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_product_galleries`
--

CREATE TABLE IF NOT EXISTS `svoms_user_product_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;',
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传的图片',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_ranks`
--

CREATE TABLE IF NOT EXISTS `svoms_user_ranks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '会员等级编号，其中0是非会员',
  `min_points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '该等级的最低积分',
  `max_points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '该等级的最高积分',
  `balance` decimal(10,2) NOT NULL COMMENT '资金',
  `discount` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '该会员等级的商品折扣',
  `show_price` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否在不是该等级会员购买页面显示该会员等级的折扣价格.1,显示;0,不显示',
  `allow_buy` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '有\r\n\r\n权购买',
  `special_rank` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否事特殊会员等级组.0,不是;1,是',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_rank_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_user_rank_i18ns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `user_rank_id` int(11) NOT NULL DEFAULT '0' COMMENT 'svoms_user_ranks用户等级主表ID',
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '会员等级名称',
  `descrption` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '会员等级描述',
  `img` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '会员等级图片',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`user_rank_id`),
  KEY `locale` (`locale`),
  KEY `user_rank_id` (`user_rank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svoms_user_rank_logs`
--

CREATE TABLE IF NOT EXISTS `svoms_user_rank_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `rank_id` int(11) NOT NULL COMMENT '等级id',
  `operator_id` int(11) NOT NULL COMMENT '操作员id',
  `balance` decimal(10,2) NOT NULL COMMENT '资金',
  `pay_status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:未支付;1:已支付',
  `start_date` datetime NOT NULL COMMENT '开通时间',
  `end_date` datetime NOT NULL COMMENT '到期时间',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='会员等级日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_blogs`
--

CREATE TABLE IF NOT EXISTS `svsns_blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '日志图片',
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '1:有效，2:删除',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户日志表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_oauth_logs`
--

CREATE TABLE IF NOT EXISTS `svsns_oauth_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `oauth_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `images` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户分享记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_configs`
--

CREATE TABLE IF NOT EXISTS `svsns_open_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `open_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '公众平台类型',
  `open_type_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '公众平台账号',
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '标识code',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态:0无效1有效',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='公众平台配置表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_configs_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsns_open_configs_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `open_config_id` int(11) NOT NULL COMMENT 'open_config表id',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '配置名称',
  `value` text COLLATE utf8_unicode_ci NOT NULL COMMENT '配置值',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='公众平台配置多语言表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_elements`
--

CREATE TABLE IF NOT EXISTS `svsns_open_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `element_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '元素类型:1.单图文;2.多图文',
  `media_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '媒体id',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '标题,文本消息内容\r\n\r\n',
  `url` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '点击后跳转的链接,音乐链接',
  `media_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '图文消息\r\n\r\n的图片链接，支持JPG、PNG格式，较好的效果为大图640*320，小图80*80;高品质音乐链接',
  `link` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '外部链接',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_keywords`
--

CREATE TABLE IF NOT EXISTS `svsns_open_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `open_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '公众平台类型(微信 易信等等)',
  `open_type_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '公众平台账号',
  `keyword` varchar(200) NOT NULL COMMENT '关键字',
  `match_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'msg类型',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关键字表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_keyword_answers`
--

CREATE TABLE IF NOT EXISTS `svsns_open_keyword_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `keyword_id` int(11) NOT NULL DEFAULT '0' COMMENT '关键字id',
  `msgtype` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'msg类型',
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '发送信息',
  `element_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '媒体id',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '有效状态：0无效1有效',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关键字回复表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_keyword_errors`
--

CREATE TABLE IF NOT EXISTS `svsns_open_keyword_errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `open_user_id` int(11) NOT NULL COMMENT '关注用户',
  `open_type_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '公众平台帐号',
  `keyword` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '关键字',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '回复状态：0未回复1已回复',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='未匹配到的关键字列表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_medias`
--

CREATE TABLE IF NOT EXISTS `svsns_open_medias` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `open_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '公众平台类型(微信 易信等等)',
  `open_type_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '公众平台账号',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '媒体文件类型，分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）',
  `media_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '媒体id',
  `media` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'form-data中媒体文件标识，有filename、filelength、content-type等信息',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='媒体数据表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_menus`
--

CREATE TABLE IF NOT EXISTS `svsns_open_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `type` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单的响应动作类型，目前有click、view两种类型',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单标题，不超过16个\r\n\r\n字节，子菜单不超过40个字节',
  `key` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'click类型必须,菜单KEY值，用于消\r\n\r\n息接口推送，不超过128字节',
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'view类型必须,网页链接，用户点击\r\n\r\n菜单可打开链接，不超过256字节',
  `orderby` tinyint(4) NOT NULL COMMENT '排序',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_models`
--

CREATE TABLE IF NOT EXISTS `svsns_open_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `open_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '公众平台类型(微信 易信等等)',
  `open_type_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '公众\r\n\r\n平台账号',
  `app_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '开发者凭据 \r\n\r\nAppId',
  `app_secret` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '开发者凭\r\n\r\n据 AppSecret',
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '接入平台令牌',
  `signature_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '验证用token',
  `status` varchar(1) NOT NULL DEFAULT '1' COMMENT '0:关闭1:开启',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '平台描述',
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '头像',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型:0订阅号;1服务号',
  `verify_status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '认证状态:0未认证;1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_relations`
--

CREATE TABLE IF NOT EXISTS `svsns_open_relations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `open_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '公众平台类型(微信 易信等等)',
  `open_type_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '公众\r\n\r\n平台账号',
  `open_user_id` int(11) NOT NULL COMMENT '关注用户',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '0:商品,1:订单',
  `type_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '商品id | 订单\r\n\r\nid',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `open_user_id` (`open_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_users`
--

CREATE TABLE IF NOT EXISTS `svsns_open_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `open_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '公众平台类型(微信 易信等等)',
  `open_type_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '公众平台账号',
  `openid` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户的标识，对当前公众号唯一',
  `nickname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户的昵称',
  `sex` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知\r\n\r\n',
  `language` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户的语言\r\n\r\n，简体中文为zh_CN',
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用\r\n\r\n户所在城市',
  `province` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '\r\n\r\n用户所在省份',
  `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '\r\n\r\n用户所在国家',
  `headimgurl` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户头像',
  `subscribe` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:取消关注 1:正在关注',
  `subscribe_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '关注时间',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_open_user_messages`
--

CREATE TABLE IF NOT EXISTS `svsns_open_user_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `open_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '公众平台类型(微信 易信等等)',
  `open_type_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '公众\r\n\r\n平台账号',
  `open_user_id` int(11) NOT NULL COMMENT '关注用户',
  `send_from` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '0:后台发的 1用\r\n\r\n户发的',
  `msgtype` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'msg类型',
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '发送信息',
  `return_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '返回码\r\n\r\n',
  `return_message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '返回信息',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `open_user_id` (`open_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公众平台信息日志表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_synchro_users`
--

CREATE TABLE IF NOT EXISTS `svsns_synchro_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '关联users表',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `account` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `oauth_token` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `oauth_token_secret` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '开启状态',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_user_actions`
--

CREATE TABLE IF NOT EXISTS `svsns_user_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '喜欢，评论，日记',
  `type_id` int(11) NOT NULL,
  `img` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户动作记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_user_chats`
--

CREATE TABLE IF NOT EXISTS `svsns_user_chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `to_user_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `read` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `read_time` datetime NOT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '1:有效，2:删除',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户私信表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_user_fans`
--

CREATE TABLE IF NOT EXISTS `svsns_user_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `fan_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户粉丝表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_user_likes`
--

CREATE TABLE IF NOT EXISTS `svsns_user_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '产品',
  `type_id` int(11) NOT NULL,
  `action` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'like:喜欢,cart:加入购物车',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户喜欢表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsns_user_visitors`
--

CREATE TABLE IF NOT EXISTS `svsns_user_visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `remark` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户访问表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_applications`
--

CREATE TABLE IF NOT EXISTS `svsys_applications` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '应用id',
  `groupby` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '应用类别',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '应用排序',
  `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '参数',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否有效 1有效0停用',
  `end_time` datetime NOT NULL COMMENT '有效期至',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_application_configs`
--

CREATE TABLE IF NOT EXISTS `svsys_application_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `app_id` int(11) NOT NULL COMMENT '应用ID',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '属性的格式',
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '代码',
  `group_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'defult_app' COMMENT '参数分类',
  `subgroup_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_application_config_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_application_config_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `app_id` int(11) NOT NULL COMMENT '应用ID',
  `app_config_id` int(11) NOT NULL,
  `config_code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `description` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '属性描述',
  `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性备\r\n\r\n注',
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '属性值',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_application_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_application_i18ns` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '应用id',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `app_id` int(11) NOT NULL COMMENT 'app id',
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '应用名称',
  `unit_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '月' COMMENT '单位名称',
  `tags` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '关键字',
  `directory` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '应用描述',
  `copyright` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '版权信息',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_attitudes`
--

CREATE TABLE IF NOT EXISTS `svsys_attitudes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论编号',
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '评论类型[商品P，分类C，品牌B，文章A，商店]',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '类型编号',
  `action` smallint(1) NOT NULL COMMENT '0 踩 1赞',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户编号',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='赞 踩信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_block_words`
--

CREATE TABLE IF NOT EXISTS `svsys_block_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `word` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='屏蔽词表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_configs`
--

CREATE TABLE IF NOT EXISTS `svsys_configs` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '参数ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商店编号[0:系统]',
  `group_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '设置参数组',
  `subgroup_code` varchar(50) NOT NULL,
  `code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '参数名\r\n\r\n称',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '参数类型',
  `readonly` int(1) NOT NULL DEFAULT '0' COMMENT '是否只读，0不是，1是',
  `section` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '版本标识',
  `status` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态：0.无效;1.有效',
  `orderby` int(4) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `type` (`type`),
  KEY `store_id` (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_config_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_config_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '配置多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `config_id` int(11) NOT NULL DEFAULT '0' COMMENT '配送编号',
  `config_code` varchar(60) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '配送名称',
  `default_value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '默认值',
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '值',
  `options` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '可选值',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '描述',
  `param01` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '参数1',
  `param02` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '参数2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_3` (`locale`,`config_id`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_cronjobs`
--

CREATE TABLE IF NOT EXISTS `svsys_cronjobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '任务名称',
  `task_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态',
  `last_time` datetime NOT NULL COMMENT '上次运行时间',
  `next_time` datetime NOT NULL COMMENT '下次运行时间',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `interval_time` datetime NOT NULL,
  `app_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `param01` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `param02` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `remark` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_name` (`task_name`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='定时器' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_departments`
--

CREATE TABLE IF NOT EXISTS `svsys_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '部门编号',
  `contact_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系人',
  `contact_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Email地址',
  `contact_tele` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `contact_mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系人手机',
  `contact_fax` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系传真',
  `contact_remark` text COLLATE utf8_unicode_ci COMMENT '联系备注',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='部门' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_department_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_department_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类多语言编号',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `department_id` int(11) NOT NULL DEFAULT '0' COMMENT '部门编号',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '部门名称',
  `description` tinytext COLLATE utf8_unicode_ci COMMENT '部门描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='部门语言' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_dictionaries`
--

CREATE TABLE IF NOT EXISTS `svsys_dictionaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言代码',
  `location` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'front' COMMENT '前后台参数区分',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '描述',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '内容',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`name`,`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='字典表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_enquiries`
--

CREATE TABLE IF NOT EXISTS `svsys_enquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part_num` text COLLATE utf8_unicode_ci NOT NULL,
  `attribute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `qty` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `l_time` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target_price` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` int(11) NOT NULL,
  `ship_to` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `company_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `company_type` int(11) NOT NULL,
  `other` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `contact_person` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tel1` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tel2` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tel3` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tel4` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0未确认1已确认2取消3完成',
  `remark` text COLLATE utf8_unicode_ci,
  `created` datetime NOT NULL DEFAULT '2012-01-01 00:00:00',
  `modified` datetime NOT NULL DEFAULT '2012-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='询价信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_information_resources`
--

CREATE TABLE IF NOT EXISTS `svsys_information_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资源ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '资源上级ID',
  `code` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '资源代码',
  `information_value` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '资源代码的值',
  `status` char(1) COLLATE utf8_unicode_ci DEFAULT '1' COMMENT '状态0:无效1:\r\n\r\n有效',
  `orderby` tinyint(3) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_information_resource_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_information_resource_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资源多语言编号',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `information_resource_id` int(11) NOT NULL DEFAULT '0' COMMENT '资源编号',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '资源名称',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`information_resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='信息多语言' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_languages`
--

CREATE TABLE IF NOT EXISTS `svsys_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '语言编号',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言代码',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言',
  `charset` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '字符集',
  `map` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '系统映射',
  `img01` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片01',
  `img02` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片02',
  `front` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '前台显示',
  `backend` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '后台显示',
  `is_default` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '1为默认',
  `google_translate_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'google 翻译接口',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `front` (`front`),
  KEY `backend` (`backend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='语言表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_mail_send_histories`
--

CREATE TABLE IF NOT EXISTS `svsys_mail_send_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sender_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '发送人姓名',
  `receiver_email` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '接收人地址',
  `cc_email` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '抄送地址',
  `bcc_email` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暗送人地址',
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '主题',
  `html_body` text COLLATE utf8_unicode_ci COMMENT '邮件内容',
  `text_body` text COLLATE utf8_unicode_ci COMMENT '邮件内容',
  `sendas` char(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'html,text',
  `flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '1.发送成功，0.发送失败',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='邮件发送队列表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_mail_send_queues`
--

CREATE TABLE IF NOT EXISTS `svsys_mail_send_queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sender_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '发送人姓名',
  `receiver_email` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '接收人地址',
  `cc_email` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '抄送地址',
  `bcc_email` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暗送人地址',
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '主题',
  `html_body` text COLLATE utf8_unicode_ci COMMENT '邮件内容',
  `text_body` text COLLATE utf8_unicode_ci COMMENT '邮件内容',
  `sendas` char(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'html,text',
  `flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0.未发送 1234.发送失败生发超过5删除',
  `pri` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '优先级 0 普通， 1 高 ',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='邮件发送队列表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_mail_statistics`
--

CREATE TABLE IF NOT EXISTS `svsys_mail_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `mail_date` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '产生日期',
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `value` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '内容',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  `start_date` datetime NOT NULL COMMENT '开始时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_mail_templates`
--

CREATE TABLE IF NOT EXISTS `svsys_mail_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '邮件模板编号',
  `code` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '编号',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `last_send` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '最后发送时间',
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '模板类型',
  `user_email_flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:无1：待发送2：已发送',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_mail_template_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_mail_template_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '邮件模板多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `mail_template_id` int(11) NOT NULL DEFAULT '0' COMMENT '邮件模板编号',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '邮件模板名称',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模板说明',
  `text_body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '邮件模板text模板',
  `html_body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '邮件模板HTML模板',
  `sms_body` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`mail_template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operators`
--

CREATE TABLE IF NOT EXISTS `svsys_operators` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员名称',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员密码',
  `session` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '会话',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员邮件',
  `mobile` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员手机',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S' COMMENT 'S:系统 D：经销商',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '经销商编号',
  `department_id` int(10) NOT NULL DEFAULT '0' COMMENT '部门ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商店编号[0:系统管理员]',
  `role_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '角色编号',
  `actions` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '功能权限',
  `default_lang` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'zh_cn' COMMENT '管理员默认语言',
  `template_code` varchar(50) NOT NULL DEFAULT 'default' COMMENT '操作员模板',
  `desktop` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '桌面设置',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '状态[0:无效;1:有效;2:冻结]',
  `log_flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '日记记录标志位(0:无效，1：有效)',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登入时间',
  `last_login_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '最后登入IP',
  `time_zone` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-8' COMMENT '时区',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `store_id` (`store_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_actions`
--

CREATE TABLE IF NOT EXISTS `svsys_operator_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '功能编号',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '功能等级',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父编号',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '代码',
  `app_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '应用code',
  `section` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'allinone' COMMENT '版本标识',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '状态[0:无效 1:有效]',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `level` (`level`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_action_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_operator_action_i18ns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `operator_action_id` int(11) NOT NULL DEFAULT '0' COMMENT '功能编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '功能名称',
  `operator_action_values` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '值',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '功能描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时\r\n\r\n\r\n\r\n间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时\r\n\r\n\r\n\r\n间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`operator_action_id`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_logs`
--

CREATE TABLE IF NOT EXISTS `svsys_operator_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志编号',
  `operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员编号',
  `session_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ipaddress` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP地址',
  `action_url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '访问地址',
  `info` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '备注',
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '存放post和get的参数',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`operator_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_menus`
--

CREATE TABLE IF NOT EXISTS `svsys_operator_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单编号',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级菜单编号',
  `operator_action_code` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '权限代码',
  `type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `app_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '应用code',
  `link` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '连接地址',
  `level` int(1) NOT NULL DEFAULT '0' COMMENT '等级 1:免费版2:付费版3:权限版',
  `section` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '版本标识',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_menu_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_operator_menu_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `operator_menu_id` int(11) NOT NULL DEFAULT '0' COMMENT '菜单编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`operator_menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_oauths`
--

CREATE TABLE IF NOT EXISTS `svsys_operator_oauths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator_id` int(11) NOT NULL COMMENT '关联operators表',
  `app_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'web app_key',
  `app_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'App Secret',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用于调用access_token，接口获取授权后的access token。 ',
  `access_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'access_token',
  `uid` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '我的用户id',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效 1:有效',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `account` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(11) NOT NULL,
  `oauth_token` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `oauth_token_secret` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_roles`
--

CREATE TABLE IF NOT EXISTS `svsys_operator_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员角色编号',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商店编号',
  `actions` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '功能权限',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '状态[0:无效;1:有效;2:冻结]',
  `orderby` smallint(4) NOT NULL DEFAULT '500' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_role_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_operator_role_i18ns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `operator_role_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员角色编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `locale` (`locale`),
  KEY `locale_2` (`locale`,`operator_role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_page_actions`
--

CREATE TABLE IF NOT EXISTS `svsys_page_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_type_id` int(11) DEFAULT NULL COMMENT 'page_type表id',
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 有效 0 无效',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_page_modules`
--

CREATE TABLE IF NOT EXISTS `svsys_page_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模块ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父编号',
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '模块编码',
  `page_action_id` int(11) DEFAULT NULL COMMENT 'page_action表id',
  `position` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模块位置',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '模块类型',
  `type_id` int(11) DEFAULT NULL COMMENT '相关信息',
  `model` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '模型名',
  `function` varchar(55) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'get_module_infos' COMMENT '方法名称',
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文件名称',
  `css` text COLLATE utf8_unicode_ci NOT NULL COMMENT '模块css',
  `width` smallint(5) DEFAULT '0' COMMENT '模块宽度',
  `height` smallint(5) DEFAULT '0' COMMENT '模块高度',
  `float` char(1) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '浮动 0.正行浮动 1.左浮动 2.右浮动',
  `limit` int(11) DEFAULT '10' COMMENT '取值数量',
  `orderby_type` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '排序方式',
  `orderby` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模块排序方式',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '模块状态',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `page_style_code` (`page_action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='模块管理' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_page_module_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_page_module_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `module_id` int(11) NOT NULL COMMENT '模块ID',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '语言',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '模块名称',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模块标题',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='模块多语言表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_page_types`
--

CREATE TABLE IF NOT EXISTS `svsys_page_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:电脑;1:手机',
  `css` text COLLATE utf8_unicode_ci,
  `status` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remark` tinytext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_portals`
--

CREATE TABLE IF NOT EXISTS `svsys_portals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '显示名称',
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '网址',
  `img` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '图片',
  `default_min` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '默认展开',
  `default_list` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '默认列表',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '有效状态：0无效1有效',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='门户配置表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_profiles`
--

CREATE TABLE IF NOT EXISTS `svsys_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '编码',
  `group` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '档案配置分类',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_profiles_fields`
--

CREATE TABLE IF NOT EXISTS `svsys_profiles_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品编号',
  `profile_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品编号',
  `locale` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'chi' COMMENT '多语言',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '代码',
  `format` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '格式',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_profiles_field_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_profiles_field_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '档案配置字段多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `profiles_field_id` int(11) NOT NULL DEFAULT '0' COMMENT '档案配置字段编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '档案配置字段名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '档案配置字段描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`profiles_field_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_profile_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_profile_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '档案配置多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `profile_id` int(11) NOT NULL DEFAULT '0' COMMENT '档案配置编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '档案配置名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '档案配置描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`profile_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_resources`
--

CREATE TABLE IF NOT EXISTS `svsys_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '资源上级ID',
  `code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '资源代码',
  `resource_value` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '资源代码的值',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '1' COMMENT '状态0:无效1:\r\n\r\n有效',
  `section` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '版本标识',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_resource_i18ns`
--

CREATE TABLE IF NOT EXISTS `svsys_resource_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `resource_id` int(11) NOT NULL DEFAULT '0' COMMENT '资源编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '资源名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_routes`
--

CREATE TABLE IF NOT EXISTS `svsys_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `controller` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `model_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模型id',
  `options` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '参数，以;分割',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0无效1有效',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='网址控制器' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_sessions`
--

CREATE TABLE IF NOT EXISTS `svsys_sessions` (
  `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '序列化后的sessionid',
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '序列化后的session数据',
  `expires` int(11) DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_sitemaps`
--

CREATE TABLE IF NOT EXISTS `svsys_sitemaps` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `cycle` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '周期',
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '链接地址',
  `priority` varchar(10) NOT NULL COMMENT '优先级',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1：有效0：无效',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_sms_send_histories`
--

CREATE TABLE IF NOT EXISTS `svsys_sms_send_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号码',
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '短信内容',
  `send_date` datetime NOT NULL COMMENT '发送时间',
  `flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0;未发送',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_sms_send_queues`
--

CREATE TABLE IF NOT EXISTS `svsys_sms_send_queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号码',
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '短信内容',
  `send_date` datetime NOT NULL COMMENT '发送时间',
  `flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0;未发送',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_sms_words`
--

CREATE TABLE IF NOT EXISTS `svsys_sms_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `word` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '敏感字',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


--
-- 表的结构 `svoms_quotes`
--

CREATE TABLE IF NOT EXISTS `svoms_quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enquiry_id` int(11) NOT NULL COMMENT '询价id',
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_sendmail` char(1) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '0未发送1已发送',
  `customer_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `contact_person` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `other` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remark` text COLLATE utf8_unicode_ci,
  `quoted_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `inquire_date` datetime NOT NULL,
  `created` datetime NOT NULL DEFAULT '2012-01-01 00:00:00',
  `modified` datetime NOT NULL DEFAULT '2012-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='报价单表' AUTO_INCREMENT=1 ;

--
-- 表的结构 `svoms_quote_products`
--

CREATE TABLE IF NOT EXISTS `svoms_quote_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_id` int(11) NOT NULL,
  `product_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `brand_code` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `data_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pack_detail` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qty_requested` int(11) DEFAULT '0',
  `qty_offered` int(11) NOT NULL DEFAULT '0',
  `target_price` decimal(11,2) DEFAULT '0.00',
  `offered_price` decimal(11,2) NOT NULL DEFAULT '0.00',
  `delivery` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `payment_terms` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT '2012-01-01 00:00:00',
  `modified` datetime NOT NULL DEFAULT '2012-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='报价单产品明细表' AUTO_INCREMENT=1 ;


--
-- 0.7版本表结构调整 -----------------------------------------------------------------------------
--

ALTER TABLE `svsys_profiles_fields` DROP `locale`;

--
-- 添加版型表
--
CREATE TABLE IF NOT EXISTS `svoms_product_styles` (
`id` int(11) NOT NULL AUTO_INCREMENT COMMENT '版型id',
`orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
`status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
`created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
`modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `svoms_product_style_i18ns` (
`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
`style_id` int(11) NOT NULL COMMENT '版型ID',
`locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '语言',
`style_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '版型名称',
`created` datetime NOT NULL COMMENT '创建时间',
`modified` datetime NOT NULL COMMENT '修改时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='版型多语言表' AUTO_INCREMENT=1 ;

--
-- 添加版型规格表
--
CREATE TABLE IF NOT EXISTS `svoms_style_type_groups` (
`id` int(11) NOT NULL AUTO_INCREMENT COMMENT '规格id',
`style_id` int(11) DEFAULT NULL COMMENT '关联版型id',
`type_id` int(11) DEFAULT NULL COMMENT '属性组id',
`attribute_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '属性值的尺码',
`group_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci  NULL DEFAULT '' COMMENT '版型规格值',
`status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
`orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
`created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
`modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- 商品版型规格尺寸表
--
CREATE TABLE IF NOT EXISTS `svoms_style_type_group_attribute_values` (
`id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品版型规格尺寸id',
`style_id` int(11) DEFAULT NULL COMMENT '关联版型id',
`type_id` int(11) DEFAULT NULL COMMENT '属性组id',
`style_type_group_id` int(11) DEFAULT NULL COMMENT '关联规格id',
`atrribute_id` int(11) DEFAULT NULL COMMENT '规格id',
`attribute_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '属性值的尺码',
`default_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '默认值;',
`select_value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '可选值列表',
`created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
`modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 用户模板表（保存用户选择版型）
--
CREATE TABLE IF NOT EXISTS `svoms_user_styles` (
`id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户选择模板id',
`user_id` int(11) DEFAULT '0' COMMENT '用户id',
`style_id` int(11) DEFAULT NULL COMMENT '关联版型id',
`type_id` int(11) DEFAULT NULL COMMENT '规格id',
`attribute_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '属性值的尺码',
`default_status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '默认模板[0:不是;1:是;]',
`user_style_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '模板名称',
`created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
`modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 用户模板属性值表（保存用户选择修改属性值）
--
CREATE TABLE IF NOT EXISTS `svoms_user_style_values` (
`id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户属性值id',
`user_style_id` int(11) DEFAULT NULL COMMENT '用户选择模板id',
`attribute_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '修改属性id',
`attribute_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '修改属性值',
`created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
`modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


ALTER TABLE `svoms_products` ADD `product_style_id` int(11) NULL DEFAULT '0' COMMENT '版型id' AFTER `product_type_id` ;

DROP TABLE IF EXISTS `svoms_attributes`;
--
-- 表的结构 `svoms_attributes`
--

CREATE TABLE IF NOT EXISTS `svoms_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '属性编号',
  `code` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '编码',
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'basic' COMMENT '属性\r\n\r\n类型',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;2:删除',
  `attr_input_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '属性输入类型',
  `attr_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '属性是否可选',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `cat_id` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `svoms_attribute_i18ns`;

--
-- 表的结构 `svoms_attribute_i18ns`
--

CREATE TABLE IF NOT EXISTS `svoms_attribute_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '属性多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `attribute_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '属性名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '属性描述',
  `attr_value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '属性值',
  `default_value` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '默认值',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`attribute_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `svoms_product_type_attributes`;
DROP TABLE IF EXISTS `svoms_product_type_attribute_i18ns`;

--
-- 表的结构 `svoms_product_type_attributes`
--

CREATE TABLE IF NOT EXISTS `svoms_product_type_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '关联记录Id',
  `product_type_id` int(11) NOT NULL DEFAULT '0',
  `attribute_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性编号',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `svoms_product_attributes`;

--
-- 表的结构 `svoms_product_attributes`
--

CREATE TABLE IF NOT EXISTS `svoms_product_attributes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `locale` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `orderby` tinyint(4) DEFAULT NULL COMMENT '排序',
  `attribute_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `attribute_value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '属性值',
  `attribute_price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '属性价格',
  `attribute_image_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性图片',
  `attribute_back_image_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性背面图片',
  `attribute_related_image_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性自定义图片',
  `attribute_related_back_image_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性自定义背面图片',
  `attribute_color_css` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性颜色css',
  `attribute_shell_num` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性外壳拼图个数',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`product_id`),
  KEY `attr_id` (`attribute_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


ALTER TABLE `svoms_product_types` ADD `customize` CHAR( 1 ) NOT NULL DEFAULT '0' COMMENT '是否为定制（0:否,1:是）' AFTER `group_code` ;

ALTER TABLE `svsys_page_modules` ADD `parameters` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '参数id' AFTER `function` ;

ALTER TABLE `svcms_category_articles` ADD `tree_show_type` CHAR( 1 ) NOT NULL DEFAULT '0' COMMENT '分类树显示类型：0.顶级;1.同级;2.子级' AFTER `link` ;

ALTER TABLE `svcms_category_articles` CHANGE `type` `type` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A' COMMENT '分类类型[A:文章,P:商品]';

ALTER TABLE `svoms_payments` ADD `parent_id` INT NOT NULL DEFAULT '0' COMMENT '上级支付方式' AFTER `id` ;

ALTER TABLE `svoms_user_configs` ADD `value_type` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text' COMMENT '配置值的类型' AFTER `type` ;

ALTER TABLE `svsns_open_menus` ADD `status` CHAR( 1 ) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态：0.无效;1.有效' AFTER `url` ;


ALTER TABLE `svcms_contacts` ADD `age` int(3) NOT NULL DEFAULT '0' COMMENT '年龄' AFTER `is_send` ;
ALTER TABLE `svcms_contacts` ADD `sex` CHAR( 1 ) NOT NULL DEFAULT '0' COMMENT '性别' AFTER `age` ;
ALTER TABLE `svcms_contacts` ADD `parameter_01` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '参数1' AFTER `browser` ;
ALTER TABLE `svcms_contacts` ADD `parameter_02` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '参数2' AFTER `parameter_01` ;
ALTER TABLE `svcms_contacts` ADD `parameter_03` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '参数3' AFTER `parameter_02` ;

ALTER TABLE `svsys_page_actions` ADD `layout` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '使用的layout名称' AFTER `page_type_id` ;


--
-- 商品材料管理数据表（Material）
--
CREATE TABLE IF NOT EXISTS `svoms_materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '材料编号',
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT '材料库存',
  `unit` varchar(20) NULL COMMENT '材料单位',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1有效0无效 默认1',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='材料表' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `svoms_material_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `product_material_id` int(11) NOT NULL DEFAULT '0' COMMENT '材料编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '材料编号',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '材料描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='材料表多语言表' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `svoms_product_materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `product_material_code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '材料编号',
  `product_code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '商品货号',
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '消耗材料数量',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品材料表' AUTO_INCREMENT=1 ;


--
-- 属性图
--
ALTER TABLE `svoms_attributes` ADD `attribute_img` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '属性图' AFTER `attr_type`;



--
-- 属性选项表（Attribute Options）
--
CREATE TABLE IF NOT EXISTS `svoms_attribute_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `attribute_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性id',
  `option_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '选项名称',
  `option_value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '选项值',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '属性价格',
  `attribute_option_image1` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '属性选项图片1',
  `attribute_option_image2` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '属性选项图片2',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1有效0无效 默认1',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='属性选项表' AUTO_INCREMENT=1 ;

--
-- 用户体型图
--
ALTER TABLE `svoms_users` ADD `img04` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '用户图片4' AFTER `img03` ;
ALTER TABLE `svoms_users` ADD `img05` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '用户图片5' AFTER `img04` ;
ALTER TABLE `svoms_users` ADD `img06` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '用户图片6' AFTER `img05` ;

ALTER TABLE `svoms_user_ranks` ADD `code` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '等级Code' AFTER `id` ;

ALTER TABLE `svoms_payments` ADD `logo` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '支付方式Logo' AFTER `status` ;
ALTER TABLE `svoms_payments` CHANGE `logo` `logo` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '支付方式Logo';

ALTER TABLE `svoms_product_type_attributes` ADD `orderby` TINYINT( 4 ) NOT NULL DEFAULT '50' COMMENT '排序' AFTER `attribute_id` ;
ALTER TABLE `svoms_attributes` ADD `orderby` TINYINT( 4 ) NOT NULL DEFAULT '50' COMMENT '排序' AFTER `attribute_img` ;
ALTER TABLE `svcms_topics` CHANGE `orderby` `orderby` TINYINT( 4 ) NOT NULL DEFAULT '50' COMMENT '排序方式';
ALTER TABLE `svoms_products` DROP `product_style_id`;

ALTER TABLE `svoms_products` CHANGE `promotion_start` `promotion_start` DATETIME NULL DEFAULT '2008-01-01 00:00:00' COMMENT '促销开始时间';
ALTER TABLE `svoms_products` CHANGE `promotion_end` `promotion_end` DATETIME NULL DEFAULT '2008-01-01 00:00:00' COMMENT '促销结束时间';
ALTER TABLE `svcms_contacts` ADD `type` VARCHAR( 1 ) NOT NULL DEFAULT '0' COMMENT '留言类型' AFTER `id` ;

ALTER TABLE `svoms_materials` ADD `frozen_quantity` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0.00' COMMENT '冻结材料数量' AFTER `quantity` ;

ALTER TABLE `svoms_user_configs` CHANGE `type` `type` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '类型';
ALTER TABLE `svoms_user_configs` ADD `is_required` CHAR( 1 ) NOT NULL DEFAULT '0' COMMENT '是否为必填项(1:是,0否:)' AFTER `value` ;
ALTER TABLE `svoms_user_configs` ADD `group_code` VARCHAR( 100 ) NULL DEFAULT NULL COMMENT '类型分组' AFTER `type` ;

ALTER TABLE `svcms_navigations` CHANGE `controller` `controller` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pages' COMMENT '系统内容';


ALTER TABLE `svoms_products` ADD `unit` VARCHAR( 20 ) NULL COMMENT '单位' AFTER `weight` ;

DROP TABLE IF EXISTS `svedi_sms_send_histories`;
DROP TABLE IF EXISTS `svedi_sms_send_queues`;

ALTER TABLE `svsns_open_medias` CHANGE `media_id` `media_id` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '媒体id';
ALTER TABLE `svsns_open_medias` CHANGE `type` `open_element_id` INT NOT NULL DEFAULT '0' COMMENT '素材编号';
ALTER TABLE `svsns_open_medias` CHANGE `media` `url` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '素材地址';
ALTER TABLE `svsns_open_medias` ADD `image_media_id` VARCHAR( 250 ) NULL DEFAULT NULL COMMENT '素材图片media_id' AFTER `open_element_id` ,
ADD `image_media_url` VARCHAR( 250 ) NULL DEFAULT NULL COMMENT '素材图片地址' AFTER `image_media_id` ;