-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Июн 17 2024 г., 10:27
-- Версия сервера: 8.0.37-0ubuntu0.20.04.3
-- Версия PHP: 7.4.3-4ubuntu2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `itntreg`
--

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_arlinks`
--

CREATE TABLE `zi_ab_arlinks` (
  `ID` int UNSIGNED NOT NULL,
  `ID_Article` int UNSIGNED NOT NULL,
  `ID_Participant` int UNSIGNED NOT NULL,
  `Format` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_articles`
--

CREATE TABLE `zi_ab_articles` (
  `ID` int UNSIGNED NOT NULL,
  `ID_Conf` int UNSIGNED NOT NULL,
  `ECID` int UNSIGNED NOT NULL,
  `Authors` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Title` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Decision` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_authors`
--

CREATE TABLE `zi_ab_authors` (
  `ID` int UNSIGNED NOT NULL,
  `ID_Article` int UNSIGNED NOT NULL,
  `FirstName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `LastName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `EMail` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `IsCorr` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_confs`
--

CREATE TABLE `zi_ab_confs` (
  `ID` int UNSIGNED NOT NULL,
  `Title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_events`
--

CREATE TABLE `zi_ab_events` (
  `ID` int UNSIGNED NOT NULL,
  `Title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Sum` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_evlinks`
--

CREATE TABLE `zi_ab_evlinks` (
  `ID` int UNSIGNED NOT NULL,
  `ID_Event` int UNSIGNED NOT NULL,
  `ID_Participant` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_logs`
--

CREATE TABLE `zi_ab_logs` (
  `ID` bigint UNSIGNED NOT NULL,
  `ID_User` bigint UNSIGNED DEFAULT NULL,
  `DateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Event` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `Location` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Value` varchar(4096) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_outmails`
--

CREATE TABLE `zi_ab_outmails` (
  `ID` smallint UNSIGNED NOT NULL,
  `ID_User` bigint UNSIGNED DEFAULT NULL,
  `MetaInfo` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `SendDateTime` datetime NOT NULL,
  `Status` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'P'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_participants`
--

CREATE TABLE `zi_ab_participants` (
  `ID` int UNSIGNED NOT NULL,
  `ID_Conf` int UNSIGNED NOT NULL,
  `Honorific` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `FirstName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `LastName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `EMail` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Country` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Organization` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `OrganizationCity` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `SciStatus` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `DateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PaymentFromOrg` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `YouthSchool` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `Link` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_rooms`
--

CREATE TABLE `zi_ab_rooms` (
  `ID` int UNSIGNED NOT NULL,
  `Link` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `SectionNo` tinyint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_uploads`
--

CREATE TABLE `zi_ab_uploads` (
  `ID` int UNSIGNED NOT NULL,
  `URL` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_workshops`
--

CREATE TABLE `zi_ab_workshops` (
  `ID` int UNSIGNED NOT NULL,
  `Title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_ab_wslinks`
--

CREATE TABLE `zi_ab_wslinks` (
  `ID` int UNSIGNED NOT NULL,
  `ID_Workshop` int UNSIGNED NOT NULL,
  `ID_Participant` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_aryo_activity_log`
--

CREATE TABLE `zi_aryo_activity_log` (
  `histid` int NOT NULL,
  `user_caps` varchar(70) NOT NULL DEFAULT 'guest',
  `action` varchar(255) NOT NULL,
  `object_type` varchar(255) NOT NULL,
  `object_subtype` varchar(255) NOT NULL DEFAULT '',
  `object_name` varchar(255) NOT NULL,
  `object_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `hist_ip` varchar(55) NOT NULL DEFAULT '127.0.0.1',
  `hist_time` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_commentmeta`
--

CREATE TABLE `zi_commentmeta` (
  `meta_id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_comments`
--

CREATE TABLE `zi_comments` (
  `comment_ID` bigint UNSIGNED NOT NULL,
  `comment_post_ID` bigint UNSIGNED NOT NULL DEFAULT '0',
  `comment_author` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_author_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_karma` int NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_parent` bigint UNSIGNED NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_links`
--

CREATE TABLE `zi_links` (
  `link_id` bigint UNSIGNED NOT NULL,
  `link_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint UNSIGNED NOT NULL DEFAULT '1',
  `link_rating` int NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_rss` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_options`
--

CREATE TABLE `zi_options` (
  `option_id` bigint UNSIGNED NOT NULL,
  `option_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `zi_options`
--

INSERT INTO `zi_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1, 'siteurl', 'http://localhost', 'yes'),
(2, 'home', 'http://localhost', 'yes'),
(3, 'blogname', 'ITNT Registration', 'yes'),
(4, 'blogdescription', 'Welcome to ITNT Conference', 'yes'),
(5, 'users_can_register', '0', 'yes'),
(6, 'admin_email', 'admin@example.com', 'yes'),
(7, 'start_of_week', '1', 'yes'),
(8, 'use_balanceTags', '0', 'yes'),
(9, 'use_smilies', '1', 'yes'),
(10, 'require_name_email', '1', 'yes'),
(11, 'comments_notify', '1', 'yes'),
(12, 'posts_per_rss', '10', 'yes'),
(13, 'rss_use_excerpt', '0', 'yes'),
(14, 'mailserver_url', 'mail.example.com', 'yes'),
(15, 'mailserver_login', 'login@example.com', 'yes'),
(16, 'mailserver_pass', 'password', 'yes'),
(17, 'mailserver_port', '110', 'yes'),
(18, 'default_category', '1', 'yes'),
(19, 'default_comment_status', 'open', 'yes'),
(20, 'default_ping_status', 'open', 'yes'),
(21, 'default_pingback_flag', '0', 'yes'),
(22, 'posts_per_page', '10', 'yes'),
(23, 'date_format', 'Y-m-d', 'yes'),
(24, 'time_format', 'H:i', 'yes'),
(25, 'links_updated_date_format', 'F j, Y g:i a', 'yes'),
(26, 'comment_moderation', '0', 'yes'),
(27, 'moderation_notify', '1', 'yes'),
(28, 'permalink_structure', '/archives/%post_id%', 'yes'),
(29, 'rewrite_rules', 'a:88:{s:11:\"^wp-json/?$\";s:22:\"index.php?rest_route=/\";s:14:\"^wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:21:\"^index.php/wp-json/?$\";s:22:\"index.php?rest_route=/\";s:24:\"^index.php/wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:20:\"^([A-Za-z0-9]{8})/?$\";s:27:\"index.php?plink=$matches[1]\";s:11:\"^restore/?$\";s:22:\"index.php?restore=true\";s:56:\"archives/category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:51:\"archives/category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:32:\"archives/category/(.+?)/embed/?$\";s:46:\"index.php?category_name=$matches[1]&embed=true\";s:44:\"archives/category/(.+?)/page/?([0-9]{1,})/?$\";s:53:\"index.php?category_name=$matches[1]&paged=$matches[2]\";s:26:\"archives/category/(.+?)/?$\";s:35:\"index.php?category_name=$matches[1]\";s:53:\"archives/tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:48:\"archives/tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:29:\"archives/tag/([^/]+)/embed/?$\";s:36:\"index.php?tag=$matches[1]&embed=true\";s:41:\"archives/tag/([^/]+)/page/?([0-9]{1,})/?$\";s:43:\"index.php?tag=$matches[1]&paged=$matches[2]\";s:23:\"archives/tag/([^/]+)/?$\";s:25:\"index.php?tag=$matches[1]\";s:54:\"archives/type/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:49:\"archives/type/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:30:\"archives/type/([^/]+)/embed/?$\";s:44:\"index.php?post_format=$matches[1]&embed=true\";s:42:\"archives/type/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?post_format=$matches[1]&paged=$matches[2]\";s:24:\"archives/type/([^/]+)/?$\";s:33:\"index.php?post_format=$matches[1]\";s:48:\".*wp-(atom|rdf|rss|rss2|feed|commentsrss2)\\.php$\";s:18:\"index.php?feed=old\";s:20:\".*wp-app\\.php(/.*)?$\";s:19:\"index.php?error=403\";s:18:\".*wp-register.php$\";s:23:\"index.php?register=true\";s:32:\"feed/(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:27:\"(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:8:\"embed/?$\";s:21:\"index.php?&embed=true\";s:20:\"page/?([0-9]{1,})/?$\";s:28:\"index.php?&paged=$matches[1]\";s:41:\"comments/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:36:\"comments/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:17:\"comments/embed/?$\";s:21:\"index.php?&embed=true\";s:44:\"search/(.+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:39:\"search/(.+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:20:\"search/(.+)/embed/?$\";s:34:\"index.php?s=$matches[1]&embed=true\";s:32:\"search/(.+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?s=$matches[1]&paged=$matches[2]\";s:14:\"search/(.+)/?$\";s:23:\"index.php?s=$matches[1]\";s:56:\"archives/author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:51:\"archives/author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:32:\"archives/author/([^/]+)/embed/?$\";s:44:\"index.php?author_name=$matches[1]&embed=true\";s:44:\"archives/author/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?author_name=$matches[1]&paged=$matches[2]\";s:26:\"archives/author/([^/]+)/?$\";s:33:\"index.php?author_name=$matches[1]\";s:83:\"archives/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:78:\"archives/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:59:\"archives/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/embed/?$\";s:74:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&embed=true\";s:71:\"archives/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]\";s:53:\"archives/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$\";s:63:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]\";s:70:\"archives/date/([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:65:\"archives/date/([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:46:\"archives/date/([0-9]{4})/([0-9]{1,2})/embed/?$\";s:58:\"index.php?year=$matches[1]&monthnum=$matches[2]&embed=true\";s:58:\"archives/date/([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]\";s:40:\"archives/date/([0-9]{4})/([0-9]{1,2})/?$\";s:47:\"index.php?year=$matches[1]&monthnum=$matches[2]\";s:57:\"archives/date/([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:52:\"archives/date/([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:33:\"archives/date/([0-9]{4})/embed/?$\";s:37:\"index.php?year=$matches[1]&embed=true\";s:45:\"archives/date/([0-9]{4})/page/?([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&paged=$matches[2]\";s:27:\"archives/date/([0-9]{4})/?$\";s:26:\"index.php?year=$matches[1]\";s:37:\"archives/[0-9]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:47:\"archives/[0-9]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:67:\"archives/[0-9]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\"archives/[0-9]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\"archives/[0-9]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:43:\"archives/[0-9]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:26:\"archives/([0-9]+)/embed/?$\";s:34:\"index.php?p=$matches[1]&embed=true\";s:30:\"archives/([0-9]+)/trackback/?$\";s:28:\"index.php?p=$matches[1]&tb=1\";s:50:\"archives/([0-9]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?p=$matches[1]&feed=$matches[2]\";s:45:\"archives/([0-9]+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?p=$matches[1]&feed=$matches[2]\";s:38:\"archives/([0-9]+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?p=$matches[1]&paged=$matches[2]\";s:45:\"archives/([0-9]+)/comment-page-([0-9]{1,})/?$\";s:41:\"index.php?p=$matches[1]&cpage=$matches[2]\";s:34:\"archives/([0-9]+)(?:/([0-9]+))?/?$\";s:40:\"index.php?p=$matches[1]&page=$matches[2]\";s:26:\"archives/[0-9]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:36:\"archives/[0-9]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:56:\"archives/[0-9]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:51:\"archives/[0-9]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:51:\"archives/[0-9]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:32:\"archives/[0-9]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:27:\".?.+?/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\".?.+?/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\".?.+?/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:33:\".?.+?/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:16:\"(.?.+?)/embed/?$\";s:41:\"index.php?pagename=$matches[1]&embed=true\";s:20:\"(.?.+?)/trackback/?$\";s:35:\"index.php?pagename=$matches[1]&tb=1\";s:40:\"(.?.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:35:\"(.?.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:28:\"(.?.+?)/page/?([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&paged=$matches[2]\";s:35:\"(.?.+?)/comment-page-([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&cpage=$matches[2]\";s:24:\"(.?.+?)(?:/([0-9]+))?/?$\";s:47:\"index.php?pagename=$matches[1]&page=$matches[2]\";}', 'yes'),
(30, 'hack_file', '0', 'yes'),
(31, 'blog_charset', 'UTF-8', 'yes'),
(32, 'moderation_keys', '', 'no'),
(33, 'active_plugins', 'a:3:{i:0;s:39:\"aryo-activity-log/aryo-activity-log.php\";i:3;s:43:\"wp-maintenance-mode/wp-maintenance-mode.php\";i:4;s:31:\"wp-statistics/wp-statistics.php\";}', 'yes'),
(34, 'category_base', '', 'yes'),
(35, 'ping_sites', 'http://rpc.pingomatic.com/', 'yes'),
(36, 'comment_max_links', '2', 'yes'),
(37, 'gmt_offset', '4', 'yes'),
(38, 'default_email_category', '1', 'yes'),
(39, 'recently_edited', '', 'no'),
(40, 'template', 'simplified', 'yes'),
(41, 'stylesheet', 'simplified', 'yes'),
(44, 'comment_registration', '0', 'yes'),
(45, 'html_type', 'text/html', 'yes'),
(46, 'use_trackback', '0', 'yes'),
(47, 'default_role', 'subscriber', 'yes'),
(48, 'db_version', '49752', 'yes'),
(49, 'uploads_use_yearmonth_folders', '1', 'yes'),
(50, 'upload_path', '', 'yes'),
(51, 'blog_public', '0', 'yes'),
(52, 'default_link_category', '2', 'yes'),
(53, 'show_on_front', 'page', 'yes'),
(54, 'tag_base', '', 'yes'),
(55, 'show_avatars', '1', 'yes'),
(56, 'avatar_rating', 'G', 'yes'),
(57, 'upload_url_path', '', 'yes'),
(58, 'thumbnail_size_w', '150', 'yes'),
(59, 'thumbnail_size_h', '150', 'yes'),
(60, 'thumbnail_crop', '1', 'yes'),
(61, 'medium_size_w', '300', 'yes'),
(62, 'medium_size_h', '300', 'yes'),
(63, 'avatar_default', 'mystery', 'yes'),
(64, 'large_size_w', '1024', 'yes'),
(65, 'large_size_h', '1024', 'yes'),
(66, 'image_default_link_type', 'none', 'yes'),
(67, 'image_default_size', '', 'yes'),
(68, 'image_default_align', '', 'yes'),
(69, 'close_comments_for_old_posts', '0', 'yes'),
(70, 'close_comments_days_old', '14', 'yes'),
(71, 'thread_comments', '1', 'yes'),
(72, 'thread_comments_depth', '5', 'yes'),
(73, 'page_comments', '0', 'yes'),
(74, 'comments_per_page', '50', 'yes'),
(75, 'default_comments_page', 'newest', 'yes'),
(76, 'comment_order', 'asc', 'yes'),
(77, 'sticky_posts', 'a:0:{}', 'yes'),
(78, 'widget_categories', 'a:2:{i:2;a:4:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:12:\"hierarchical\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}', 'yes'),
(79, 'widget_text', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(80, 'widget_rss', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(82, 'timezone_string', '', 'yes'),
(83, 'page_for_posts', '0', 'yes'),
(84, 'page_on_front', '0', 'yes'),
(85, 'default_post_format', '0', 'yes'),
(86, 'link_manager_enabled', '0', 'yes'),
(87, 'finished_splitting_shared_terms', '1', 'yes'),
(88, 'site_icon', '0', 'yes'),
(89, 'medium_large_size_w', '768', 'yes'),
(90, 'medium_large_size_h', '0', 'yes'),
(91, 'wp_page_for_privacy_policy', '3', 'yes'),
(92, 'show_comments_cookies_opt_in', '1', 'yes'),
(93, 'admin_email_lifespan', '1636760765', 'yes'),
(94, 'initial_db_version', '45805', 'yes'),
(95, 'zi_user_roles', 'a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:62:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;s:26:\"view_all_aryo_activity_log\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}', 'yes'),
(96, 'fresh_site', '0', 'yes'),
(97, 'widget_search', 'a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}', 'yes'),
(98, 'widget_recent-posts', 'a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}', 'yes'),
(99, 'widget_recent-comments', 'a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}', 'yes'),
(100, 'widget_archives', 'a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}', 'yes'),
(101, 'widget_meta', 'a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}', 'yes'),
(102, 'sidebars_widgets', 'a:2:{s:19:\"wp_inactive_widgets\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:13:\"array_version\";i:3;}', 'yes'),
(103, 'cron', 'a:9:{i:1631035528;a:1:{s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1631049928;a:2:{s:32:\"recovery_mode_clean_expired_keys\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1631049929;a:2:{s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1631049938;a:2:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:25:\"delete_expired_transients\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1631049939;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1631052481;a:1:{s:28:\"wp_statistics_add_visit_hook\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1631053852;a:1:{s:22:\"redirection_log_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1631096351;a:2:{s:14:\"updraft_backup\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:23:\"updraft_backup_database\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}s:7:\"version\";i:2;}', 'yes'),
(104, 'widget_pages', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(105, 'widget_calendar', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(106, 'widget_media_audio', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(107, 'widget_media_image', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(108, 'widget_media_gallery', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(109, 'widget_media_video', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(110, 'widget_tag_cloud', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(111, 'widget_nav_menu', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(112, 'widget_custom_html', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(114, 'recovery_keys', 'a:0:{}', 'yes'),
(127, 'can_compress_scripts', '1', 'no'),
(140, 'current_theme', 'simplified', 'yes'),
(141, 'theme_mods_simplified', 'a:4:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1583186189;s:4:\"data\";a:1:{s:19:\"wp_inactive_widgets\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}}}s:18:\"custom_css_post_id\";i:-1;}', 'yes'),
(142, 'theme_switched', '', 'yes'),
(143, 'recovery_mode_email_last_sent', '1583632343', 'yes'),
(160, 'activity_log_db_version', '1.0', 'yes'),
(161, 'activity-log-settings', 'a:1:{s:13:\"logs_lifespan\";s:2:\"30\";}', 'yes'),
(184, 'widget_wp_statistics_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(185, 'wp_statistics_check_useronline', '1631046368', 'yes'),
(191, 'WPLANG', '', 'yes'),
(239, 'wpmm_settings', 'a:5:{s:7:\"general\";a:10:{s:6:\"status\";i:0;s:11:\"status_date\";s:0:\"\";s:11:\"bypass_bots\";i:0;s:12:\"backend_role\";a:0:{}s:13:\"frontend_role\";a:0:{}s:11:\"meta_robots\";i:0;s:11:\"redirection\";s:0:\"\";s:7:\"exclude\";a:3:{i:0;s:4:\"feed\";i:1;s:8:\"wp-login\";i:2;s:5:\"login\";}s:6:\"notice\";i:1;s:10:\"admin_link\";i:0;}s:6:\"design\";a:10:{s:5:\"title\";s:16:\"Maintenance mode\";s:7:\"heading\";s:16:\"Maintenance mode\";s:13:\"heading_color\";s:0:\"\";s:4:\"text\";s:138:\"<p>Sorry for the inconvenience.<br />Our website is currently undergoing scheduled maintenance.<br />Thank you for your understanding.</p>\";s:10:\"text_color\";s:0:\"\";s:7:\"bg_type\";s:5:\"color\";s:8:\"bg_color\";s:0:\"\";s:9:\"bg_custom\";s:0:\"\";s:13:\"bg_predefined\";s:7:\"bg1.jpg\";s:10:\"custom_css\";a:0:{}}s:7:\"modules\";a:24:{s:16:\"countdown_status\";i:0;s:15:\"countdown_start\";s:19:\"2020-03-03 07:49:33\";s:17:\"countdown_details\";a:3:{s:4:\"days\";i:0;s:5:\"hours\";i:1;s:7:\"minutes\";i:0;}s:15:\"countdown_color\";s:0:\"\";s:16:\"subscribe_status\";i:0;s:14:\"subscribe_text\";s:25:\"Notify me when it\'s ready\";s:20:\"subscribe_text_color\";s:0:\"\";s:13:\"social_status\";i:0;s:13:\"social_target\";i:1;s:13:\"social_github\";s:0:\"\";s:15:\"social_dribbble\";s:0:\"\";s:14:\"social_twitter\";s:0:\"\";s:15:\"social_facebook\";s:0:\"\";s:16:\"social_instagram\";s:0:\"\";s:16:\"social_pinterest\";s:0:\"\";s:14:\"social_google+\";s:0:\"\";s:15:\"social_linkedin\";s:0:\"\";s:14:\"contact_status\";i:0;s:13:\"contact_email\";s:14:\"admin@example.com\";s:15:\"contact_effects\";s:20:\"move_top|move_bottom\";s:9:\"ga_status\";i:0;s:15:\"ga_anonymize_ip\";i:0;s:7:\"ga_code\";s:0:\"\";s:10:\"custom_css\";a:0:{}}s:3:\"bot\";a:6:{s:6:\"status\";i:0;s:4:\"name\";s:5:\"Admin\";s:6:\"avatar\";s:0:\"\";s:8:\"messages\";a:11:{s:2:\"01\";s:97:\"Hey! My name is {bot_name}, I\'m the owner of this website and I\'d like to be your assistant here.\";s:2:\"02\";s:28:\"I have just a few questions.\";s:2:\"03\";s:18:\"What is your name?\";s:2:\"04\";s:38:\"Nice to meet you here, {visitor_name}!\";s:2:\"05\";s:55:\"How you can see, our website will be lauched very soon.\";s:2:\"06\";s:76:\"I know, you are very excited to see it, but we need a few days to finish it.\";s:2:\"07\";s:37:\"Would you like to be first to see it?\";s:4:\"08_1\";s:81:\"Cool! Please leave your email here and I will send you a message when it\'s ready.\";s:4:\"08_2\";s:56:\"Sad to hear that, {visitor_name} :( See you next time…\";s:2:\"09\";s:40:\"Got it! Thank you and see you soon here!\";i:10;s:17:\"Have a great day!\";}s:9:\"responses\";a:4:{s:2:\"01\";s:22:\"Type your name here…\";s:4:\"02_1\";s:12:\"Tell me more\";s:4:\"02_2\";s:6:\"Boring\";s:2:\"03\";s:23:\"Type your email here…\";}s:10:\"custom_css\";a:0:{}}s:4:\"gdpr\";a:6:{s:6:\"status\";i:0;s:17:\"policy_page_label\";s:14:\"Privacy Policy\";s:16:\"policy_page_link\";s:0:\"\";s:18:\"policy_page_target\";i:0;s:17:\"contact_form_tail\";s:186:\"This form collects your name and email so that we can reach you back. Check out our <a href=\"#\">Privacy Policy</a> page to fully understand how we protect and manage your submitted data.\";s:19:\"subscribe_form_tail\";s:193:\"This form collects your email so that we can add you to our newsletter list. Check out our <a href=\"#\">Privacy Policy</a> page to fully understand how we protect and manage your submitted data.\";}}', 'yes'),
(240, 'wpmm_version', '2.2.4', 'yes'),
(2691, 'custom_permalinks_plugin_version', '1.5.1', 'yes'),
(3031, 'wp_statistics_referrals_detail', 'a:14:{s:14:\"91.222.131.219\";a:3:{s:2:\"ip\";s:14:\"91.222.131.219\";s:7:\"country\";s:0:\"\";s:5:\"title\";s:17:\"Access forbidden!\";}s:10:\"sucuri.net\";a:3:{s:2:\"ip\";s:14:\"192.124.249.16\";s:7:\"country\";s:0:\"\";s:5:\"title\";s:59:\"Sucuri - Complete Website Security, Protection & Monitoring\";}s:14:\"www.google.com\";a:3:{s:2:\"ip\";s:14:\"173.194.222.99\";s:7:\"country\";s:0:\"\";s:5:\"title\";s:6:\"Google\";}s:17:\"itnt2020.ddns.net\";a:3:{s:2:\"ip\";s:14:\"91.222.131.219\";s:7:\"country\";s:0:\"\";s:5:\"title\";s:17:\"Access forbidden!\";}s:15:\"mail.bsu.edu.ru\";a:3:{s:2:\"ip\";s:13:\"95.167.109.75\";s:7:\"country\";s:2:\"RU\";s:5:\"title\";s:100:\"Единая система доступа к корпоративному порталу БелГУ\";}s:13:\"mail.misis.ru\";a:3:{s:2:\"ip\";s:14:\"85.143.104.132\";s:7:\"country\";s:2:\"RU\";s:5:\"title\";s:66:\"MISIS Webmail :: Добро пожаловать в MISIS Webmail!\";}s:12:\"www.bing.com\";a:3:{s:2:\"ip\";s:13:\"13.107.21.200\";s:7:\"country\";s:2:\"US\";s:5:\"title\";s:4:\"Bing\";}s:8:\"m.vk.com\";a:3:{s:2:\"ip\";s:13:\"87.240.190.67\";s:7:\"country\";s:2:\"RU\";s:5:\"title\";s:71:\"Мобильная версия ВКонтакте | ВКонтакте\";}s:9:\"e.mail.ru\";a:3:{s:2:\"ip\";s:14:\"217.69.139.216\";s:7:\"country\";s:2:\"RU\";s:5:\"title\";s:22:\"Авторизация\";}s:13:\"itnt-conf.org\";a:3:{s:2:\"ip\";s:13:\"31.31.198.154\";s:7:\"country\";s:2:\"RU\";s:5:\"title\";s:14:\"Главная\";}s:16:\"outlook.live.com\";a:3:{s:2:\"ip\";s:12:\"13.107.42.11\";s:7:\"country\";s:2:\"US\";s:5:\"title\";s:59:\"Outlook – free personal email and calendar from Microsoft\";}s:16:\"www.photonics.su\";a:3:{s:2:\"ip\";s:13:\"91.142.90.121\";s:7:\"country\";s:2:\"RU\";s:5:\"title\";s:69:\"Фотоника - научно-технический журнал -\";}s:14:\"mail.yahoo.com\";a:3:{s:2:\"ip\";s:13:\"87.248.118.23\";s:7:\"country\";s:2:\"GB\";s:5:\"title\";s:10:\"Yahoo Mail\";}s:14:\"mail.yandex.ru\";a:3:{s:2:\"ip\";s:11:\"77.88.21.37\";s:7:\"country\";s:2:\"RU\";s:5:\"title\";s:44:\"ÐÐ²ÑÐ¾ÑÐ¸Ð·Ð°ÑÐ¸Ñ\";}}', 'no'),
(5200, 'wp_statistics_plugin_version', '12.6.13', 'yes'),
(5201, 'wp_statistics_db_version', '12.6.13', 'yes'),
(5202, 'wp_statistics', 'a:86:{s:18:\"pending_db_updates\";a:2:{s:13:\"date_ip_agent\";b:0;s:11:\"unique_date\";b:0;}s:16:\"search_converted\";i:1;s:9:\"robotlist\";s:1888:\"007ac9\r\n5bot\r\nA6-Indexer\r\nAbachoBOT\r\naccoona\r\nAcoiRobot\r\nAddThis.com\r\nADmantX\r\nAdsBot-Google\r\nadvbot\r\nAhrefsBot\r\naiHitBot\r\nalexa\r\nalphabot\r\nAltaVista\r\nAntivirusPro\r\nanyevent\r\nappie\r\nApplebot\r\narchive.org_bot\r\nAsk Jeeves\r\nASPSeek\r\nBaiduspider\r\nBenjojo\r\nBeetleBot\r\nbingbot\r\nBlekkobot\r\nblexbot\r\nBOT for JCE\r\nbubing\r\nButterfly\r\ncbot\r\nclamantivirus\r\ncliqzbot\r\nclumboot\r\ncoccoc\r\ncrawler\r\nCrocCrawler\r\ncrowsnest.tv\r\ndbot\r\ndl2bot\r\ndotbot\r\ndownloadbot\r\nduckduckgo\r\nDumbot\r\nEasouSpider\r\neStyle\r\nEveryoneSocialBot\r\nExabot\r\nezooms\r\nfacebook.com\r\nfacebookexternalhit\r\nFAST\r\nFeedfetcher-Google\r\nfeedzirra\r\nfindxbot\r\nFirfly\r\nFriendFeedBot\r\nfroogle\r\nGeonaBot\r\nGigabot\r\ngirafabot\r\ngimme60bot\r\nglbot\r\nGooglebot\r\nGroupHigh\r\nia_archiver\r\nIDBot\r\nInfoSeek\r\ninktomi\r\nIstellaBot\r\njetmon\r\nKraken\r\nLeikibot\r\nlinkapediabot\r\nlinkdexbot\r\nLinkpadBot\r\nLoadTimeBot\r\nlooksmart\r\nltx71\r\nLycos\r\nMail.RU_Bot\r\nMe.dium\r\nmeanpathbot\r\nmediabot\r\nmedialbot\r\nMediapartners-Google\r\nMJ12bot\r\nmsnbot\r\nMojeekBot\r\nmonobot\r\nmoreover\r\nMRBOT\r\nNationalDirectory\r\nNerdyBot\r\nNetcraftSurveyAgent\r\nniki-bot\r\nnutch\r\nOpenbot\r\nOrangeBot\r\nowler\r\np4Bot\r\nPaperLiBot\r\npageanalyzer\r\nPagesInventory\r\nPimonster\r\nporkbun\r\npr-cy\r\nproximic\r\npwbot\r\nr4bot\r\nrabaz\r\nRambler\r\nRankivabot\r\nrevip\r\nriddler\r\nrogerbot\r\nScooter\r\nScrubby\r\nscrapy.org\r\nSearchmetricsBot\r\nsees.co\r\nSemanticBot\r\nSemrushBot\r\nSeznamBot\r\nsfFeedReader\r\nshareaholic-bot\r\nsistrix\r\nSiteExplorer\r\nSlurp\r\nSocialradarbot\r\nSocialSearch\r\nSogou web spider\r\nSpade\r\nspbot\r\nSpiderLing\r\nSputnikBot\r\nSuperfeedr\r\nSurveyBot\r\nTechnoratiSnoop\r\nTECNOSEEK\r\nTeoma\r\ntrendictionbot\r\nTweetmemeBot\r\nTwiceler\r\nTwitterbot\r\nTwitturls\r\nu2bot\r\nuMBot-LN\r\nuni5download\r\nunrulymedia\r\nUptimeRobot\r\nURL_Spider_SQL\r\nVagabondo\r\nvBSEO\r\nWASALive-Bot\r\nWebAlta Crawler\r\nWebBug\r\nWebFindBot\r\nWebMasterAid\r\nWeSEE\r\nWotbox\r\nwsowner\r\nwsr-agent\r\nwww.galaxy.com\r\nx100bot\r\nXoviBot\r\nxzybot\r\nyandex\r\nYahoo\r\nYammybot\r\nYoudaoBot\r\nZyBorg\r\nZemlyaCrawl\";s:13:\"anonymize_ips\";s:0:\"\";s:5:\"geoip\";s:2:\"on\";s:10:\"useronline\";s:1:\"1\";s:6:\"visits\";s:1:\"1\";s:8:\"visitors\";s:1:\"1\";s:5:\"pages\";s:1:\"1\";s:12:\"check_online\";s:3:\"120\";s:8:\"menu_bar\";s:1:\"0\";s:11:\"coefficient\";s:1:\"1\";s:12:\"stats_report\";s:0:\"\";s:11:\"time_report\";s:5:\"daily\";s:11:\"send_report\";s:4:\"mail\";s:14:\"content_report\";s:0:\"\";s:12:\"update_geoip\";s:0:\"\";s:8:\"store_ua\";s:0:\"\";s:21:\"exclude_administrator\";s:1:\"1\";s:18:\"disable_se_clearch\";s:1:\"1\";s:16:\"disable_se_qwant\";s:1:\"1\";s:16:\"disable_se_baidu\";s:1:\"1\";s:14:\"disable_se_ask\";s:1:\"1\";s:8:\"map_type\";s:6:\"jqvmap\";s:9:\"ip_method\";s:11:\"REMOTE_ADDR\";s:18:\"force_robot_update\";s:1:\"1\";s:17:\"show_welcome_page\";b:0;s:23:\"first_show_welcome_page\";b:1;s:15:\"disable_se_bing\";s:0:\"\";s:21:\"disable_se_duckduckgo\";s:0:\"\";s:17:\"disable_se_google\";s:0:\"\";s:16:\"disable_se_yahoo\";s:0:\"\";s:17:\"disable_se_yandex\";s:0:\"\";s:12:\"visitors_log\";s:0:\"\";s:15:\"track_all_pages\";s:0:\"\";s:16:\"use_cache_plugin\";s:0:\"\";s:14:\"disable_column\";s:0:\"\";s:16:\"hit_post_metabox\";s:0:\"\";s:9:\"show_hits\";s:0:\"\";s:21:\"display_hits_position\";s:1:\"0\";s:12:\"chart_totals\";s:0:\"\";s:12:\"hide_notices\";s:0:\"\";s:10:\"all_online\";s:0:\"\";s:20:\"strip_uri_parameters\";s:0:\"\";s:14:\"addsearchwords\";s:0:\"\";s:8:\"hash_ips\";s:0:\"\";s:10:\"email_list\";s:14:\"admin@example.com\";s:12:\"geoip_report\";s:0:\"\";s:12:\"prune_report\";s:0:\"\";s:14:\"upgrade_report\";s:0:\"\";s:13:\"admin_notices\";s:0:\"\";s:11:\"disable_map\";s:0:\"\";s:17:\"disable_dashboard\";s:0:\"\";s:14:\"disable_editor\";s:0:\"\";s:15:\"read_capability\";s:14:\"manage_options\";s:17:\"manage_capability\";s:14:\"manage_options\";s:14:\"exclude_editor\";s:0:\"\";s:14:\"exclude_author\";s:0:\"\";s:19:\"exclude_contributor\";s:0:\"\";s:18:\"exclude_subscriber\";s:0:\"\";s:17:\"record_exclusions\";s:0:\"\";s:10:\"exclude_ip\";s:0:\"\";s:17:\"exclude_loginpage\";s:0:\"\";s:17:\"exclude_adminpage\";s:0:\"\";s:18:\"excluded_countries\";s:0:\"\";s:18:\"included_countries\";s:0:\"\";s:14:\"excluded_hosts\";s:0:\"\";s:15:\"robot_threshold\";s:0:\"\";s:12:\"use_honeypot\";s:0:\"\";s:15:\"honeypot_postid\";s:0:\"\";s:13:\"exclude_feeds\";s:0:\"\";s:13:\"excluded_urls\";s:0:\"\";s:12:\"exclude_404s\";s:0:\"\";s:20:\"corrupt_browser_info\";s:0:\"\";s:12:\"exclude_ajax\";s:0:\"\";s:14:\"schedule_geoip\";s:0:\"\";s:10:\"geoip_city\";s:2:\"on\";s:8:\"auto_pop\";s:0:\"\";s:20:\"private_country_code\";s:3:\"000\";s:12:\"referrerspam\";s:0:\"\";s:21:\"schedule_referrerspam\";s:0:\"\";s:16:\"schedule_dbmaint\";s:0:\"\";s:21:\"schedule_dbmaint_days\";s:3:\"365\";s:24:\"schedule_dbmaint_visitor\";s:0:\"\";s:29:\"schedule_dbmaint_visitor_hits\";s:2:\"50\";s:13:\"last_geoip_dl\";i:1587405013;}', 'yes'),
(5215, 'wp_statistics_overview_page_ads', 'a:3:{s:9:\"timestamp\";i:1630081758;s:3:\"ads\";a:6:{s:2:\"ID\";s:18:\"advanced-reporting\";s:5:\"title\";s:18:\"Advanced Reporting\";s:4:\"link\";s:69:\"https://wp-statistics.com/downloads/wp-statistics-advanced-reporting/\";s:5:\"image\";s:20:\"https://j.mp/3iD5wXn\";s:7:\"_target\";s:3:\"yes\";s:6:\"status\";s:3:\"yes\";}s:4:\"view\";s:0:\"\";}', 'no');

-- --------------------------------------------------------

--
-- Структура таблицы `zi_postmeta`
--

CREATE TABLE `zi_postmeta` (
  `meta_id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `zi_postmeta`
--

INSERT INTO `zi_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
(15, 11, '_edit_lock', '1598989238:1'),
(16, 11, '_wp_page_template', 'page-templates/service.php'),
(17, 11, '_edit_last', '1'),
(18, 13, '_edit_lock', '1583792070:1'),
(19, 13, '_wp_page_template', 'page-templates/mails.php'),
(20, 16, '_edit_lock', '1583632398:1'),
(21, 16, '_wp_page_template', 'page-templates/autosender.php'),
(31, 13, '_edit_last', '1'),
(32, 22, '_edit_lock', '1583802264:1'),
(33, 22, '_wp_page_template', 'page-templates/letters.php'),
(41, 32, '_edit_lock', '1598987951:1'),
(42, 32, '_wp_page_template', 'page-templates/stats.php'),
(43, 38, '_edit_lock', '1593375295:1'),
(44, 38, '_wp_page_template', 'page-templates/helper.php');

-- --------------------------------------------------------

--
-- Структура таблицы `zi_posts`
--

CREATE TABLE `zi_posts` (
  `ID` bigint UNSIGNED NOT NULL,
  `post_author` bigint UNSIGNED NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` bigint UNSIGNED NOT NULL DEFAULT '0',
  `guid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int NOT NULL DEFAULT '0',
  `post_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `zi_posts`
--

INSERT INTO `zi_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(11, 1, '2020-03-05 20:32:27', '2020-03-05 16:32:27', '', 'Service', '', 'publish', 'closed', 'closed', '', 'service', '', '', '2020-03-05 20:32:51', '2020-03-05 16:32:51', '', 0, '', 0, 'page', '', 0),
(13, 1, '2020-03-05 20:41:47', '2020-03-05 16:41:47', '', 'Mails', '', 'publish', 'closed', 'closed', '', 'mails', '', '', '2020-03-10 02:14:30', '2020-03-09 22:14:30', '', 0, '', 0, 'page', '', 0),
(16, 1, '2020-03-08 05:31:34', '2020-03-08 01:31:34', '', 'Autosender', '', 'publish', 'closed', 'closed', '', 'autosender', '', '', '2020-03-08 05:31:34', '2020-03-08 01:31:34', '', 0, '', 0, 'page', '', 0),
(22, 1, '2020-03-10 03:32:12', '2020-03-09 23:32:12', '', 'Letters', '', 'publish', 'closed', 'closed', '', 'letters', '', '', '2020-03-10 03:32:12', '2020-03-09 23:32:12', '', 0, '', 0, 'page', '', 0),
(32, 1, '2020-04-24 10:28:41', '2020-04-24 06:28:41', '', 'Stats', '', 'publish', 'closed', 'closed', '', 'stats', '', '', '2020-04-24 10:28:41', '2020-04-24 06:28:41', '', 0, '', 0, 'page', '', 0),
(38, 1, '2020-06-28 22:03:44', '2020-06-28 18:03:44', '', 'Helper', '', 'publish', 'closed', 'closed', '', 'helper', '', '', '2020-06-28 22:03:56', '2020-06-28 18:03:56', '', 0, '', 0, 'page', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `zi_statistics_exclusions`
--

CREATE TABLE `zi_statistics_exclusions` (
  `ID` int NOT NULL,
  `date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `count` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_statistics_historical`
--

CREATE TABLE `zi_statistics_historical` (
  `ID` bigint NOT NULL,
  `category` varchar(25) NOT NULL,
  `page_id` bigint NOT NULL,
  `uri` varchar(255) NOT NULL,
  `value` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_statistics_pages`
--

CREATE TABLE `zi_statistics_pages` (
  `page_id` bigint NOT NULL,
  `uri` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `count` int NOT NULL,
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_statistics_search`
--

CREATE TABLE `zi_statistics_search` (
  `ID` bigint NOT NULL,
  `last_counter` date NOT NULL,
  `engine` varchar(64) NOT NULL,
  `host` varchar(255) DEFAULT NULL,
  `words` varchar(255) DEFAULT NULL,
  `visitor` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_statistics_useronline`
--

CREATE TABLE `zi_statistics_useronline` (
  `ID` int NOT NULL,
  `ip` varchar(60) NOT NULL,
  `created` int DEFAULT NULL,
  `timestamp` int NOT NULL,
  `date` datetime NOT NULL,
  `referred` text NOT NULL,
  `agent` varchar(255) NOT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `location` varchar(10) DEFAULT NULL,
  `user_id` bigint NOT NULL,
  `page_id` bigint NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_statistics_visit`
--

CREATE TABLE `zi_statistics_visit` (
  `ID` int NOT NULL,
  `last_visit` datetime NOT NULL,
  `last_counter` date NOT NULL,
  `visit` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_statistics_visitor`
--

CREATE TABLE `zi_statistics_visitor` (
  `ID` int NOT NULL,
  `last_counter` date NOT NULL,
  `referred` text NOT NULL,
  `agent` varchar(255) NOT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `UAString` varchar(255) DEFAULT NULL,
  `ip` varchar(60) NOT NULL,
  `location` varchar(10) DEFAULT NULL,
  `hits` int DEFAULT NULL,
  `honeypot` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_termmeta`
--

CREATE TABLE `zi_termmeta` (
  `meta_id` bigint UNSIGNED NOT NULL,
  `term_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_terms`
--

CREATE TABLE `zi_terms` (
  `term_id` bigint UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `term_group` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_term_relationships`
--

CREATE TABLE `zi_term_relationships` (
  `object_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `term_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_term_taxonomy`
--

CREATE TABLE `zi_term_taxonomy` (
  `term_taxonomy_id` bigint UNSIGNED NOT NULL,
  `term_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` bigint UNSIGNED NOT NULL DEFAULT '0',
  `count` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_usermeta`
--

CREATE TABLE `zi_usermeta` (
  `umeta_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zi_users`
--

CREATE TABLE `zi_users` (
  `ID` bigint UNSIGNED NOT NULL,
  `user_login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_status` int NOT NULL DEFAULT '0',
  `display_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `zi_v_report`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `zi_v_report` (
`ID` int unsigned
,`Honorific` varchar(8)
,`FirstName` varchar(64)
,`LastName` varchar(64)
,`EMail` varchar(64)
,`Country` varchar(3)
,`Organization` varchar(1024)
,`OrganizationCity` varchar(128)
,`Status` varchar(16)
,`DateTime` datetime
,`PaymentFromOrg` varchar(1)
,`YouthSchool` varchar(1)
,`Format` varchar(6)
,`ECID` int unsigned
);

-- --------------------------------------------------------

--
-- Структура таблицы `zi_wpmm_subscribers`
--

CREATE TABLE `zi_wpmm_subscribers` (
  `id_subscriber` bigint NOT NULL,
  `email` varchar(50) NOT NULL,
  `insert_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура для представления `zi_v_report`
--
DROP TABLE IF EXISTS `zi_v_report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zi_v_report`  AS SELECT `p`.`ID` AS `ID`, `p`.`Honorific` AS `Honorific`, `p`.`FirstName` AS `FirstName`, `p`.`LastName` AS `LastName`, `p`.`EMail` AS `EMail`, `p`.`Country` AS `Country`, `p`.`Organization` AS `Organization`, `p`.`OrganizationCity` AS `OrganizationCity`, if((`p`.`SciStatus` = 'D'),'Doctor/PhD',if((`p`.`SciStatus` = 'S'),'Student/Postgrad','Other')) AS `Status`, `p`.`DateTime` AS `DateTime`, `p`.`PaymentFromOrg` AS `PaymentFromOrg`, `p`.`YouthSchool` AS `YouthSchool`, if((`al`.`Format` is null),NULL,if((`al`.`Format` = 'P'),'Poster',if((`al`.`Format` = 'V'),'Video','Live'))) AS `Format`, `a`.`ECID` AS `ECID` FROM ((`zi_ab_participants` `p` left join `zi_ab_arlinks` `al` on((`al`.`ID_Participant` = `p`.`ID`))) left join `zi_ab_articles` `a` on((`a`.`ID` = `al`.`ID_Article`))) WHERE (`p`.`ID_Conf` = 2) ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `zi_ab_arlinks`
--
ALTER TABLE `zi_ab_arlinks`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID_Article` (`ID_Article`),
  ADD KEY `ARLRel_2` (`ID_Participant`);

--
-- Индексы таблицы `zi_ab_articles`
--
ALTER TABLE `zi_ab_articles`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ARel_1` (`ID_Conf`);

--
-- Индексы таблицы `zi_ab_authors`
--
ALTER TABLE `zi_ab_authors`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `AULink_1` (`ID_Article`);

--
-- Индексы таблицы `zi_ab_confs`
--
ALTER TABLE `zi_ab_confs`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `zi_ab_events`
--
ALTER TABLE `zi_ab_events`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `zi_ab_evlinks`
--
ALTER TABLE `zi_ab_evlinks`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ELRel_1` (`ID_Event`),
  ADD KEY `ELRel_2` (`ID_Participant`);

--
-- Индексы таблицы `zi_ab_logs`
--
ALTER TABLE `zi_ab_logs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `LRel_1` (`ID_User`);

--
-- Индексы таблицы `zi_ab_outmails`
--
ALTER TABLE `zi_ab_outmails`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `OMLinks_1` (`ID_User`);

--
-- Индексы таблицы `zi_ab_participants`
--
ALTER TABLE `zi_ab_participants`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Link` (`Link`),
  ADD UNIQUE KEY `EMail` (`EMail`,`ID_Conf`) USING BTREE,
  ADD KEY `PRel_1` (`ID_Conf`);

--
-- Индексы таблицы `zi_ab_rooms`
--
ALTER TABLE `zi_ab_rooms`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Link` (`Link`);

--
-- Индексы таблицы `zi_ab_uploads`
--
ALTER TABLE `zi_ab_uploads`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `zi_ab_workshops`
--
ALTER TABLE `zi_ab_workshops`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `zi_ab_wslinks`
--
ALTER TABLE `zi_ab_wslinks`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `WLRel_1` (`ID_Participant`),
  ADD KEY `WLRel_2` (`ID_Workshop`);

--
-- Индексы таблицы `zi_aryo_activity_log`
--
ALTER TABLE `zi_aryo_activity_log`
  ADD PRIMARY KEY (`histid`);

--
-- Индексы таблицы `zi_commentmeta`
--
ALTER TABLE `zi_commentmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Индексы таблицы `zi_comments`
--
ALTER TABLE `zi_comments`
  ADD PRIMARY KEY (`comment_ID`),
  ADD KEY `comment_post_ID` (`comment_post_ID`),
  ADD KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  ADD KEY `comment_date_gmt` (`comment_date_gmt`),
  ADD KEY `comment_parent` (`comment_parent`),
  ADD KEY `comment_author_email` (`comment_author_email`(10));

--
-- Индексы таблицы `zi_links`
--
ALTER TABLE `zi_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `link_visible` (`link_visible`);

--
-- Индексы таблицы `zi_options`
--
ALTER TABLE `zi_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`),
  ADD KEY `autoload` (`autoload`);

--
-- Индексы таблицы `zi_postmeta`
--
ALTER TABLE `zi_postmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Индексы таблицы `zi_posts`
--
ALTER TABLE `zi_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `post_name` (`post_name`(191)),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`);

--
-- Индексы таблицы `zi_statistics_exclusions`
--
ALTER TABLE `zi_statistics_exclusions`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `date` (`date`),
  ADD KEY `reason` (`reason`);

--
-- Индексы таблицы `zi_statistics_historical`
--
ALTER TABLE `zi_statistics_historical`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `page_id` (`page_id`),
  ADD UNIQUE KEY `uri` (`uri`),
  ADD KEY `category` (`category`);

--
-- Индексы таблицы `zi_statistics_pages`
--
ALTER TABLE `zi_statistics_pages`
  ADD PRIMARY KEY (`page_id`),
  ADD UNIQUE KEY `date_2` (`date`,`uri`),
  ADD KEY `url` (`uri`),
  ADD KEY `date` (`date`),
  ADD KEY `id` (`id`),
  ADD KEY `uri` (`uri`,`count`,`id`);

--
-- Индексы таблицы `zi_statistics_search`
--
ALTER TABLE `zi_statistics_search`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `last_counter` (`last_counter`),
  ADD KEY `engine` (`engine`),
  ADD KEY `host` (`host`);

--
-- Индексы таблицы `zi_statistics_useronline`
--
ALTER TABLE `zi_statistics_useronline`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `zi_statistics_visit`
--
ALTER TABLE `zi_statistics_visit`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_date` (`last_counter`);

--
-- Индексы таблицы `zi_statistics_visitor`
--
ALTER TABLE `zi_statistics_visitor`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `date_ip_agent` (`last_counter`,`ip`,`agent`(75),`platform`(75),`version`(75)),
  ADD KEY `agent` (`agent`),
  ADD KEY `platform` (`platform`),
  ADD KEY `version` (`version`),
  ADD KEY `location` (`location`);

--
-- Индексы таблицы `zi_termmeta`
--
ALTER TABLE `zi_termmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `term_id` (`term_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Индексы таблицы `zi_terms`
--
ALTER TABLE `zi_terms`
  ADD PRIMARY KEY (`term_id`),
  ADD KEY `slug` (`slug`(191)),
  ADD KEY `name` (`name`(191));

--
-- Индексы таблицы `zi_term_relationships`
--
ALTER TABLE `zi_term_relationships`
  ADD PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  ADD KEY `term_taxonomy_id` (`term_taxonomy_id`);

--
-- Индексы таблицы `zi_term_taxonomy`
--
ALTER TABLE `zi_term_taxonomy`
  ADD PRIMARY KEY (`term_taxonomy_id`),
  ADD UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  ADD KEY `taxonomy` (`taxonomy`);

--
-- Индексы таблицы `zi_usermeta`
--
ALTER TABLE `zi_usermeta`
  ADD PRIMARY KEY (`umeta_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Индексы таблицы `zi_users`
--
ALTER TABLE `zi_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_login_key` (`user_login`),
  ADD KEY `user_nicename` (`user_nicename`),
  ADD KEY `user_email` (`user_email`);

--
-- Индексы таблицы `zi_wpmm_subscribers`
--
ALTER TABLE `zi_wpmm_subscribers`
  ADD PRIMARY KEY (`id_subscriber`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `zi_ab_arlinks`
--
ALTER TABLE `zi_ab_arlinks`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_articles`
--
ALTER TABLE `zi_ab_articles`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_authors`
--
ALTER TABLE `zi_ab_authors`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_confs`
--
ALTER TABLE `zi_ab_confs`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_events`
--
ALTER TABLE `zi_ab_events`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_evlinks`
--
ALTER TABLE `zi_ab_evlinks`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_logs`
--
ALTER TABLE `zi_ab_logs`
  MODIFY `ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_outmails`
--
ALTER TABLE `zi_ab_outmails`
  MODIFY `ID` smallint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_participants`
--
ALTER TABLE `zi_ab_participants`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_rooms`
--
ALTER TABLE `zi_ab_rooms`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_workshops`
--
ALTER TABLE `zi_ab_workshops`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_ab_wslinks`
--
ALTER TABLE `zi_ab_wslinks`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_aryo_activity_log`
--
ALTER TABLE `zi_aryo_activity_log`
  MODIFY `histid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_commentmeta`
--
ALTER TABLE `zi_commentmeta`
  MODIFY `meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_comments`
--
ALTER TABLE `zi_comments`
  MODIFY `comment_ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_links`
--
ALTER TABLE `zi_links`
  MODIFY `link_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_options`
--
ALTER TABLE `zi_options`
  MODIFY `option_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28757;

--
-- AUTO_INCREMENT для таблицы `zi_postmeta`
--
ALTER TABLE `zi_postmeta`
  MODIFY `meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT для таблицы `zi_posts`
--
ALTER TABLE `zi_posts`
  MODIFY `ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `zi_statistics_exclusions`
--
ALTER TABLE `zi_statistics_exclusions`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_statistics_historical`
--
ALTER TABLE `zi_statistics_historical`
  MODIFY `ID` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_statistics_pages`
--
ALTER TABLE `zi_statistics_pages`
  MODIFY `page_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_statistics_search`
--
ALTER TABLE `zi_statistics_search`
  MODIFY `ID` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_statistics_useronline`
--
ALTER TABLE `zi_statistics_useronline`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_statistics_visit`
--
ALTER TABLE `zi_statistics_visit`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_statistics_visitor`
--
ALTER TABLE `zi_statistics_visitor`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_termmeta`
--
ALTER TABLE `zi_termmeta`
  MODIFY `meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_terms`
--
ALTER TABLE `zi_terms`
  MODIFY `term_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_term_taxonomy`
--
ALTER TABLE `zi_term_taxonomy`
  MODIFY `term_taxonomy_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_usermeta`
--
ALTER TABLE `zi_usermeta`
  MODIFY `umeta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_users`
--
ALTER TABLE `zi_users`
  MODIFY `ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zi_wpmm_subscribers`
--
ALTER TABLE `zi_wpmm_subscribers`
  MODIFY `id_subscriber` bigint NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `zi_ab_arlinks`
--
ALTER TABLE `zi_ab_arlinks`
  ADD CONSTRAINT `ARLRel_1` FOREIGN KEY (`ID_Article`) REFERENCES `zi_ab_articles` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ARLRel_2` FOREIGN KEY (`ID_Participant`) REFERENCES `zi_ab_participants` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `zi_ab_articles`
--
ALTER TABLE `zi_ab_articles`
  ADD CONSTRAINT `ARel_1` FOREIGN KEY (`ID_Conf`) REFERENCES `zi_ab_confs` (`ID`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `zi_ab_authors`
--
ALTER TABLE `zi_ab_authors`
  ADD CONSTRAINT `AULink_1` FOREIGN KEY (`ID_Article`) REFERENCES `zi_ab_articles` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `zi_ab_evlinks`
--
ALTER TABLE `zi_ab_evlinks`
  ADD CONSTRAINT `ELRel_1` FOREIGN KEY (`ID_Event`) REFERENCES `zi_ab_events` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ELRel_2` FOREIGN KEY (`ID_Participant`) REFERENCES `zi_ab_participants` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `zi_ab_logs`
--
ALTER TABLE `zi_ab_logs`
  ADD CONSTRAINT `LRel_1` FOREIGN KEY (`ID_User`) REFERENCES `zi_users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `zi_ab_outmails`
--
ALTER TABLE `zi_ab_outmails`
  ADD CONSTRAINT `OMLinks_1` FOREIGN KEY (`ID_User`) REFERENCES `zi_users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `zi_ab_participants`
--
ALTER TABLE `zi_ab_participants`
  ADD CONSTRAINT `PRel_1` FOREIGN KEY (`ID_Conf`) REFERENCES `zi_ab_confs` (`ID`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `zi_ab_wslinks`
--
ALTER TABLE `zi_ab_wslinks`
  ADD CONSTRAINT `WLRel_1` FOREIGN KEY (`ID_Participant`) REFERENCES `zi_ab_participants` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `WLRel_2` FOREIGN KEY (`ID_Workshop`) REFERENCES `zi_ab_workshops` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
