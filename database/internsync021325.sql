-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2025 at 07:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `internsync`
--

-- --------------------------------------------------------

--
-- Table structure for table `academics`
--

CREATE TABLE `academics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `semester` varchar(255) NOT NULL,
  `ay_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academics`
--

INSERT INTO `academics` (`id`, `academic_year`, `semester`, `ay_default`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, '2024-2025', '1', 1, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthday`, `remember_token`, `created_at`, `updated_at`, `image`) VALUES
(1, 1, 'Admin', NULL, 'Admin', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `has_break` time DEFAULT NULL,
  `total_hours` time DEFAULT NULL,
  `status` enum('regular','late','absent') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `student_id`, `date`, `time_in`, `time_out`, `has_break`, `total_hours`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-12-10', '07:00:00', NULL, NULL, NULL, '', NULL, NULL),
(2, 1, '2024-12-12', '07:00:00', '17:00:00', '01:00:00', NULL, 'regular', '2024-12-11 10:27:27', '2024-12-11 10:27:27'),
(3, 1, '2024-12-14', '07:00:00', '17:00:00', '01:00:00', NULL, 'regular', '2024-12-11 10:33:10', '2024-12-11 10:33:10'),
(4, 1, '2024-12-14', '07:00:00', '17:00:00', '01:00:00', NULL, 'regular', '2024-12-11 10:39:35', '2024-12-11 10:39:35'),
(5, 1, '2024-12-14', '07:00:00', '17:00:00', '01:00:00', '09:00:00', 'regular', '2024-12-11 10:40:13', '2024-12-11 10:40:13'),
(6, 1, '2024-12-14', '07:00:00', '11:00:00', '01:00:00', '03:00:00', 'regular', '2024-12-11 10:40:53', '2024-12-11 10:40:53'),
(7, 1, '2024-12-12', '09:00:00', '05:00:00', '00:00:00', '00:00:00', 'regular', '2024-12-11 11:09:51', '2024-12-11 11:09:51');

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
('17f8a330c3d0cb5910c795b74a3fbeafe6c3cf2b', 'i:4;', 1739437654),
('17f8a330c3d0cb5910c795b74a3fbeafe6c3cf2b:timer', 'i:1739437654;', 1739437654);

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
  `company_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `address`, `contact_person`, `contact_email`, `created_at`, `updated_at`) VALUES
(1, 'Monster Inc.', 'Address Sample', 'Personss', 'example@emailll.com', '2024-12-09 22:52:44', '2024-12-09 22:52:44'),
(3, 'Business Inc', 'dawdeawd', NULL, NULL, '2024-12-09 23:04:31', '2024-12-09 23:04:31'),
(4, 'Gulayan Sa Paaralan', 'Biliran City', 'Karen', 'gulayan@gmail.com', '2024-12-11 02:11:34', '2024-12-11 02:11:34');

-- --------------------------------------------------------

--
-- Table structure for table `company_departments`
--

CREATE TABLE `company_departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `required_hours` int(11) NOT NULL,
  `custom_hours` int(11) DEFAULT NULL,
  `academic_year_id` bigint(20) UNSIGNED DEFAULT NULL,
  `year_level` int(11) DEFAULT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `course_code`, `required_hours`, `custom_hours`, `academic_year_id`, `year_level`, `instructor_id`, `created_at`, `updated_at`) VALUES
(1, 'Bachelor of Science in Information Systems', 'BSIS', 500, 250, 1, 4, 1, '2024-12-09 02:05:07', '2024-12-09 02:05:07'),
(2, 'Bachelor of Science in Office Management', 'BSOM', 500, 250, 1, 2, 0, '2024-12-09 02:18:12', '2024-12-09 02:18:12'),
(3, 'Bachelor of Science in Tourism Management', 'BSTM', 500, 250, 1, NULL, 5, '2024-12-11 02:08:42', '2024-12-11 02:08:42');

-- --------------------------------------------------------

--
-- Table structure for table `deployments`
--

CREATE TABLE `deployments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `supervisor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `company_dept_id` bigint(20) UNSIGNED DEFAULT NULL,
  `academic_id` bigint(20) UNSIGNED NOT NULL,
  `custom_hours` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deployments`
--

INSERT INTO `deployments` (`id`, `student_id`, `instructor_id`, `supervisor_id`, `company_id`, `company_dept_id`, `academic_id`, `custom_hours`, `created_at`, `updated_at`) VALUES
(8, 1, 1, NULL, 4, NULL, 1, NULL, '2024-12-11 02:39:31', '2024-12-11 02:39:31'),
(9, 2, 1, 2, 4, NULL, 1, NULL, '2024-12-11 14:12:35', '2024-12-11 14:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `supporting_doc` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `e_signature` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `user_id`, `instructor_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthday`, `address`, `contact`, `remember_token`, `created_at`, `updated_at`, `supporting_doc`, `image`, `e_signature`) VALUES
(1, 2, '2000000001', 'Juan', 'Santos', 'Dela Cruz', '', '1995-12-25', '0123, Dakila, City Of Malolos (Capital), Bulacan', '09999999999', NULL, '2024-12-09 02:03:30', '2024-12-09 02:03:30', NULL, NULL, NULL),
(4, 8, '2111111111', 'Harry', '', 'Potter', '', '2000-12-21', 'ew2523, Nagwaling, Pilar, Bataan', '09999999999', NULL, '2024-12-09 23:26:30', '2024-12-09 23:26:30', NULL, NULL, NULL),
(5, 10, '2121341241', 'Karen', '', 'Torres', '', '1999-03-05', '', '09082903471', NULL, '2024-12-11 01:52:46', '2024-12-11 01:52:46', NULL, NULL, NULL),
(6, 12, '3212312312', 'Instructor', '', 'freerfergger', '', '2001-12-11', 'r234523, Bacolod, City Of Tabaco, Albay', '34554356456', NULL, '2024-12-11 06:47:51', '2024-12-11 06:47:51', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `journals`
--

CREATE TABLE `journals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `text` text NOT NULL,
  `remarks` text NOT NULL,
  `is_submitted` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journals`
--

INSERT INTO `journals` (`id`, `student_id`, `date`, `text`, `remarks`, `is_submitted`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-12-10', '12 10 journal', 'pending', 0, 0, NULL, NULL);

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
(1, '0001_01_01_000000_create_sessions_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '2024_11_21_000001_create_users_table', 1),
(4, '2024_11_21_000002_create_students_table', 1),
(5, '2024_11_21_000003_create_admins_table', 1),
(6, '2024_11_21_000004_create_instructors_table', 1),
(7, '2024_11_21_000005_create_supervisors_table', 1),
(8, '2024_11_21_000006_create_academics_table', 1),
(9, '2024_11_21_000007_create_courses_table', 1),
(10, '2024_11_21_000008_create_sections_table', 1),
(11, '2024_11_21_000009_create_companies_table', 1),
(12, '2024_11_21_000010_create_company_departments_table', 1),
(13, '2024_11_21_000011_create_attendance_table', 1),
(14, '2024_11_21_000012_create_journals_table', 1),
(15, '2024_11_21_000013_create_notifications_table', 1),
(16, '2024_11_21_000014_create_deployments_table', 1),
(17, '2024_11_21_000015_create_settings_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `notif_read_at` timestamp NULL DEFAULT NULL,
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
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `year_level` int(11) DEFAULT NULL,
  `class_section` varchar(255) NOT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `course_id`, `year_level`, `class_section`, `instructor_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'A', 1, '2024-12-09 02:05:47', '2024-12-09 02:05:47'),
(2, 2, 2, 'B', 1, '2024-12-09 02:18:29', '2024-12-09 02:18:29'),
(3, 1, 4, 'B', 1, '2024-12-09 08:00:02', '2024-12-09 08:00:02'),
(4, 3, 2, 'A', 5, '2024-12-11 02:09:14', '2024-12-11 02:09:14');

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
('e2SsE8AL12CBVGtwQjfLlLlglFJnNCZCoMUAN7SL', NULL, '192.168.100.5', 'Mozilla/5.0 (Android 13; Mobile; rv:135.0) Gecko/135.0 Firefox/135.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0ZGWEhkRzFVVHFBcldXejV0c1l2VENvM2NvOUtEcmpzRnhvRlFONyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xOTIuMTY4LjEwMC4yOjgwMDEvcmVnaXN0ZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1739440959),
('SApvLiRiaE7v4eBvtgmzh6CTySQzWC2dbFymB270', NULL, '192.168.100.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidzYzWjJ3UnJFUHlTTHJhdlJDdVUwdzh2UDhyOXRmc08wWWJwSTd1OCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xOTIuMTY4LjEwMC4yOjgwMDEvcmVnaXN0ZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1739444422);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_name` varchar(255) NOT NULL,
  `default_theme` varchar(255) NOT NULL,
  `default_logo` varchar(255) DEFAULT NULL,
  `school_name` varchar(255) NOT NULL,
  `school_address` varchar(255) NOT NULL,
  `system_email` varchar(255) NOT NULL,
  `system_contact` varchar(255) NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `system_name`, `default_theme`, `default_logo`, `school_name`, `school_address`, `system_email`, `system_contact`, `updated_by`, `created_at`, `updated_at`) VALUES
(3, 'InternSync', 'blue', 'logos/logo_67599abdf2a8b.svg', 'Bulacan Polytechnic College', 'Bulihan, City of Malolos, Bulacan', 'example@email.com', '09123456789', NULL, NULL, '2024-12-11 14:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `year_section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `supporting_doc` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthday`, `address`, `contact`, `year_section_id`, `remember_token`, `created_at`, `updated_at`, `supporting_doc`, `image`) VALUES
(1, 5, '2400000001', 'Sinio', '', 'Cagasan', '', '2001-11-22', '321, Sulipan, Apalit, Pampanga', '09999999999', 1, NULL, '2024-12-09 08:16:12', '2024-12-09 08:16:12', NULL, NULL),
(2, 6, '2400000002', 'Joana', '', 'Dela Cruz', '', '2012-12-12', '321, Sagrada Familia, Hagonoy, Bulacan', '09999992261', 2, NULL, '2024-12-09 13:19:40', '2024-12-09 13:19:40', NULL, NULL),
(3, 11, '2113124313', 'JDSGASHJDFAF', '', 'EWFEW', '', '2001-12-11', '1234123, Mabayo, Morong, Bataan', '04913947137', 4, NULL, '2024-12-11 06:45:53', '2024-12-11 06:45:53', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supervisors`
--

CREATE TABLE `supervisors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `supporting_doc` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `e_signature` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supervisors`
--

INSERT INTO `supervisors` (`id`, `user_id`, `company_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthday`, `contact`, `remember_token`, `created_at`, `updated_at`, `supporting_doc`, `image`, `e_signature`) VALUES
(2, 7, 1, 'Super', '', 'Visor', '', '1995-01-21', '09999999999', NULL, '2024-12-09 23:11:59', '2024-12-09 23:11:59', NULL, NULL, NULL),
(3, 9, NULL, 'SuperTwo', '', 'Visor', '', '1997-12-21', '09999999999', NULL, '2024-12-10 09:03:09', '2024-12-10 09:03:09', NULL, NULL, NULL),
(4, 13, NULL, 'supervisooooo', '', 'afeqadfeq', '', '2004-12-11', '47567567567', NULL, '2024-12-11 06:49:12', '2024-12-11 06:49:12', NULL, NULL, NULL),
(5, 14, NULL, 'Gen', '', 'eqwrqe', '', '2002-12-22', '34214123434', NULL, '2024-12-11 07:15:46', '2024-12-11 07:15:46', NULL, NULL, NULL),
(6, 15, NULL, 'dcdas', '', 'sdcsd', '', NULL, '23234242342', NULL, '2025-02-13 06:58:01', '2025-02-13 06:58:01', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student','instructor','supervisor') NOT NULL,
  `otp` bigint(20) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `otp`, `otp_expires_at`, `email_verified_at`, `is_verified`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin@admin.com', '$2y$12$upBsNJQd1cixpKfESJ.6MuPtXYVuzo0VdHLXevj8MzZDvpZOv.0ZG', 'admin', 765145, '2024-12-09 01:59:59', '2024-12-09 01:45:30', 0, 1, NULL, '2024-12-09 01:44:59', '2024-12-09 01:44:59'),
(2, 'ins@gmail.com', '$2y$12$scv6P.fInZ/BPvHLAUZ0MebS4Dv3Jy4m1wCQZe6Z7rtZK/lBgget.', 'instructor', 387082, '2024-12-09 02:18:30', '2024-12-09 08:16:57', 1, 1, NULL, '2024-12-09 02:03:30', '2024-12-09 04:58:48'),
(5, 'student@g.com', '$2y$12$cmNs9wjodVa5Lt3Qo0wrdeay0rAVVETAuel92mFX2yy9yoZmMcBKq', 'student', 111507, '2024-12-09 08:31:12', '2024-12-10 14:16:20', 1, 1, NULL, '2024-12-09 08:16:12', '2024-12-10 13:02:29'),
(6, 'student2@gmail.comm', '$2y$12$Ay0B01Qprq8dgm3WHpcEwu1VNm0wrPeueoGPWh3KoTkuVgBZYhGjm', 'student', 699229, '2024-12-09 13:34:40', NULL, 1, 1, NULL, '2024-12-09 13:19:40', '2024-12-09 13:20:15'),
(7, 'super@gmail.com', '$2y$12$tjpvJWs81W7KNxbhqwCnaejcM0cAWZbKruzJNlexv9Wysz3t3pUKO', 'supervisor', 998906, '2024-12-09 23:26:59', '2024-12-11 02:43:53', 1, 1, NULL, '2024-12-09 23:11:59', '2024-12-10 10:14:21'),
(8, 'ins2@gmail.com', '$2y$12$i0VFIsRh/HfwM/o0qt587OucuAQ2GVfk3qwgFC2nqay0zCkoL4WQi', 'instructor', 708532, '2024-12-09 23:41:30', '2024-12-09 23:26:44', 0, 1, NULL, '2024-12-09 23:26:30', '2024-12-09 23:26:30'),
(9, 'super2@gmail.com', '$2y$12$sBi9A9kwZAl0zp.G9PLrsesE.tXX6hgkxVcDFeoXH25HvTxaXAawG', 'supervisor', 322917, '2024-12-10 09:18:09', NULL, 1, 1, NULL, '2024-12-10 09:03:09', '2024-12-11 02:39:27'),
(10, 'genesisroxas4@gmail.com', '$2y$12$ZPkTF0teEk.DkIIlCmU.q.adq6CR2zuviZKd1FaYZ4yrSLchAib6K', 'instructor', NULL, NULL, '2024-12-11 01:53:44', 1, 1, NULL, '2024-12-11 01:52:43', '2024-12-11 02:05:32'),
(11, 'gg@ggg.com', '$2y$12$IEkwc8H.zF4049DTiuzWseULrFYAFqcZyx7Kyb0OzJqFVQqHuYUeW', 'student', 704668, '2024-12-11 07:00:53', NULL, 0, 1, NULL, '2024-12-11 06:45:53', '2024-12-11 06:45:53'),
(12, 'gen@gg.com', '$2y$12$Ahvy8ZTN3f.6kEGhbE1FdOdzsGH.5UjH3jxYvHU6bTkx3BTdyJwqe', 'instructor', 251372, '2024-12-11 07:02:51', NULL, 0, 1, NULL, '2024-12-11 06:47:51', '2024-12-11 06:47:51'),
(13, 'g@gmmm.cxom', '$2y$12$LtRcyYCyacLkaDDnb9Hdae.rnhYABiB7YSHKRrxAmrqAPxwSbfIV.', 'supervisor', 371633, '2024-12-11 07:04:12', NULL, 0, 1, NULL, '2024-12-11 06:49:12', '2024-12-11 06:49:12'),
(14, 'genesisroxas4@gmail.comcccc', '$2y$12$s.UVP7ci3HbnguiGiHnuaOTl7.JH5zwykACdWeKKemZclyUEv93BW', 'supervisor', 560815, '2024-12-11 07:30:46', NULL, 0, 1, NULL, '2024-12-11 07:15:46', '2024-12-11 07:15:46'),
(15, 'gen1@g.com', '$2y$12$2I9UKpIsZi2PahiF8hJPNu6FHc4lxXXENswFcsXtJ.w0eIFWWrPsW', 'supervisor', 659087, '2025-02-13 07:12:58', NULL, 0, 1, NULL, '2025-02-13 06:57:58', '2025-02-13 06:57:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academics`
--
ALTER TABLE `academics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_user_id_unique` (`user_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_student_id_foreign` (`student_id`);

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
-- Indexes for table `company_departments`
--
ALTER TABLE `company_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_departments_company_id_foreign` (`company_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_course_name_unique` (`course_name`),
  ADD UNIQUE KEY `courses_course_code_unique` (`course_code`),
  ADD KEY `courses_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `deployments`
--
ALTER TABLE `deployments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deployments_student_id_foreign` (`student_id`),
  ADD KEY `deployments_instructor_id_foreign` (`instructor_id`),
  ADD KEY `deployments_supervisor_id_foreign` (`supervisor_id`),
  ADD KEY `deployments_company_id_foreign` (`company_id`),
  ADD KEY `deployments_company_dept_id_foreign` (`company_dept_id`),
  ADD KEY `deployments_academic_id_foreign` (`academic_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instructors_user_id_unique` (`user_id`);

--
-- Indexes for table `journals`
--
ALTER TABLE `journals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journals_student_id_foreign` (`student_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notifications_reference_unique` (`reference`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sections_course_id_foreign` (`course_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_user_id_unique` (`user_id`);

--
-- Indexes for table `supervisors`
--
ALTER TABLE `supervisors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supervisors_user_id_unique` (`user_id`);

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
-- AUTO_INCREMENT for table `academics`
--
ALTER TABLE `academics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `company_departments`
--
ALTER TABLE `company_departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deployments`
--
ALTER TABLE `deployments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `journals`
--
ALTER TABLE `journals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supervisors`
--
ALTER TABLE `supervisors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_departments`
--
ALTER TABLE `company_departments`
  ADD CONSTRAINT `company_departments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deployments`
--
ALTER TABLE `deployments`
  ADD CONSTRAINT `deployments_academic_id_foreign` FOREIGN KEY (`academic_id`) REFERENCES `academics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deployments_company_dept_id_foreign` FOREIGN KEY (`company_dept_id`) REFERENCES `company_departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deployments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deployments_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deployments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deployments_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journals`
--
ALTER TABLE `journals`
  ADD CONSTRAINT `journals_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
