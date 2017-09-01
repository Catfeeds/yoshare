ALTER TABLE `cms_sites` CHANGE `name` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '站点标题';
ALTER TABLE `cms_sites` ADD `name` VARCHAR(40) NOT NULL COMMENT '英文名称' AFTER `id`;

ALTER TABLE `cms_sites` ADD `domain` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '域名' AFTER `company`;
ALTER TABLE `cms_sites` ADD `directory` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '目录' AFTER `domain`;
ALTER TABLE `cms_sites` CHANGE `desktop_theme_id` `default_theme` INT(10)  unsigned NOT NULL DEFAULT 1 COMMENT '默认主题';
ALTER TABLE `cms_sites` CHANGE `mobile_theme_id` `mobile_theme` INT(10) unsigned NOT NULL DEFAULT 2 COMMENT '移动主题';
ALTER TABLE `cms_sites` CHANGE `app_key` `jpush_app_key` INT(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '极光AppKey';
ALTER TABLE `cms_sites` CHANGE `master_secret` `jpush_app_secret` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '极光AppSecret';
ALTER TABLE `cms_sites` ADD `wechat_app_id` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '微信AppID' AFTER `jpush_app_secret`;
ALTER TABLE `cms_sites` ADD `wechat_secret` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '微信Secret' AFTER `wechat_app_id`;
ALTER TABLE `cms_sites` CHANGE `username` `user_id` INT(10) NOT NULL COMMENT '用户ID';

ALTER TABLE `cms_sites` CHANGE `default_theme` `default_theme_id` INT(10)  unsigned NOT NULL DEFAULT 1 COMMENT '默认主题';
ALTER TABLE `cms_sites` CHANGE `mobile_theme` `mobile_theme_id` INT(10)  unsigned NOT NULL DEFAULT 1 COMMENT '默认主题';
ALTER TABLE `cms_sites` CHANGE `jpush_app_key` `jpush_app_key` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '极光AppKey';

CREATE TABLE `cms_sms_logs`(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned NOT NULL COMMENT '站点ID',
    `mobile` VARCHAR(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
    `message` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '信息',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `site_id` (`site_id`)
);

