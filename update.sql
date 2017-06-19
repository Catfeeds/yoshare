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