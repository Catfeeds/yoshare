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
  `ip` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `member_id` int(10) NOT NULL COMMENT '会员ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户',
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
-- -----------
-- 2017-9-8
-- -----------
ALTER TABLE `cms_comments` ADD INDEX `cms_comments_index_2` (`refer_id`, `refer_type`, `deleted_at`);
ALTER TABLE `cms_comments` ADD INDEX `cms_comments_index_3` (`refer_id`, `refer_type`, `state`, `deleted_at`);
ALTER TABLE `cms_comments` DROP `parent_id`;

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

-- -----------
-- 2017-9-11
-- -----------
DROP TABLE IF EXISTS `cms_questions`;
CREATE TABLE `cms_questions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `category_id` int(10) NOT NULL COMMENT '栏目ID',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '问答类型',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题',
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '问题概述',
  `image_url` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片',
  `video_url` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '视频',
  `images` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片集',
  `videos` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '视频集',
  `member_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `sort` int(11) NOT NULL COMMENT '序号',
  `state` int(11) NOT NULL COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  `published_at` datetime DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cms_module_fields` (`module_id`, `name`, `title`, `label`, `type`, `default`, `required`, `unique`, `min_length`, `max_length`, `system`, `index`, `column_show`, `column_editable`, `column_align`, `column_width`, `column_formatter`, `column_index`, `editor_show`, `editor_readonly`, `editor_type`, `editor_options`, `editor_rows`, `editor_columns`, `editor_group`, `editor_index`, `created_at`, `updated_at`) VALUES
(4, 'id', 'ID', 'ID', 3, '', 0, 0, 0, 0, 1, 1, 1, 0, 1, 30, '', 1, 0, 0, 0, '', 0, 0, '', 0, '2017-08-29 02:32:25', '2017-08-30 08:14:54'),
(4, 'site_id', '站点ID', '站点', 3, '', 0, 0, 0, 0, 1, 2, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-08-29 02:32:25', '2017-09-08 03:27:45'),
(4, 'category_id', '栏目ID', '栏目ID', 3, '', 0, 0, 1, 10, 0, 3, 0, 0, 1, 0, '', 0, 0, 0, 1, '', 1, 11, '基本信息', 0, '2017-09-08 03:11:27', '2017-09-08 10:19:56'),
(4, 'type', '问题类型', '类型', 3, '', 1, 0, 1, 10, 0, 4, 0, 0, 1, 0, '', 0, 1, 0, 3, '', 1, 3, '基本信息', 4, '2017-09-08 03:26:07', '2017-09-08 09:33:10'),
(4, 'title', '标题', '标题', 1, '''', 1, 0, 0, 0, 0, 4, 0, 0, 1, 0, '''', 0, 0, 0, 1, '''', 1, 11, '基本信息', 0, '2017-09-13 06:57:04', '2017-09-13 06:57:19'),
(4, 'state', '状态', '状态', 1, '', 1, 0, 0, 0, 0, 6, 1, 0, 2, 45, 'stateFormatter', 3, 0, 0, 0, '', 0, 0, '', 0, '2017-08-29 02:32:25', '2017-08-30 08:14:32'),
(4, 'summary', '问题概要', '问题概要', 1, '', 1, 0, 0, 0, 0, 7, 1, 0, 1, 0, '', 2, 1, 0, 1, '', 1, 11, '基本信息', 5, '2017-08-29 02:34:32', '2017-09-08 08:58:45'),
(4, 'image_url', '图片', '图片地址', 1, '', 0, 0, 0, 0, 0, 10, 0, 0, 1, 0, '', 0, 1, 0, 8, '', 1, 11, '基本信息', 6, '2017-09-05 08:44:49', '2017-09-08 09:56:29'),
(4, 'video_url', '视频地址', '视频', 1, '', 0, 0, 0, 0, 0, 11, 0, 0, 1, 0, '', 0, 1, 0, 10, '', 1, 11, '基本信息', 7, '2017-09-08 03:27:32', '2017-09-08 09:57:17'),
(4, 'member_id', '会员ID', '会员ID', 3, '', 0, 0, 1, 10, 0, 12, 0, 0, 1, 0, '', 0, 0, 0, 1, '', 1, 11, '基本信息', 0, '2017-09-08 03:30:21', '2017-09-08 03:30:21'),
(4, 'user_id', '后台用户ID', '用户ID', 3, '', 0, 0, 0, 10, 0, 13, 0, 0, 1, 0, '', 0, 0, 0, 1, '', 1, 11, '基本信息', 0, '2017-09-08 03:31:26', '2017-09-08 03:31:26'),
(4, 'images', '图片集', '图片集', 11, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 1, 0, 11, '', 1, 11, '图片集', 0, '2017-06-20 16:00:00', '2017-06-20 16:00:00'),
(4, 'videos', '视频', '视频', 13, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 1, 0, 13, '', 1, 11, '视频集', 0, '2017-06-20 16:00:00', '2017-06-20 16:00:00'),
(4, 'sort', '序号', '序号', 3, '', 0, 0, 0, 0, 1, 91, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-08-29 02:32:25', '2017-08-30 08:14:30'),
(4, 'created_at', '创建时间', '创建时间', 5, '', 0, 0, 0, 0, 1, 93, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-08-29 02:32:25', '2017-08-30 08:14:35'),
(4, 'updated_at', '修改时间', '修改时间', 5, '', 0, 0, 0, 0, 1, 94, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-08-29 02:32:25', '2017-08-30 08:14:37'),
(4, 'deleted_at', '删除时间', '删除时间', 5, '', 0, 0, 0, 0, 1, 95, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-08-29 02:32:25', '2017-08-30 08:14:42'),
(4, 'published_at', '发布时间', '发布时间', 5, '', 0, 0, 0, 0, 1, 96, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-08-29 02:32:25', '2017-08-30 08:14:46');

-- -----------
-- 2017-9-12
-- -----------
CREATE TABLE `cms_user_site` (
  `user_id` int(10) NOT NULL,
  `site_id` int(10) NOT NULL,
  PRIMARY KEY (`user_id`,`site_id`) USING BTREE,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`);
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



