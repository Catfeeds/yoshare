-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-11-16 19:52:55
-- 服务器版本： 10.1.10-MariaDB
-- PHP Version: 7.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yoshare`
--

-- --------------------------------------------------------

--
-- 表的结构 `cms_carts`
--

CREATE TABLE `cms_carts` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `price` int(11) NOT NULL COMMENT '价格',
  `number` int(11) NOT NULL COMMENT '数量',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `state` int(11) NOT NULL COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `cms_orders`
--

CREATE TABLE `cms_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `order_num` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单号',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '收货人姓名',
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '收货地址',
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '电话',
  `ship_id` int(11) NOT NULL COMMENT '物流ID',
  `pay_id` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付方式ID',
  `total_pay` double(8,2) NOT NULL COMMENT '总支付',
  `total_price` double(8,2) NOT NULL COMMENT '总价',
  `ship_price` double(8,2) NOT NULL COMMENT '物流费用',
  `state` int(11) NOT NULL COMMENT '状态',
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL COMMENT '支付时间',
  `shipped_at` datetime DEFAULT NULL COMMENT '邮寄时间',
  `finished_at` datetime DEFAULT NULL COMMENT '完成时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `cms_payments`
--

CREATE TABLE `cms_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '方式名',
  `pic` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片',
  `intro` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '简介',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '详情',
  `type` int(11) NOT NULL COMMENT '类型',
  `sort` int(11) NOT NULL COMMENT '序号',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  `published_at` datetime DEFAULT NULL COMMENT '发布时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `cms_ships`
--

CREATE TABLE `cms_ships` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '物流名',
  `pic` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片',
  `intro` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '简介',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '详情',
  `sort` int(11) NOT NULL COMMENT '序号',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  `published_at` datetime DEFAULT NULL COMMENT '发布时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_carts`
--
ALTER TABLE `cms_carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_orders`
--
ALTER TABLE `cms_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_payments`
--
ALTER TABLE `cms_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_ships`
--
ALTER TABLE `cms_ships`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `cms_carts`
--
ALTER TABLE `cms_carts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `cms_orders`
--
ALTER TABLE `cms_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `cms_payments`
--
ALTER TABLE `cms_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `cms_ships`
--
ALTER TABLE `cms_ships`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
