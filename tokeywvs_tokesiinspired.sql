-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2026 at 02:34 PM
-- Server version: 11.4.9-MariaDB-cll-lve-log
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tokeywvs_tokesiinspired`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_articles`
--

CREATE TABLE `blog_articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` enum('blog','event') NOT NULL DEFAULT 'blog',
  `short_description` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `author_name` varchar(255) NOT NULL DEFAULT 'Tokesi Akinola',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_keywords`)),
  `status` enum('draft','scheduled','published') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `views_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `comments_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_articles`
--

INSERT INTO `blog_articles` (`id`, `title`, `slug`, `type`, `short_description`, `content`, `featured_image`, `author_name`, `meta_title`, `meta_description`, `meta_keywords`, `status`, `published_at`, `sort_order`, `views_count`, `comments_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Show Me Your Friend', 'show-me-your-friend', 'blog', '<p>just testing this</p>', '<p>Lorem ipsum</p>', 'media-library/01KBDAFE5SG87S0GDND0HMJNM2.webp', 'Tokesi Akinola', 'Show Me Your Friend', NULL, '[]', 'published', '2025-12-01 13:04:33', 1, 50, 0, '2025-12-01 13:06:58', '2026-02-20 18:41:28', NULL),
(2, 'Raising Cubs Into Lion', 'what-children-expects', 'blog', '<p>Raising children is like nurturing cubs into lions. This article explores how guidance, storytelling, and values shape confident and courageous children.</p>', '<p>Every child is born with potential, much like a cub destined to become a lion. The journey from childhood to strength, confidence, and leadership does not happen by chance—it is shaped by guidance, values, and intentional nurturing. For parents and educators, raising children means preparing them not just for today, but for the future.</p><p><img src=\"http://localhost:8000/storage/blog-content-images/uhGvvpVRoiF4xt0eslBtuvxBOzqhV28jAlLohGwz.jpg\" alt=\"children\" data-id=\"blog-content-images/uhGvvpVRoiF4xt0eslBtuvxBOzqhV28jAlLohGwz.jpg\"></p><p>Children learn best through stories. Stories help them understand courage, responsibility, kindness, and resilience in ways that rules alone cannot. When children read or hear meaningful stories, they begin to see examples of strong character and positive decision-making that they can mirror in their own lives.</p><p>Guidance plays a crucial role in shaping a child’s mindset. Encouragement builds confidence, while correction teaches discipline and accountability. When children are supported yet challenged, they grow into individuals who are not afraid to stand firm, speak up, and lead with integrity.</p><p><img src=\"http://localhost:8000/storage/blog-content-images/76zC1pUZO1YN0oTR27imI2X1oiVbgYQdBwqojnZ6.jpg\" alt=\"cubsflyer\" data-id=\"blog-content-images/76zC1pUZO1YN0oTR27imI2X1oiVbgYQdBwqojnZ6.jpg\"></p><p>Values are the foundation of every strong adult. Teaching children honesty, empathy, respect, and perseverance early in life helps them develop emotional strength. These values become the compass that guides their decisions as they grow older.</p><p>Raising cubs into lions is not about force, but about patience, consistency, and love. When children are nurtured in the right environment, they grow into confident, courageous individuals ready to face the world and make a meaningful impact.</p>', 'blog-images/01KGM1CZWRXXQMGDW0ZV1WNJP8.webp', 'Tokesi Akinola', 'Raising Cubs Into Lions: Shaping Strong Children', 'Explore how stories, guidance, and values help raise confident children into strong, courageous leaders of tomorrow.', '[]', 'published', '2026-02-04 08:45:32', 0, 34, 5, '2026-02-04 08:58:09', '2026-02-20 18:41:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_article_blog_category`
--

CREATE TABLE `blog_article_blog_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_article_id` bigint(20) UNSIGNED NOT NULL,
  `blog_category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_article_blog_category`
--

INSERT INTO `blog_article_blog_category` (`id`, `blog_article_id`, `blog_category_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_article_blog_tag`
--

CREATE TABLE `blog_article_blog_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_article_id` bigint(20) UNSIGNED NOT NULL,
  `blog_tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Children Event', 'children-event', NULL, '2026-01-12 18:46:18', '2026-01-12 18:46:18'),
(2, 'Children Story', 'children-story', NULL, '2026-02-04 08:57:17', '2026-02-04 08:57:17');

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_article_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `author_name` varchar(255) NOT NULL,
  `author_email` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_comments`
--

INSERT INTO `blog_comments` (`id`, `blog_article_id`, `parent_id`, `author_name`, `author_email`, `content`, `is_admin`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 'Michael Adeyeye', 'madeyeye13@gmail.com', 'Nice Content.I love it', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 09:10:42', '2026-02-04 09:10:42'),
(2, 2, NULL, 'Michael Adeyeye', 'madeyeye13@gmail.com', 'Nice Content.I love it', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 09:22:13', '2026-02-04 09:22:13'),
(3, 2, NULL, 'Bezalel Concept', 'madeyeye13@gmail.com', 'Wonderful blog content.', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 09:23:26', '2026-02-04 09:23:26'),
(4, 2, NULL, 'Toyosi Adeoye', 'madeyeye13@gmail.com', 'I  love this masterpiece. Worth Reading', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 09:29:23', '2026-02-04 09:29:23'),
(5, 2, 4, 'Tokesi Akinola (Admin)', 'author@tokesiakinola.com', 'Thanks', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-04 09:43:37', '2026-02-04 09:43:37');

-- --------------------------------------------------------

--
-- Table structure for table `blog_tags`
--

CREATE TABLE `blog_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('tokesi-akinola-cache-laravel-sitemap.sitemap', 's:2591:\"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"><url><loc>https://tokesiakinola.com/</loc><lastmod>2026-02-23T01:24:44+00:00</lastmod><changefreq>weekly</changefreq><priority>1</priority></url><url><loc>https://tokesiakinola.com/about</loc><lastmod>2026-02-23T01:24:44+00:00</lastmod><changefreq>monthly</changefreq><priority>0.8</priority></url><url><loc>https://tokesiakinola.com/contact</loc><lastmod>2026-02-23T01:24:44+00:00</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url><url><loc>https://tokesiakinola.com/shop</loc><lastmod>2026-02-23T01:24:44+00:00</lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url><url><loc>https://tokesiakinola.com/blogs</loc><lastmod>2026-02-23T01:24:44+00:00</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url><url><loc>https://tokesiakinola.com/wigan</loc><lastmod>2026-02-23T01:24:44+00:00</lastmod><changefreq>weekly</changefreq><priority>0.85</priority></url><url><loc>https://tokesiakinola.com/manchester</loc><lastmod>2026-02-23T01:24:44+00:00</lastmod><changefreq>weekly</changefreq><priority>0.85</priority></url><url><loc>https://tokesiakinola.com/shop/product/show-me-your-friend</loc><lastmod>2025-12-08T07:38:07+00:00</lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url><url><loc>https://tokesiakinola.com/shop/product/what-if-your-cup</loc><lastmod>2025-12-09T07:12:21+00:00</lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url><url><loc>https://tokesiakinola.com/shop/product/sarahs-perfect-gift</loc><lastmod>2025-12-09T10:24:04+00:00</lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url><url><loc>https://tokesiakinola.com/shop/product/show-me-your-friend</loc><lastmod>2025-12-08T07:38:07+00:00</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url><url><loc>https://tokesiakinola.com/shop/product/what-if-your-cup</loc><lastmod>2025-12-09T07:12:21+00:00</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url><url><loc>https://tokesiakinola.com/shop/product/sarahs-perfect-gift</loc><lastmod>2025-12-09T10:24:04+00:00</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url><url><loc>https://tokesiakinola.com/blog/show-me-your-friend</loc><lastmod>2025-12-01T08:04:33+00:00</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url><url><loc>https://tokesiakinola.com/blog/what-children-expects</loc><lastmod>2026-02-04T03:45:32+00:00</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url></urlset>\n\";', 1771813484),
('tokesi-akinola-cache-livewire-rate-limiter:585b000d8f52d606526fc2b5cbf4d5953809c712', 'i:1;', 1770847103),
('tokesi-akinola-cache-livewire-rate-limiter:585b000d8f52d606526fc2b5cbf4d5953809c712:timer', 'i:1770847103;', 1770847103),
('tokesi-akinola-cache-livewire-rate-limiter:817522ae7a3cd6d3b2e9518260997088a69d8e0e', 'i:1;', 1770812971),
('tokesi-akinola-cache-livewire-rate-limiter:817522ae7a3cd6d3b2e9518260997088a69d8e0e:timer', 'i:1770812971;', 1770812971);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `applied_discount` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `session_id`, `product_id`, `quantity`, `price`, `applied_discount`, `created_at`, `updated_at`) VALUES
(2, 'cart_6919dde5e896e7.29313419', 1, 1, 6.00, NULL, '2025-11-16 15:08:54', '2025-11-16 15:08:54'),
(5, 'cart_691af6d2d43907.21626903', 1, 1, 6.00, NULL, '2025-11-17 12:05:17', '2025-11-17 12:12:28'),
(6, 'cart_691e6492841bd6.07759135', 1, 1, 6.00, NULL, '2025-11-19 23:45:21', '2025-11-19 23:45:21'),
(9, 'cart_691f36cf6e78f3.69508822', 1, 4, 6.00, NULL, '2025-11-20 15:51:01', '2025-11-20 16:52:07'),
(14, 'cart_693812449ac005.15877856', 3, 1, 6.99, NULL, '2025-12-09 15:28:17', '2025-12-09 15:28:17'),
(21, 'cart_693bee5c673a86.15751757', 2, 1, 5.00, NULL, '2025-12-12 10:29:46', '2025-12-12 10:29:46'),
(22, 'cart_693bf0619cc4f0.47816891', 2, 2, 5.00, NULL, '2025-12-12 10:38:15', '2025-12-12 10:40:57'),
(23, 'cart_693bf0619cc4f0.47816891', 1, 1, 6.99, NULL, '2025-12-12 10:40:55', '2025-12-12 10:40:55'),
(24, 'cart_693d8cc9a5f058.75942877', 3, 1, 6.99, NULL, '2025-12-13 15:57:38', '2025-12-13 15:57:38'),
(26, 'cart_6940c5afd0fed7.20312627', 3, 1, 6.99, NULL, '2025-12-16 02:42:17', '2025-12-16 02:42:17'),
(27, 'cart_69412727bca490.89659775', 3, 1, 6.99, NULL, '2025-12-16 09:32:26', '2025-12-16 09:32:26'),
(28, 'cart_6941ed95d9d1b9.93096456', 3, 1, 6.99, NULL, '2025-12-16 23:39:24', '2025-12-16 23:39:24'),
(29, 'cart_69652f3d12c4d7.84162164', 3, 1, 6.99, NULL, '2026-01-12 19:23:01', '2026-01-12 19:23:01'),
(30, 'cart_69738bc7446088.74430653', 2, 1, 5.00, NULL, '2026-01-23 15:00:59', '2026-01-23 15:00:59');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Children Book', 'children-book', NULL, '2025-12-08 12:36:16', '2025-12-08 12:36:16'),
(2, 'Inspiring Book', 'inspiring-book', NULL, '2025-12-08 12:36:54', '2025-12-08 12:36:54');

-- --------------------------------------------------------

--
-- Table structure for table `category_product`
--

CREATE TABLE `category_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_product`
--

INSERT INTO `category_product` (`id`, `product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 2, 1, NULL, NULL),
(4, 2, 2, NULL, NULL),
(5, 3, 1, NULL, NULL),
(6, 3, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') NOT NULL DEFAULT 'unread',
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `subject`, `message`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 'Michael Adeyeye', 'mikkygraphix14@gmail.com', 'BOOK INQUIRY', 'Hello Tokesi Akinola, I would like to make an enquiry on your book \"What If Your Cup\". I would like to know if you can supply our school with this book. \r\n\r\nKindly reach back to us.', 'unread', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-10 12:25:29', '2025-12-10 12:25:29'),
(2, 'Michael Adeyeye', 'mikkygraphix14@gmail.com', 'STILL CHECKING', 'I would like to check if you receive my message. Please reach back', 'unread', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-10 12:27:44', '2025-12-10 12:27:44'),
(3, 'Michael Adeyeye', 'mikkygraphix14@gmail.com', 'STILL CHECKING', 'I would like to check if you receive my message. Please reach back', 'unread', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-10 12:38:24', '2025-12-10 12:38:24'),
(4, 'Michael Adeyeye', 'mikkygraphix14@gmail.com', 'Domain Activation', 'I would like to check if you receive my message. Please reach back', 'unread', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-10 12:39:19', '2025-12-10 12:39:19'),
(5, 'Emmanuel Adeyeye', 'madeyeye13@gmail.com', 'URL NOT FOUND ON SERVER', 'The Lord is good all thetime', 'unread', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-10 12:43:07', '2025-12-10 12:43:07'),
(6, 'Michael Adeyeye', 'mikkygraphix14@gmail.com', 'COURSE MATERIALS', 'I’d love to hear from readers, parents, and educators! Send me a message using the form below, and I’ll get back to you soon.', 'unread', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-10 12:46:26', '2025-12-10 12:46:26'),
(7, 'John Doe', 'madeyeye13@gmail.com', 'Domain Activation', 'I’d love to hear from readers, parents, and educators! Send me a message using the form below, and I’ll get back to you soon.', 'unread', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-10 12:49:58', '2025-12-10 12:49:58'),
(8, 'John David', 'mikkygraphix14@gmail.com', 'CHECKING', 'I’d love to hear from readers, parents, and educators! Send me a message using the form below, and I’ll get back to you soon.', 'unread', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2025-12-10 12:54:26', '2025-12-10 12:54:26'),
(9, 'Michael Adeyeye', 'mikkygraphix14@gmail.com', 'Checking', 'I’d love to hear from readers, parents, and educators! Send me a message using the form below, and I’ll get back to you soon.', 'unread', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2025-12-10 12:57:23', '2025-12-10 12:57:23'),
(10, 'Michael Adeyeye', 'mikkygraphix14@gmail.com', 'Checking', 'I’d love to hear from readers, parents, and educators! Send me a message using the form below, and I’ll get back to you soon.', 'unread', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2025-12-10 12:59:13', '2025-12-10 12:59:13'),
(11, 'Michael Adeyeye', 'mikkygraphix14@gmail.com', 'Consultation', 'I’d love to hear from readers, parents, and educators! Send me a message using the form below, and I’ll get back to you soon.', 'unread', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-10 13:08:07', '2025-12-10 13:08:07'),
(12, 'Michael Adeyeye', 'mikkygraphix14@gmail.com', 'Domain Activation', 'I’d love to hear from readers, parents, and educators! Send me a message using the form below, and I’ll get back to you soon.', 'unread', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-10 13:10:27', '2025-12-10 13:10:27'),
(13, 'Michael Aderibigbe', 'mikkygraphix14@gmail.com', 'Help', 'Jesus is Lord Amen.Hallelujah. Thank u Lord', 'unread', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2025-12-10 13:14:18', '2025-12-10 13:14:18'),
(14, 'Emmanuel Adeyeye', 'mikkygraphix14@gmail.com', 'STILL CHECKING', 'Oh Jesus, please help me get through this code', 'unread', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-10 13:26:00', '2025-12-10 13:26:00'),
(15, 'Dontae SEO', 'dontae.lucas2@gmail.com', 'Broken links issue', 'Hello,\r\n\r\nI reviewed your website briefly and found a few broken links that lead to ERROR PAGES. This can affect user experience and your Google rankings.\r\n\r\nIf you’d like, I can prepare a FREE REPORT that highlights these errors and explains how to fix them.\r\n\r\nI’m also available for a short Google Meet call if you prefer to go through the details together.\r\n\r\nWould you like me to send the FULL REPORT or a PROPOSAL?\r\n\r\nKind regards,', 'unread', '49.205.47.50', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 OPR/89.0.4447.51', '2026-01-02 05:09:08', '2026-01-02 05:09:08'),
(16, 'Deepak Parcha', 'parchad78@gmail.com', 'Want a Website That Brings You More Clients?', 'Hello http://tokesiakinola.com,\r\n\r\nI’d like to discuss website design and development for your business.\r\n\r\nWe create professional websites that help businesses attract more customers and build trust online.\r\n\r\nIf interested, I can share our website design packages and pricing details.\r\n\r\nThank you,\r\n\r\nDeepak Parcha', 'unread', '182.77.78.84', 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36', '2026-01-06 00:20:26', '2026-01-06 00:20:26'),
(17, 'Anaya', 'rawlings.teodoro@gmail.com', 'Get Your Website to Google 1st Page.', 'Hello http://tokesiakinola.com,\r\n \r\nIf you’re looking to boost your website’s visibility, I can help you achieve top Google rankings.\r\n \r\nI’ll prepare a complete SEO plan with actionable steps and potential growth insights for your products or services.\r\n \r\nOnce you share your target keywords and target market, I’ll send a full proposal.\r\n \r\nBest Regards,\r\nAnaya', 'unread', '122.162.144.199', 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36', '2026-01-06 07:33:07', '2026-01-06 07:33:07'),
(18, 'Sonam Prajapati', 'sonam.websolution12@gmail.com', 'Let\'s Get Your Website to Google\'s 1st Page', 'Hello http://tokesiakinola.com, \r\n\r\nI wanted to reach out to see if you’re open to exploring ways to grow your website traffic and boost online performance. \r\n\r\nWe offer customized SEO services that deliver measurable improvements. \r\n\r\nOnce you share your target keywords and target market, I’ll send a full proposal. \r\n\r\nBest Regards, \r\nSonam', 'unread', '223.233.71.96', 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36', '2026-01-06 16:36:56', '2026-01-06 16:36:56'),
(19, 'Deepak Parcha', 'parchad78@gmail.com', 'Want a Website That Brings You More Clients?', 'Hello http://tokesiakinola.com,\r\n\r\nI’d like to discuss website design and development for your business.\r\n\r\nWe create professional websites that help businesses attract more customers and build trust online.\r\n\r\nIf interested, I can share our website design packages and pricing details.\r\n\r\nThank you,\r\n\r\nDeepak Parcha', 'unread', '182.77.78.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36', '2026-01-06 21:51:36', '2026-01-06 21:51:36'),
(20, 'Nikita Joshi', 'nikita.sale01@gmail.com', 'Re Improve Traffic To Your Website', 'Hello http://tokesiakinola.com,\r\n\r\nIf you’re looking to boost your website’s visibility, I can help you achieve top Google rankings.\r\n\r\nI’ll prepare a complete SEO plan with actionable steps and potential growth insights for your products or services.\r\n\r\nIf you are interested, I can send you our past work, pricing and proposals.\r\n\r\nBest Regards,\r\nNikita', 'unread', '119.42.59.47', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 Edg/114.0.1264.71', '2026-01-08 07:42:06', '2026-01-08 07:42:06'),
(21, 'Naina Singh', 'naina.websolutions@gmail.com', 'Enhancing Your Website Design to Attract More Clients', 'Hello http://tokesiakinola.com,\r\n\r\nI design modern, user-friendly websites for small businesses and help improve their online presence.\r\n\r\nI wanted to check if you’re considering any updates to your current website—such as improving the design, usability, or adding features to better support your business.\r\n\r\nIf you’re planning a new website or a redesign, feel free to share your requirements and  reference website link. \r\n\r\nI’d be happy to discuss.\r\n\r\nKind regards,\r\nNaina', 'read', '223.233.74.134', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36', '2026-01-12 06:52:02', '2026-01-12 19:33:17'),
(22, 'Michael Adeyeye', 'mikkygraphix14@gmail.com', 'Checking', 'Wanted to confirm the email is working', 'unread', '102.88.108.119', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-12 19:49:42', '2026-01-12 19:49:42'),
(23, 'Ivy Brisbane', 'indexing@searches-tokesiakinola.com', 'tokesiakinola.com', 'Hi,\r\n\r\nRegister tokesiakinola.com to GoogleSearchIndex and have it displayed in search results!\r\n\r\nAdd tokesiakinola.com now at https://searchregister.org', 'unread', '68.182.248.10', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:114.0) Gecko/20100101 Firefox/114.0', '2026-01-24 22:11:02', '2026-01-24 22:11:02'),
(24, 'Aleida Vida', 'better@ai-tokesiakinola.com', 'tokesiakinola.com and A.I.', 'Users search using AI more & more.\r\n\r\nAdd tokesiakinola.com to our AI-optimized directory now to increase your chances of being recommended / mentioned.\r\n\r\nList it here:  https://AIREG.pro', 'unread', '161.115.239.145', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:114.0) Gecko/20100101 Firefox/114.0', '2026-01-26 00:50:51', '2026-01-26 00:50:51');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_count` int(11) NOT NULL DEFAULT 0,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_library`
--

CREATE TABLE `media_library` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `disk` varchar(255) NOT NULL DEFAULT 'public',
  `mime_type` varchar(255) NOT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `size_human` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `dimensions` varchar(255) DEFAULT NULL,
  `alt_text` text DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `collection` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media_library`
--

INSERT INTO `media_library` (`id`, `filename`, `original_filename`, `path`, `disk`, `mime_type`, `size`, `size_human`, `type`, `width`, `height`, `dimensions`, `alt_text`, `caption`, `tags`, `collection`, `metadata`, `created_at`, `updated_at`) VALUES
(3, '01KBDAFE5SG87S0GDND0HMJNM2.webp', '01KBDAFE5SG87S0GDND0HMJNM2.webp', 'media-library/01KBDAFE5SG87S0GDND0HMJNM2.webp', 'public', 'image/webp', 69232, '67.61 KB', 'image', 1462, 1110, '1462x1110', 'Tokesi', 'Tokesi', '[]', 'general', NULL, '2025-12-01 15:03:43', '2025-12-01 15:03:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_15_125935_add_is_admin_to_users_table', 2),
(5, '2025_11_15_153256_create_products_table', 3),
(6, '2025_11_15_162822_create_product_relations_tables', 3),
(7, '2025_11_15_163516_create_coupons_and_cart_items_tables', 3),
(8, '2025_11_16_171423_add_coupon_code_to_products_table', 4),
(9, '2025_11_16_171434_add_applied_discount_to_cart_items_table', 4),
(10, '2025_11_17_102506_create_reviews_table', 5),
(11, '2025_11_18_121928_create_orders_table', 6),
(12, '2025_11_20_002415_create_shipping_methods_table', 6),
(13, '2025_11_20_045556_create_blog_articles_table', 7),
(14, '2025_11_20_045752_create_blog_categories_tags_and_pivots_tables', 8),
(15, '2025_11_20_045905_create_blog_comments_table', 8),
(16, '2025_12_01_120434_create_testimonials_table', 9),
(17, '2025_12_01_124413_create_media_libraries_table', 10),
(18, '2025_12_01_143931_add_tags_to_media_library_table', 11),
(19, '2025_12_01_150946_add_missing_columns_to_media_library_table', 12),
(20, '2025_12_08_163318_add_tracking_fields_to_orders_table', 13),
(21, '2025_12_10_130724_create_contacts_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `shipping_address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `shipping_method` varchar(255) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `coupon_code` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment_method` enum('stripe','paypal') NOT NULL,
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_intent_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `tracking_number` varchar(255) DEFAULT NULL,
  `tracking_link` text DEFAULT NULL,
  `shipping_company` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `product_snapshot` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `compare_at_price` decimal(10,2) DEFAULT NULL,
  `type` enum('physical','pdf','both') NOT NULL DEFAULT 'physical',
  `inventory_qty` int(11) NOT NULL DEFAULT 0,
  `pdf_file_path` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `long_description` longtext DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_new_arrival` tinyint(1) NOT NULL DEFAULT 0,
  `coupon_percentage` decimal(5,2) DEFAULT NULL,
  `coupon_usage_limit` int(11) DEFAULT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `coupon_valid_from` timestamp NULL DEFAULT NULL,
  `coupon_valid_until` timestamp NULL DEFAULT NULL,
  `coupon_usage_count` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `price`, `compare_at_price`, `type`, `inventory_qty`, `pdf_file_path`, `short_description`, `long_description`, `is_featured`, `is_new_arrival`, `coupon_percentage`, `coupon_usage_limit`, `coupon_code`, `coupon_valid_from`, `coupon_valid_until`, `coupon_usage_count`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Show Me Your Friend', 'show-me-your-friend', 'SMYF-10-25', 6.99, NULL, 'physical', 8, NULL, 'A beautifully illustrated children’s book highlighting the impact of negative fun and the powerful influence of friendships on young lives.', '<p>A beautifully illustrated children’s book highlighting the impact of negative fun and the powerful influence of friendships on young lives.</p><ul><li><p>Reading Age: 5 - 13 years</p></li><li><p>Pages: 39 Pages</p></li><li><p>Language: English</p></li></ul>', 1, 0, NULL, NULL, NULL, NULL, NULL, 0, 1, '2025-11-16 14:11:59', '2025-12-08 12:38:07', NULL),
(2, 'What If Your Cup?', 'what-if-your-cup', 'WIYC-08-24', 5.00, NULL, 'physical', 7, NULL, 'What If Your Cup? is a short, inspiring poem that helps young children understand the dangers of spending excessive hours on gadgets and the importance of living with purpose. Using the simple metaphor of a cup, It explains one of the psychological effects of screen time in a way children can easily grasp.', '<p><strong>What If Your Cup?</strong> is a short, inspiring poem that helps young children understand the dangers of spending excessive hours on gadgets and the importance of living with purpose. Using the simple metaphor of a cup, It explains one of the psychological effects of screen time in a way children can easily grasp. Engaging and thoughtful, this book offers parents and educators a meaningful tool to spark conversations about balance and healthy screen time engagement. This is an ideal bedtime story or classroom read-aloud for children ages 4–9.</p><p></p><ul><li><p>Reading Age: 4 - 8 years</p></li><li><p>Pages: 24 Pages</p></li><li><p>Language: English<br><br></p></li></ul>', 1, 0, NULL, NULL, NULL, NULL, NULL, 0, 1, '2025-12-08 12:58:07', '2025-12-09 12:12:21', NULL),
(3, 'Sarah\'s Perfect Gift', 'sarahs-perfect-gift', 'SPG-08-24', 6.99, NULL, 'physical', 10, NULL, 'An uplifting children’s storybook that celebrates confidence, family love and the reassuring truth that we are fearfully and wonderfully made by the heavenly father, while standing firmly against bullying.', '<p><strong>Sarah&#039;s Perfect Gift</strong>: An uplifting children’s storybook that celebrates confidence, family love and the reassuring truth that we are fearfully and wonderfully made by the heavenly father, while standing firmly against bullying.</p><p></p><ul><li><p>Reading Age: 7 - 12 years</p></li><li><p>Pages: 43 pages</p></li><li><p>Language: English</p></li></ul>', 1, 0, NULL, NULL, NULL, NULL, NULL, 0, 1, '2025-12-08 13:03:19', '2025-12-09 15:24:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `sort_order`, `is_primary`, `created_at`, `updated_at`) VALUES
(11, 1, 'products/images/01KBZ3FQHPGVEAR2KMTH0TJS58.jpg', 0, 1, '2025-12-08 12:47:45', '2025-12-08 12:47:45'),
(12, 2, 'products/images/01KBZ42Q2XKM6G3N5R3E9FVFE5.jpg', 0, 1, '2025-12-08 12:58:08', '2025-12-08 12:58:08'),
(13, 3, 'products/images/01KBZ4C7ZPWGM8YSH9E6QZCFBG.jpg', 0, 1, '2025-12-08 13:03:20', '2025-12-08 13:03:20');

-- --------------------------------------------------------

--
-- Table structure for table `product_tag`
--

CREATE TABLE `product_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_tag`
--

INSERT INTO `product_tag` (`id`, `product_id`, `tag_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 1, NULL, NULL),
(3, 3, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `rating` decimal(2,1) NOT NULL,
  `review_text` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `first_name`, `last_name`, `rating`, `review_text`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, 'Michael', 'Brian', 5.0, 'Wonderful piece, My children love the book. I will recommend anytime', 1, '2025-11-17 11:33:22', '2025-11-17 11:51:03');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2ZTLXNdsU6PeisBo4olRJyBUeZK3LGNnLTQ7fF4S', NULL, '45.138.48.190', 'python-requests/2.32.5', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidUh2ZU1OaUpWVmMwMzZxZm04UGRGemVZUHJabnY5ZzczcEwxZnVOVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vdG9rZXNpYWtpbm9sYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771868226),
('4eqQmPsoJhxcIiCJVWTgXJm2z6Af4ODBJ0APA80f', NULL, '66.249.64.69', 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.7559.132 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzZWNXZQc29URjRWcGppVkZRNjlYblNmeFhNU1VEQnl3U0dDQ1VRMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vd3d3LnRva2VzaWFraW5vbGEuY29tL2NvbnRhY3QiO3M6NToicm91dGUiO3M6NzoiY29udGFjdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771857172),
('5AnHYpQTZy8iAnLYSUKyBe73IVbYlZAHbZ2v6fhN', NULL, '106.219.166.154', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:114.0) Gecko/20100101 Firefox/114.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieXFhZXB4Zms2Y3FSRXg4NDIyWXNiV3JlOW5wZXY1YkFHUlg3UDJZOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vdG9rZXNpYWtpbm9sYS5jb20vY29udGFjdCI7czo1OiJyb3V0ZSI7czo3OiJjb250YWN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771874660),
('8padSWsZD2vNo5SvLhEpm3xFny7yeHxGYJfOfYTV', NULL, '15.235.98.246', 'Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVnZ5b3NpTFJ0dUZqQzByMUYzOXY3Q1VUNllHeGRuRUVLeEJ6N3lHOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vdG9rZXNpYWtpbm9sYS5jb20vc2hvcC9wcm9kdWN0L3NhcmFocy1wZXJmZWN0LWdpZnQiO3M6NToicm91dGUiO3M6MTI6InByb2R1Y3Quc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771873489),
('Ahz3XMvUwosjv9vorBGTJzk87H55trrrjknvfyCa', NULL, '51.222.168.0', 'Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiakEwUm9FS25ZRkxxSEtYcXRLQ29PVWJHNE1yR09VNTNUZFJ4T052QyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vdG9rZXNpYWtpbm9sYS5jb20vc2hvcC9wcm9kdWN0L3doYXQtaWYteW91ci1jdXAiO3M6NToicm91dGUiO3M6MTI6InByb2R1Y3Quc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771866223),
('cUqdU7O4Gz2CTXJyFAnaVQrLWVyDIUjgMici2Y7A', NULL, '45.138.48.190', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiT2ZkZVY4MGdCQjdFQVNNbjVhRWIxVDBXcVNXbVFCcVBlTkQ2SUIxQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771868246),
('fFrt2W3vBok48NksY4hMc0GccMxYIm1YRpTU4lQK', NULL, '142.44.233.185', 'Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGxOSFY2NzE3V1AwMFIzc2tQbHVHQ3VZM05LTGVNZ3BWMjBLc2xCbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vdG9rZXNpYWtpbm9sYS5jb20vc2hvcC9wcm9kdWN0L3Nob3ctbWUteW91ci1mcmllbmQiO3M6NToicm91dGUiO3M6MTI6InByb2R1Y3Quc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771870000),
('Gdn1C52dJ0a5mt5B1IVmVf3xOQ4Xju4kv7ZrDr4A', NULL, '107.21.11.47', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicWRuQURCazhaWm16RThCbjdPam13bVJqWXpqSWk4amdKRElSMDF2ViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vd3d3LnRva2VzaWFraW5vbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771871974),
('HgEJmdJgi1VwUtGB6lXsospqmelnt1Hky3Wbz3ce', NULL, '52.200.58.199', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Amazonbot/0.1; +https://developer.amazon.com/support/amazonbot) Chrome/119.0.6045.214 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUWhWYm1DTkdyZnQ4dzh1MEM0b2Iyb1RTRjhGZWV3NmdHU2M0TE5FaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vdG9rZXNpYWtpbm9sYS5jb20vc2hvcC9wcm9kdWN0L3Nob3ctbWUteW91ci1mcmllbmQiO3M6NToicm91dGUiO3M6MTI6InByb2R1Y3Quc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771871808),
('HUdwvh21u8qw9hrABcHQPUFQFoCaN3Akd8CFSsLD', NULL, '41.218.199.208', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV016NWVKeHBTVzBvRjV1Y3pQMDVGbElSMndZU2dIOVh6cTJnNTVGTCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cHM6Ly90b2tlc2lha2lub2xhLmNvbS9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwczovL3Rva2VzaWFraW5vbGEuY29tL2FkbWluL2xvZ2luIjtzOjU6InJvdXRlIjtzOjI1OiJmaWxhbWVudC5hZG1pbi5hdXRoLmxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771864121),
('m9VeGagDeuJ3GSEz1EqsvqNFn7PITjSzmwz2OzGd', NULL, '125.94.144.102', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMXpIWXo4QjVsb0hzWXBrTGowa1ZraWxEaTdEMWkyWklMZ2cxMzVIZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vd3d3LnRva2VzaWFraW5vbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771868246),
('PpU6Zn6pRMFy6SpmApw1UpKqjOrHabNAC9OpDSdM', NULL, '43.157.53.115', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZGowRlZ1UUMyS3pXNkVWTGZUVkFWQ1hLVXlVQXpsT1REUmp0TE1VeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vd3d3LnRva2VzaWFraW5vbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771859183),
('qU85yqUiMq92V0nXy3dTuwaplanv8wYivzWsHdJY', NULL, '170.106.35.137', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFJYUUE3M2liOXlVZ3JNV05aSnRmSjJsaGU1Wm5LQ3AyMmZMSWRWaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vd3d3LnRva2VzaWFraW5vbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771863372),
('ty9PlUO01P1BhCRKXkEkIq4d9EaAgxR0KRjvH0F2', NULL, '49.13.129.57', 'Mozilla/5.0 (compatible; bot/1.0)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDVKZUtGSG51czVNU25tNFRFbFJlQzdtbk90bWxjSXpXTVVlbEJPYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vdG9rZXNpYWtpbm9sYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771865602),
('w9wC8mShOsLttg441JwZHBkyDbkWvzEtqljrDVMm', NULL, '199.45.154.129', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiazdjWm1HSHRRRDZiT0VlMFZKTFlENTJyNVVQY2g1bGlBZHVVVEh5cSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vd3d3LnRva2VzaWFraW5vbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771872890),
('Xl0UKTy3S1ID6FpE9V6C3zY5wBXgCybPfpHafO9D', NULL, '43.135.130.202', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUZXMXZoQnFzeGRsVnR5bGhKN3pTRmY0TkZKMk1XRzl6SEZMVDJ1aSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vdG9rZXNpYWtpbm9sYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771860032);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `delivery_time` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `description`, `price`, `delivery_time`, `country`, `state`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Standard', NULL, 2.00, '2-5', 'United Kingdom', 'Hull', 0, 1, '2026-01-12 19:26:24', '2026-01-12 19:26:24');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Story', 'story', '2025-12-08 12:37:29', '2025-12-08 12:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `location`, `text`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Sarah Williams', 'Wigan, Manchester', 'Tokesi’s stories have brought so much joy to my children. They can’t wait for the next book!\n\nMy kids love reading her book', 4, 1, '2025-12-11 18:46:19', '2025-12-16 10:22:13'),
(2, 'Emily Hart', 'Bolton, UK', 'We love reading Tokesi’s books before bedtime. Imaginative and educational all at once.', 3, 1, '2025-12-11 18:48:47', '2025-12-16 10:22:00'),
(3, 'Michael Thompson', 'Wigan, UK', 'Engaging, creative, and full of heart. Tokesi’s books are a must-have for every child.\n\nI would glady recommend her books anytime', 2, 1, '2025-12-11 18:50:09', '2025-12-16 10:21:48'),
(4, 'Elsie Hayford', 'United Kingdom', 'Meeting Tokesi Akinola as both a person and an author has been a joy, and her warmth and intentionality shine clearly through her books. As a parent, I value stories that go beyond entertainment, and her writing stands out for its purposeful, values-driven approach. Rooted in her background in early years education, her stories are age-appropriate, thoughtful, and never moralising. They gently encourage integrity, kindness, courage, and wise choices, sparking meaningful conversations and influencing my children’s everyday decisions. Tokesi Akinola’s books are a true gift to both children and parents seeking stories that nurture character and purpose from an early age.', 1, 1, '2025-12-16 10:19:56', '2025-12-16 14:48:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Tokesi Akinola', 'tokesiakinola04@gmail.com', NULL, '$2y$12$HCs37iRvHLNKntdzURG4BOrr16946us9gCG5vs6kRvx/QTpYw5NW2', 1, 'ONDt11olL0vko6nO3iRXiar6Dd0zf9hEWNvQbFXHLS5fWkJaiu0fyuoCncDp', '2025-11-15 12:19:09', '2025-11-15 12:27:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_articles`
--
ALTER TABLE `blog_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_articles_slug_unique` (`slug`),
  ADD KEY `blog_articles_status_published_at_index` (`status`,`published_at`),
  ADD KEY `blog_articles_type_index` (`type`),
  ADD KEY `blog_articles_sort_order_index` (`sort_order`);

--
-- Indexes for table `blog_article_blog_category`
--
ALTER TABLE `blog_article_blog_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `article_category_unq` (`blog_article_id`,`blog_category_id`),
  ADD KEY `blog_article_blog_category_blog_category_id_foreign` (`blog_category_id`);

--
-- Indexes for table `blog_article_blog_tag`
--
ALTER TABLE `blog_article_blog_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `article_tag_unq` (`blog_article_id`,`blog_tag_id`),
  ADD KEY `blog_article_blog_tag_blog_tag_id_foreign` (`blog_tag_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_categories_slug_unique` (`slug`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_comments_parent_id_foreign` (`parent_id`),
  ADD KEY `blog_comments_blog_article_id_parent_id_index` (`blog_article_id`,`parent_id`),
  ADD KEY `blog_comments_created_at_index` (`created_at`);

--
-- Indexes for table `blog_tags`
--
ALTER TABLE `blog_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_tags_slug_unique` (`slug`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`),
  ADD KEY `cart_items_session_id_index` (`session_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `category_product`
--
ALTER TABLE `category_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_product_product_id_foreign` (`product_id`),
  ADD KEY `category_product_category_id_foreign` (`category_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_status_index` (`status`),
  ADD KEY `contacts_created_at_index` (`created_at`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media_library`
--
ALTER TABLE `media_library`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_order_number_index` (`order_number`),
  ADD KEY `orders_email_index` (`email`),
  ADD KEY `orders_status_index` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_tag_product_id_foreign` (`product_id`),
  ADD KEY `product_tag_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_is_approved_index` (`product_id`,`is_approved`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_methods_country_state_is_active_index` (`country`,`state`,`is_active`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_articles`
--
ALTER TABLE `blog_articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blog_article_blog_category`
--
ALTER TABLE `blog_article_blog_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blog_article_blog_tag`
--
ALTER TABLE `blog_article_blog_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blog_tags`
--
ALTER TABLE `blog_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `category_product`
--
ALTER TABLE `category_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_library`
--
ALTER TABLE `media_library`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_tag`
--
ALTER TABLE `product_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_article_blog_category`
--
ALTER TABLE `blog_article_blog_category`
  ADD CONSTRAINT `blog_article_blog_category_blog_article_id_foreign` FOREIGN KEY (`blog_article_id`) REFERENCES `blog_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_article_blog_category_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_article_blog_tag`
--
ALTER TABLE `blog_article_blog_tag`
  ADD CONSTRAINT `blog_article_blog_tag_blog_article_id_foreign` FOREIGN KEY (`blog_article_id`) REFERENCES `blog_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_article_blog_tag_blog_tag_id_foreign` FOREIGN KEY (`blog_tag_id`) REFERENCES `blog_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD CONSTRAINT `blog_comments_blog_article_id_foreign` FOREIGN KEY (`blog_article_id`) REFERENCES `blog_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD CONSTRAINT `product_tag_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
