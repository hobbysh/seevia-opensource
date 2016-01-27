--
--	V0.9升级
--
ALTER TABLE `svsns_open_medias` CHANGE `media_id` `media_id` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '媒体id';
ALTER TABLE `svsns_open_medias` CHANGE `type` `open_element_id` INT NOT NULL DEFAULT '0' COMMENT '素材编号';
ALTER TABLE `svsns_open_medias` CHANGE `media` `url` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '素材地址';
ALTER TABLE `svsns_open_medias` ADD `image_media_id` VARCHAR( 250 ) NULL DEFAULT NULL COMMENT '素材图片media_id' AFTER `open_element_id` ,
ADD `image_media_url` VARCHAR( 250 ) NULL DEFAULT NULL COMMENT '素材图片地址' AFTER `image_media_id` ;
ALTER TABLE `svsns_open_medias` ADD `media_type` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT '媒体类型(news,image,voice,video)' AFTER `id` ;



--
-- 重置表中的数据 `svcms_templates`
--

TRUNCATE table `svcms_templates`;

INSERT INTO `svcms_templates` (`id`, `name`, `description`, `template_style`, `template_img`, `url`, `show_css`, `mobile_css`, `mobile_status`, `status`, `is_default`, `author`, `version`, `style`, `created`, `modified`) VALUES
(1, 'default', 'HTML5 跨屏前端框架', 'default', 'http://img.seevia.cn/img/photos/201409/0/2/original/d65c9fd2a0bc814d730a072314bef935.jpg', '', '', '{"header_background_color1":"","header_background_color2":"","header_font_color":"","header_frame_color":"","foot_background_color1":"","foot_font_color":"","foot_frame_color":"","foot_hightlight_background_color1":"","foot_hightlight_font_color":"","mobile_comment_css":""}', '0', '1', '1', 'AmazeUI', '', '', '2008-01-01 00:00:00', '2014-12-22 12:03:12');

--
-- 重置表中的数据 `svsys_page_types`
--

TRUNCATE table `svsys_page_types`;

INSERT INTO `svsys_page_types` (`id`, `code`, `name`, `page_type`, `css`, `status`, `remark`, `created`, `modified`) VALUES
(1, 'default', 'HTML5', '0', '', '1', NULL, '2014-10-11 14:30:29', '2014-10-11 14:42:37');


--
-- 插入之前先把表清空（truncate） `svsys_page_actions`
--

TRUNCATE TABLE `svsys_page_actions`;
--
-- 转存表中的数据 `svsys_page_actions`
--

INSERT INTO `svsys_page_actions` (`id`, `controller`, `action`, `name`, `page_type_id`, `layout`, `status`, `created`, `modified`) VALUES
(1, 'pages', 'home', '首页', 1, '', '1', '2014-10-11 14:31:58', '2014-10-11 14:31:58'),
(2, 'products', 'view', '商品详细页', 1, 'product', '1', '2014-10-11 14:33:11', '2014-10-11 14:33:11'),
(3, 'categories', 'view', '产品分类', 1, '', '1', '2014-11-03 14:01:16', '2014-11-03 14:01:16'),
(4, 'topics', 'index', '专题列表页', 1, '', '1', '2014-11-05 10:35:26', '2014-11-05 10:35:26'),
(5, 'topics', 'view', '专题详情页', 1, '', '1', '2014-11-05 13:13:35', '2014-11-05 13:13:35'),
(6, 'articles', 'index', '文章列表页', 1, '', '1', '2014-11-05 14:12:27', '2014-11-05 14:12:27'),
(7, 'articles', 'view', '文章详情页', 1, '', '1', '2014-11-05 17:28:36', '2014-11-05 17:28:36'),
(8, 'brands', 'index', '品牌列表', 1, '', '1', '2014-11-06 11:21:19', '2014-11-06 11:21:19'),
(9, 'brands', 'view', '品牌详情', 1, '', '1', '2014-11-06 13:15:39', '2014-11-06 13:15:39'),
(10, 'contacts', 'index', '联系我们页', 1, '', '1', '2014-11-07 09:09:58', '2014-11-07 09:09:58'),
(11, 'articles', 'category', '文章分类页', 1, '', '1', '2014-11-11 09:06:17', '2014-11-11 09:06:17'),
(12, 'articles', 'video', '视频页', 1, '', '1', '2014-12-25 08:50:54', '2014-12-25 08:50:54');

--
-- 插入之前先把表清空（truncate） `svsys_page_modules`
--

TRUNCATE TABLE `svsys_page_modules`;
--
-- 转存表中的数据 `svsys_page_modules`
--

INSERT INTO `svsys_page_modules` (`id`, `parent_id`, `code`, `page_action_id`, `position`, `type`, `type_id`, `model`, `function`, `parameters`, `file_name`, `css`, `width`, `height`, `float`, `limit`, `orderby_type`, `orderby`, `status`, `created`, `modified`) VALUES
(1, 0, 'amaze_home_flash', 1, 'top', 'module_flash', NULL, 'Flash', 'get_module_infos', '0', '', '', NULL, NULL, '0', 10, 'id', '57', '1', '2014-10-11 14:36:04', '2014-10-11 14:37:14'),
(2, 0, 'amaze_home_topic', 1, 'top', 'module_home_topic', NULL, 'Topic', 'get_module_home_topic', '0', '', '', NULL, NULL, '0', 4, 'created', '58', '1', '2014-10-31 16:19:47', '2014-11-17 09:50:23'),
(3, 0, 'amaze_home_product', 1, 'top', 'module_product', NULL, 'Product', 'get_module_infos', '0', '', '', NULL, NULL, '0', 12, 'created', '59', '1', '2014-11-03 09:49:41', '2014-11-17 09:50:22'),
(4, 34, 'amaze_home_article', 1, 'top', 'module_home_article', NULL, 'Article', 'get_module_home_article', '0', '', '', NULL, NULL, '0', 8, 'created desc', '60', '1', '2014-11-03 10:40:49', '2014-12-22 16:10:15'),
(5, 0, 'amaze_category_list', 3, 'top', 'module_category_list', NULL, 'CategoryProduct', 'get_module_category_pro_list', '0', '', '', NULL, NULL, '1', 10, 'created', '60', '1', '2014-11-03 14:05:06', '2014-11-03 15:02:49'),
(6, 0, 'amaze_category_flash', 3, 'top', 'module_flash', NULL, 'CategoryProduct', 'get_module_category_flash', '0', '', '', NULL, NULL, '0', 3, 'created', '50', '1', '2014-11-03 14:48:24', '2014-11-04 09:56:12'),
(7, 0, 'amaze_category_product', 3, 'right', 'module_category_product', NULL, 'CategoryProduct', 'get_module_category_product', '0', '', '', NULL, NULL, '2', 9, 'created', '61', '1', '2014-11-03 15:08:35', '2014-11-14 16:37:13'),
(8, 0, 'amaze_product_image', 2, 'top', 'module_pro_image', NULL, 'ProductGallery', 'get_module_pro_photos', '0', '', '', NULL, NULL, '1', 4, 'orderby', '50', '1', '2014-11-04 10:21:36', '2014-11-26 16:04:14'),
(9, 0, 'amaze_product_info', 2, 'top', 'module_pro_info', NULL, 'Product', 'get_module_pro_info', '0', '', '', NULL, NULL, '2', 1, 'created', '63', '1', '2014-11-04 14:21:21', '2014-11-04 14:21:21'),
(10, 0, 'amaze_product_detail', 2, 'top', 'module_pro_detail', NULL, 'Product', 'get_module_pro_detail', '0', '', '', NULL, NULL, '0', 10, 'created', '64', '1', '2014-11-04 15:18:06', '2014-11-04 15:18:06'),
(11, 0, 'amaze_product_category', 2, 'top', 'module_pro_category', NULL, 'Product', 'get_module_pro_category', '0', '', '', NULL, NULL, '0', 12, 'created', '65', '1', '2014-11-04 15:54:33', '2014-11-04 15:54:33'),
(12, 0, 'amaze_package_product', 2, 'top', 'module_package_product', NULL, 'Product', 'get_product_package_list', '0', '', '', NULL, NULL, '0', 5, 'created', '66', '1', '2014-11-04 16:25:50', '2014-11-04 16:33:50'),
(13, 0, 'amaze_product_message', 2, 'top', 'module_pro_message', NULL, 'Product', 'get_module_pro_message', '0', '', '', NULL, NULL, '0', 10, 'created', '68', '1', '2014-11-04 16:48:45', '2014-11-04 16:48:45'),
(14, 0, 'amaze_product_comment', 2, 'top', 'module_pro_comment_infos', NULL, 'Product', 'get_module_pro_comment_infos', '0', '', '', NULL, NULL, '0', 5, 'created', '67', '1', '2014-11-05 09:41:10', '2014-11-05 09:41:10'),
(15, 0, 'amaze_all_topic', 4, 'top', 'module_topic', NULL, 'Topic', 'get_module_infos', '0', '', '', NULL, NULL, '0', 4, 'created', '70', '1', '2014-11-05 10:37:56', '2014-11-07 09:54:35'),
(16, 0, 'amaze_topic_article', 4, 'top', 'module_article_recommend', NULL, 'Article', 'get_module_article_recommend', '0', '', '', NULL, NULL, '0', 6, 'created', '71', '1', '2014-11-05 11:42:53', '2014-11-05 11:42:53'),
(17, 0, 'amaze_topic_info', 5, 'top', 'module_topic_info', NULL, 'Topic', 'get_module_topic_info', '0', '', '', NULL, NULL, '0', 1, 'created', '72', '1', '2014-11-05 13:17:50', '2014-11-05 13:17:50'),
(18, 0, 'amaze_topic_product', 5, 'top', 'module_topic_product', NULL, 'Topic', 'get_module_topic_product', '0', '', '', NULL, NULL, '0', 4, 'created', '73', '1', '2014-11-05 13:34:54', '2014-11-05 13:34:54'),
(19, 0, 'amaze_topic_article_info', 5, 'top', 'module_topic_article', NULL, 'Topic', 'get_module_topic_article', '0', '', '', NULL, NULL, '0', 4, 'created', '74', '1', '2014-11-05 13:51:18', '2014-11-05 13:51:18'),
(20, 0, 'amaze_article_list', 6, 'top', 'module_article', NULL, 'Article', 'get_module_infos', '0', '', '', NULL, NULL, '0', 5, 'created', '75', '1', '2014-11-05 14:14:31', '2014-11-07 10:11:30'),
(21, 0, 'amaze_article_category1', 6, 'top', 'module_article_category', NULL, 'CategoryArticle', 'get_article_category_list', '0', '', '', NULL, NULL, '0', 10, 'created', '76', '1', '2014-11-05 16:47:03', '2014-11-05 16:47:03'),
(22, 0, 'amaze_article_detail', 7, 'top', 'module_article_infos', NULL, 'Article', 'get_module_article_infos', '0', '', '', NULL, NULL, '0', 10, 'created', '77', '1', '2014-11-05 17:30:58', '2014-11-12 09:32:57'),
(23, 0, 'amaze_article_category', 7, 'top', 'module_article_category', NULL, 'CategoryArticle', 'get_article_category_list', '0', '', '', NULL, NULL, '0', 10, 'created', '78', '1', '2014-11-06 10:26:02', '2014-11-14 16:01:05'),
(24, 0, 'amaze_article_recommend', 7, 'top', 'module_article_recommend', NULL, 'Article', 'get_module_article_recommend', '0', '', '', NULL, NULL, '0', 4, 'created', '79', '1', '2014-11-06 10:58:38', '2014-11-14 16:01:18'),
(25, 0, 'amaze_brand_index', 8, 'top', 'module_brand', NULL, 'Brand', 'get_module_infos', '0', '', '', NULL, NULL, '0', 8, 'created', '80', '1', '2014-11-06 11:29:20', '2014-11-06 11:29:20'),
(26, 0, 'amaze_brand_category', 9, 'top', 'module_brand_category', NULL, 'Brand', 'get_module_brand_category', '0', '', '', NULL, NULL, '0', 10, 'created', '81', '1', '2014-11-06 13:17:32', '2014-11-06 13:17:32'),
(27, 0, 'amaze_brand_flash', 9, 'top', 'module_brand_flash', NULL, 'Brand', 'get_module_brand_flash', '0', '', '', NULL, NULL, '0', 10, 'created', '82', '1', '2014-11-06 13:45:07', '2014-11-06 13:45:07'),
(28, 0, 'amaze_brand_info', 9, 'top', 'module_brand_info', NULL, 'Brand', 'get_module_brand_infos', '0', '', '', NULL, NULL, '0', 10, 'created', '83', '1', '2014-11-06 13:59:40', '2014-11-06 13:59:40'),
(29, 0, 'amaze_brand_product', 9, 'top', 'module_brand_product', NULL, 'Brand', 'get_module_brand_product', '0', '', '', NULL, NULL, '0', 12, 'Product.created', '84', '1', '2014-11-06 14:18:24', '2014-11-06 14:21:30'),
(30, 0, 'amaze_contact', 10, 'top', 'contact_us', NULL, 'Contact', 'get_contact_sel_list', '0', '', '', NULL, NULL, '0', 10, 'created', '85', '1', '2014-11-07 09:11:53', '2014-11-07 09:11:53'),
(31, 0, 'amaze_article_category_list', 11, 'top', 'module_article_category', NULL, 'CategoryArticle', 'get_article_category_list', '0', '', '', NULL, NULL, '0', 10, 'created', '86', '1', '2014-11-11 09:07:56', '2014-11-11 09:07:56'),
(32, 0, 'amaze_category_article_list', 11, 'top', 'module_category_article', NULL, 'ArticleCategory', 'get_module_infos', '0', '', '', NULL, NULL, '0', 10, 'created', '87', '1', '2014-11-11 09:09:59', '2014-11-11 09:09:59'),
(33, 34, 'amaze_home_recommend_article', 1, 'top', 'module_home_recommend_article', NULL, 'Article', 'get_module_article_recommend', '0', '', '', NULL, NULL, '0', 10, 'created', '89', '1', '2014-11-13 13:52:32', '2014-12-29 14:46:53'),
(34, 0, 'amaze_home_center', 1, 'right', 'module_parent', NULL, '', '', '0', '', '', NULL, NULL, '0', 10, 'created', '88', '1', '2014-11-17 09:17:09', '2014-11-17 09:51:14'),
(35, 0, 'amaze_product_view_log', 2, 'top', 'module_product_view_log', NULL, 'Product', 'pro_view_log', '0', '', '', NULL, NULL, '0', 10, 'created', '69', '1', '2014-12-01 15:32:58', '2014-12-09 17:28:01'),
(36, 0, 'amaze_category_product_view_log', 3, 'right', 'module_product_view_log', NULL, 'Product', 'pro_view_log', '0', '', '', NULL, NULL, '2', 10, 'created', '62', '1', '2014-12-01 15:36:51', '2014-12-09 17:28:38'),
(37, 0, 'amaze_video_show', 12, 'top', 'module_article_video', NULL, 'Article', 'get_module_article_video', '0', '', '', NULL, NULL, '1', 10, 'created', '50', '1', '2014-12-25 08:55:13', '2014-12-25 11:10:06'),
(38, 0, 'amaze_relation_video', 12, 'top', 'module_relation_video', NULL, 'Article', 'get_module_relation_video', '0', '', '', NULL, NULL, '1', 10, 'created desc', '50', '1', '2014-12-25 11:07:15', '2014-12-25 11:10:14'),
(39, 0, 'amaze_video_comment', 12, 'top', 'module_video_comment', NULL, 'Article', 'get_module_video_comment', '0', '', '', NULL, NULL, '1', 10, 'created desc', '50', '1', '2014-12-25 13:15:41', '2014-12-25 13:15:41'),
(40, 0, 'amaze_video_recommend', 12, 'top', 'module_video_recommend', NULL, 'Article', 'get_module_video_recommend', '0', '', '', NULL, NULL, '1', 4, 'created', '50', '1', '2014-12-25 15:06:46', '2014-12-25 15:21:03');

--
-- 插入之前先把表清空（truncate） `svsys_page_module_i18ns`
--

TRUNCATE TABLE `svsys_page_module_i18ns`;
--
-- 转存表中的数据 `svsys_page_module_i18ns`
--

INSERT INTO `svsys_page_module_i18ns` (`id`, `module_id`, `locale`, `name`, `title`, `created`, `modified`) VALUES
(1, 1, 'chi', '轮播', '', '2014-10-11 14:36:04', '2014-10-11 14:37:14'),
(2, 1, 'eng', 'Slide', '', '2014-10-11 14:36:04', '2014-10-11 14:37:14'),
(3, 2, 'chi', '专题', '', '2014-10-31 16:19:47', '2014-10-31 17:52:16'),
(4, 2, 'eng', 'Topic', '', '2014-10-31 16:19:47', '2014-10-31 17:52:16'),
(5, 3, 'chi', '商品', '商品', '2014-11-03 09:49:41', '2014-11-03 09:49:41'),
(6, 3, 'eng', '商品', '商品', '2014-11-03 09:49:41', '2014-11-03 09:49:41'),
(7, 4, 'chi', '最新文章', '最新文章', '2014-11-03 10:40:49', '2014-12-22 16:10:15'),
(8, 4, 'eng', '最新文章', '最新文章', '2014-11-03 10:40:49', '2014-12-22 16:10:15'),
(9, 5, 'chi', '分类列表', '', '2014-11-03 14:05:06', '2014-11-03 15:02:49'),
(10, 5, 'eng', 'category list', '', '2014-11-03 14:05:06', '2014-11-03 15:02:49'),
(11, 6, 'chi', '产品分类轮播', '', '2014-11-03 14:48:24', '2014-11-03 15:00:20'),
(12, 6, 'eng', 'category flash', '', '2014-11-03 14:48:24', '2014-11-03 15:00:20'),
(13, 7, 'chi', '分类产品', '', '2014-11-03 15:08:35', '2014-11-14 16:37:13'),
(14, 7, 'eng', 'category product', '', '2014-11-03 15:08:35', '2014-11-14 16:37:13'),
(15, 8, 'chi', '商品图片', '', '2014-11-04 10:21:36', '2014-11-26 16:04:14'),
(16, 8, 'eng', 'product image', '', '2014-11-04 10:21:36', '2014-11-26 16:04:14'),
(17, 9, 'chi', '商品信息', '', '2014-11-04 14:21:21', '2014-11-04 14:21:21'),
(18, 9, 'eng', 'product info', '', '2014-11-04 14:21:21', '2014-11-04 14:21:21'),
(19, 10, 'chi', '商品详情', '', '2014-11-04 15:18:06', '2014-11-04 15:18:06'),
(20, 10, 'eng', 'product detail', '', '2014-11-04 15:18:06', '2014-11-04 15:18:06'),
(21, 11, 'chi', '分类商品', '', '2014-11-04 15:54:33', '2014-11-04 15:54:33'),
(22, 11, 'eng', 'product category', '', '2014-11-04 15:54:33', '2014-11-04 15:54:33'),
(23, 12, 'chi', '套装商品', '', '2014-11-04 16:25:50', '2014-11-04 16:33:50'),
(24, 12, 'eng', 'package product', '', '2014-11-04 16:25:50', '2014-11-04 16:33:50'),
(25, 13, 'chi', '商品提问', '', '2014-11-04 16:48:45', '2014-11-04 16:48:45'),
(26, 13, 'eng', 'product message', '', '2014-11-04 16:48:45', '2014-11-04 16:48:45'),
(27, 14, 'chi', '商品评论', '', '2014-11-05 09:41:10', '2014-11-05 09:41:10'),
(28, 14, 'eng', 'product comment', '', '2014-11-05 09:41:10', '2014-11-05 09:41:10'),
(29, 15, 'chi', '所有专题', '', '2014-11-05 10:37:56', '2014-11-07 09:54:35'),
(30, 15, 'eng', 'all topic', '', '2014-11-05 10:37:56', '2014-11-07 09:54:35'),
(31, 16, 'chi', '专题推荐文章', '', '2014-11-05 11:42:53', '2014-11-05 11:42:53'),
(32, 16, 'eng', 'topic article', '', '2014-11-05 11:42:53', '2014-11-05 11:42:53'),
(33, 17, 'chi', '专题详情', '专题介绍', '2014-11-05 13:17:50', '2014-11-05 13:17:50'),
(34, 17, 'eng', 'topic info', 'Topic Details', '2014-11-05 13:17:50', '2014-11-05 13:17:50'),
(35, 18, 'chi', '专题关联商品', '', '2014-11-05 13:34:54', '2014-11-05 13:34:54'),
(36, 18, 'eng', 'topic product', '', '2014-11-05 13:34:54', '2014-11-05 13:34:54'),
(37, 19, 'chi', '专题关联文章', '', '2014-11-05 13:51:18', '2014-11-05 13:51:18'),
(38, 19, 'eng', 'topic article', '', '2014-11-05 13:51:18', '2014-11-05 13:51:18'),
(39, 20, 'chi', '文章列表', '', '2014-11-05 14:14:31', '2014-11-07 10:11:30'),
(40, 20, 'eng', 'article list', '', '2014-11-05 14:14:31', '2014-11-07 10:11:30'),
(41, 21, 'chi', '文章分类', '文章分类', '2014-11-05 16:47:03', '2014-11-05 16:47:03'),
(42, 21, 'eng', 'article category', '', '2014-11-05 16:47:03', '2014-11-05 16:47:03'),
(43, 22, 'chi', '文章描述', '', '2014-11-05 17:30:58', '2014-11-05 17:30:58'),
(44, 22, 'eng', 'article detail', '', '2014-11-05 17:30:58', '2014-11-05 17:30:58'),
(45, 23, 'chi', '文章分类', '', '2014-11-06 10:26:02', '2014-11-14 16:01:05'),
(46, 23, 'eng', 'Article Category', '', '2014-11-06 10:26:02', '2014-11-14 16:01:05'),
(47, 24, 'chi', '推荐文章', '', '2014-11-06 10:58:38', '2014-11-14 16:01:18'),
(48, 24, 'eng', 'Recommend Articles', '', '2014-11-06 10:58:38', '2014-11-14 16:01:18'),
(49, 25, 'chi', '品牌列表', '', '2014-11-06 11:29:20', '2014-11-06 11:29:20'),
(50, 25, 'eng', 'Brand List', '', '2014-11-06 11:29:20', '2014-11-06 11:29:20'),
(51, 26, 'chi', '品牌分类', '', '2014-11-06 13:17:32', '2014-11-06 13:17:32'),
(52, 26, 'eng', 'brand category', '', '2014-11-06 13:17:32', '2014-11-06 13:17:32'),
(53, 27, 'chi', '品牌轮播', '', '2014-11-06 13:45:07', '2014-11-06 13:45:07'),
(54, 27, 'eng', 'topic article', '', '2014-11-06 13:45:07', '2014-11-06 13:45:07'),
(55, 28, 'chi', '品牌详情', '', '2014-11-06 13:59:40', '2014-11-06 13:59:40'),
(56, 28, 'eng', 'Brand Info', '', '2014-11-06 13:59:40', '2014-11-06 13:59:40'),
(57, 29, 'chi', '品牌商品', '', '2014-11-06 14:18:24', '2014-11-06 14:21:30'),
(58, 29, 'eng', 'brand product', '', '2014-11-06 14:18:24', '2014-11-06 14:21:30'),
(59, 30, 'chi', '联系我们', '', '2014-11-07 09:11:53', '2014-11-07 09:11:53'),
(60, 30, 'eng', 'contact', '', '2014-11-07 09:11:53', '2014-11-07 09:11:53'),
(61, 31, 'chi', '文章分类', '', '2014-11-11 09:07:56', '2014-11-11 09:07:56'),
(62, 31, 'eng', 'article category', '', '2014-11-11 09:07:56', '2014-11-11 09:07:56'),
(63, 32, 'chi', '分类文章列表', '', '2014-11-11 09:09:59', '2014-11-11 09:09:59'),
(64, 32, 'eng', 'article list', '', '2014-11-11 09:09:59', '2014-11-11 09:09:59'),
(65, 33, 'chi', '推荐文章', '', '2014-11-13 13:52:32', '2014-12-29 14:46:53'),
(66, 33, 'eng', 'recommend article', '', '2014-11-13 13:52:32', '2014-12-29 14:46:53'),
(67, 34, 'eng', 'home center', '', '2014-11-17 09:17:09', '2014-11-17 09:51:14'),
(68, 34, 'chi', '首页居中', '', '2014-11-17 09:17:09', '2014-11-17 09:51:14'),
(69, 35, 'chi', '商品浏览历史', '', '2014-12-01 15:32:58', '2014-12-09 17:28:01'),
(70, 35, 'eng', '商品浏览历史', '', '2014-12-01 15:32:58', '2014-12-09 17:28:01'),
(71, 36, 'chi', '分类商品浏览历史', '', '2014-12-01 15:36:51', '2014-12-09 17:28:38'),
(72, 36, 'eng', '分类商品浏览历史', '', '2014-12-01 15:36:51', '2014-12-09 17:28:39'),
(73, 37, 'chi', '视频播放', '', '2014-12-25 08:55:13', '2014-12-25 11:10:06'),
(74, 37, 'eng', 'video show', '', '2014-12-25 08:55:13', '2014-12-25 11:10:06'),
(75, 38, 'chi', '相关视频', '', '2014-12-25 11:07:15', '2014-12-25 11:10:14'),
(76, 38, 'eng', 'relation video', '', '2014-12-25 11:07:15', '2014-12-25 11:10:14'),
(77, 39, 'chi', '视频评论', '', '2014-12-25 13:15:41', '2014-12-25 13:15:41'),
(78, 39, 'eng', 'video comment', '', '2014-12-25 13:15:41', '2014-12-25 13:15:41'),
(79, 40, 'chi', '推荐视频', '', '2014-12-25 15:06:46', '2014-12-25 15:21:03'),
(80, 40, 'eng', 'video recommend', '', '2014-12-25 15:06:46', '2014-12-25 15:21:03');