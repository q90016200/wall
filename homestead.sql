-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- 主機: localhost
-- 產生時間： 2018 年 05 月 14 日 13:34
-- 伺服器版本: 5.7.21-0ubuntu0.16.04.1
-- PHP 版本： 7.1.15-1+ubuntu16.04.1+deb.sury.org+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `homestead`
--
CREATE DATABASE IF NOT EXISTS `homestead` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;
USE `homestead`;

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `wall_posts`
--

DROP TABLE IF EXISTS `wall_posts`;
CREATE TABLE `wall_posts` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_author` bigint(20) NOT NULL,
  `post_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_like_count` int(11) NOT NULL DEFAULT '0',
  `post_comment_count` int(11) NOT NULL DEFAULT '0',
  `post_image_count` int(11) NOT NULL DEFAULT '0',
  `post_preview_link` varchar(2083) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_create_date` datetime DEFAULT NULL,
  `post_create_timestamp` bigint(20) UNSIGNED NOT NULL COMMENT 'millisecond',
  `post_sort_time` bigint(20) UNSIGNED NOT NULL COMMENT 'millisecond',
  `post_modify_date` datetime DEFAULT NULL,
  `post_ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_category` text COLLATE utf8mb4_unicode_ci COMMENT '文章類型',
  `post_tag_works` text COLLATE utf8mb4_unicode_ci,
  `post_tag_actors` text COLLATE utf8mb4_unicode_ci,
  `post_top` tinyint(1) NOT NULL DEFAULT '0',
  `post_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish' COMMENT 'publish ; delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `wall_post_comments`
--

DROP TABLE IF EXISTS `wall_post_comments`;
CREATE TABLE `wall_post_comments` (
  `comment_id` bigint(20) UNSIGNED NOT NULL COMMENT '留言id',
  `comment_post_id` bigint(20) NOT NULL COMMENT '貼文id',
  `comment_author` bigint(20) NOT NULL COMMENT '建立此留言的使用者id',
  `comment_content` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_date` datetime NOT NULL COMMENT '建立時間',
  `comment_timestamp` bigint(20) UNSIGNED NOT NULL,
  `comment_modify_date` datetime DEFAULT NULL COMMENT '修改時間',
  `comment_ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment_like_count` int(11) NOT NULL DEFAULT '0',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish' COMMENT 'publish ; delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `wall_post_comment_likes`
--

DROP TABLE IF EXISTS `wall_post_comment_likes`;
CREATE TABLE `wall_post_comment_likes` (
  `like_id` bigint(20) NOT NULL,
  `like_comment_id` bigint(20) UNSIGNED NOT NULL,
  `like_uid` bigint(20) UNSIGNED NOT NULL,
  `like_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'like' COMMENT 'like ; delete',
  `like_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `wall_post_imgs`
--

DROP TABLE IF EXISTS `wall_post_imgs`;
CREATE TABLE `wall_post_imgs` (
  `img_id` bigint(20) UNSIGNED NOT NULL,
  `img_post_id` bigint(20) DEFAULT NULL,
  `img_path` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `wall_post_likes`
--

DROP TABLE IF EXISTS `wall_post_likes`;
CREATE TABLE `wall_post_likes` (
  `like_id` bigint(20) UNSIGNED NOT NULL,
  `like_post_id` bigint(20) NOT NULL,
  `like_uid` bigint(20) NOT NULL,
  `like_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'like' COMMENT 'like ; delete',
  `like_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `wall_preview_links`
--

DROP TABLE IF EXISTS `wall_preview_links`;
CREATE TABLE `wall_preview_links` (
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `link_url` varchar(2083) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_image` varchar(2083) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_image_width` int(11) DEFAULT NULL,
  `link_image_height` int(11) DEFAULT NULL,
  `link_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `wall_posts`
--
ALTER TABLE `wall_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `post_author` (`post_author`),
  ADD KEY `post_status` (`post_status`);

--
-- 資料表索引 `wall_post_comments`
--
ALTER TABLE `wall_post_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_post_id` (`comment_post_id`,`comment_author`,`comment_status`);

--
-- 資料表索引 `wall_post_comment_likes`
--
ALTER TABLE `wall_post_comment_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `like_uid` (`like_uid`),
  ADD KEY `like_status` (`like_status`),
  ADD KEY `like_comment_id` (`like_comment_id`);

--
-- 資料表索引 `wall_post_imgs`
--
ALTER TABLE `wall_post_imgs`
  ADD PRIMARY KEY (`img_id`),
  ADD KEY `post_id` (`img_post_id`);

--
-- 資料表索引 `wall_post_likes`
--
ALTER TABLE `wall_post_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `like_post_id` (`like_post_id`,`like_status`) USING BTREE,
  ADD KEY `like_uid` (`like_uid`);

--
-- 資料表索引 `wall_preview_links`
--
ALTER TABLE `wall_preview_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `link_url` (`link_url`(191));

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表 AUTO_INCREMENT `wall_posts`
--
ALTER TABLE `wall_posts`
  MODIFY `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `wall_post_comments`
--
ALTER TABLE `wall_post_comments`
  MODIFY `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '留言id';

--
-- 使用資料表 AUTO_INCREMENT `wall_post_comment_likes`
--
ALTER TABLE `wall_post_comment_likes`
  MODIFY `like_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `wall_post_imgs`
--
ALTER TABLE `wall_post_imgs`
  MODIFY `img_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `wall_post_likes`
--
ALTER TABLE `wall_post_likes`
  MODIFY `like_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `wall_preview_links`
--
ALTER TABLE `wall_preview_links`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
