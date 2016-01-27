--
--	V0.8升级
--

ALTER TABLE `svcms_navigations` CHANGE `controller` `controller` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pages' COMMENT '系统内容';
ALTER TABLE `svoms_products` ADD `unit` VARCHAR( 20 ) NULL COMMENT '单位' AFTER `weight` ;
DROP TABLE IF EXISTS `svedi_sms_send_histories`;
DROP TABLE IF EXISTS `svedi_sms_send_queues`;