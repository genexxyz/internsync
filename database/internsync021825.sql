-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 17, 2025 at 09:17 PM
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
(1, '2024-2025', '1', 1, 1, NULL, NULL, NULL),
(2, '2024-2025', '2', 0, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `acceptance_letters`
--

CREATE TABLE `acceptance_letters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `is_generated` tinyint(1) NOT NULL DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 0,
  `signed_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acceptance_letters`
--

INSERT INTO `acceptance_letters` (`id`, `student_id`, `company_name`, `name`, `position`, `address`, `contact`, `is_generated`, `is_verified`, `signed_path`, `created_at`, `updated_at`) VALUES
(13, 36, 'Mcdo', 'Pedro Santos', 'Manager', 'DFBVEHWFVEGSF', '09345435234', 1, NULL, 'acceptance_letters/1111111111-Kid-Bala-BSIS-acceptance-letter-signed-4AC3INwk.pdf', '2025-02-17 11:59:35', '2025-02-17 13:15:25');

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
(1, 67, 'Admin', NULL, 'Admin', NULL, NULL, NULL, NULL, NULL, NULL);

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
  `start_break` time DEFAULT NULL,
  `end_break` time DEFAULT NULL,
  `total_hours` time DEFAULT NULL,
  `status` enum('regular','late','absent') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `student_id`, `date`, `time_in`, `time_out`, `start_break`, `end_break`, `total_hours`, `status`, `created_at`, `updated_at`) VALUES
(22, 1, '2025-02-16', '19:41:11', '22:41:29', NULL, NULL, '03:00:00', 'regular', '2025-02-16 11:41:11', '2025-02-16 14:41:29'),
(23, 1, '2025-02-17', '00:32:58', '00:33:33', NULL, NULL, '00:00:00', 'regular', '2025-02-16 16:32:58', '2025-02-16 16:33:33');

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
('17f8a330c3d0cb5910c795b74a3fbeafe6c3cf2b', 'i:1;', 1739820099),
('17f8a330c3d0cb5910c795b74a3fbeafe6c3cf2b:timer', 'i:1739820099;', 1739820099),
('4d89d294cd4ca9f2ca57dc24a53ffb3ef5303122', 'i:1;', 1739823271),
('4d89d294cd4ca9f2ca57dc24a53ffb3ef5303122:timer', 'i:1739823271;', 1739823271);

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

--
-- Dumping data for table `company_departments`
--

INSERT INTO `company_departments` (`id`, `department_name`, `company_id`, `created_at`, `updated_at`) VALUES
(1, 'Test Department', 1, NULL, NULL),
(2, 'Test 2', 1, NULL, NULL);

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
(2, 'Bachelor of Science in Office Management', 'BSOM', 400, 200, 1, 2, 0, '2024-12-09 02:18:12', '2024-12-09 02:18:12'),
(3, 'Bachelor of Science in Tourism Management', 'BSTM', 400, 300, 1, 4, 5, '2024-12-11 02:08:42', '2024-12-11 02:08:42'),
(4, 'Bachelor of Science in Entrepreneurship', 'BSE', 500, 250, 1, NULL, NULL, '2025-02-18 05:26:09', '2025-02-18 05:26:09');

-- --------------------------------------------------------

--
-- Table structure for table `deployments`
--

CREATE TABLE `deployments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `year_section_id` bigint(20) NOT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supervisor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_dept_id` bigint(20) UNSIGNED DEFAULT NULL,
  `academic_id` bigint(20) UNSIGNED NOT NULL,
  `custom_hours` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deployments`
--

INSERT INTO `deployments` (`id`, `student_id`, `year_section_id`, `instructor_id`, `supervisor_id`, `company_id`, `company_dept_id`, `academic_id`, `custom_hours`, `created_at`, `updated_at`) VALUES
(25, 33, 4, NULL, NULL, NULL, NULL, 1, 300, '2025-02-16 09:09:40', '2025-02-16 09:09:40'),
(26, 34, 1, NULL, NULL, NULL, NULL, 1, 500, '2025-02-16 09:12:45', '2025-02-16 09:12:45'),
(27, 35, 2, NULL, NULL, NULL, NULL, 1, 400, '2025-02-18 07:05:35', '2025-02-18 07:05:35'),
(28, 36, 1, NULL, NULL, NULL, NULL, 1, 500, '2025-02-17 10:16:14', '2025-02-17 10:16:14'),
(29, 37, 3, NULL, NULL, NULL, NULL, 1, 500, '2025-02-17 19:20:42', '2025-02-17 19:20:42');

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
(6, 12, '3212312312', 'Instructor', '', 'freerfergger', '', '2001-12-11', 'r234523, Bacolod, City Of Tabaco, Albay', '34554356456', NULL, '2024-12-11 06:47:51', '2024-12-11 06:47:51', NULL, NULL, NULL),
(7, 22, 'rw34234423', 'faasdfasd', '', 'dasdas', '', NULL, NULL, '23454353453', NULL, '2025-02-15 14:13:11', '2025-02-15 14:13:11', 'storage/documents/22_instructor_dasdas_faasdfasd_uc3SKgYY.png', NULL, NULL),
(8, 23, '3242423423', 'fewfewf', '', 'ewfewf', '', NULL, NULL, '12312312312', NULL, '2025-02-15 14:18:20', '2025-02-15 14:18:20', 'storage/documents/23_instructor_ewfewf_fewfewf_gPnAl62I.png', NULL, NULL),
(9, 24, '4321423423', 'gggg', '', 'gggg', '', NULL, NULL, '31231231231', NULL, '2025-02-15 14:22:38', '2025-02-15 14:22:38', 'storage/documents/24_instructor_gggg_gggg_D8Bir8Hr.png', NULL, NULL),
(11, 26, '3424234234', 'ewfewfew', '', 'ewfewfew', '', NULL, NULL, '41243543252', NULL, '2025-02-15 14:45:45', '2025-02-15 14:45:45', 'storage/documents/26_instructor_ewfewfew_ewfewfew_cuAQqddi.png', NULL, NULL),
(12, 27, '1231231231', 'sxcdc', '', 'ascasc', '', NULL, NULL, '54235435432', NULL, '2025-02-16 01:40:55', '2025-02-16 01:40:55', 'storage/documents/27_instructor_ascasc_sxcdc_hXUza6Qw.png', NULL, NULL),
(13, 28, '3412341341', 'saqdqwd', '', 'dcasdasd', '', NULL, NULL, '32213123123', NULL, '2025-02-16 02:45:17', '2025-02-16 02:45:17', 'storage/documents/28_instructor_dcasdasd_saqdqwd_vUJCWTzo.png', NULL, NULL),
(14, 29, '23424234', 'fvdfvs', '', 'vfsfv', '', NULL, NULL, '32432432423', NULL, '2025-02-16 02:51:42', '2025-02-16 02:51:42', 'storage/documents/29_instructor_vfsfv_fvdfvs_yJervBp3.png', NULL, NULL),
(18, 68, '4675656757', 'Juan', '', 'dela cruz', '', NULL, NULL, '56567567567', NULL, '2025-02-18 04:34:41', '2025-02-18 04:34:41', 'documents/68_instructor_dela-cruz_juan_8CpsBlOi.png', NULL, NULL),
(19, 70, '2345245453', 'Juana', '', 'Cruz', '', NULL, NULL, '31242342342', NULL, '2025-02-18 07:31:29', '2025-02-18 07:31:29', 'documents/70_instructor_cruz_juana_GjWQRWQd.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_courses`
--

CREATE TABLE `instructor_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `academic_year_id` bigint(20) UNSIGNED NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor_courses`
--

INSERT INTO `instructor_courses` (`id`, `instructor_id`, `course_id`, `academic_year_id`, `is_verified`, `created_at`, `updated_at`) VALUES
(3, 18, 1, 1, 1, '2025-02-18 04:34:41', '2025-02-18 10:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_sections`
--

CREATE TABLE `instructor_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `year_section_id` bigint(20) UNSIGNED NOT NULL,
  `academic_year_id` bigint(20) UNSIGNED NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor_sections`
--

INSERT INTO `instructor_sections` (`id`, `instructor_id`, `year_section_id`, `academic_year_id`, `is_verified`, `created_at`, `updated_at`) VALUES
(2, 13, 2, 1, 0, '2025-02-16 02:45:17', '2025-02-16 02:45:17'),
(4, 14, 2, 1, 0, '2025-02-16 02:51:42', '2025-02-16 02:51:42'),
(13, 18, 1, 1, 1, '2025-02-18 04:34:41', '2025-02-18 10:15:00'),
(15, 19, 3, 1, 1, '2025-02-18 07:31:29', '2025-02-18 10:14:04');

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
(7, 1, '2025-02-17', 'aswdeafeqfq', 'done', 1, 0, '2025-02-16 17:58:39', '2025-02-16 18:23:01');

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
(17, '2024_11_21_000015_create_settings_table', 1),
(18, '2025_02_13_194423_instructor_sections', 2),
(19, '2025_02_16_092231_instructor_courses', 2),
(20, '2025_02_17_004553_create_rich_texts_table', 3),
(21, '2025_02_17_164201_acceptance_letters', 4);

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
-- Table structure for table `rich_texts`
--

CREATE TABLE `rich_texts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `record_type` varchar(255) NOT NULL,
  `record_id` bigint(20) UNSIGNED NOT NULL,
  `field` varchar(255) NOT NULL,
  `body` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(4, 3, 2, 'A', 5, '2024-12-11 02:09:14', '2024-12-11 02:09:14'),
(5, 4, 4, 'A', NULL, '2025-02-18 05:39:37', '2025-02-18 05:39:37');

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
('eAPZTRR4AJBCjPziiQ1O9CfZHK0aoyewQ6d4tFQL', NULL, '192.168.100.2', 'Mozilla/5.0 (X11; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN1FDMDNibUhqV0Mxdk9QWTFEeFd5UkV4cnpwOFhhT1dSSGhwUkcwZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8xOTIuMTY4LjEwMC4yOjgwMDEiO319', 1739823437);

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
(3, 'InternSync', 'blue', 'logos/logo_67599abdf2a8b.svg', 'Bulacan Polytechnic College', 'Bulihan, City of Malolos, Bulacan', 'example@email.com', '09123456789', NULL, NULL, '2025-02-17 20:13:35');

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
(30, 62, '4324324', 'dwqdqwd', '', 'dqwqwd', '', NULL, 'efwfewfew, Savidug, Sabtang, Batanes', '23334324342', 4, NULL, '2025-02-16 08:50:33', '2025-02-16 08:50:33', 'storage/documents/62_student_dqwqwd_dwqdqwd_dooWeJvW.png', NULL),
(31, 63, '4324324234', 'ewfewf', '', 'fewewfew', '', NULL, '44ge, Sulloh (Tapiantana), Tabuan-lasa, Basilan', '43523423432', 4, NULL, '2025-02-16 08:53:20', '2025-02-16 08:53:20', 'storage/documents/63_student_fewewfew_ewfewf_NcfntQSA.png', NULL),
(32, 64, '342423', 'efwfeqf', '', 'fewfwef', '', NULL, 'wdqqwedqw, Cabengbeng Lower, Sumisip, Basilan', '31231231233', 4, NULL, '2025-02-16 08:56:09', '2025-02-16 08:56:09', 'storage/documents/64_student_fewfwef_efwfeqf_o7Cfl6Oi.png', NULL),
(33, 65, '43243gr', 'cadsf', '', 'dfssdfsf', '', NULL, 'ergerger, Madaymen, Kibungan, Benguet', '43242343243', 4, NULL, '2025-02-16 09:09:40', '2025-02-16 09:09:40', 'documents/65_student_dfssdfsf_cadsf_e5X4mz7H.png', NULL),
(34, 66, '2131232312', 'fewfew', '', 'fewfew', '', NULL, 'efewfe, Suligan (Babuan Island), Tabuan-lasa, Basilan', '32432423423', 1, NULL, '2025-02-16 09:12:45', '2025-02-16 09:12:45', 'storage/documents/66_student_fewfew_fewfew_3q40VzZ1.png', NULL),
(35, 69, '43243gfe', 'desferwf', '', 'wefwefwe', '', NULL, 'fvrsfr, Lubas, La Trinidad (Capital), Benguet', '42312423142', 2, NULL, '2025-02-18 07:05:35', '2025-02-18 07:05:35', 'documents/69_student_wefwefwe_desferwf_PfUKBP1O.png', NULL),
(36, 71, '1111111111', 'Bala', '', 'Kid', '', NULL, 'dwcewdfew, Saluping, Tabuan-lasa, Basilan', '31231231231', 1, NULL, '2025-02-17 10:16:14', '2025-02-17 10:16:14', 'documents/71_student_kid_bala_1EZTjvXf.png', NULL),
(37, 72, '2222222222', 'Ma', '', 'Cathy', '', NULL, 'adcefd, Poblacion, Dingalan, Aurora', '42342344432', 3, NULL, '2025-02-17 19:20:42', '2025-02-17 19:20:42', 'documents/72_student_cathy_ma_166B78Dg.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supervisors`
--

CREATE TABLE `supervisors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_department_id` bigint(20) DEFAULT NULL,
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

INSERT INTO `supervisors` (`id`, `user_id`, `company_id`, `company_department_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthday`, `contact`, `remember_token`, `created_at`, `updated_at`, `supporting_doc`, `image`, `e_signature`) VALUES
(2, 7, 1, NULL, 'Super', '', 'Visor', '', '1995-01-21', '09999999999', NULL, '2024-12-09 23:11:59', '2024-12-09 23:11:59', NULL, NULL, NULL),
(3, 9, NULL, NULL, 'SuperTwo', '', 'Visor', '', '1997-12-21', '09999999999', NULL, '2024-12-10 09:03:09', '2024-12-10 09:03:09', NULL, NULL, NULL),
(4, 13, NULL, NULL, 'supervisooooo', '', 'afeqadfeq', '', '2004-12-11', '47567567567', NULL, '2024-12-11 06:49:12', '2024-12-11 06:49:12', NULL, NULL, NULL),
(5, 14, NULL, NULL, 'Gen', '', 'eqwrqe', '', '2002-12-22', '34214123434', NULL, '2024-12-11 07:15:46', '2024-12-11 07:15:46', NULL, NULL, NULL),
(6, 15, NULL, NULL, 'dcdas', '', 'sdcsd', '', NULL, '23234242342', NULL, '2025-02-13 06:58:01', '2025-02-13 06:58:01', NULL, NULL, NULL),
(7, 35, NULL, NULL, 'efewf', '', 'ewfewf', '', NULL, '34534345345', NULL, '2025-02-16 05:08:49', '2025-02-16 05:08:49', 'storage/documents/35_supervisor_ewfewf_efewf_j2wjrOar.png', NULL, NULL),
(8, 38, NULL, NULL, 'xAXAX', '', 'XAaxX', '', NULL, '31231231231', NULL, '2025-02-16 05:18:38', '2025-02-16 05:18:38', 'storage/documents/38_supervisor_xaaxx_xaxax_Vq6ciUFf.png', NULL, NULL),
(9, 39, NULL, NULL, 'sfgsdfaesfsf', '', 'fewfewfew', '', NULL, '32131231231', NULL, '2025-02-16 05:24:38', '2025-02-16 05:24:38', 'storage/documents/39_supervisor_fewfewfew_sfgsdfaesfsf_qCsar8zj.png', NULL, NULL),
(10, 40, 3, NULL, 'awdawdaw', '', 'dawdawd', '', NULL, '13431243423', NULL, '2025-02-16 05:28:13', '2025-02-16 05:28:13', 'storage/documents/40_supervisor_dawdawd_awdawdaw_Y2A6Ubab.png', NULL, NULL),
(11, 41, 3, NULL, 'ASXsAX', '', 'asxsa', '', NULL, '12441314334', NULL, '2025-02-16 05:41:43', '2025-02-16 05:41:43', 'storage/documents/41_supervisor_asxsa_asxsax_OEW9V8eF.png', NULL, NULL),
(12, 42, 1, NULL, 'fvsvdfs', '', 'vdfvdf', '', NULL, '34653456546', NULL, '2025-02-16 07:13:53', '2025-02-16 07:13:53', 'storage/documents/42_supervisor_vdfvdf_fvsvdfs_KIuguVnT.png', NULL, NULL),
(13, 43, 1, 2, 'adfds', '', 'fdssdf', '', NULL, '23454234242', NULL, '2025-02-16 07:15:49', '2025-02-16 07:15:49', 'storage/documents/43_supervisor_fdssdf_adfds_AtvjXeeU.png', NULL, NULL),
(14, 67, 3, NULL, 'Admin', '', 'User', '', NULL, '12425445345', NULL, '2025-02-17 18:38:57', '2025-02-17 18:38:57', 'storage/documents/67_supervisor_user_admin_BKg5J56v.png', NULL, NULL);

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
(5, 'student@g.com', '$2y$12$cmNs9wjodVa5Lt3Qo0wrdeay0rAVVETAuel92mFX2yy9yoZmMcBKq', 'student', 111507, '2024-12-09 08:31:12', '2024-12-10 14:16:20', 1, 1, NULL, '2024-12-09 08:16:12', '2024-12-10 13:02:29'),
(6, 'student2@gmail.comm', '$2y$12$Ay0B01Qprq8dgm3WHpcEwu1VNm0wrPeueoGPWh3KoTkuVgBZYhGjm', 'student', 699229, '2024-12-09 13:34:40', NULL, 1, 1, NULL, '2024-12-09 13:19:40', '2024-12-09 13:20:15'),
(29, 'gbh@dhj.com', '$2y$12$ctgz61.UtbzS6rVMeBJNduEL/6tcEudcT.wUTM9xIrkSvaV/RKM6C', 'instructor', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 02:51:42', '2025-02-16 02:51:42'),
(30, 'f@gdgvv.com', '$2y$12$YuyQPJaAnZ92gIduTle0KOAPwy8Dcax425ol.R4L7GTb6baT7n50m', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 02:58:43', '2025-02-16 02:58:43'),
(33, 'gfwergwerg@gg.com', '$2y$12$qtyoyH.aGS7iHlqRDqA1GuZcXtPe6.TBvn2Ym/tZAelxJcYi1eqVe', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 03:36:36', '2025-02-16 03:36:36'),
(35, 'gfewrf@gg.comff', '$2y$12$AbZItMYKEnkWu0WQwQmXKOHFap9zJaKEIpiDgJWbf4yXlCzun0.a2', 'supervisor', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 05:08:49', '2025-02-16 05:08:49'),
(36, 'f@gdgvv.comff', '$2y$12$6yKNfXQtLOtYGlkQv358VeIwv8h6TyteMGk2pyK9p///w4mKVRCYC', 'supervisor', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 05:11:37', '2025-02-16 05:11:37'),
(37, 'vsgf@gg.comdd', '$2y$12$CIoGkqOLVNGAO7nw8FlEHet6RjvPic7CW6R0BzhCp2f9gVAWm9Mvi', 'supervisor', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 05:16:33', '2025-02-16 05:16:33'),
(38, 'gbh@dhj.comd', '$2y$12$GnVh0Sk25uBoQJEhS8hrZOdBUI2Z8ublNUQzNUrSLtm.16qfrWPhW', 'supervisor', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 05:18:38', '2025-02-16 05:18:38'),
(39, '111cds@g.com', '$2y$12$Ai41nRrk803iQW4lYrJTXuekxCOsErp1tG3tfUZVRH0rm/5Fl8mmC', 'supervisor', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 05:24:38', '2025-02-16 05:24:38'),
(40, 'ddf@gg.commm', '$2y$12$Dy2A6j6S2KC5adeuJGGgQeA7RUTuPUt4wRhfJ8n4tW0hwNVRv6ozi', 'supervisor', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 05:28:13', '2025-02-16 05:28:13'),
(41, 'ddsqfwe@gmail.com', '$2y$12$3k4dP0UhIa.2ixnqUi2ld.eACvNrJH5tWZONHXliGJuWWTun2.J36', 'supervisor', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 05:41:43', '2025-02-16 05:41:43'),
(42, 'f@gg.comvvv', '$2y$12$EVn4tyhoUHTJ1OqFXPmKW.6FE942LiKBheSrbf6wrPsGUFw1vFP36', 'supervisor', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 07:13:53', '2025-02-16 07:13:53'),
(43, 'f@g.comcadf', '$2y$12$TF6giBoF7dNw7PD02Gtxc.tdeC1fGyLwf2Fg4BI1KZlqDCT1UECV.', 'supervisor', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 07:15:49', '2025-02-16 07:15:49'),
(44, 'f@g.comcvf', '$2y$12$ZJkgIcuuu99kxMPEBo3GPenRkEWs2h8Nz8d1r0FNhjL0MbCayVv/e', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 07:42:29', '2025-02-16 07:42:29'),
(45, 'defwwef@gmaiiiil.com', '$2y$12$CgOcd89h8B7XVODRRbWblOgNLTzLuE4Cnr5fUCvhiTTLLBKsd6qTq', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 07:44:35', '2025-02-16 07:44:35'),
(46, 'vsgf@gg.comvvv', '$2y$12$cO6w3BHg32nqU.TQ6lbkC.bBhXooC35APkdNDhx2BXqffI5nW/GTa', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 07:48:56', '2025-02-16 07:48:56'),
(47, 'fddq@gg.com', '$2y$12$ZU3bC.hnCSyhwKnRwFO.ael36SvLFcmdUgFsiXlj6K1qdHL5CBROC', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 07:56:22', '2025-02-16 07:56:22'),
(48, 'f@gdg.comvv', '$2y$12$TdE4F.T38u0MLQhM48iSF.nxz63AXK9xYkAsCQVfiwBI1MEyt.eEa', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 07:58:01', '2025-02-16 07:58:01'),
(49, 'f@gdg.comvrg', '$2y$12$MbJ2Sl14O3Lq.w3Zj0QhZu1YcOVtHxHAGYGioOY5UYRA8mO476DbO', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:02:23', '2025-02-16 08:02:23'),
(50, 'f@g.comcvffefef', '$2y$12$zFIA.oYGnj21wfkAPJaeuO2Ce8hhlVdXU2vaqqg.H1g.ODeFTGU9K', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:05:08', '2025-02-16 08:05:08'),
(51, 'gbh@dhj.comdvre', '$2y$12$C2QPZ6tmjk2Eq2PTdvarm.EqIGTHxxrEkMTBRbv2hqPfm6FDBjtpu', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:08:02', '2025-02-16 08:08:02'),
(52, 'f@g.commsddggfrg', '$2y$12$cDaICyw9QWt71boRcdXlp.2dTOWYriRmjXs1L0fXO0RzgKGrRTusW', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:10:09', '2025-02-16 08:10:09'),
(53, 'gbh@dfhj.comdcvdswf', '$2y$12$rWww8bInVNw65EjgaNYcte8Gs//OC9NTw4lKN8nUtE7hfS.zpGWjm', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:18:20', '2025-02-16 08:18:20'),
(54, 'gbh@dfhj.comdfdewf', '$2y$12$082CleVZA8QPCUec9diLj.PpTVFmL0EVMFL7BlGk1U9hypPEZQHUW', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:22:14', '2025-02-16 08:22:14'),
(55, 'gfewrf@gg.comfgf', '$2y$12$mdBDDRofn1tNcNM2dLJFvekk.7dEuA1PXIoBMb66J3DjXsK/8y79.', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:26:22', '2025-02-16 08:26:22'),
(56, 'fddq@gg.comdf', '$2y$12$BO0NrK8Ux7yZ1obzdDbMBeCSE0nNssGvbMVVPZlXNp4xBRlCb06Iu', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:31:31', '2025-02-16 08:31:31'),
(57, 'f@g.comcvffewfgew', '$2y$12$Y0jZFLwDxxnJPis6aPQWH.hCDnsxCTcn32SJBJYEuPPb3xC.DGlkC', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:33:40', '2025-02-16 08:33:40'),
(58, 'gfewrf@gg.comffgeg', '$2y$12$p528Af/wUsjyQIYbYHUfnOFU8ANE7vo/8qNdIFG5qXWTMW.yBohTm', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:38:31', '2025-02-16 08:38:31'),
(59, 'ffgwfe@gmail.commm', '$2y$12$4.QZMm7jDDKqGUc/nxBBMes.dQQ8GEIuWfWngo78wvsOvJ85qH/6q', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:42:27', '2025-02-16 08:42:27'),
(60, 'gbh@dhj.comdfef', '$2y$12$ctW2YXXUqqs22bJ2800ID.pGZt2eF9Z775P5YOBYbi4tDFC5cte/W', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:45:12', '2025-02-16 08:45:12'),
(61, 'f@gdg.combt', '$2y$12$Qa4zS2631QUIS.jOrbm5duGU8hhqa30ohD5TehZscJdJgj6dVl98i', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:47:12', '2025-02-16 08:47:12'),
(62, 'f@gdg.comfgre', '$2y$12$Ht5Y9mREnpy9KXMmdEgd9uS44EI/sAJEkc7qBU1HEE/xD7dmVMmNy', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:50:33', '2025-02-16 08:50:33'),
(63, 'f@g.comcvfbr', '$2y$12$HizBiPg9Jnhn7MceF2mDnOejLLJjw4ELDsTD7UNyEtuD3Sx5qsSCu', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:53:20', '2025-02-16 08:53:20'),
(64, 'ddf@gg.commmff', '$2y$12$Im7SPaNKu/xgbI/FVtn5TO43tYq4OkhND7T9ZyWz4J8cck2whfbA6', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 08:56:09', '2025-02-16 08:56:09'),
(65, 'gbh@dhj.comdfefgr', '$2y$12$D84Tq0pxrLvUgU.jvP2IPetx9w87RtlqhiZEKnrV9JvOqOB2yjrHy', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 09:09:40', '2025-02-16 09:09:40'),
(66, 'ffgwfe@gmail.commmf23rf32', '$2y$12$njSY.G0dmns4QS87M3mEouzoQjMMmJXHbufJXXXLXFBqobAaqaqpu', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-16 09:12:45', '2025-02-16 09:12:45'),
(67, 'admin@admin.com', '$2y$12$wPzev.AhSi8LAG61z8iGr.4Chdey6T7nCW71QECDt4dDhX9joMJXK', 'admin', NULL, NULL, '2025-02-17 18:41:04', 1, 1, NULL, '2025-02-17 18:38:57', '2025-02-17 18:38:57'),
(68, 'ins22@gg.com', '$2y$12$VGUk/09F9si/vJ/goXxrPeMNcUKOhtc44zsuMGpZUkQEwNSJT4o1y', 'instructor', NULL, NULL, '2025-02-18 07:11:41', 1, 1, NULL, '2025-02-18 04:34:41', '2025-02-18 10:15:00'),
(69, 'f@g.commsddbb', '$2y$12$nqTT6R1HPb4KYjdjOLBhSe9Q89MZqy0qD4i9ASOCWxSUnRvGn5Muy', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-18 07:05:35', '2025-02-18 07:05:35'),
(70, 'ins@g.com', '$2y$12$rZ5NJPD7bK1Or.p.GOQhG.NhJghtmS8qgIN7lqS6FsOZ06uaUGewe', 'instructor', NULL, NULL, '2025-02-18 07:31:47', 1, 1, NULL, '2025-02-18 07:31:29', '2025-02-18 10:14:04'),
(71, 'stud@g.com', '$2y$12$7IubEz3kd6LrXbVwRqYogO.7nTHyXaOfjlRRTwcSdjSyBsjz52I8C', 'student', NULL, NULL, '2025-02-17 10:16:29', 1, 1, NULL, '2025-02-17 10:16:14', '2025-02-17 10:16:14'),
(72, 'stud2@gg.com', '$2y$12$b3oQ9wI9ZYviUUv8cCHliOAhrewaWxLBShOFUZ2kVFy3oGBfdhbgi', 'student', NULL, NULL, NULL, 0, 1, NULL, '2025-02-17 19:20:42', '2025-02-17 19:20:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academics`
--
ALTER TABLE `academics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acceptance_letters`
--
ALTER TABLE `acceptance_letters`
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
-- Indexes for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_courses_instructor_id_foreign` (`instructor_id`),
  ADD KEY `instructor_courses_academic_year_id_foreign` (`academic_year_id`),
  ADD KEY `instructor_courses_course_id_foreign` (`course_id`) USING BTREE;

--
-- Indexes for table `instructor_sections`
--
ALTER TABLE `instructor_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_sections_instructor_id_foreign` (`instructor_id`),
  ADD KEY `instructor_sections_year_section_id_foreign` (`year_section_id`),
  ADD KEY `instructor_sections_academic_year_id_foreign` (`academic_year_id`);

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
-- Indexes for table `rich_texts`
--
ALTER TABLE `rich_texts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rich_texts_field_record_type_record_id_unique` (`field`,`record_type`,`record_id`),
  ADD KEY `rich_texts_record_type_record_id_index` (`record_type`,`record_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `acceptance_letters`
--
ALTER TABLE `acceptance_letters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `company_departments`
--
ALTER TABLE `company_departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `deployments`
--
ALTER TABLE `deployments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `instructor_sections`
--
ALTER TABLE `instructor_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `journals`
--
ALTER TABLE `journals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rich_texts`
--
ALTER TABLE `rich_texts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `supervisors`
--
ALTER TABLE `supervisors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

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
-- Constraints for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
  ADD CONSTRAINT `instructor_courses_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instructor_courses_courses_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instructor_courses_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `instructor_sections`
--
ALTER TABLE `instructor_sections`
  ADD CONSTRAINT `instructor_sections_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instructor_sections_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instructor_sections_year_section_id_foreign` FOREIGN KEY (`year_section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

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
