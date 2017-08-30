ALTER TABLE `cms_contents` ADD `views` INT(10) UNSIGNED NOT NULL COMMENT '阅读数' AFTER `clicks`;
ALTER TABLE `cms_contents` ADD `data` TEXT NOT NULL COMMENT 'JSON数据' AFTER `memo`;
ALTER TABLE `cms_contents` ADD `member_id` INT UNSIGNED NOT NULL COMMENT '会员ID' AFTER `sort`;
ALTER TABLE `cms_contents` ADD `parent_id` INT UNSIGNED NOT NULL COMMENT '上级ID' AFTER `type`;
ALTER TABLE `cms_contents` ADD `sync_id` INT UNSIGNED NOT NULL COMMENT '同步ID' AFTER `sort`;
update `cms_contents` set `sync_id` = `id`;

CREATE TABLE `cms_keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL COMMENT '站点id',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `times` int(10) unsigned NOT NULL COMMENT '使用次数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `cms_categories` CHANGE `type` `link_type` TINYINT(1) NOT NULL COMMENT '外链类型';
ALTER TABLE `cms_categories` ADD `link` VARCHAR(255) NOT NULL COMMENT '外链' AFTER `link_type`;
ALTER TABLE `cms_categories` ADD `slug` VARCHAR(40) NOT NULL COMMENT '网址缩略名' AFTER `parent_id`;

ALTER TABLE `cms_contents` ADD `video_duration` VARCHAR(20) NOT NULL COMMENT '视频时长' AFTER `live_url`;

ALTER TABLE `zsnc`.`cms_contents` ADD INDEX `cms_contents_index_5` (`site_id`, `category_id`, `deleted_at`);

DROP TABEL `cms_sites`;
CREATE TABLE `cms_sibtes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '站点名称',
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '单位名称',
  `desktop_theme` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '桌面端主题',
  `mobile_theme` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '移动端主题',
  `app_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `master_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci 

ALTER TABLE `cms_sites` CHANGE `company` `title` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '标题';
ALTER TABLE `cms_sites` ADD `directory` VARCHAR(100)  COLLATE utf8_unicode_ci NOT NULL COMMENT '目录';
ALTER TABLE `cms_sites` ADD `domain` VARCHAR(100)  COLLATE utf8_unicode_ci NOT NULL COMMENT '域名';
ALTER TABLE `cms_sites` CHANGE `desktop_theme` `default_theme` INT(10)  unsigned NOT NULL DEFAULT 1 COMMENT '默认主题';
ALTER TABLE `cms_sites` CHANGE `mobile_theme` `mobile_theme` INT(10) unsigned NOT NULL DEFAULT 1 COMMENT '移动主题';
ALTER TABLE `cms_sites` CHANGE `app_key` `jpush_app_key` INT(255)  COLLATE utf8_unicode_ci NOT NULL COMMENT '极光AppKey';
ALTER TABLE `cms_sites` CHANGE `master_secret` `jpus_app_secret` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '极光AppSecret';
ALTER TABLE `cms_sites` ADD `wechat_app_id` VARCHAR(255)  COLLATE utf8_unicode_ci NOT NULL COMMENT '微信AppID';
ALTER TABLE `cms_sites` ADD `wechat_secret` VARCHAR(255)  COLLATE utf8_unicode_ci NOT NULL COMMENT '微信Secret';



