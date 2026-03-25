-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 25, 2026 at 01:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `imageshorten`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Platform', '2026-03-24 01:22:10', '2026-03-24 01:22:10'),
(2, 'Test Company 1', '2026-03-24 03:50:33', '2026-03-24 03:50:33');

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
-- Table structure for table `invitations`
--

CREATE TABLE `invitations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `invited_by` bigint(20) UNSIGNED NOT NULL,
  `invited_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invitations`
--

INSERT INTO `invitations` (`id`, `company_id`, `invited_by`, `invited_name`, `email`, `role_id`, `token`, `expires_at`, `accepted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, 'hgaur91@gmail.com', 2, 'eOQLVVAII2iLEiaQArvqOap5oyuI6MVlQqcbCud2d7ti9lgtPasarLtRq5Js0VHk', '2026-03-24 09:29:20', '2026-03-24 03:59:20', '2026-03-24 03:50:33', '2026-03-24 03:59:20'),
(4, 2, 2, 'testuser', 'hgpilani91@gmail.com', 3, 'DXrnWifhfXyZ4kCtjHVw98uepJU495zjLYJBKrhcQboj51T87jKEo6pEu9yV8t2l', '2026-03-24 10:10:45', '2026-03-24 04:40:45', '2026-03-24 04:39:17', '2026-03-24 04:40:45');

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
(4, '2026_03_24_064626_create_companies_table', 1),
(5, '2026_03_24_064626_create_roles_table', 1),
(6, '2026_03_24_064626_create_urls_table', 1),
(7, '2026_03_24_064627_create_invitations_table', 1),
(8, '2026_03_24_064731_add_company_and_role_to_users_table', 1),
(9, '2026_03_24_094638_add_hits_to_urls_table', 2),
(10, '2026_03_24_100205_add_invited_name_to_invitations_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('hgaur91@gmail.com', '$2y$12$GnraggZXlYwXxRK/kw6IauFw2KOVe9y6g1DdMlCA/9b5mFzu5QGdy', '2026-03-25 06:30:26');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'SuperAdmin', '2026-03-24 01:22:10', '2026-03-24 01:22:10'),
(2, 'Admin', '2026-03-24 01:22:10', '2026-03-24 01:22:10'),
(3, 'Member', '2026-03-24 01:22:10', '2026-03-24 01:22:10');

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
('EmrPDziicCMjs7JqRvXgI2vwss7sG0PW4v95Xnkc', 1, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNWNVcDYwUm5WaWdVVnFRaVZ0ejI4aUNndU9va1ZoR2lrSEhpRkZaSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zdXBlcmFkbWluL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1774440094),
('L6kV4i79jG0TLERwzfEc8jb2g9rRSVlcP2Q18eSC', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidVZ1ZTFqdmtZV2YxQlQ5eXk4ZlZtMm5QVDJBRGhLVXpQQ1gzWkgzWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=', 1774439953);

-- --------------------------------------------------------

--
-- Table structure for table `urls`
--

CREATE TABLE `urls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `original_url` text NOT NULL,
  `short_code` varchar(255) NOT NULL,
  `hits` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `urls`
--

INSERT INTO `urls` (`id`, `company_id`, `created_by`, `original_url`, `short_code`, `hits`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'https://www.google.com/search?q=dhurandhar+movie+box+office+collection&sca_esv=cdf1ae9af23e2b08&source=hp&ei=cVzCafW6BKGL4-EP7MnO8AI&iflsig=AFdpzrgAAAAAacJqgRlcYC6riZ79iiunFPWlfGWqf3JN&gs_ss=1&oq=&gs_lp=Egdnd3Mtd2l6IgAqAggBMgoQLhgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQLhgDGOoCGI8BMgoQABgDGOoCGI8BSNYKUABYAHABeACQAQCYAQCgAQCqAQC4AQHIAQCYAgGgAg2oAgqYAw3xBS6Lnh9-aVW2kgcBMaAHALIHALgHAMIHAzMtMcgHC4AIAA&sclient=gws-wiz&sei=dFzCabD9GOma4-EPur_RsAI', 'flyto9j5', 0, '2026-03-24 04:12:18', '2026-03-24 04:12:18'),
(2, 2, 2, 'https://www.google.com/search?q=dhurandhar+movie+box+office+collection&sca_esv=cdf1ae9af23e2b08&source=hp&ei=cVzCafW6BKGL4-EP7MnO8AI&iflsig=AFdpzrgAAAAAacJqgRlcYC6riZ79iiunFPWlfGWqf3JN&gs_ss=1&oq=&gs_lp=Egdnd3Mtd2l6IgAqAggBMgoQLhgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQLhgDGOoCGI8BMgoQABgDGOoCGI8BSNYKUABYAHABeACQAQCYAQCgAQCqAQC4AQHIAQCYAgGgAg2oAgqYAw3xBS6Lnh9-aVW2kgcBMaAHALIHALgHAMIHAzMtMcgHC4AIAA&sclient=gws-wiz&sei=dFzCabD9GOma4-EPur_RsAI', 'juje2aen', 0, '2026-03-24 04:19:03', '2026-03-24 04:19:03'),
(3, 2, 2, 'https://www.google.com/search?q=dhurandhar+movie+box+office+collection&sca_esv=cdf1ae9af23e2b08&source=hp&ei=cVzCafW6BKGL4-EP7MnO8AI&iflsig=AFdpzrgAAAAAacJqgRlcYC6riZ79iiunFPWlfGWqf3JN&gs_ss=1&oq=&gs_lp=Egdnd3Mtd2l6IgAqAggBMgoQLhgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQABgDGOoCGI8BMgoQLhgDGOoCGI8BMgoQABgDGOoCGI8BSNYKUABYAHABeACQAQCYAQCgAQCqAQC4AQHIAQCYAgGgAg2oAgqYAw3xBS6Lnh9-aVW2kgcBMaAHALIHALgHAMIHAzMtMcgHC4AIAA&sclient=gws-wiz&sei=dFzCabD9GOma4-EPur_RsAI', 'ft7oh8af', 1, '2026-03-24 04:19:29', '2026-03-24 04:25:44'),
(4, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'p3jdxshq', 0, '2026-03-24 04:41:18', '2026-03-24 04:41:18'),
(5, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'dhpiaopg', 0, '2026-03-24 04:51:31', '2026-03-24 04:51:31'),
(6, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'rvfnrvot', 2, '2026-03-24 04:53:13', '2026-03-24 05:04:29'),
(7, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'y1dky4zw', 0, '2026-03-24 05:41:00', '2026-03-24 05:41:00'),
(8, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'q2mxcew5', 0, '2026-03-24 05:41:04', '2026-03-24 05:41:04'),
(9, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', '1gn3zlft', 0, '2026-03-24 05:41:06', '2026-03-24 05:41:06'),
(10, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', '2jtadp4f', 0, '2026-03-24 05:41:08', '2026-03-24 05:41:08'),
(11, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'v7ngaozx', 0, '2026-03-24 05:41:10', '2026-03-24 05:41:10'),
(12, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'wpbwmnqk', 0, '2026-03-24 05:41:13', '2026-03-24 05:41:13'),
(13, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', '7kf6cxyu', 0, '2026-03-24 05:41:16', '2026-03-24 05:41:16'),
(14, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'svwh8qal', 0, '2026-03-24 05:41:19', '2026-03-24 05:41:19'),
(15, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'rzgpjlf5', 0, '2026-03-24 05:41:21', '2026-03-24 05:41:21'),
(16, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'kvopdbpu', 0, '2026-03-24 05:41:23', '2026-03-24 05:41:23'),
(17, 2, 2, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'tl9fotkk', 0, '2026-03-24 05:41:26', '2026-03-24 05:41:26'),
(18, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'ouidaihk', 0, '2026-03-24 05:46:49', '2026-03-24 05:46:49'),
(19, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'wwyo2fmj', 0, '2026-03-24 05:46:53', '2026-03-24 05:46:53'),
(20, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'j30gufwg', 0, '2026-03-24 05:46:55', '2026-03-24 05:46:55'),
(21, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'zoihoker', 0, '2026-03-24 05:46:57', '2026-03-24 05:46:57'),
(22, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'xc7abuxo', 0, '2026-03-24 05:47:01', '2026-03-24 05:47:01'),
(23, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'ejzzqjlo', 0, '2026-03-24 05:47:04', '2026-03-24 05:47:04'),
(24, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'ro3gcfw2', 0, '2026-03-24 05:47:07', '2026-03-24 05:47:07'),
(25, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'mvhza0rl', 0, '2026-03-24 05:47:11', '2026-03-24 05:47:11'),
(26, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', 'kcejva8i', 0, '2026-03-24 05:47:14', '2026-03-24 05:47:14'),
(27, 2, 3, 'https://indianexpress.com/article/explained/explained-economics/iran-war-why-india-must-step-on-the-gas-with-ethanol-10596597/?utm_source=firefox-newtab-en-intl', '9zeqgwkf', 0, '2026-03-24 05:47:17', '2026-03-24 05:47:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `company_id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Super Admin1', 'superadmin@example.com', NULL, '$2y$12$99J4al0xy2gX/tx9MOvWxecZ/YKdGqk8ZIUfnP5U2.FCEHPW6cUw.', 'FhKC4DMe6ixYSYYnOCMcFJzT8ClT81ZBloOevPp8y42KUjZFqQFIH4lOOQfG', '2026-03-24 01:22:10', '2026-03-25 06:24:38'),
(2, 2, 2, 'Test User 1', 'hgaur91@gmail.com', NULL, '$2y$12$IqraoCIa.5iE8InXfRzO7ezjrs/e1VkWvWOKyuiMHbr3nOVzei5vG', 'H95NcSEVhhY8nb0Bii8h0lrPTStsjp6McPC62ds7EqRlZQhH9YwK1sEkRoII', '2026-03-24 03:59:20', '2026-03-24 03:59:20'),
(3, 2, 3, 'Test User 2', 'hgpilani91@gmail.com', NULL, '$2y$12$JPhjPl8keEpj2PCZRHF3fOBzn2Zr0Lk70MQcsrqRshybyjHjOQY72', NULL, '2026-03-24 04:40:45', '2026-03-24 04:40:45');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invitations_token_unique` (`token`),
  ADD KEY `invitations_company_id_foreign` (`company_id`),
  ADD KEY `invitations_invited_by_foreign` (`invited_by`),
  ADD KEY `invitations_role_id_foreign` (`role_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `urls`
--
ALTER TABLE `urls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `urls_short_code_unique` (`short_code`),
  ADD KEY `urls_company_id_foreign` (`company_id`),
  ADD KEY `urls_created_by_foreign` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_company_id_foreign` (`company_id`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `urls`
--
ALTER TABLE `urls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invitations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invitations_invited_by_foreign` FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invitations_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `urls`
--
ALTER TABLE `urls`
  ADD CONSTRAINT `urls_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `urls_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
