-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- 主機: localhost
-- 產生時間： 2018 年 12 月 27 日 05:18
-- 伺服器版本: 5.5.60-MariaDB
-- PHP 版本： 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `wall`
--
CREATE DATABASE IF NOT EXISTS `wall` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `wall`;

-- --------------------------------------------------------

--
-- 資料表結構 `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'q90016200', 'q90016200@gmail.com', '$2y$10$4dk97931T2SZb69tTCpG2e8bFS8a61gbDSO6/P9sbbdMs5Q5TOx3W', NULL, '2018-12-27 13:14:12', '2018-12-27 13:14:12');

-- --------------------------------------------------------

--
-- 資料表結構 `wall_posts`
--

DROP TABLE IF EXISTS `wall_posts`;
CREATE TABLE `wall_posts` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_author` bigint(20) UNSIGNED NOT NULL,
  `post_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_comment_count` int(11) NOT NULL DEFAULT '0',
  `post_image_count` int(11) NOT NULL DEFAULT '0',
  `post_like_count` int(11) NOT NULL DEFAULT '0',
  `post_preview_link` varchar(2083) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `wall_posts`
--

INSERT INTO `wall_posts` (`post_id`, `post_author`, `post_content`, `post_comment_count`, `post_image_count`, `post_like_count`, `post_preview_link`, `post_ip`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, '123', 1, 0, 0, NULL, NULL, '2018-06-11 17:20:38', '2018-06-11 17:20:38', NULL),
(2, 4, '1', 0, 0, 0, NULL, NULL, '2018-06-20 16:51:24', '2018-06-20 16:51:24', NULL),
(3, 4, '2', 0, 0, 0, NULL, NULL, '2018-06-20 16:51:25', '2018-06-20 16:51:25', NULL),
(4, 4, '3', 0, 0, 0, NULL, NULL, '2018-06-20 16:51:26', '2018-06-20 16:51:26', NULL),
(5, 4, '4', 0, 0, 0, NULL, NULL, '2018-06-20 16:51:27', '2018-06-20 16:51:27', NULL),
(6, 4, '5', 0, 0, 0, NULL, NULL, '2018-06-20 16:51:28', '2018-06-20 16:51:28', NULL),
(7, 4, '6', 0, 0, 0, NULL, NULL, '2018-06-20 17:01:24', '2018-06-20 17:01:24', NULL),
(8, 4, '7', 0, 0, 0, NULL, NULL, '2018-06-20 17:01:25', '2018-06-20 17:01:25', NULL),
(9, 4, '8', 0, 0, 0, NULL, NULL, '2018-06-20 17:01:28', '2018-06-20 17:01:28', NULL),
(10, 4, '9', 0, 0, 0, NULL, NULL, '2018-06-20 17:01:30', '2018-06-20 17:01:30', NULL),
(11, 4, '10', 0, 0, 0, NULL, NULL, '2018-06-20 17:01:31', '2018-06-20 17:01:31', NULL),
(12, 1, '213', 0, 0, 0, NULL, NULL, '2018-12-27 13:14:20', '2018-12-27 13:14:24', '2018-12-27 13:14:24');

-- --------------------------------------------------------

--
-- 資料表結構 `wall_post_comments`
--

DROP TABLE IF EXISTS `wall_post_comments`;
CREATE TABLE `wall_post_comments` (
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `comment_post_id` bigint(20) UNSIGNED NOT NULL,
  `comment_author` bigint(20) UNSIGNED NOT NULL,
  `comment_content` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `comment_like_count` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `wall_post_comments`
--

INSERT INTO `wall_post_comments` (`comment_id`, `comment_post_id`, `comment_author`, `comment_content`, `created_at`, `updated_at`, `comment_like_count`, `deleted_at`) VALUES
(1, 1, 4, '123', '2018-06-11 17:20:41', '2018-06-11 17:20:41', 0, NULL);

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

--
-- 資料表的匯出資料 `wall_post_imgs`
--

INSERT INTO `wall_post_imgs` (`img_id`, `img_post_id`, `img_path`) VALUES
(1, 7, 'wall/posts/7/7_5afd3a70016ca.jpg');

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
-- 資料表的匯出資料 `wall_preview_links`
--

INSERT INTO `wall_preview_links` (`link_id`, `link_url`, `link_title`, `link_description`, `link_image`, `link_image_width`, `link_image_height`, `link_updated`) VALUES
(5, 'https://www.moviemovie.com.tw/', '影劇圈圈 | 電影電視劇社群網', '「影劇圈圈」提供電影、電視劇相關新聞資訊，未來也將會建立更多電影、電視劇相關服務，包括電影電視劇討論區社群、電影電視劇資料庫、專屬於台灣觀眾的評價機制 等。', 'https://www.moviemovie.com.tw/img/470x246_moviemovie_logo.jpg', 470, 246, '2018-05-21 06:53:14'),
(6, 'https://www.moviemovie.com.tw/wall/posts/605', '馬克恰恰 - 無限手套說明書~~感覺很威!!!!XDD｜影劇動態牆 | 影劇圈圈', '馬克恰恰 - 無限手套說明書~~感覺很威!!!!XDD', 'https://scontent.ftpe7-2.fna.fbcdn.net/v/t1.0-9/32643279_1915325941851643_7543758838768336896_o.jpg?_nc_fx=ftpe7-1&_nc_cat=0&oh=71480229aa9f5bd1f8afb3c7d8df4cef&oe=5B8E25A8', 1280, 905, '2018-05-17 08:25:58'),
(7, 'https://doc.react-china.org', 'React 中文文档 - 用于构建用户界面的 JavaScript 库', 'A JavaScript library for building user interfaces', '', 0, 0, '2018-05-21 06:56:03'),
(8, 'https://doc.react-china.org/', 'React 中文文档 - 用于构建用户界面的 JavaScript 库', 'A JavaScript library for building user interfaces', '', 0, 0, '2018-05-21 07:02:43'),
(9, 'https://www.youtube.com/watch?v=i0p1bmr0EmE', 'TWICE \"What is Love?\" M/V', 'TWICE(트와이스) \"What is Love?\" M/V Spotify https://goo.gl/jVLYYY iTunes & Apple Music https://goo.gl/DKyKZf Google Music https://goo.gl/DxAtPd TWICE Official Yo...', 'https://i.ytimg.com/vi/i0p1bmr0EmE/hqdefault.jpg', 480, 360, '2018-05-23 04:29:17'),
(10, 'https://www.youtube.com/watch?v=d9IxdwEFk1c', '[MV] IU(아이유) _ Palette(팔레트) (Feat. G-DRAGON)', '[MV] IU(아이유) _ Palette(팔레트) (Feat. G-DRAGON) *English subtitles are now available. :D (Please click on \'CC\' button or activate \'Interactive Transcript\' funct...', 'https://i.ytimg.com/vi/d9IxdwEFk1c/maxresdefault.jpg', 1280, 720, '2018-05-23 04:28:39'),
(11, 'https://www.gamer.com.tw/', '巴哈姆特電玩資訊站', '華人最大動漫及遊戲社群網站，提供 ACG 每日最新新聞、熱門排行榜，以及豐富的討論交流空間，還有精采的個人影音實況、部落格文章。', 'https://i2.bahamut.com.tw/bahaLOGO_1200x630.jpg', 1200, 630, '2018-12-27 13:17:38');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`(191));

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
  ADD KEY `post_author` (`post_author`);

--
-- 資料表索引 `wall_post_comments`
--
ALTER TABLE `wall_post_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_post_id` (`comment_post_id`),
  ADD KEY `comment_author` (`comment_author`);

--
-- 資料表索引 `wall_post_comment_likes`
--
ALTER TABLE `wall_post_comment_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `like_comment_id` (`like_comment_id`),
  ADD KEY `like_uid` (`like_uid`);

--
-- 資料表索引 `wall_post_imgs`
--
ALTER TABLE `wall_post_imgs`
  ADD PRIMARY KEY (`img_id`),
  ADD KEY `img_post_id` (`img_post_id`);

--
-- 資料表索引 `wall_post_likes`
--
ALTER TABLE `wall_post_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `like_post_id` (`like_post_id`),
  ADD KEY `like_uid` (`like_uid`),
  ADD KEY `like_status` (`like_status`);

--
-- 資料表索引 `wall_preview_links`
--
ALTER TABLE `wall_preview_links`
  ADD PRIMARY KEY (`link_id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表 AUTO_INCREMENT `wall_posts`
--
ALTER TABLE `wall_posts`
  MODIFY `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用資料表 AUTO_INCREMENT `wall_post_imgs`
--
ALTER TABLE `wall_post_imgs`
  MODIFY `img_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表 AUTO_INCREMENT `wall_post_likes`
--
ALTER TABLE `wall_post_likes`
  MODIFY `like_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `wall_preview_links`
--
ALTER TABLE `wall_preview_links`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
