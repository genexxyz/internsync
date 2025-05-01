-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 01, 2025 at 09:02 PM
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
(1, '2024-2025', '2nd Semester', 1, 1, NULL, '2024-01-06', '2025-09-30', '2025-05-01 18:46:29', '2025-05-01 01:39:47');

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
  `reference_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `is_super_admin` tinyint(1) NOT NULL,
  `birthday` date DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `is_super_admin`, `birthday`, `remember_token`, `created_at`, `updated_at`, `image`) VALUES
(1, 67, 'Admin', NULL, 'Admin', NULL, 1, NULL, NULL, NULL, '2025-05-01 18:06:08', 'profile-photos/hriXXhcHCoV6MCNQ6Qj7eDqpns19rpYnlyrrB7PS.png');

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
('17f8a330c3d0cb5910c795b74a3fbeafe6c3cf2b', 'i:1;', 1745979794),
('17f8a330c3d0cb5910c795b74a3fbeafe6c3cf2b:timer', 'i:1745979794;', 1745979794),
('4d89d294cd4ca9f2ca57dc24a53ffb3ef5303122', 'i:1;', 1746125960),
('4d89d294cd4ca9f2ca57dc24a53ffb3ef5303122:timer', 'i:1746125960;', 1746125960),
('admin@email.com|192.168.100.2', 'i:1;', 1746125842),
('admin@email.com|192.168.100.2:timer', 'i:1746125842;', 1746125842),
('genesisroxas3@email.com|192.168.100.2', 'i:5;', 1746125855),
('genesisroxas3@email.com|192.168.100.2:timer', 'i:1746125855;', 1746125855);

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
(17, 'McDonalds', 'Mohon, City of Malolos', NULL, NULL, '2025-04-30 02:08:40', '2025-04-30 02:08:40');

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
(18, 'Counter', 17, '2025-04-30 02:08:40', '2025-04-30 02:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `required_hours` int(11) NOT NULL,
  `allows_custom_hours` tinyint(1) NOT NULL DEFAULT 0,
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

INSERT INTO `courses` (`id`, `course_name`, `course_code`, `required_hours`, `allows_custom_hours`, `custom_hours`, `academic_year_id`, `year_level`, `instructor_id`, `created_at`, `updated_at`) VALUES
(1, 'Bachelor of Science in Information Systems', 'BSIS', 500, 1, 250, 1, 4, 1, '2024-12-09 02:05:07', '2024-12-09 02:05:07');

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
  `status` enum('pending','ongoing','completed','cancelled') NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `permit_path` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(18, 68, '4675656757', 'Juan', '', 'Dela Cruz', '', NULL, NULL, '0922434212121', NULL, '2025-02-18 04:34:41', '2025-04-22 03:14:13', 'documents/68_instructor_dela-cruz_juan_8CpsBlOi.png', 'profiles/instructor/profile_instructor__mb8fvkFd.png', 'signatures/68_instructor_dela cruz_juan_XhclxMK5.png');

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
(25, 18, 1, 1, 1, '2025-05-01 16:53:02', '2025-05-01 16:53:02');

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
(13, 18, 1, 1, 1, '2025-02-18 04:34:41', '2025-02-25 13:17:38');

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

-- --------------------------------------------------------

--
-- Table structure for table `letter_templates`
--

CREATE TABLE `letter_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `variables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variables`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letter_templates`
--

INSERT INTO `letter_templates` (`id`, `title`, `content`, `variables`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '2024-2025 2nd Sem', '     The {school_name} offers a {course_name} program, which requires all graduating students to complete On-the-Job Training as part of their academic curriculum. With the resumption of on-site OJT opportunities, the student is expected to complete {required_hours} hours of training, either in person or online, depending on the company\'s health protocols and work arrangements.\n    \n      In line with this, we respectfully request your good office to accommodate {student_name}, a dedicated and competent {course_name} student, for his/her OJT in your esteemed company. S/he has been carefully selected based on her academic achievements and technical skills. Additionally, s/he has undergone proper orientation to ensure s/he meets the performance standards expected by both the school and the industry.\n    \n     We sincerely appreciate your time and consideration. Thank you in advance for your support, and we look forward to a positive collaboration.', '[\"{school_name}\",\"{course_name}\",\"{student_name}\",\"{supervisor_name}\",\"{company_name}\",\"{current_date}\",\"{required_hours}\"]', 1, '2025-05-01 04:54:39', '2025-05-01 05:05:50');

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
(33, '2025_02_16_092231_instructor_courses', 14),
(34, '2025_05_01_123540_letter_templates', 15);

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
(3, 67, 'test_notif', 'Test 233', 'Test message 1', 'admin.company', 'fa-user', 1, 0, '2025-04-21 04:37:46', '2025-04-21 06:35:44');

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
(1, 1, 4, 'A', 1, '2024-12-09 02:05:47', '2024-12-09 02:05:47');

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
('ZaYr1pLcwqRuMfjDKtVMmg3jBUzvWUuEeWnl6sob', NULL, '192.168.100.2', 'Mozilla/5.0 (X11; Linux x86_64; rv:136.0) Gecko/20100101 Firefox/136.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidFdKRWNUUlV0MHRKWEVmRzRndFJlU1JVbG9SeHZFZFJMUEluMkZFNSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xOTIuMTY4LjEwMC4yOjgwMDIvcmVnaXN0ZXIiO319', 1746125924);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_name` varchar(255) NOT NULL,
  `default_theme` varchar(255) NOT NULL,
  `default_logo` varchar(255) DEFAULT NULL,
  `header_image` varchar(255) DEFAULT NULL,
  `footer_image` varchar(255) DEFAULT NULL,
  `school_name` varchar(255) NOT NULL,
  `school_address` varchar(255) NOT NULL,
  `system_email` varchar(255) NOT NULL,
  `system_contact` varchar(255) NOT NULL,
  `minimum_hours` int(11) NOT NULL DEFAULT 0,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `system_name`, `default_theme`, `default_logo`, `header_image`, `footer_image`, `school_name`, `school_address`, `system_email`, `system_contact`, `minimum_hours`, `updated_by`, `created_at`, `updated_at`) VALUES
(3, 'InternSync', 'blue', 'logos/logo_e3d60a00-6eca-4717-90ac-5561d74b064b.svg', 'logos/header_a0fd00a2-7024-4b48-96bd-521004b571ec.jpg', NULL, 'Bulacan Polytechnic College', 'Bulihan, City of Malolos, Bulacan', 'communications@bpc.edu.ph', '(044) 802 6716 / (044) 796 2306', 4, NULL, NULL, '2025-05-01 18:58:22');

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

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_id` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(67, 'genesisroxas3@gmail.com', '$2y$12$wPzev.AhSi8LAG61z8iGr.4Chdey6T7nCW71QECDt4dDhX9joMJXK', 'admin', NULL, NULL, '2025-02-17 18:41:04', 1, 1, 'BEmm0k0cE9hQODOBkOTgUQDCCLFDo1brbLF3QLYR5Ly3gmNQfu8KXBDfaM5f', '2025-02-17 18:38:57', '2025-05-01 18:42:44');

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
-- Indexes for table `letter_templates`
--
ALTER TABLE `letter_templates`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `company_departments`
--
ALTER TABLE `company_departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `deployments`
--
ALTER TABLE `deployments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `instructor_sections`
--
ALTER TABLE `instructor_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `journals`
--
ALTER TABLE `journals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `letter_templates`
--
ALTER TABLE `letter_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `moa_requests`
--
ALTER TABLE `moa_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `reopen_requests`
--
ALTER TABLE `reopen_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rich_texts`
--
ALTER TABLE `rich_texts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `supervisors`
--
ALTER TABLE `supervisors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `task_histories`
--
ALTER TABLE `task_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
