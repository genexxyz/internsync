-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 22, 2025 at 05:29 AM
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
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academics`
--

INSERT INTO `academics` (`id`, `academic_year`, `semester`, `ay_default`, `status`, `description`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, '2024-2025', '1st Semester', 1, 1, NULL, NULL, NULL, NULL, '2025-04-21 20:15:37'),
(2, '2024-2025', '2nd Semester', 0, 1, NULL, NULL, NULL, NULL, '2025-04-21 20:15:37'),
(3, '2025-2026', 'Summer', 0, 1, '', '2025-05-01', '2025-12-31', '2025-04-21 23:10:20', '2025-04-21 23:10:20'),
(4, '2025-2026', '1st Semester', 0, 1, '', '2025-04-22', '2025-06-26', '2025-04-21 23:37:32', '2025-04-21 23:37:32');

-- --------------------------------------------------------

--
-- Table structure for table `acceptance_letters`
--

CREATE TABLE `acceptance_letters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `department_name` varchar(255) DEFAULT NULL,
  `supervisor_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `email` varchar(225) DEFAULT NULL,
  `is_generated` tinyint(1) NOT NULL DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 0,
  `signed_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acceptance_letters`
--

INSERT INTO `acceptance_letters` (`id`, `student_id`, `company_name`, `department_name`, `supervisor_name`, `address`, `contact`, `email`, `is_generated`, `is_verified`, `signed_path`, `created_at`, `updated_at`) VALUES
(17, 1, 'Jollibee Inc.', 'No Department', 'Mc Douglas', 'Mohon, City of Malolos', '098273827163712', 'super@ggg.com', 1, 0, 'acceptance_letters/2400000001-cruz-juanito-BSIS-acceptance-letter-signed-mdECtfyR.pdf', '2025-03-09 17:25:08', '2025-04-21 10:22:55'),
(23, 40, 'Krusty Krab', 'No Department', 'Mr Krabs', 'Bikini Bottom', '09123456789', 'mrkrabs@fakemaill.com', 1, 0, 'acceptance_letters/MA21011456-roxas-genesis-BSIS-acceptance-letter-signed-BnZQbQ01.pdf', '2025-03-25 04:30:12', '2025-04-19 06:21:22');

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
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `student_id`, `date`, `time_in`, `time_out`, `start_break`, `end_break`, `total_hours`, `status`, `is_approved`, `created_at`, `updated_at`) VALUES
(50, 1, '2025-03-25', '11:42:04', '11:44:51', '11:42:28', '11:43:52', '00:01:00', 'regular', 0, '2025-03-25 03:42:04', '2025-03-25 03:44:51'),
(51, 1, '2025-04-17', '08:30:00', '17:00:00', '12:00:00', '01:00:00', '07:30:00', 'regular', 1, '2025-04-19 06:27:16', '2025-04-19 10:59:22'),
(53, 1, '2025-04-19', '18:00:00', '19:00:00', NULL, NULL, '01:00:00', 'regular', 2, '2025-04-19 10:49:50', '2025-04-19 13:07:09'),
(55, 1, '2025-04-20', '06:11:00', '06:42:00', NULL, NULL, '00:30:00', 'regular', 1, '2025-04-19 22:11:47', '2025-04-21 08:30:01'),
(56, 1, '2025-04-18', '08:05:00', '16:00:00', '12:00:00', '01:00:00', '06:55:00', 'regular', 1, '2025-04-19 06:27:16', '2025-04-19 10:59:22'),
(57, 1, '2025-04-16', '08:00:00', '17:00:00', '12:00:00', '13:00:00', NULL, 'regular', 0, '2025-04-20 06:20:13', '2025-04-20 07:28:55'),
(58, 1, '2025-04-15', '08:00:00', '16:00:00', NULL, NULL, '08:00:00', 'regular', 1, '2025-04-20 07:35:23', '2025-04-20 07:38:27'),
(59, 1, '2025-04-14', '08:00:00', '17:00:00', '12:00:00', '13:00:00', '08:00:00', 'regular', 2, '2025-04-20 07:46:08', '2025-04-20 07:52:55'),
(61, 1, '2025-04-21', '12:00:43', '16:40:19', NULL, NULL, '04:39:00', 'regular', 1, '2025-04-21 04:00:43', '2025-04-21 15:12:10');

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
  `company_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `address`, `contact_person`, `contact`, `created_at`, `updated_at`) VALUES
(12, 'Jollibee Inc.', 'Mohon, City of Malolos', 'Abdul', '0084938314', '2025-03-09 17:56:51', '2025-04-21 17:00:56'),
(15, 'Krusty Krab', 'Bikini Bottom', NULL, NULL, '2025-04-14 08:02:39', '2025-04-14 08:02:39');

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
(13, 'No Department', 15, '2025-04-14 08:02:39', '2025-04-14 08:02:39'),
(14, 'No Department', 12, '2025-04-14 10:58:34', '2025-04-14 10:58:34');

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
(9, 'Bachelor of Science in Computer Science', 'BSCS', 500, 250, 1, NULL, NULL, '2025-03-25 02:55:15', '2025-04-21 16:15:43');

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
  `student_type` enum('regular','special') DEFAULT 'regular',
  `starting_date` date DEFAULT NULL,
  `ending_date` date DEFAULT NULL,
  `status` enum('pending','ongoing','completed') NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `permit_path` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deployments`
--

INSERT INTO `deployments` (`id`, `student_id`, `year_section_id`, `instructor_id`, `supervisor_id`, `company_id`, `company_dept_id`, `academic_id`, `custom_hours`, `student_type`, `starting_date`, `ending_date`, `status`, `is_verified`, `permit_path`, `created_at`, `updated_at`) VALUES
(32, 1, 1, 18, 16, 12, 14, 1, 20, 'special', '2025-04-20', '2025-04-18', 'completed', 0, 'permits/2400000001_juanito_cruz_jJPRZrAI.jpg', '2025-03-01 09:15:26', '2025-04-22 02:42:01'),
(34, 40, 1, 18, 18, 12, 14, 1, 500, 'regular', NULL, NULL, 'pending', 0, NULL, '2025-03-25 04:27:37', '2025-04-19 06:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `endorsement_letter_requests`
--

CREATE TABLE `endorsement_letter_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `status` enum('requested','for_pickup','picked_up') NOT NULL DEFAULT 'requested',
  `requested_at` timestamp NULL DEFAULT NULL,
  `for_pickup_at` timestamp NULL DEFAULT NULL,
  `picked_up_at` timestamp NULL DEFAULT NULL,
  `received_by` varchar(255) DEFAULT NULL,
  `admin_remarks` text DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `endorsement_letter_requests`
--

INSERT INTO `endorsement_letter_requests` (`id`, `company_id`, `requested_by`, `status`, `requested_at`, `for_pickup_at`, `picked_up_at`, `received_by`, `admin_remarks`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 12, 1, 'picked_up', '2025-04-20 14:09:12', '2025-04-21 07:02:27', '2025-04-21 07:03:05', 'Genesis', NULL, 67, '2025-04-20 14:09:12', '2025-04-21 07:03:05'),
(7, 12, 40, 'picked_up', '2025-04-21 01:02:35', '2025-04-21 06:54:49', '2025-04-21 07:13:26', 'adefaef', NULL, 67, '2025-04-21 01:02:35', '2025-04-21 07:13:26');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deployment_id` bigint(20) UNSIGNED NOT NULL,
  `supervisor_id` bigint(20) UNSIGNED NOT NULL,
  `quality_work` int(11) NOT NULL COMMENT 'Max: 20',
  `completion_time` int(11) NOT NULL COMMENT 'Max: 15',
  `dependability` int(11) NOT NULL COMMENT 'Max: 15',
  `judgment` int(11) NOT NULL COMMENT 'Max: 10',
  `cooperation` int(11) NOT NULL COMMENT 'Max: 10',
  `attendance` int(11) NOT NULL COMMENT 'Max: 10',
  `personality` int(11) NOT NULL COMMENT 'Max: 10',
  `safety` int(11) NOT NULL COMMENT 'Max: 10',
  `total_score` int(11) NOT NULL COMMENT 'Sum of all ratings',
  `recommendation` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `deployment_id`, `supervisor_id`, `quality_work`, `completion_time`, `dependability`, `judgment`, `cooperation`, `attendance`, `personality`, `safety`, `total_score`, `recommendation`, `created_at`, `updated_at`) VALUES
(11, 32, 16, 1, 1, 1, 1, 1, 1, 1, 1, 8, ' MOA Ready for Pickup\n\nGreat news! Your MOA is now ready for pickup at the OJT Office. Please bring a valid ID when collecting the document. ', '2025-04-21 10:35:09', '2025-04-21 10:35:09');

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
  `signature_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `user_id`, `instructor_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthday`, `address`, `contact`, `remember_token`, `created_at`, `updated_at`, `supporting_doc`, `image`, `signature_path`) VALUES
(18, 68, '4675656757', 'Juan', '', 'Dela Cruz', '', NULL, NULL, '0922434212121', NULL, '2025-02-18 04:34:41', '2025-04-22 03:14:13', 'documents/68_instructor_dela-cruz_juan_8CpsBlOi.png', 'profiles/instructor/profile_instructor__mb8fvkFd.png', 'signatures/68_instructor_dela cruz_juan_XhclxMK5.png'),
(20, 81, '4234762374', 'James', '', 'Reid', '', NULL, NULL, '09999999953', NULL, '2025-04-21 07:24:45', '2025-04-21 16:03:48', 'documents/81_instructor_reid_james_ntWwgwRP.jpg', NULL, NULL);

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
(1, 18, 1, 1, 1, NULL, NULL);

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
(13, 18, 1, 1, 1, '2025-02-18 04:34:41', '2025-02-25 13:17:38'),
(17, 20, 21, 1, 1, '2025-04-21 07:24:45', '2025-04-21 07:48:54'),
(24, 18, 3, 1, 1, '2025-02-18 04:34:41', '2025-02-25 13:17:38');

-- --------------------------------------------------------

--
-- Table structure for table `journals`
--

CREATE TABLE `journals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `text` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `is_submitted` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `feedback` text DEFAULT NULL,
  `is_reopened` tinyint(1) DEFAULT 0,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journals`
--

INSERT INTO `journals` (`id`, `student_id`, `date`, `text`, `remarks`, `is_submitted`, `is_approved`, `feedback`, `is_reopened`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(29, 1, '2025-03-25', NULL, NULL, 0, 0, NULL, 0, NULL, '2025-03-25 03:42:19', '2025-03-25 03:42:19'),
(30, 1, '2025-04-17', NULL, NULL, 1, 1, NULL, 0, NULL, '2025-04-19 06:27:35', '2025-04-19 10:59:22'),
(31, 1, '2025-04-18', NULL, NULL, 1, 1, NULL, 0, '2025-04-19 21:07:20', '2025-04-18 06:27:35', '2025-04-19 13:07:20'),
(32, 1, '2025-04-19', NULL, NULL, 1, 2, 'NO LUCJJJJAS', 1, '2025-04-20 04:22:19', '2025-04-19 10:49:54', '2025-04-19 20:22:19'),
(38, 1, '2025-04-20', NULL, NULL, 1, 1, NULL, 0, '2025-04-21 16:30:01', '2025-04-19 22:37:09', '2025-04-21 08:30:01'),
(39, 1, '2025-04-16', NULL, NULL, 0, 0, NULL, 1, NULL, '2025-04-20 06:20:13', '2025-04-20 06:20:13'),
(40, 1, '2025-04-15', NULL, NULL, 0, 1, NULL, 0, '2025-04-20 15:38:27', '2025-04-20 07:35:23', '2025-04-20 07:38:27'),
(41, 1, '2025-04-14', NULL, NULL, 0, 2, 'dsvdecveefaefaef', 0, '2025-04-20 15:57:52', '2025-04-20 07:46:08', '2025-04-20 07:57:52'),
(44, 1, '2025-04-21', NULL, NULL, 1, 1, NULL, 0, '2025-04-21 23:12:10', '2025-04-21 08:40:11', '2025-04-21 15:12:10');

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
(16, '2024_11_21_000014_create_deployments_table', 1),
(17, '2024_11_21_000015_create_settings_table', 1),
(18, '2025_02_13_194423_instructor_sections', 2),
(20, '2025_02_17_004553_create_rich_texts_table', 3),
(21, '2025_02_17_164201_acceptance_letters', 4),
(22, '2025_03_03_114307_weekly_reports', 5),
(23, '2025_03_22_201141_create_signatures_table', 6),
(26, '2025_03_23_174547_task_histories', 8),
(27, '2025_03_23_134550_tasks', 9),
(28, '2025_03_24_141215_evaluations', 9),
(29, '2025_04_20_123120_create_reopen_requests_table', 10),
(30, '2025_04_20_183444_create_moa_requests_table', 11),
(31, '2025_04_20_212318_create_endorsement_letter_requests_table', 12),
(32, '2024_11_21_000013_create_notifications_table', 13),
(33, '2025_02_16_092231_instructor_courses', 14);

-- --------------------------------------------------------

--
-- Table structure for table `moa_requests`
--

CREATE TABLE `moa_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `company_number` varchar(255) NOT NULL,
  `officer_name` varchar(255) NOT NULL,
  `officer_position` varchar(255) NOT NULL,
  `witness_name` varchar(255) NOT NULL,
  `witness_position` varchar(255) NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `status` enum('requested','for_pickup','picked_up','received_by_company') NOT NULL DEFAULT 'requested',
  `requested_at` timestamp NULL DEFAULT NULL,
  `for_pickup_at` timestamp NULL DEFAULT NULL,
  `picked_up_at` timestamp NULL DEFAULT NULL,
  `received_by_company_at` timestamp NULL DEFAULT NULL,
  `received_by_student` varchar(255) DEFAULT NULL,
  `received_by_supervisor` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_remarks` text DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `moa_requests`
--

INSERT INTO `moa_requests` (`id`, `company_id`, `company_number`, `officer_name`, `officer_position`, `witness_name`, `witness_position`, `requested_by`, `status`, `requested_at`, `for_pickup_at`, `picked_up_at`, `received_by_company_at`, `received_by_student`, `received_by_supervisor`, `admin_remarks`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 12, '0932139832423', 'John', 'Manager', 'Peter', 'Janitor', 1, 'for_pickup', '2025-04-20 11:57:50', '2025-04-21 07:12:38', NULL, NULL, NULL, NULL, NULL, 1, '2025-04-20 11:57:50', '2025-04-21 07:12:38');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'fa-bell',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `is_archived`, `created_at`, `updated_at`) VALUES
(1, 67, 'test_notif', 'Test', 'Test message', 'admin.company', 'fa-user', 1, 0, '2025-04-21 05:37:46', '2025-04-21 06:35:46'),
(2, 67, 'test_notif', 'Test 2dsadasdasdasda dd', 'Test message 1ewfdxzzzzzzzfffffffffef ewfewrfewfewsfewfewfewf', 'admin.company', 'fa-user', 0, 1, '2025-04-18 05:37:46', '2025-04-21 06:34:54'),
(3, 67, 'test_notif', 'Test 233', 'Test message 1', 'admin.company', 'fa-user', 1, 0, '2025-04-21 04:37:46', '2025-04-21 06:35:44'),
(7, 5, 'endorsement_update', 'Endorsement Letter Status', 'Your endorsement letter has been updated to picked_up', 'student.document', 'fa-file-signature', 1, 1, '2025-04-21 06:57:11', '2025-04-21 07:01:55'),
(8, 5, 'endorsement_for_pickup', 'Endorsement Letter Ready', 'Your endorsement letter is ready for pickup at the OJT Office. Please bring a valid ID.', 'student.document', 'fa-envelope', 1, 0, '2025-04-21 07:02:27', '2025-04-21 07:09:17'),
(9, 5, 'endorsement_picked_up', 'Endorsement Letter Picked Up', 'Your endorsement letter has been picked up by Genesis.', 'student.document', 'fa-check-circle', 1, 0, '2025-04-21 07:03:05', '2025-04-21 07:09:15'),
(10, 5, 'moa_for_pickup', 'MOA Ready for Pickup', 'Great news! Your MOA is now ready for pickup at the OJT Office. Please bring a valid ID when collecting the document.', 'student.document', 'fa-envelope', 1, 0, '2025-04-21 07:08:46', '2025-04-21 07:09:13'),
(11, 5, 'moa_picked_up', 'MOA Picked Up', 'Your MOA has been successfully picked up by Genesis Roxas. Make sure to submit it to your company supervisor promptly.', 'student.document', 'fa-check-circle', 1, 0, '2025-04-21 07:09:50', '2025-04-21 07:10:00'),
(12, 5, 'moa_for_pickup', 'MOA Ready for Pickup', 'Great news! Your MOA is now ready for pickup at the OJT Office. Please bring a valid ID when collecting the document.', 'student.document', 'fa-envelope', 1, 0, '2025-04-21 07:12:38', '2025-04-21 08:03:21'),
(13, 79, 'endorsement_picked_up', 'Endorsement Letter Picked Up', 'Your endorsement letter has been picked up by adefaef.', 'student.document', 'fa-check-circle', 1, 0, '2025-04-21 07:13:26', '2025-04-21 07:13:47'),
(14, 68, 'instructor_verified', 'Instructor Verified', 'Your instructor account has been verified successfully.', '', 'fa-user-check', 1, 0, '2025-04-21 07:32:46', '2025-04-21 07:35:46'),
(16, 81, 'instructor_verified', 'Instructor Verified', 'Your instructor account has been verified successfully.', '', 'fa-user-check', 1, 0, '2025-04-21 07:48:54', '2025-04-21 07:51:11'),
(17, 76, 'supervisor_verified', 'Account Verified', 'Your account has been verified successfully.', '', 'fa-user-check', 1, 0, '2025-04-21 07:54:55', '2025-04-21 14:14:19'),
(18, 5, 'student_deployment', 'Deployment Assigned', 'You have been assigned to Jollibee Inc. under No Department department.', 'student.journey', 'fa-building', 1, 0, '2025-04-21 08:00:45', '2025-04-21 08:03:04'),
(19, 5, 'student_verified', 'Account Verified', 'Your account has been verified successfully.', '', 'fa-user-check', 1, 0, '2025-04-21 08:02:50', '2025-04-21 10:22:59'),
(20, 76, 'reopen_entry_completed', 'Daily Entry Completed', 'Juanito Santos Cruz Jr. has updated their entry for April 20, 2025. Please review the changes.', 'supervisor.dailyReports', 'fa-calendar-check', 1, 0, '2025-04-21 08:28:38', '2025-04-21 08:28:47'),
(22, 76, 'journal_submitted', 'Daily Journal Entry Submitted', 'Journal entry of Juanito Santos Cruz Jr. for April 21, 2025 has been submitted for review.', 'supervisor.dailyReports', 'fa-check-circle', 1, 0, '2025-04-21 08:42:44', '2025-04-21 08:42:48'),
(23, 76, 'journal_submitted', 'Daily Journal Entry Submitted', 'Journal entry of Juanito Santos Cruz Jr. for <b>April 21, 2025</b> has been submitted for review.', 'supervisor.dailyReports', 'fa-calendar-days', 1, 0, '2025-04-21 08:49:32', '2025-04-21 09:06:08'),
(24, 76, 'weekly_report_submitted', 'Weekly Report Submitted', 'Week 3 (2025-04-21-2025-04-21) of Juanito Santos Cruz Jr. has been submitted for review.', 'supervisor.weeklyReports', 'fa-calendar-week', 1, 0, '2025-04-21 09:14:29', '2025-04-21 09:25:16'),
(25, 76, 'weekly_report_submitted', 'Weekly Report Submitted', 'Week 3 (April 21, 2025-April 21, 2025) of Juanito Santos Cruz Jr. has been submitted for review.', 'supervisor.weeklyReports', 'fa-calendar-week', 1, 0, '2025-04-21 09:25:11', '2025-04-21 09:25:18'),
(26, 5, 'student_acceptance', 'Supervisor Assignment Confirmed', 'Welcome! Mc Douglas from Jollibee Inc. has accepted you as their intern. Your acceptance letter is now available.', 'student.document', 'fa-handshake', 1, 0, '2025-04-21 10:22:55', '2025-04-21 10:24:11'),
(27, 5, 'student_evaluation', 'Performance Evaluation Report', 'Your performance evaluation report has been graded by your supervisor.', 'student.document', 'fa-list-check', 1, 0, '2025-04-21 10:35:09', '2025-04-21 10:37:38'),
(28, 5, 'Reopen Journal Entry', 'Your request to reopen the journal entry for 2025-04-01 has been granted.', 'student.journal', 'student.taskAttendance', 'fa-lock-open', 1, 0, '2025-04-21 14:17:33', '2025-04-21 14:18:53'),
(29, 5, 'reopen_request', 'Reopen Journal Entry', 'Your request to reopen the journal entry for April 02, 2025 has been granted.', 'student.taskAttendance', 'fa-lock-open', 1, 0, '2025-04-21 14:24:48', '2025-04-21 14:25:08'),
(30, 5, 'reopen_rejected_entry', 'Entry Rejected', 'Your journal entry for April 21, 2025has been rejected. Please review the feedback provided and take action immediately.', 'student.taskAttendance', 'fa-circle-exclamation', 1, 0, '2025-04-21 15:03:08', '2025-04-21 15:04:01'),
(31, 5, 'rejected_entry', 'Entry Rejected', 'Your journal entry for April 21, 2025 has been rejected. Please review the feedback provided.', '', 'fa-xmark', 1, 0, '2025-04-21 15:05:15', '2025-04-21 15:05:49'),
(32, 5, 'approved_entry', 'Entry Approved', 'Your journal entry for April 21, 2025 has been approved.', 'student.taskAttendance', 'fa-check-circle', 1, 0, '2025-04-21 15:12:10', '2025-04-21 15:12:22'),
(33, 5, 'approved_weekly_report', 'Weekly Report Approved', 'Your Week  (April 20, 2025-April 20, 2025) has been approved.', 'student.taskAttendance', 'fa-check-circle', 0, 0, '2025-04-21 15:27:04', '2025-04-21 15:27:04'),
(34, 5, 'approved_weekly_report', 'Weekly Report Approved', 'Your Week 3 (April 21, 2025-April 21, 2025) has been approved.', 'student.taskAttendance', 'fa-check-circle', 0, 0, '2025-04-21 15:27:49', '2025-04-21 15:27:49'),
(35, 5, 'rejected_weekly_report', 'Weekly Report Rejected', 'Your Week 3 (April 21, 2025-April 21, 2025) has been rejected. Please review the feedback provided.', 'student.taskAttendance', 'fa-check-circle', 0, 0, '2025-04-21 15:28:38', '2025-04-21 15:28:38'),
(36, 80, 'supervisor_verified', 'Account Verified', 'Your account has been verified successfully.', '', 'fa-user-check', 0, 0, '2025-04-22 01:59:10', '2025-04-22 01:59:10'),
(37, 81, 'instructor_verified', 'Account Verified', 'Your instructor account has been verified successfully.', '', 'fa-user-check', 0, 0, '2025-04-22 01:59:38', '2025-04-22 01:59:38');

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
-- Table structure for table `reopen_requests`
--

CREATE TABLE `reopen_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `supervisor_id` bigint(20) UNSIGNED NOT NULL,
  `reopened_date` date NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `message` text DEFAULT NULL,
  `status` enum('PENDING','COMPLETED','EXPIRED','CANCELLED') NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reopen_requests`
--

INSERT INTO `reopen_requests` (`id`, `student_id`, `supervisor_id`, `reopened_date`, `expires_at`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 16, '2025-04-16', '2025-04-20 07:34:22', '', 'EXPIRED', '2025-04-20 04:56:05', '2025-04-20 07:34:22'),
(3, 1, 16, '2025-04-15', '2025-04-20 07:35:23', '', 'COMPLETED', '2025-04-20 05:50:49', '2025-04-20 07:35:23'),
(7, 1, 16, '2025-04-20', '2025-04-21 08:28:38', 'gawin mo sis', 'COMPLETED', '2025-04-21 07:53:16', '2025-04-21 08:28:38'),
(8, 1, 16, '2025-04-14', '2025-04-21 07:58:19', 'dsvdecveefaefaef', 'EXPIRED', '2025-04-20 07:57:52', '2025-04-21 07:58:19'),
(9, 1, 16, '2025-04-01', '2025-04-22 14:17:33', 'galingan mo sis', 'PENDING', '2025-04-21 14:17:33', '2025-04-21 14:17:33'),
(10, 1, 16, '2025-04-02', '2025-04-22 14:24:48', 'gawinn mo', 'PENDING', '2025-04-21 14:24:48', '2025-04-21 14:24:48');

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
(20, 9, 4, 'A', NULL, '2025-03-25 02:55:37', '2025-04-21 16:24:55'),
(21, 9, 4, 'B', NULL, '2025-03-25 02:55:37', '2025-03-25 02:55:37'),
(23, 9, 4, 'C', NULL, '2025-04-21 19:09:38', '2025-04-21 19:09:38');

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
('u5fzE3mLgJxiZLcSDvKh052ZLAc7Zzn0mOwihUcJ', 67, '192.168.100.2', 'Mozilla/5.0 (X11; Linux x86_64; rv:136.0) Gecko/20100101 Firefox/136.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYWhxeUcxYWs3STdpNG95cTI1eVh1Uk4yOVdrRThtYjRPc2k3QlE3SCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xOTIuMTY4LjEwMC4yOjgwMDIvYWRtaW4vZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njc7fQ==', 1745292276);

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
  `minimum_minutes` int(11) NOT NULL DEFAULT 0,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `system_name`, `default_theme`, `default_logo`, `school_name`, `school_address`, `system_email`, `system_contact`, `minimum_minutes`, `updated_by`, `created_at`, `updated_at`) VALUES
(3, 'InternSync', 'blue', 'logos/logo_62268f75-499e-478c-a690-18804a76bbf1.svg', 'Bulacan Polytechnic College', 'Bulihan, City of Malolos, Bulacan', 'example@email.com', '09123456789', 20, NULL, NULL, '2025-04-21 03:51:45');

-- --------------------------------------------------------

--
-- Table structure for table `signatures`
--

CREATE TABLE `signatures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `document_filename` varchar(255) DEFAULT NULL,
  `certified` tinyint(1) NOT NULL DEFAULT 0,
  `from_ips` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`from_ips`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 5, '2400000001', 'Juanito', 'Santos', 'Cruz', 'Jr.', '2001-11-22', '321, Sulipan, Apalit, Pampanga', '09999999999', 1, NULL, '2024-12-09 08:16:12', '2025-04-22 03:11:43', NULL, 'profiles/student/profile_student__wB15JyOr.png'),
(40, 79, 'MA21011456', 'Genesis', 'Retardo', 'Roxas', '', NULL, '123, Sagrada Familia, Hagonoy, Bulacan', '09123787545', 1, NULL, '2025-03-25 04:27:37', '2025-04-21 18:35:23', 'documents/79_student_roxas_genesis_5GWWYAI8.jpeg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supervisors`
--

CREATE TABLE `supervisors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_department_id` bigint(20) DEFAULT NULL,
  `position` varchar(225) NOT NULL,
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
  `signature_path` varchar(255) DEFAULT NULL,
  `is_profile_complete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supervisors`
--

INSERT INTO `supervisors` (`id`, `user_id`, `company_id`, `company_department_id`, `position`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthday`, `contact`, `remember_token`, `created_at`, `updated_at`, `supporting_doc`, `image`, `signature_path`, `is_profile_complete`) VALUES
(16, 76, 12, 14, 'Manager', 'Mc', '', 'Douglas', '', NULL, '09876786565', NULL, '2025-03-09 18:09:02', '2025-04-22 03:16:08', 'documents/76_supervisor_douglas_mc_Jdx0dS13.jpg', 'profiles/supervisor/profile_supervisor__dig2gqCg.png', 'signatures/76_supervisor_douglas_mc_iKUMSqlH.png', 0),
(18, 80, 15, 13, 'Manager', 'Mr', '', 'Krabs', '', NULL, '09123456789', NULL, '2025-03-25 04:36:09', '2025-04-19 03:55:36', 'documents/80_supervisor_krabs_mr_D20bpwj3.jpg', NULL, 'signatures/80_supervisor_krabs_mr_K7IsoDFD.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `description`, `order`, `created_at`, `updated_at`) VALUES
(3, 'nice', 0, '2025-03-25 03:42:19', '2025-03-25 03:42:19'),
(4, 'Test', 0, '2025-04-17 06:27:35', '2025-04-19 06:27:35'),
(5, 'Test', 0, '2025-04-18 06:27:35', '2025-04-18 06:27:35'),
(6, 'Testing', 1, '2025-04-19 10:50:10', '2025-04-19 10:50:10'),
(12, 'dqwdqw', 0, '2025-04-19 22:37:09', '2025-04-19 22:37:35'),
(13, 'afaefaef', 0, '2025-04-20 07:28:55', '2025-04-20 07:28:55'),
(14, 'April 15', 0, '2025-04-20 07:35:23', '2025-04-20 07:35:23'),
(15, 'ANother 15', 0, '2025-04-20 07:35:23', '2025-04-20 07:35:23'),
(16, 'Hello', 0, '2025-04-20 07:46:08', '2025-04-20 07:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `task_histories`
--

CREATE TABLE `task_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `journal_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_histories`
--

INSERT INTO `task_histories` (`id`, `task_id`, `journal_id`, `status`, `changed_at`, `created_at`, `updated_at`) VALUES
(17, 3, 29, 'pending', '2025-03-25 03:42:19', '2025-03-25 03:42:19', '2025-03-25 03:42:19'),
(19, 4, 30, 'done', '2025-04-17 06:27:39', '2025-04-17 06:27:39', '2025-04-17 06:27:39'),
(20, 5, 31, 'done', '2025-04-19 10:46:32', '2025-04-18 06:27:39', '2025-04-18 06:27:39'),
(21, 3, 32, 'pending', '2025-04-19 10:49:54', '2025-04-19 10:49:54', '2025-04-19 10:49:54'),
(22, 3, 32, 'done', '2025-04-19 10:50:04', '2025-04-19 10:50:04', '2025-04-19 10:50:04'),
(23, 6, 32, 'pending', '2025-04-19 10:50:10', '2025-04-19 10:50:10', '2025-04-19 10:50:10'),
(31, 12, 38, 'pending', '2025-04-19 22:37:09', '2025-04-19 22:37:09', '2025-04-19 22:37:09'),
(32, 13, 39, 'pending', '2025-04-20 07:28:55', '2025-04-20 07:28:55', '2025-04-20 07:28:55'),
(33, 14, 40, 'pending', '2025-04-20 07:35:23', '2025-04-20 07:35:23', '2025-04-20 07:35:23'),
(34, 15, 40, 'pending', '2025-04-20 07:35:23', '2025-04-20 07:35:23', '2025-04-20 07:35:23'),
(35, 16, 41, 'done', '2025-04-20 07:46:08', '2025-04-20 07:46:08', '2025-04-20 07:46:08'),
(36, 16, 41, 'done', '2025-04-20 07:50:15', '2025-04-20 07:50:15', '2025-04-20 07:50:15'),
(38, 12, 38, 'pending', '2025-04-21 08:13:07', '2025-04-21 08:13:07', '2025-04-21 08:13:07'),
(39, 12, 38, 'pending', '2025-04-21 08:13:12', '2025-04-21 08:13:12', '2025-04-21 08:13:12'),
(40, 12, 38, 'pending', '2025-04-21 08:14:16', '2025-04-21 08:14:16', '2025-04-21 08:14:16'),
(41, 12, 38, 'pending', '2025-04-21 08:14:16', '2025-04-21 08:14:16', '2025-04-21 08:14:16'),
(42, 12, 38, 'pending', '2025-04-21 08:14:17', '2025-04-21 08:14:17', '2025-04-21 08:14:17'),
(43, 12, 38, 'pending', '2025-04-21 08:14:29', '2025-04-21 08:14:29', '2025-04-21 08:14:29'),
(44, 12, 38, 'pending', '2025-04-21 08:15:18', '2025-04-21 08:15:18', '2025-04-21 08:15:18'),
(45, 12, 38, 'pending', '2025-04-21 08:18:51', '2025-04-21 08:18:51', '2025-04-21 08:18:51'),
(46, 12, 38, 'pending', '2025-04-21 08:18:51', '2025-04-21 08:18:51', '2025-04-21 08:18:51'),
(47, 12, 38, 'pending', '2025-04-21 08:18:51', '2025-04-21 08:18:51', '2025-04-21 08:18:51'),
(48, 12, 38, 'pending', '2025-04-21 08:18:51', '2025-04-21 08:18:51', '2025-04-21 08:18:51'),
(49, 12, 38, 'pending', '2025-04-21 08:18:51', '2025-04-21 08:18:51', '2025-04-21 08:18:51'),
(50, 12, 38, 'pending', '2025-04-21 08:18:51', '2025-04-21 08:18:51', '2025-04-21 08:18:51'),
(51, 12, 38, 'pending', '2025-04-21 08:18:51', '2025-04-21 08:18:51', '2025-04-21 08:18:51'),
(52, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(53, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(54, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(55, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(56, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(57, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(58, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(59, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(60, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(61, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(62, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(63, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(64, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(65, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(66, 12, 38, 'pending', '2025-04-21 08:20:44', '2025-04-21 08:20:44', '2025-04-21 08:20:44'),
(67, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(68, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(69, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(70, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(71, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(72, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(73, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(74, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(75, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(76, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(77, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(78, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(79, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(80, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(81, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(82, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(83, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(84, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(85, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(86, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(87, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(88, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(89, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(90, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(91, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(92, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(93, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(94, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(95, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(96, 12, 38, 'pending', '2025-04-21 08:28:38', '2025-04-21 08:28:38', '2025-04-21 08:28:38'),
(97, 6, 44, 'pending', '2025-04-21 08:40:11', '2025-04-21 08:40:11', '2025-04-21 08:40:11'),
(98, 6, 44, 'done', '2025-04-21 08:40:14', '2025-04-21 08:40:14', '2025-04-21 08:40:14');

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
(5, 'student@email.com', '$2y$12$7o46g0PsM2Ct3aYk41oXkOa18Gx2l6YyGWjHPpf.D4Yp6w58v8si.', 'student', 111507, '2024-12-09 08:31:12', '2024-12-10 14:16:20', 1, 1, NULL, '2024-12-09 08:16:12', '2025-04-22 03:06:26'),
(67, 'admin@email.com', '$2y$12$wPzev.AhSi8LAG61z8iGr.4Chdey6T7nCW71QECDt4dDhX9joMJXK', 'admin', NULL, NULL, '2025-02-17 18:41:04', 1, 1, NULL, '2025-02-17 18:38:57', '2025-02-17 18:38:57'),
(68, 'instructor@email.com', '$2y$12$LRQv1huhPcAKLttg3AvbUOZz9WK64n0G.ZRa7JSf1hIL4DzXoP1Ae', 'instructor', NULL, NULL, '2025-02-25 13:08:24', 1, 1, 'UsQUKPoRASdkD5dWQsUdYZFADpS6yQbR6f4wtaefhy9p2sa3TSntuko7wxE9', '2025-02-18 04:34:41', '2025-04-21 07:32:46'),
(76, 'supervisor@email.com', '$2y$12$0KHWzUFr6OucYoIyLRvpt.2zvrVe/LVpSaXEGZEmIu2OycsmsFDbe', 'supervisor', NULL, NULL, '2025-03-09 18:09:27', 1, 1, NULL, '2025-03-09 18:09:02', '2025-04-21 07:54:55'),
(79, 'genesisroxas4@gmail.com', '$2y$12$KrQDrB79UBQT1fEx6QPHZOxZaPzAVL/1xUS2StsM5SiIuJ1tMCCCC', 'student', NULL, NULL, '2025-04-22 02:47:52', 1, 1, NULL, '2025-03-25 04:27:35', '2025-04-22 02:47:52'),
(80, 'mrkrabs@fakemaill.com', '$2y$12$TuqaZdfPbEVZ9JfCcjfVle0Nkyl3Ol4ZiXf1rjxRBipD05BWUE416', 'supervisor', 210367, '2025-03-25 04:51:08', '2025-03-25 04:36:27', 1, 1, NULL, '2025-03-25 04:36:08', '2025-04-22 01:59:10'),
(81, 'ins2@email.com', '$2y$12$pDGsJaFe7Y1tLIp5JL1nourzFUTSvz14y/piEci.GTl8GUaSr1dOu', 'instructor', 201592, '2025-04-21 07:39:43', '2025-04-21 07:49:32', 1, 1, NULL, '2025-04-21 07:24:43', '2025-04-22 01:59:38');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_reports`
--

CREATE TABLE `weekly_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `week_number` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `learning_outcomes` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `supervisor_feedback` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `weekly_reports`
--

INSERT INTO `weekly_reports` (`id`, `student_id`, `week_number`, `start_date`, `end_date`, `learning_outcomes`, `status`, `supervisor_feedback`, `submitted_at`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(26, 1, 1, '2025-04-16', '2025-04-19', 'Marzipan ice cream chocolate oat cake marzipan macaroon bear claw dragée chocolate cake. Pudding marzipan soufflé cotton candy chocolate cake pudding. Ice cream sweet sesame snaps sweet gummi bears. Candy candy brownie gummies gummies.\nCotton candy lollipop halvah gummi bears oat cake pastry croissant gingerbread. Chocolate bar cotton candy dragée muffin caramels jelly. Sesame snaps candy macaroon liquorice caramels wafer. Biscuit powder topping lollipop apple pie.\nCandy canes lemon drops oat cake powder apple pie sweet lemon drops. Sweet roll caramels macaroon jelly marshmallow shortbread dessert wafer cotton candy. Muffin jujubes sugar plum fruitcake tiramisu. Halvah biscuit chocolate bar gummies jelly beans sweet roll soufflé sugar plum.\nBear claw muffin cake tart pudding. Liquorice lollipop bear claw pie cake marzipan. Dragée cake marshmallow danish jelly. Macaroon cake oat cake bear claw donut jujubes macaroon liquorice brownie.\nBrownie sweet roll lollipop dessert gummies chupa chups lemon drops danish tiramisu. Candy canes cheesecake shortbread sesame snaps muffin halvah. Bonbon pie cheesecake fruitcake lollipop pie pie chocolate.', 'approved', '', '2025-04-19 09:42:12', '2025-04-19 22:09:21', '2025-04-19 09:42:12', '2025-04-19 22:09:21'),
(28, 1, 2, '2025-04-20', '2025-04-20', 'Marzipan ice cream chocolate oat cake marzipan macaroon bear claw dragée chocolate cake. Pudding marzipan soufflé cotton candy chocolate cake pudding. Ice cream sweet sesame snaps sweet gummi bears. Candy candy brownie gummies gummies. Cotton candy lollipop halvah gummi bears oat cake pastry croissant gingerbread. Chocolate bar cotton candy dragée muffin caramels jelly. Sesame snaps candy macaroon liquorice caramels wafer. Biscuit powder topping lollipop apple pie. Candy canes lemon drops oat cake powder apple pie sweet lemon drops. Sweet roll caramels macaroon jelly marshmallow shortbread dessert wafer cotton candy. Muffin jujubes sugar plum fruitcake tiramisu. Halvah biscuit chocolate bar gummies jelly beans sweet roll soufflé sugar plum. Bear claw muffin cake tart pudding. Liquorice lollipop bear claw pie cake marzipan. Dragée cake marshmallow danish jelly. Macaroon cake oat cake bear claw donut jujubes macaroon liquorice brownie. Brownie sweet roll lollipop dessert gummies chupa chups lemon drops danish tiramisu. Candy canes cheesecake shortbread sesame snaps muffin halvah. Bonbon pie cheesecake fruitcake lollipop pie pie chocolate. ', 'approved', 'yesssss', '2025-04-19 22:49:42', '2025-04-21 15:27:03', '2025-04-19 22:49:42', '2025-04-21 15:27:03'),
(33, 1, 3, '2025-04-21', '2025-04-21', 'Marzipan ice cream chocolate oat cake marzipan macaroon bear claw dragée chocolate cake. Pudding marzipan soufflé cotton candy chocolate cake pudding. Ice cream sweet sesame snaps sweet gummi bears. Candy candy brownie gummies gummies. Cotton candy lollipop halvah gummi bears oat cake pastry croissant gingerbread. Chocolate bar cotton candy dragée muffin caramels jelly. Sesame snaps candy macaroon liquorice caramels wafer. Biscuit powder topping lollipop apple pie. Candy canes lemon drops oat cake powder apple pie sweet lemon drops. Sweet roll caramels macaroon jelly marshmallow shortbread dessert wafer cotton candy. Muffin jujubes sugar plum fruitcake tiramisu. Halvah biscuit chocolate bar gummies jelly beans sweet roll soufflé sugar plum. Bear claw muffin cake tart pudding. Liquorice lollipop bear claw pie cake marzipan. Dragée cake marshmallow danish jelly. Macaroon cake oat cake bear claw donut jujubes macaroon liquorice brownie. Brownie sweet roll lollipop dessert gummies chupa chups lemon drops danish tiramisu. Candy canes cheesecake shortbread sesame snaps muffin halvah. Bonbon pie cheesecake fruitcake lollipop pie pie chocolate.', 'rejected', 'dswaDAWDWADW', '2025-04-21 09:25:11', '2025-04-21 15:28:38', '2025-04-21 09:25:11', '2025-04-21 15:28:38');

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
-- Indexes for table `endorsement_letter_requests`
--
ALTER TABLE `endorsement_letter_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `endorsement_letter_requests_company_id_foreign` (`company_id`),
  ADD KEY `endorsement_letter_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `endorsement_letter_requests_admin_id_foreign` (`admin_id`),
  ADD KEY `endorsement_letter_requests_status_index` (`status`),
  ADD KEY `endorsement_letter_requests_requested_at_index` (`requested_at`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evaluations_deployment_id_unique` (`deployment_id`),
  ADD KEY `evaluations_supervisor_id_foreign` (`supervisor_id`);

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
  ADD KEY `instructor_courses_course_id_foreign` (`course_id`),
  ADD KEY `instructor_courses_academic_year_id_foreign` (`academic_year_id`);

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
-- Indexes for table `moa_requests`
--
ALTER TABLE `moa_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `moa_requests_company_id_foreign` (`company_id`),
  ADD KEY `moa_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `moa_requests_received_by_supervisor_foreign` (`received_by_supervisor`),
  ADD KEY `moa_requests_admin_id_foreign` (`admin_id`),
  ADD KEY `moa_requests_status_index` (`status`),
  ADD KEY `moa_requests_requested_at_index` (`requested_at`),
  ADD KEY `moa_requests_company_number_index` (`company_number`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_is_read_is_archived_index` (`user_id`,`is_read`,`is_archived`),
  ADD KEY `notifications_type_index` (`type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reopen_requests`
--
ALTER TABLE `reopen_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reopen_requests_student_id_reopened_date_status_unique` (`student_id`,`reopened_date`,`status`),
  ADD KEY `reopen_requests_supervisor_id_foreign` (`supervisor_id`);

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
-- Indexes for table `signatures`
--
ALTER TABLE `signatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `signatures_model_type_model_id_index` (`model_type`,`model_id`);

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
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_histories`
--
ALTER TABLE `task_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_histories_task_id_changed_at_index` (`task_id`,`changed_at`),
  ADD KEY `task_histories_journal_id_status_index` (`journal_id`,`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `weekly_reports_student_id_week_number_unique` (`student_id`,`week_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academics`
--
ALTER TABLE `academics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `acceptance_letters`
--
ALTER TABLE `acceptance_letters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `company_departments`
--
ALTER TABLE `company_departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `deployments`
--
ALTER TABLE `deployments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `endorsement_letter_requests`
--
ALTER TABLE `endorsement_letter_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `instructor_sections`
--
ALTER TABLE `instructor_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `journals`
--
ALTER TABLE `journals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `moa_requests`
--
ALTER TABLE `moa_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `reopen_requests`
--
ALTER TABLE `reopen_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rich_texts`
--
ALTER TABLE `rich_texts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `signatures`
--
ALTER TABLE `signatures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `supervisors`
--
ALTER TABLE `supervisors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `task_histories`
--
ALTER TABLE `task_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
-- Constraints for table `endorsement_letter_requests`
--
ALTER TABLE `endorsement_letter_requests`
  ADD CONSTRAINT `endorsement_letter_requests_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `endorsement_letter_requests_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `endorsement_letter_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_deployment_id_foreign` FOREIGN KEY (`deployment_id`) REFERENCES `deployments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
  ADD CONSTRAINT `instructor_courses_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instructor_courses_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
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
-- Constraints for table `moa_requests`
--
ALTER TABLE `moa_requests`
  ADD CONSTRAINT `moa_requests_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `moa_requests_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `moa_requests_received_by_supervisor_foreign` FOREIGN KEY (`received_by_supervisor`) REFERENCES `supervisors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `moa_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reopen_requests`
--
ALTER TABLE `reopen_requests`
  ADD CONSTRAINT `reopen_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reopen_requests_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisors` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `task_histories`
--
ALTER TABLE `task_histories`
  ADD CONSTRAINT `task_histories_journal_id_foreign` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`),
  ADD CONSTRAINT `task_histories_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  ADD CONSTRAINT `weekly_reports_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
