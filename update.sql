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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `cms_sms_logs` ADD `state`  TINYINT(1) NOT NULL COMMENT '状态:1成功 2失败' AFTER `message`;

-- -----------
-- 2017-9-5
-- -----------
DROP TABLE IF EXISTS `cms_comments`;
CREATE TABLE `cms_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL COMMENT '站点ID',
  `parent_id` int(10) NOT NULL COMMENT '上级ID',
  `refer_id` int(10) unsigned NOT NULL COMMENT '关联ID',
  `refer_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '关联类型',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '评论内容',
  `likes` int(10) unsigned NOT NULL COMMENT '点赞数',
  `member_id` int(10) NOT NULL COMMENT '会员ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户',
  `ip` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `state` tinyint(1) NOT NULL COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cms_comments_index_1` (`refer_id`,`state`),
  KEY `site_id` (`site_id`),
  KEY `state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -----------
-- 2017-9-6
-- -----------
DROP TABLE IF EXISTS `cms_favorites`;
CREATE TABLE `cms_favorites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL COMMENT '站点ID',
  `refer_id` int(10) unsigned NOT NULL COMMENT '关联ID',
  `refer_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '关联类型',
  `member_id` int(10) NOT NULL COMMENT '会员ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`),
  KEY `content_id` (`refer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- -----------
-- 2017-9-7
-- -----------
ALTER TABLE `cms_comments` ADD `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除日期' AFTER `updated_at`;


#问卷表
CREATE TABLE `cms_surveys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL COMMENT '站点ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `image_url` text NOT NULL COMMENT '封面图',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `state` tinyint(1) NOT NULL COMMENT '状态',
  `amount` int(11) DEFAULT NULL COMMENT '点击量',
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `is_top` tinyint(1) DEFAULT NULL COMMENT '是否推荐到轮播图',
  `likes` int(10) DEFAULT '0' COMMENT '点赞数',
  `multiple` tinyint(1) DEFAULT NULL COMMENT '是否多选',
  `link` varchar(255) DEFAULT NULL COMMENT '外链',
  `member_id` int(10) NOT NULL COMMENT '会员ID',
  `sort` int(11) NOT NULL COMMENT '序号',
  `begin_date` datetime DEFAULT NULL COMMENT '问卷开始时间',
  `end_date` datetime DEFAULT NULL COMMENT '问卷结束时间',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `published_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

#问卷回答连表
CREATE TABLE `cms_survey_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` int(10) unsigned NOT NULL COMMENT '问卷ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '标题',
  `image_url` text COLLATE utf8_unicode_ci NOT NULL COMMENT '图片URL',
  `survey_title_id` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `amount` int(10) unsigned NOT NULL COMMENT '问卷数量',
  `percent` float NOT NULL COMMENT '百分比',
  `sort` int(11) NOT NULL COMMENT '序号',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `published_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `survey_id` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#问卷子标题连表
CREATE TABLE `cms_survey_titles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` int(10) unsigned NOT NULL COMMENT '问卷ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '子标题',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `published_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `survey_id` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#问卷数据记录表
CREATE TABLE `cms_survey_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` int(10) unsigned NOT NULL COMMENT '问卷ID',
  `survey_item_ids` text COLLATE utf8_unicode_ci NOT NULL COMMENT '选项IDS',
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '评论',
  `person_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '姓名',
  `person_mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `avatar_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `member_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '会员名',
  `nick_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称',
  `ip` char(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'IP',
  `sort` int(11) NOT NULL COMMENT '序号',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `published_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `survey_id` (`survey_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




