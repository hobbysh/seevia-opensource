--
--	V0.8����
--

ALTER TABLE `svcms_navigations` CHANGE `controller` `controller` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pages' COMMENT 'ϵͳ����';
ALTER TABLE `svoms_products` ADD `unit` VARCHAR( 20 ) NULL COMMENT '��λ' AFTER `weight` ;
DROP TABLE IF EXISTS `svedi_sms_send_histories`;
DROP TABLE IF EXISTS `svedi_sms_send_queues`;