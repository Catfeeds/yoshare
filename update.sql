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

-- -----------
-- 2017-9-11
-- -----------
DROP TABLE IF EXISTS `cms_questions`;
CREATE TABLE `cms_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `images` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图集',
  `videos` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '视频集',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `sort` int(11) NOT NULL COMMENT '序号',
  `state` int(11) NOT NULL COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  `published_at` datetime DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------
-- 2017-9-12
-- -----------
CREATE TABLE `cms_user_sites` (
  `user_id` int(10) NOT NULL,
  `site_id` int(10) NOT NULL,
  PRIMARY KEY (`user_id`,`site_id`) USING BTREE,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `cms_modules` WHERE `id` = 3;
INSERT INTO `cms_modules` (`id`, `name`, `title`, `table_name`, `groups`, `is_lock`, `icon`, `state`, `created_at`, `updated_at`)
VALUES
	(3, 'Comment', '评论', 'comments', '', 0, 'fa-comment', 1, '2017-09-14 15:06:32', '2017-09-14 15:06:32');

DELETE FROM `cms_module_fields` WHERE `module_id` = 3;
INSERT INTO `cms_module_fields` (`module_id`, `name`, `title`, `label`, `type`, `default`, `required`, `unique`, `min_length`, `max_length`, `system`, `index`, `column_show`, `column_editable`, `column_align`, `column_width`, `column_formatter`, `column_index`, `editor_show`, `editor_readonly`, `editor_type`, `editor_options`, `editor_rows`, `editor_columns`, `editor_group`, `editor_index`, `created_at`, `updated_at`)
VALUES
	(3, 'id', 'ID', 'ID', 3, '', 0, 0, 0, 0, 1, 0, 1, 0, 1, 30, '', 1, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:06:32', '2017-09-14 15:06:32'),
	(3, 'site_id', '站点ID', '站点', 3, '', 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:06:32', '2017-09-14 15:06:32'),
	(3, 'refer_id', '关联ID', '关联ID', 3, '', 1, 0, 0, 0, 0, 2, 0, 0, 1, 0, '', 0, 0, 0, 1, '', 1, 11, '', 0, '2017-09-14 15:07:29', '2017-09-14 15:07:29'),
	(3, 'refer_type', '关联类型', '关联类型', 1, '', 1, 0, 0, 0, 0, 3, 0, 0, 1, 0, '', 0, 0, 0, 1, '', 1, 11, '', 0, '2017-09-14 15:08:00', '2017-09-14 15:08:00'),
	(3, 'content', '内容', '内容', 1, '', 1, 0, 0, 0, 0, 4, 0, 0, 1, 0, '', 0, 0, 0, 1, '', 1, 11, '', 0, '2017-09-14 15:10:07', '2017-09-14 15:10:07'),
	(3, 'member_id', '会员ID', '会员', 7, '', 0, 0, 0, 0, 1, 91, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:06:32', '2017-09-14 15:06:32'),
	(3, 'user_id', '用户ID', '用户', 7, '', 0, 0, 0, 0, 1, 92, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:06:32', '2017-09-14 15:06:32'),
	(3, 'sort', '序号', '序号', 3, '', 0, 0, 0, 0, 1, 93, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:06:32', '2017-09-14 15:06:32'),
	(3, 'state', '状态', '状态', 3, '', 0, 0, 0, 0, 1, 94, 1, 0, 2, 45, 'stateFormatter', 9, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:06:32', '2017-09-14 15:06:32'),
	(3, 'created_at', '创建时间', '创建时间', 5, '', 0, 0, 0, 0, 1, 95, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:06:32', '2017-09-14 15:06:32'),
	(3, 'updated_at', '修改时间', '修改时间', 5, '', 0, 0, 0, 0, 1, 96, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:06:32', '2017-09-14 15:06:32'),
	(3, 'deleted_at', '删除时间', '删除时间', 5, '', 0, 0, 0, 0, 1, 97, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:06:32', '2017-09-14 15:06:32');

DELETE FROM `cms_modules` WHERE `id` = 4;
INSERT INTO `cms_modules` (`id`, `name`, `title`, `table_name`, `groups`, `is_lock`, `icon`, `state`, `created_at`, `updated_at`) VALUES
(4, 'Question', '问答', 'questions', '基本信息,图片集,视频集', 0, 'fa-question-circle', 1, '2017-08-29 02:32:25', '2017-09-13 07:16:26');

INSERT INTO `cms_module_fields` (`module_id`, `name`, `title`, `label`, `type`, `default`, `required`, `unique`, `min_length`, `max_length`, `system`, `index`, `column_show`, `column_editable`, `column_align`, `column_width`, `column_formatter`, `column_index`, `editor_show`, `editor_readonly`, `editor_type`, `editor_options`, `editor_rows`, `editor_columns`, `editor_group`, `editor_index`, `created_at`, `updated_at`)
VALUES
	(4, 'id', 'ID', 'ID', 3, '', 0, 0, 0, 0, 1, 0, 1, 0, 1, 30, '', 1, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:20:25', '2017-09-14 15:20:25'),
	(4, 'site_id', '站点ID', '站点', 3, '', 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:20:25', '2017-09-14 15:20:25'),
	(4, 'title', '标题', '标题', 1, '', 1, 0, 0, 0, 0, 2, 1, 0, 1, 300, '', 2, 1, 0, 1, '', 1, 11, '基本信息', 0, '2017-09-14 15:21:03', '2017-09-14 15:31:47'),
	(4, 'content', '内容', '内容', 1, '', 0, 0, 0, 0, 0, 3, 0, 0, 1, 0, '', 0, 1, 0, 2, '', 6, 11, '基本信息', 0, '2017-09-14 15:21:53', '2017-09-14 15:21:53'),
	(4, 'images', '图集', '图集', 11, '', 0, 0, 0, 0, 0, 4, 0, 0, 1, 0, '', 0, 1, 0, 11, '', 1, 11, '图片集', 0, '2017-09-14 15:22:22', '2017-09-14 15:22:22'),
	(4, 'videos', '视频集', '视频集', 13, '', 0, 0, 0, 0, 0, 5, 0, 0, 1, 0, '', 0, 1, 0, 13, '', 1, 11, '视频集', 0, '2017-09-14 15:22:45', '2017-09-14 15:22:45'),
	(4, 'member_id', '会员ID', '会员', 7, '', 0, 0, 0, 0, 1, 91, 1, 0, 2, 45, '', 3, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:20:25', '2017-09-14 15:31:55'),
	(4, 'user_id', '用户ID', '用户', 7, '', 0, 0, 0, 0, 1, 92, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:20:25', '2017-09-14 15:20:25'),
	(4, 'sort', '序号', '序号', 3, '', 0, 0, 0, 0, 1, 93, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:20:25', '2017-09-14 15:20:25'),
	(4, 'state', '状态', '状态', 3, '', 0, 0, 0, 0, 1, 94, 1, 0, 2, 45, 'stateFormatter', 9, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:20:25', '2017-09-14 15:20:25'),
	(4, 'created_at', '创建时间', '创建时间', 5, '', 0, 0, 0, 0, 1, 95, 1, 0, 2, 90, '', 10, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:20:25', '2017-09-14 15:32:03'),
	(4, 'updated_at', '修改时间', '修改时间', 5, '', 0, 0, 0, 0, 1, 96, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:20:25', '2017-09-14 15:20:25'),
	(4, 'deleted_at', '删除时间', '删除时间', 5, '', 0, 0, 0, 0, 1, 97, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:20:25', '2017-09-14 15:20:25'),
	(4, 'published_at', '发布时间', '发布时间', 5, '', 0, 0, 0, 0, 1, 98, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-09-14 15:20:25', '2017-09-14 15:20:25');

-- -----------
-- 2017-9-14
-- -----------
CREATE TABLE `cms_user_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL COMMENT '站点ID',
  `refer_id` int(10) unsigned NOT NULL COMMENT '关联ID',
  `refer_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '关联类型',
  `action` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作',
  `ip` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'IP地址',
  `user_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -----------
-- 2017-9-15
-- -----------
CREATE TABLE `cms_likes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `refer_id` int(10) unsigned NOT NULL COMMENT '内容ID',
  `refer_type` varchar(255) DEFAULT NULL,
  `count` int(10) unsigned NOT NULL COMMENT ' 会员ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cms_clicks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `refer_id` int(10) unsigned NOT NULL COMMENT '内容ID',
  `refer_type` varchar(255) DEFAULT NULL,
  `count` int(10) unsigned NOT NULL COMMENT ' 会员ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1148 DEFAULT CHARSET=utf8;

-- -----------
-- 2017-9-15
-- -----------
DROP TABLE IF EXISTS `cms_menus`;
CREATE TABLE `cms_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL COMMENT '站点ID',
  `parent_id` int(10) unsigned NOT NULL COMMENT '上级ID',
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '英文名称',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '中文名称',
  `permission` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '权限',
  `icon` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `sort` int(10) unsigned NOT NULL COMMENT '序号',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO `cms_menus` (`id`, `site_id`, `parent_id`, `name`, `url`, `permission`, `icon`, `sort`, `created_at`, `updated_at`)
VALUES
	(1, 1, 0, '内容管理', '#', '', 'fa-edit', 0, '2017-08-16 15:42:05', '2017-08-17 16:11:50'),
	(2, 1, 1, '文章管理', '/admin/articles', '@article', 'fa-file-o', 0, '2017-08-16 15:43:03', '2017-09-04 11:49:26'),
	(3, 1, 1, '单页管理', '/admin/pages', '', 'fa-file-o', 1, '2017-08-22 16:08:06', '2017-09-04 11:49:26'),
	(4, 1, 1, '问答管理', '/admin/questions', '@question', 'fa-question-circle', 2, '2017-08-28 15:45:45', '2017-09-15 15:16:13'),
	(5, 1, 1, '评论管理', '/admin/comments', '@comment', 'fa-comment-o', 3, '2017-08-16 16:52:47', '2017-09-15 15:16:13'),
	(6, 1, 0, '会员管理', '#', '', 'fa-user', 1, '2017-08-17 16:05:29', '2017-08-17 16:05:57'),
	(7, 1, 6, '会员管理', '/admin/members', '@member', 'fa-user-o', 0, '2017-08-17 16:05:53', '2017-08-17 16:08:58'),
	(8, 1, 0, '日志查询', '#', '', 'fa-calendar', 2, '2017-08-18 10:32:04', '2017-08-18 10:33:12'),
	(9, 1, 8, '操作日志', '/admin/users/logs', '', 'fa-user-o', 0, '2017-09-15 15:16:10', '2017-09-15 15:24:13'),
	(10, 1, 8, '推送日志', '/admin/push/logs', '', 'fa-envelope-o', 1, '2017-08-18 10:33:10', '2017-09-15 15:24:13'),
	(11, 1, 8, '短信日志', '/admin/sms/logs', '', 'fa-commenting-o', 2, '2017-09-04 11:49:21', '2017-09-15 15:24:13');

CREATE TABLE `cms_follows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `refer_id` int(10) unsigned NOT NULL COMMENT '内容ID',
  `refer_type` varchar(255) DEFAULT NULL,
  `member_id` int(10) unsigned NOT NULL COMMENT ' 会员ID',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------
-- 2017-9-15
-- -----------
ALTER TABLE `cms_files` RENAME `cms_items`;

TRUNCATE TABLE `cms_sites`;
INSERT INTO `cms_sites` (`id`, `name`, `title`, `company`, `domain`, `directory`, `default_theme_id`, `mobile_theme_id`, `jpush_app_key`, `jpush_app_secret`, `wechat_app_id`, `wechat_secret`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'nnbwg', '南宁博物馆', '南宁博物馆', 'cms.dev.asia-cloud.com', 'sites/nnbwg', 1, 2, '0', '', '', '', 2, '2016-07-31 16:00:00', '2017-09-13 07:50:36'),
(2, 'zsgy', '众思高远', '', 'zsgy.dev.asia-cloud.com', 'sites/zsfy', 1, 2, '', '', '', '', 2, '2017-09-16 10:23:03', '2017-09-16 10:23:03');

ALTER TABLE `cms_user_sites` RENAME `cms_site_user`;
TRUNCATE TABLE cms_site_user;
INSERT INTO `cms_site_user` (`user_id`, `site_id`)
VALUES
	(1, 1),
	(1, 2);

ALTER TABLE `cms_items` ADD `string1` TEXT NOT NULL COMMENT '字符串扩展字段' AFTER `summary`;
ALTER TABLE `cms_items` ADD `string2` TEXT NOT NULL COMMENT '字符串扩展字段' AFTER `string1`;
ALTER TABLE `cms_items` ADD  `string3` TEXT NOT NULL COMMENT '字符串扩展字段' AFTER `string2`;
ALTER TABLE `cms_items` ADD  `string4` TEXT NOT NULL COMMENT '字符串扩展字段' AFTER `string3`;
ALTER TABLE `cms_items` ADD  `string5` TEXT NOT NULL COMMENT '字符串扩展字段' AFTER `string4`;
ALTER TABLE `cms_items` ADD  `integer1` INT(10) NOT NULL COMMENT '整数扩展字段' AFTER `string5`;
ALTER TABLE `cms_items` ADD  `integer2` INT(10) NOT NULL COMMENT '整数扩展字段' AFTER `integer1`;
ALTER TABLE `cms_items` ADD  `integer3` INT(10) NOT NULL COMMENT '整数扩展字段' AFTER `integer2`;
ALTER TABLE `cms_items` ADD  `integer4` INT(10) NOT NULL COMMENT '整数扩展字段' AFTER `integer3`;
ALTER TABLE `cms_items` ADD  `integer5` INT(10) NOT NULL COMMENT '整数扩展字段' AFTER `integer4`;
ALTER TABLE `cms_items` ADD  `float1` FLOAT(12,2) NOT NULL COMMENT '浮点数扩展字段' AFTER `integer5`;
ALTER TABLE `cms_items` ADD  `float2` FLOAT(12,2) NOT NULL COMMENT '浮点数扩展字段' AFTER `float1`;
ALTER TABLE `cms_items` ADD  `float3` FLOAT(12,2) NOT NULL COMMENT '浮点数扩展字段' AFTER `float2`;
ALTER TABLE `cms_items` ADD  `float4` FLOAT(12,2) NOT NULL COMMENT '浮点数扩展字段' AFTER `float3`;
ALTER TABLE `cms_items` ADD  `float5` FLOAT(12,2) NOT NULL COMMENT '浮点数扩展字段' AFTER `float4`;

ALTER TABLE `cms_categories` ADD `type` TINYINT(1) NOT NULL COMMENT '栏目类型' AFTER `module_id`;

-- -----------
-- 2017-9-18
-- -----------
#cms_surveys
INSERT INTO `cms_modules` VALUES ('5', 'Survey', '问卷', 'surveys', '问卷管理', '0', 'fa-bookmark', '1', '2017-09-04 16:55:26', '2017-09-04 17:04:20');

INSERT INTO `cms_module_fields` VALUES ('', '5', 'id', 'ID', 'ID', '3', '', '0', '0', '0', '0', '1', '0', '1', '0', '1', '30', '', '0', '0', '0', '0', '', '0', '0', '', '0', '2017-09-04 16:55:26', '2017-09-05 17:01:54');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'site_id', '站点ID', '站点', '3', '', '0', '0', '0', '0', '1', '1', '0', '0', '0', '0', '', '0', '0', '0', '0', '', '0', '0', '', '0', '2017-09-04 16:55:26', '2017-09-04 16:55:26');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'member_id', '会员ID', '会员', '7', '', '0', '0', '0', '0', '1', '90', '0', '0', '0', '0', '', '0', '0', '0', '0', '', '0', '0', '', '0', '2017-09-04 16:55:26', '2017-09-13 14:46:04');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'user_id', '用户ID', '用户', '7', '', '0', '0', '0', '0', '1', '89', '0', '0', '0', '0', '', '0', '0', '0', '0', '', '0', '0', '', '0', '2017-09-04 16:55:26', '2017-09-13 14:45:52');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'sort', '序号', '序号', '3', '', '0', '0', '0', '0', '1', '91', '0', '0', '0', '0', '', '0', '0', '0', '0', '', '0', '0', '', '0', '2017-09-04 16:55:26', '2017-09-13 14:44:01');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'state', '状态', '状态', '3', '', '0', '0', '0', '0', '1', '94', '1', '0', '2', '45', 'stateFormatter', '9', '0', '0', '0', '', '0', '0', '', '0', '2017-09-04 16:55:26', '2017-09-04 16:55:26');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'created_at', '创建时间', '创建时间', '5', '', '0', '0', '0', '0', '1', '95', '0', '0', '0', '0', '', '0', '0', '0', '0', '', '0', '0', '', '0', '2017-09-04 16:55:26', '2017-09-04 16:55:26');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'updated_at', '修改时间', '修改时间', '5', '', '0', '0', '0', '0', '1', '96', '0', '0', '0', '0', '', '0', '0', '0', '0', '', '0', '0', '', '0', '2017-09-04 16:55:26', '2017-09-04 16:55:26');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'deleted_at', '删除时间', '删除时间', '5', '', '0', '0', '0', '0', '1', '97', '0', '0', '0', '0', '', '0', '0', '0', '0', '', '0', '0', '', '0', '2017-09-04 16:55:26', '2017-09-04 16:55:26');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'published_at', '发布时间', '发布时间', '5', '', '0', '0', '0', '0', '1', '98', '0', '0', '0', '0', '', '0', '0', '0', '0', '', '0', '0', '', '0', '2017-09-04 16:55:26', '2017-09-04 16:55:26');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'title', '标题', '标题', '1', '', '0', '0', '0', '0', '0', '2', '1', '0', '1', '0', '', '0', '1', '0', '1', '', '1', '11', '问卷管理', '0', '2017-09-05 14:40:00', '2017-09-05 17:03:58');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'image_url', '缩略图', '缩略图', '1', '', '0', '0', '0', '0', '0', '3', '0', '0', '1', '0', '', '0', '1', '0', '1', '', '1', '11', '问卷管理', '0', '2017-09-05 16:48:30', '2017-09-05 17:04:20');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'description', '描述', '描述', '1', '', '0', '0', '0', '0', '0', '4', '0', '0', '1', '0', '', '0', '0', '0', '1', '', '1', '11', '问卷管理', '0', '2017-09-05 16:49:02', '2017-09-05 16:49:02');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'amount', '点击量', '点击量', '3', '', '0', '0', '0', '0', '0', '5', '1', '1', '2', '0', '', '0', '0', '0', '1', '', '1', '11', '问卷管理', '0', '2017-09-05 16:51:11', '2017-09-05 17:02:38');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'username', '用户名', '用户名', '1', '', '0', '0', '0', '0', '0', '6', '0', '0', '1', '0', '', '0', '0', '0', '1', '', '1', '11', '问卷管理', '0', '2017-09-05 16:51:43', '2017-09-05 16:51:43');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'is_top', '是否推荐到轮播图', '是否推荐到轮播图', '3', '0', '0', '0', '0', '0', '0', '7', '0', '0', '1', '0', '', '0', '0', '0', '1', '', '1', '11', '问卷管理', '0', '2017-09-05 16:52:33', '2017-09-05 16:52:33');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'likes', '点赞数', '点赞数', '1', '0', '0', '0', '0', '0', '0', '8', '0', '0', '1', '0', '', '0', '0', '0', '1', '', '1', '11', '问卷管理', '0', '2017-09-05 16:52:53', '2017-09-05 16:56:09');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'multiple', '是否多选', '是否多选', '1', '', '0', '0', '0', '0', '0', '9', '0', '0', '1', '0', '', '0', '1', '0', '3', '', '1', '1', '问卷管理', '0', '2017-09-05 16:53:07', '2017-09-05 17:10:13');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'link', '外链', '外链', '1', '', '0', '0', '0', '0', '0', '10', '0', '0', '1', '0', '', '0', '0', '0', '1', '', '1', '11', '问卷管理', '0', '2017-09-05 16:54:17', '2017-09-05 16:54:17');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'begin_date', '问卷开始时间', '问卷开始时间', '5', '', '0', '0', '0', '0', '0', '92', '1', '0', '2', '0', '', '0', '0', '0', '1', '', '1', '11', '问卷管理', '0', '2017-09-05 16:54:50', '2017-09-13 14:43:52');
INSERT INTO `cms_module_fields` VALUES ('', '5', 'end_date', '问卷结束时间', '问卷结束时间', '5', '', '0', '0', '0', '0', '0', '93', '1', '0', '2', '0', '', '0', '0', '0', '1', '', '1', '11', '问卷管理', '0', '2017-09-05 16:55:19', '2017-09-13 14:43:08');

# survey_items
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

# survey_title
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

# survey_data
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
-- 2017-9-19
-- -----------
DELETE FROM `cms_module_fields` WHERE module_id = 1;
INSERT INTO `cms_module_fields` (`module_id`, `name`, `title`, `label`, `type`, `default`, `required`, `unique`, `min_length`, `max_length`, `system`, `index`, `column_show`, `column_editable`, `column_align`, `column_width`, `column_formatter`, `column_index`, `editor_show`, `editor_readonly`, `editor_type`, `editor_options`, `editor_rows`, `editor_columns`, `editor_group`, `editor_index`, `created_at`, `updated_at`)
VALUES
	(1, 'id', 'ID', 'ID', 3, '', 0, 0, 0, 0, 1, 1, 1, 0, 1, 30, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-08-10 16:21:51'),
	(1, 'site_id', '站点ID', '站点', 3, '', 0, 0, 0, 0, 1, 2, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-08-10 16:20:53'),
	(1, 'category_id', '栏目ID', '栏目', 3, '', 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(1, 'type', '类型', '类型', 3, '', 0, 0, 0, 0, 0, 4, 0, 0, 0, 0, '', 0, 1, 0, 3, '小图,多图,大图', 1, 2, '基本信息', 3, '2017-06-21 00:00:00', '2017-09-19 11:37:29'),
	(1, 'title', '标题', '标题', 1, '', 1, 0, 0, 0, 0, 5, 1, 0, 1, 300, '', 2, 1, 0, 1, '', 1, 11, '基本信息', 1, '2017-06-21 00:00:00', '2017-08-18 10:45:19'),
	(1, 'subtitle', '副标题', '副标题', 1, '', 0, 0, 0, 0, 0, 6, 0, 0, 1, 0, '', 0, 1, 0, 1, '', 1, 11, '基本信息', 2, '2017-09-19 11:05:51', '2017-09-19 11:05:51'),
	(1, 'link_type', '外链类型', '外链类型', 3, '', 0, 0, 0, 0, 0, 7, 0, 0, 1, 0, '', 0, 1, 0, 3, '页面,栏目', 1, 2, '基本信息', 4, '2017-09-19 11:28:05', '2017-09-19 11:38:30'),
	(1, 'link', '外链', '外链', 1, '', 0, 0, 0, 0, 0, 8, 0, 0, 1, 0, '', 0, 1, 0, 1, '', 1, 5, '基本信息', 5, '2017-09-19 11:32:09', '2017-09-19 11:37:16'),
	(1, 'author', '作者', '作者', 1, '', 0, 0, 0, 0, 0, 9, 0, 0, 1, 0, '', 0, 1, 0, 1, '', 1, 2, '基本信息', 6, '2017-09-19 11:29:39', '2017-09-19 11:37:58'),
	(1, 'origin', '内容来源', '内容来源', 1, '', 0, 0, 0, 0, 0, 10, 0, 0, 1, 0, '', 0, 1, 0, 1, '', 1, 2, '基本信息', 7, '2017-09-19 11:30:09', '2017-09-19 11:38:11'),
	(1, 'keywords', '关键字', '关键字', 1, '', 0, 0, 0, 0, 0, 11, 0, 0, 1, 0, '', 0, 1, 0, 1, '', 1, 5, '基本信息', 8, '2017-09-19 11:30:59', '2017-09-19 11:36:27'),
	(1, 'summary', '摘要', '摘要', 1, '', 0, 0, 0, 0, 0, 12, 0, 0, 0, 0, '', 0, 1, 0, 2, '', 4, 11, '基本信息', 9, '2017-06-21 00:00:00', '2017-09-19 11:36:29'),
	(1, 'image_url', '缩略图', '缩略图', 8, '', 0, 0, 0, 0, 0, 13, 0, 0, 0, 0, '', 0, 1, 0, 8, '', 1, 11, '基本信息', 10, '2017-06-21 00:00:00', '2017-09-19 11:36:32'),
	(1, 'video_url', '视频', '视频', 10, '', 0, 0, 0, 0, 0, 14, 0, 0, 1, 0, '', 0, 1, 0, 10, '', 1, 11, '基本信息', 11, '2017-09-19 11:33:06', '2017-09-19 11:36:35'),
	(1, 'images', '图片集', '图片集', 11, '', 0, 0, 0, 0, 0, 15, 0, 0, 0, 0, '', 0, 1, 0, 11, '', 1, 11, '图片集', 12, '2017-06-21 00:00:00', '2017-09-19 11:36:39'),
	(1, 'videos', '视频集', '视频集', 13, '', 0, 0, 0, 0, 0, 16, 0, 0, 0, 0, '', 0, 1, 0, 13, '', 1, 11, '视频集', 13, '2017-06-21 00:00:00', '2017-09-19 11:36:42'),
	(1, 'content', '正文', '正文', 6, '', 0, 0, 0, 0, 0, 17, 0, 0, 0, 0, '', 0, 1, 0, 6, '', 40, 12, '正文', 14, '2017-06-21 00:00:00', '2017-09-19 11:36:44'),
	(1, 'top', '是否置顶', '是否置顶', 3, '0', 0, 0, 0, 0, 0, 90, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-09-19 11:35:51'),
	(1, 'member_id', '会员ID', '会员', 7, '', 0, 0, 0, 0, 0, 91, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(1, 'user_id', '用户ID', '操作员', 7, '', 0, 0, 0, 0, 0, 92, 1, 0, 2, 45, '', 4, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-08-10 14:04:29'),
	(1, 'sort', '序号', '序号', 3, '0', 0, 0, 0, 0, 0, 93, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(1, 'state', '状态', '状态', 3, '1', 0, 0, 0, 0, 0, 94, 1, 0, 2, 45, 'stateFormatter', 5, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-08-10 14:04:38'),
	(1, 'created_at', '创建时间', '创建时间', 5, '', 0, 0, 0, 0, 1, 95, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(1, 'updated_at', '修改时间', '修改时间', 5, '', 0, 0, 0, 0, 1, 96, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(1, 'deleted_at', '删除时间', '删除时间', 5, '', 0, 0, 0, 0, 1, 97, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(1, 'published_at', '发布时间', '发布时间', 5, '', 0, 0, 0, 0, 0, 98, 1, 0, 2, 120, '', 3, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00');

DELETE FROM `cms_module_fields` WHERE module_id = 2;
INSERT INTO `cms_module_fields` (`module_id`, `name`, `title`, `label`, `type`, `default`, `required`, `unique`, `min_length`, `max_length`, `system`, `index`, `column_show`, `column_editable`, `column_align`, `column_width`, `column_formatter`, `column_index`, `editor_show`, `editor_readonly`, `editor_type`, `editor_options`, `editor_rows`, `editor_columns`, `editor_group`, `editor_index`, `created_at`, `updated_at`)
VALUES
	(2, 'id', 'ID', 'ID', 3, '', 0, 0, 0, 0, 1, 1, 1, 0, 1, 30, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-08-10 16:21:51'),
	(2, 'site_id', '站点ID', '站点', 3, '', 0, 0, 0, 0, 1, 2, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-08-10 16:20:53'),
	(2, 'title', '标题', '标题', 1, '', 1, 0, 0, 0, 0, 5, 1, 0, 1, 300, '', 2, 1, 0, 1, '', 1, 11, '基本信息', 1, '2017-06-21 00:00:00', '2017-08-18 10:45:19'),
	(2, 'subtitle', '副标题', '副标题', 1, '', 0, 0, 0, 0, 0, 6, 0, 0, 1, 0, '', 0, 1, 0, 1, '', 1, 11, '基本信息', 2, '2017-09-19 11:05:51', '2017-09-19 11:05:51'),
	(2, 'author', '作者', '作者', 1, '', 0, 0, 0, 0, 0, 9, 0, 0, 1, 0, '', 0, 1, 0, 1, '', 1, 2, '基本信息', 6, '2017-09-19 11:29:39', '2017-09-19 11:37:58'),
	(2, 'origin', '内容来源', '内容来源', 1, '', 0, 0, 0, 0, 0, 10, 0, 0, 1, 0, '', 0, 1, 0, 1, '', 1, 2, '基本信息', 7, '2017-09-19 11:30:09', '2017-09-19 11:38:11'),
	(2, 'slug', '网址缩略名', '网址缩略名', 1, '', 0, 0, 0, 0, 0, 11, 0, 0, 1, 0, '', 0, 1, 0, 1, '', 1, 5, '基本信息', 8, '2017-09-19 11:30:59', '2017-09-19 11:36:27'),
	(2, 'summary', '摘要', '摘要', 1, '', 0, 0, 0, 0, 0, 12, 0, 0, 0, 0, '', 0, 1, 0, 2, '', 4, 11, '基本信息', 9, '2017-06-21 00:00:00', '2017-09-19 11:36:29'),
	(2, 'image_url', '缩略图', '缩略图', 8, '', 0, 0, 0, 0, 0, 13, 0, 0, 0, 0, '', 0, 1, 0, 8, '', 1, 11, '基本信息', 10, '2017-06-21 00:00:00', '2017-09-19 11:36:32'),
	(2, 'video_url', '视频', '视频', 10, '', 0, 0, 0, 0, 0, 14, 0, 0, 1, 0, '', 0, 1, 0, 10, '', 1, 11, '基本信息', 11, '2017-09-19 11:33:06', '2017-09-19 11:36:35'),
	(2, 'audio_url', '音频', '音频', 10, '', 0, 0, 0, 0, 0, 15, 0, 0, 1, 0, '', 0, 1, 0, 9, '', 1, 11, '基本信息', 12, '2017-09-19 11:33:06', '2017-09-19 11:36:35'),
	(2, 'images', '图片集', '图片集', 11, '', 0, 0, 0, 0, 0, 16, 0, 0, 0, 0, '', 0, 1, 0, 11, '', 1, 11, '图片集', 13, '2017-06-21 00:00:00', '2017-09-19 11:36:39'),
	(2, 'videos', '视频集', '视频集', 13, '', 0, 0, 0, 0, 0, 17, 0, 0, 0, 0, '', 0, 1, 0, 13, '', 1, 11, '视频集', 14, '2017-06-21 00:00:00', '2017-09-19 11:36:42'),
	(2, 'content', '正文', '正文', 6, '', 0, 0, 0, 0, 0, 19, 0, 0, 0, 0, '', 0, 1, 0, 6, '', 40, 12, '正文', 16, '2017-06-21 00:00:00', '2017-09-19 11:36:44'),
	(2, 'top', '是否置顶', '是否置顶', 3, '0', 0, 0, 0, 0, 0, 90, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-09-19 11:35:51'),
	(2, 'member_id', '会员ID', '会员', 7, '', 0, 0, 0, 0, 0, 91, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(2, 'user_id', '用户ID', '操作员', 7, '', 0, 0, 0, 0, 0, 92, 1, 0, 2, 45, '', 4, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-08-10 14:04:29'),
	(2, 'sort', '序号', '序号', 3, '0', 0, 0, 0, 0, 0, 93, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(2, 'state', '状态', '状态', 3, '1', 0, 0, 0, 0, 0, 94, 1, 0, 2, 45, 'stateFormatter', 5, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-08-10 14:04:38'),
	(2, 'created_at', '创建时间', '创建时间', 5, '', 0, 0, 0, 0, 1, 95, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(2, 'updated_at', '修改时间', '修改时间', 5, '', 0, 0, 0, 0, 1, 96, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(2, 'deleted_at', '删除时间', '删除时间', 5, '', 0, 0, 0, 0, 1, 97, 0, 0, 0, 0, '', 0, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00'),
	(2, 'published_at', '发布时间', '发布时间', 5, '', 0, 0, 0, 0, 0, 98, 1, 0, 2, 120, '', 3, 0, 0, 0, '', 0, 0, '', 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00');

DROP TABLE IF EXISTS `cms_push_logs`;
CREATE TABLE `cms_push_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(10) unsigned NOT NULL COMMENT '站点ID',
  `refer_id` int(10) unsigned NOT NULL COMMENT '关联ID',
  `refer_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '关联类型',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `url` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'URL',
  `send_no` int(11) unsigned NOT NULL COMMENT '推送序号',
  `msg_id` int(11) unsigned NOT NULL COMMENT '消息ID',
  `err_msg` text COLLATE utf8_unicode_ci NOT NULL COMMENT '错误消息',
  `user_id` int(10) unsigned NOT NULL COMMENT '操作员ID',
  `state` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY (`site_id`),
  KEY (`refer_id`, `refer_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `cms_categories` ADD `type` TINYINT(1) NOT NULL COMMENT '栏目类型' AFTER `module_id`;

CREATE TABLE `cms_features` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL COMMENT '栏目ID',
  `type` int(11) NOT NULL COMMENT '类型',
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题',
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '摘要',
  `image_url` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '缩略图',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '正文',
  `top` int(11) NOT NULL COMMENT '是否置顶',
  `published_at` datetime DEFAULT NULL COMMENT '发布时间',
  `images` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片集',
  `videos` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '视频',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `sort` int(11) NOT NULL COMMENT '序号',
  `state` int(11) NOT NULL COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_permissions`;
CREATE TABLE `cms_permissions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `groups` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '分组',
  `sort` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `cms_permissions`
--

INSERT INTO `cms_permissions` (`id`, `name`, `description`, `groups`, `sort`, `created_at`, `updated_at`) VALUES
(1, '@option', '系统设置', '@option', 1, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(2, '@dictionary', '字典设置', '@dictionary', 2, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(3, '@site', '站点设置', '@site', 3, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(4, '@app', '应用管理', '@option', 4, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(5, '@user', '用户管理', '@option', 5, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(6, '@role', '角色管理', '@option', 6, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(7, '@category', '栏目管理', '@site', 7, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(8, '@article', '文章管理', '@article', 8, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(9, '@comment', '评论管理', '@comment', 9, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(10, '@member', '会员管理', '@member', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(11, '@log', '日志查询', '@log', 10, '2016-09-28 00:00:00', '2016-09-28 00:00:00'),
(31, '@comment-delete', '评论管理-删除', '@comment', 2, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(32, '@comment-pass', '评论管理-审核', '@comment', 1, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(41, '@article-create', '文章管理-新增', '@article', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(42, '@article-edit', '文章管理-编辑', '@article', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(43, '@article-publish', '文章管理-发布', '@article', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(44, '@article-cancel', '文章管理-撤搞', '@article', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(45, '@article-delete', '文章管理-删除', '@article', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(46, '@article-copy', '文章管理-复制', '@article', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(47, '@article-sort', '文章管理-排序', '@article', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(48, '@article-top', '文章管理-置顶', '@article', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(49, '@article-tag', '文章管理-标记', '@article', 0, '2016-11-16 16:00:00', '2016-11-16 16:00:00'),
(50, '@article-push', '文章管理-推送', '@article', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(100, '@member-create', '会员管理-添加', '@member', 0, '2016-11-06 16:00:00', '2016-11-02 16:00:00'),
(101, '@member-edit', '会员管理-编辑', '@member', 0, '2016-11-06 16:00:00', '2016-11-02 16:00:00'),
(145, '@push', '推送管理', '@log', 0, '2016-11-16 16:00:00', '2016-11-16 16:00:00'),
(146, '@module', '模块管理', '@log', 11, '2017-02-15 16:00:00', '2017-02-15 16:00:00'),
(175, '@page-sort', '单页-排序', '@page', 7, NULL, NULL),
(174, '@page-cancel', '单页-撤回', '@page', 6, NULL, NULL),
(172, '@page-delete', '单页-删除', '@page', 4, NULL, NULL),
(173, '@page-publish', '单页-发布', '@page', 5, NULL, NULL),
(171, '@page-edit', '单页-编辑', '@page', 3, NULL, NULL),
(170, '@page-create', '单页-添加', '@page', 2, NULL, NULL),
(169, '@page', '单页', '@page', 1, NULL, NULL),
(12, '@menu', '菜单管理', '@option', 0, '2017-02-15 16:00:00', '2017-02-15 16:00:00'),
(13, '@theme', '主题管理', '@site', 0, '2017-02-15 16:00:00', '2017-02-15 16:00:00'),
(176, '@video', '视频', '@video', 1, NULL, NULL),
(177, '@video-create', '视频-添加', '@video', 2, NULL, NULL),
(178, '@video-edit', '视频-编辑', '@video', 3, NULL, NULL),
(179, '@video-delete', '视频-删除', '@video', 4, NULL, NULL),
(180, '@video-publish', '视频-发布', '@video', 5, NULL, NULL),
(181, '@video-cancel', '视频-撤回', '@video', 6, NULL, NULL),
(182, '@video-sort', '视频-排序', '@video', 7, NULL, NULL),
(183, '@question', '问答', '@question', 1, NULL, NULL),
(184, '@question-create', '问答-添加', '@question', 2, NULL, NULL),
(185, '@question-edit', '问答-编辑', '@question', 3, NULL, NULL),
(186, '@question-delete', '问答-删除', '@question', 4, NULL, NULL),
(187, '@question-publish', '问答-发布', '@question', 5, NULL, NULL),
(188, '@question-cancel', '问答-撤回', '@question', 6, NULL, NULL),
(189, '@question-sort', '问答-排序', '@question', 7, NULL, NULL),
(190, '@feature', '专题管理', '@feature', 8, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(191, '@feature-create', '专题管理-新增', '@feature', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(192, '@feature-edit', '专题管理-编辑', '@feature', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(193, '@feature-publish', '专题管理-发布', '@feature', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(194, '@feature-cancel', '专题管理-撤搞', '@feature', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(195, '@feature-delete', '专题管理-删除', '@feature', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(196, '@feature-copy', '专题管理-复制', '@feature', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(197, '@feature-sort', '专题管理-排序', '@feature', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(198, '@feature-top', '专题管理-置顶', '@feature', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00'),
(199, '@feature-tag', '专题管理-标记', '@feature', 0, '2016-11-16 16:00:00', '2016-11-16 16:00:00'),
(200, '@feature-push', '专题管理-推送', '@feature', 0, '2016-06-30 00:00:00', '2016-06-30 00:00:00');