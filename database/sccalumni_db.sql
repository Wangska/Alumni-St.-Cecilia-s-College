-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2025 at 11:42 AM
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
-- Database: `sccalumni_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumni_documents`
--

CREATE TABLE `alumni_documents` (
  `id` int(11) NOT NULL,
  `alumnus_id` int(11) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `is_verified` tinyint(1) DEFAULT 0,
  `verified_by` int(11) DEFAULT NULL,
  `verified_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni_documents`
--

INSERT INTO `alumni_documents` (`id`, `alumnus_id`, `document_type`, `document_name`, `file_path`, `file_size`, `upload_date`, `is_verified`, `verified_by`, `verified_date`, `notes`) VALUES
(3, 22, 'tor', '546103898_726364643754624_7205992835825845671_n.jpg', 'documents/doc_tor_decf81e92b3a.jpg', 787824, '2025-10-19 14:55:14', 0, NULL, NULL, NULL),
(4, 22, 'diploma', 'ssss.jpg', 'documents/doc_diploma_32054cf29303.jpg', 5361, '2025-10-19 14:55:14', 0, NULL, NULL, NULL),
(5, 24, 'tor', 'bsit.png', 'documents/doc_tor_eab89e3aac78.png', 373494, '2025-10-20 02:11:02', 0, NULL, NULL, NULL),
(6, 24, 'diploma', 'htm.png', 'documents/doc_diploma_cf9c45afe81b.png', 441287, '2025-10-20 02:11:02', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `alumnus_bio`
--

CREATE TABLE `alumnus_bio` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `middlename` varchar(200) DEFAULT '',
  `lastname` varchar(200) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `batch` year(4) NOT NULL,
  `course_id` int(30) NOT NULL,
  `email` varchar(250) NOT NULL,
  `contact` varchar(20) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `connected_to` text DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0= Unverified, 1= Verified',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumnus_bio`
--

INSERT INTO `alumnus_bio` (`id`, `firstname`, `middlename`, `lastname`, `gender`, `batch`, `course_id`, `email`, `contact`, `address`, `connected_to`, `avatar`, `status`, `date_created`) VALUES
(22, 'Joshua', '', 'Espanillo', 'Male', '2014', 3, 'wangska1283@gmail.com', '09666091329', 'Little Valley Colon City of Naga Cebu', '', 'avatar_2af301c3f9bf.png', 1, '2025-10-19 14:55:14'),
(24, 'johnrey', '', 'cambaya', 'Male', '2014', 11, 'johnreycanete2001@gmail.com', '09927854615', 'Little Valley\r\nColon', '', 'avatar_6d3e76cf286c.jpg', 1, '2025-10-20 02:11:02');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `date_posted` datetime NOT NULL DEFAULT current_timestamp(),
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `image`, `content`, `date_posted`, `date_created`) VALUES
(18, 'General Alumni Gathering Announcement', 'announcement_6cd8220ed9c5.png', '📢 ANNOUNCEMENT: Alumni Homecoming 2025!\r\nCalling all proud alumni of SCC! 🎉\r\nJoin us for our Grand Alumni Homecoming happening on [Date], at [Venue]. It’s a perfect time to reconnect with old friends, share memories, and celebrate the spirit of our alma mater.\r\n\r\nLet’s make this event memorable with laughter, stories, and friendship that last a lifetime. 💙\r\n\r\n📅 Date: [Insert Date]\r\n📍 Venue: [Insert Venue]\r\n🕒 Time: [Insert Time]\r\n\r\nStay tuned for updates! #AlumniHomecoming #ForeverSCC', '2025-10-20 16:56:29', '2025-10-20 16:56:29'),
(19, 'Career Opportunity Announcement', 'announcement_a21e4a35a2fb.png', '📢 ATTENTION ALUMNI!\r\nWe’re excited to share that [Company Name] is looking for qualified candidates for the position of [Job Title].\r\nIf you’re passionate, skilled, and ready for a new challenge, this could be your chance to grow your career!\r\n\r\n📍 Position: [Job Title]\r\n📧 How to Apply: Send your resume to [email address]\r\n⏰ Deadline: [Insert Date]\r\n\r\nDon’t miss this opportunity — your next big step might start here! 🚀', '2025-10-20 16:57:59', '2025-10-20 16:57:59'),
(20, 'Alumni Spotlight Announcement', 'announcement_0647cf2ca3d2.png', '🌟 ALUMNI SPOTLIGHT!\r\nWe are proud to feature one of our outstanding alumni, [Name] (Batch [Year]), who has made remarkable achievements in [field or profession].\r\nYour success continues to inspire current students and fellow alumni. Congratulations and keep making us proud! 👏\r\n\r\nWant to be featured next? Message us and share your story! 💬\r\n\r\n#AlumniSpotlight #SuccessStory #ProudToBe[SchoolName]', '2025-10-20 17:29:34', '2025-10-20 17:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `careers`
--

CREATE TABLE `careers` (
  `id` int(30) NOT NULL,
  `company` varchar(250) NOT NULL,
  `location` varchar(255) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `company_logo` text DEFAULT NULL COMMENT 'Company logo image file path',
  `user_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `careers`
--

INSERT INTO `careers` (`id`, `company`, `location`, `job_title`, `description`, `company_logo`, `user_id`, `date_created`) VALUES
(5, 'Accenture', 'IT Park', 'IT tech', 'Job Description:\r\n\r\nThe IT Technician is responsible for maintaining, troubleshooting, and supporting the organization’s computer systems, networks, and other technology-related equipment. This role ensures that all hardware, software, and network systems run efficiently and securely to support daily operations.\r\n\r\nThe IT Technician assists users with technical issues, installs and configures computer hardware and software, and ensures data security through regular maintenance and updates.', 'company_dfc83a17694a.png', 1, '2025-10-20 09:45:52'),
(6, 'Teleperformance', 'IT Park', 'Administrative Assistant', 'Responsibilities:\r\nManage schedules, appointments, and meetings\r\nPrepare and file documents\r\nHandle phone calls, emails, and correspondence\r\nSupport staff with office tasks and reports\r\n\r\nQualifications:\r\nGraduate of Business Administration or any related field\r\nProficient in MS Office (Word, Excel, PowerPoint)\r\nOrganized and detail-oriented\r\n\r\nSkills:\r\n\r\nTime management, communication, multitasking', 'company_945a4b67b538.png', 1, '2025-10-20 11:27:10'),
(7, 'PLDT', 'Tabunok', 'Accountant', 'Job Description:\r\nManages financial records, ensures accurate reporting, and supports budget planning.\r\n\r\nResponsibilities:\r\nPrepare financial statements and reports\r\nHandle accounts payable and receivable\r\nMonitor expenses and budget usage\r\nEnsure compliance with accounting standards\r\n\r\nQualifications:\r\nBachelor’s degree in Accountancy or Finance\r\nCPA license is an advantage\r\nKnowledge of accounting software (e.g., QuickBooks)\r\n\r\nSkills:\r\nAnalytical thinking, accuracy, and financial reporting', 'company_47d8c667443d.png', 1, '2025-10-20 11:28:03');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(30) NOT NULL,
  `course` varchar(200) NOT NULL,
  `about` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course`, `about`, `image`) VALUES
(1, 'BS Information Technology', 'Sample', 'course_bc859a7d3a70.png'),
(3, 'BS Education', '', 'course_056bfd47968e.png'),
(4, 'BS Nursing', '', 'course_4a2cb9db9706.png'),
(5, 'BS Criminology', '', 'course_cb3c51ce804e.png'),
(7, 'BS Business Administration', '', 'course_897191ca4320.png'),
(11, 'BS Tourism', '', 'course_16f50daaee7e.png');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(30) NOT NULL,
  `title` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `schedule` datetime NOT NULL,
  `banner` text DEFAULT NULL,
  `participant_limit` int(11) DEFAULT NULL COMMENT 'Maximum number of participants allowed for this event',
  `max_participants` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `content`, `schedule`, `banner`, `participant_limit`, `max_participants`, `date_created`) VALUES
(36, 'Tree Planting Activity', 'The Tree Planting Activity aims to promote environmental awareness and sustainability by encouraging participants to take part in greening the community. This event provides an opportunity for volunteers, students, and local residents to work together in planting trees that will contribute to cleaner air, improved biodiversity, and a healthier ecosystem.\r\n\r\nThrough this initiative, participants will not only help combat climate change but also learn the importance of environmental stewardship and teamwork. The activity includes a short orientation on proper tree planting techniques, followed by the actual planting session and post-care instructions.\r\n\r\nBy planting trees today, we are investing in a greener and more sustainable future for the next generations. 🌱', '2025-10-21 05:00:00', 'banner_74f533dd6796.png', 10, 50, '2025-10-20 13:28:28'),
(37, 'Clean Up Drive', 'Let’s keep our surroundings clean and beautiful! 💪\r\nThe Clean-Up Drive aims to inspire everyone to take part in preserving our environment by collecting litter and properly disposing of waste in public areas. Together, we can make a big difference in keeping our community pollution-free and welcoming for all.', '2025-10-23 08:11:00', 'banner_f44e74b0cde6.png', 20, NULL, '2025-10-20 15:12:18'),
(38, 'Environmental Awareness Seminar', 'Learn. Act. Inspire. 🌎\r\nJoin our Environmental Awareness Seminar and discover how small actions can create a big change for the planet. Speakers will share insights on waste management, climate change, and sustainable living — empowering everyone to be an eco-warrior!', '2025-10-25 08:00:00', 'banner_296f8a6a8d88.png', 50, NULL, '2025-10-20 15:14:32');

-- --------------------------------------------------------

--
-- Table structure for table `event_commits`
--

CREATE TABLE `event_commits` (
  `id` int(30) NOT NULL,
  `event_id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_commits`
--

INSERT INTO `event_commits` (`id`, `event_id`, `user_id`) VALUES
(9, 36, 7),
(10, 36, 1),
(11, 36, 8),
(12, 38, 8),
(13, 37, 8);

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=cancelled, 1=registered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_comments`
--

CREATE TABLE `forum_comments` (
  `id` int(30) NOT NULL,
  `topic_id` int(30) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_comments`
--

INSERT INTO `forum_comments` (`id`, `topic_id`, `comment`, `user_id`, `date_created`) VALUES
(48, 14, 'Ubana ko partski', 7, '2025-10-20 01:51:46'),
(49, 15, 'kay naay Aircon', 7, '2025-10-20 01:57:19'),
(50, 15, 'syempre gwapo ang mga titser', 8, '2025-10-20 02:12:10');

-- --------------------------------------------------------

--
-- Table structure for table `forum_topics`
--

CREATE TABLE `forum_topics` (
  `id` int(30) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_topics`
--

INSERT INTO `forum_topics` (`id`, `title`, `description`, `user_id`, `date_created`) VALUES
(14, 'Job hunting On IT PARK', 'Kinsay wala pay mga trabaho diha tana mag kuyog ta ninyu pangapplyyyy.....', 7, '2025-10-20 01:44:26'),
(15, 'Bakit ka nag IT', 'nganu man daw?', 7, '2025-10-20 01:57:00');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(30) NOT NULL,
  `image_path` varchar(255) NOT NULL DEFAULT '',
  `about` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image_path`, `about`, `created`) VALUES
(24, 'gallery_7cd6893f8edd.jpg', 'SCC days', '2025-10-20 01:23:01'),
(25, 'gallery_e9cd358f8fb0.jpg', 'SCC Days', '2025-10-20 01:23:11'),
(26, 'gallery_c230ec7df0dd.jpg', 'Acquintance', '2025-10-20 01:23:22'),
(27, 'gallery_5617187ff3b6.jpg', 'Acquintance', '2025-10-20 01:23:30'),
(28, 'gallery_de7094315306.jpg', 'IT Congress', '2025-10-20 01:23:42');

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(30) NOT NULL,
  `job_id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `cover_letter` text NOT NULL,
  `resume_file` text DEFAULT NULL COMMENT 'Uploaded resume/CV file path',
  `status` enum('pending','reviewed','accepted','rejected') DEFAULT 'pending',
  `applied_at` datetime NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL COMMENT 'Admin notes about the application'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','danger') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `success_stories`
--

CREATE TABLE `success_stories` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=pending,1=approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `success_stories`
--

INSERT INTO `success_stories` (`id`, `user_id`, `title`, `content`, `image`, `created`, `status`) VALUES
(13, 7, 'The Dream Chaser', 'After years of studying and sleepless nights, she finally walked across the stage to receive her diploma. What started as a dream turned into a journey filled with challenges, doubts, and sacrifices. But she never gave up. Today, she’s not only a graduate — she’s proof that persistence always pays off.', 'uploads/success-stories/story_1760892877_7.png', '2025-10-20 00:54:37', 1),
(14, 7, 'The Late Bloomer', 'He didn’t have the best grades at first, and many thought he wouldn’t make it. But he kept believing in himself, worked harder each day, and turned failure into motivation. Now, he stands proudly among the achievers — showing everyone that it’s not how you start, but how you finish that defines success.', 'uploads/success-stories/story_1760892891_7.png', '2025-10-20 00:54:51', 1),
(15, 7, 'The Overcomer', 'Life tested her with struggles — financial problems, personal loss, and self-doubt — but none of it stopped her. Through faith, determination, and the support of loved ones, she conquered every obstacle. Her story reminds us that true success shines brightest when born from resilience.', 'uploads/success-stories/story_1760892905_7.png', '2025-10-20 00:55:05', 1),
(16, 7, 'The Innovator', 'He saw every project not just as a requirement, but as an opportunity to learn and create something meaningful. His curiosity led him to develop ideas that inspired others and made a difference in his field. Today, he continues to grow, proving that education is just the beginning of endless innovation.', 'uploads/success-stories/story_1760892921_7.png', '2025-10-20 00:55:21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text DEFAULT NULL,
  `about_content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `quote` text NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `graduation_year` int(4) NOT NULL,
  `course` varchar(255) NOT NULL,
  `graduation_photo` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=pending,1=approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `user_id`, `quote`, `author_name`, `graduation_year`, `course`, `graduation_photo`, `created`, `status`) VALUES
(2, 7, '\"The future belongs to those who believe in the beauty of their dreams.\" – Eleanor Roosevelt', 'Kaye Lacida', 2024, 'BS Education', 'uploads/testimonials/testimonial_1760885173_68f4f9b5d3a46.png', '2025-10-19 22:46:13', 1),
(3, 7, '\"Don’t just follow the path. Go where there is no path and leave a trail.\" – Ralph Waldo Emerson', 'Chaw Omaña', 2024, 'BS Education', 'uploads/testimonials/testimonial_1760885230_68f4f9ee277ba.png', '2025-10-19 22:47:10', 1),
(4, 7, '\"Your education is a dress rehearsal for a life that is yours to lead.\" – Nora Ephron', 'Venuz Waskin', 2024, 'BS Education', 'uploads/testimonials/testimonial_1760885289_68f4fa29318a9.png', '2025-10-19 22:48:09', 1),
(5, 7, '\"It always seems impossible until it’s done.\" – Nelson Mandela', 'Aida Sacil', 2024, 'BS Education', 'uploads/testimonials/testimonial_1760885320_68f4fa4858aec.png', '2025-10-19 22:48:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1=Admin,2=Officer,3=Alumnus',
  `auto_generated_pass` varchar(255) DEFAULT '',
  `alumnus_id` int(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`, `auto_generated_pass`, `alumnus_id`) VALUES
(1, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 1, '', NULL),
(7, 'Joshua  Espanillo', 'wangska', 'bbec9b9dde115310d162c4bb1e1bb374', 3, '', 22),
(8, 'johnrey  cambaya', 'johnrey2001', 'bbec9b9dde115310d162c4bb1e1bb374', 3, '', 24);

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `action_type` enum('login','logout','create','update','delete','view') NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `username`, `action`, `action_type`, `module`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 16:51:21'),
(2, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 16:54:47'),
(3, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 16:54:54'),
(4, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 16:57:29'),
(5, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 16:57:36'),
(6, 1, 'admin', 'Updated Alumni', 'update', 'Alumni', 'Item: John Rey Pangan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:16:42'),
(7, 1, 'admin', 'Updated Announcement', 'update', 'Announcement', 'Item: Testing for Announcement', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:18:03'),
(8, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:18:18'),
(9, 1, 'admin', 'Created new Course', 'create', 'Course', 'Item: BS Tourism', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:19:02'),
(10, 1, 'admin', 'Deleted Event', 'delete', 'Event', 'Item: Graduation', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:21:13'),
(11, 1, 'admin', 'Deleted Announcement', 'delete', 'Announcement', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:21:24'),
(12, 1, 'admin', 'Created new Job Posting', 'create', 'Job Posting', 'Item: IT tech', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:22:00'),
(13, 1, 'admin', 'Updated Event', 'update', 'Event', 'Item: Alumni Awards Night', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:23:19'),
(14, 1, 'admin', 'Updated Event', 'update', 'Event', 'Item: Alumni Homecoming', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:23:32'),
(15, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: f49a812f-f2d1-404a-b729-62551a578837.jpg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:25:33'),
(16, 1, 'admin', 'Deleted Gallery', 'delete', 'Gallery', 'Item: gallery_d226e0c54ce3.jpg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:25:44'),
(17, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: scc.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:26:05'),
(18, 1, 'admin', 'Deleted Gallery', 'delete', 'Gallery', 'Item: gallery_73f1ea980de2.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:26:29'),
(19, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: Joshua Gwapo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:27:38'),
(20, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:27:58'),
(21, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: test2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:28:11'),
(22, 1, 'admin', 'Created new Event', 'create', 'Event', 'Item: graduate', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:28:47'),
(23, 1, 'admin', 'Created new Event', 'create', 'Event', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:29:05'),
(24, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:50:43'),
(25, NULL, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:51:12'),
(26, NULL, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:51:15'),
(27, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:53:38'),
(28, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:58:28'),
(29, NULL, 'test', 'User logged in', 'login', 'Authentication', 'User: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:59:34'),
(30, NULL, 'test', 'User logged out', 'logout', 'Authentication', 'User: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:59:37'),
(31, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 17:59:50'),
(32, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 18:09:55'),
(33, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 18:10:02'),
(34, 1, 'admin', 'Deleted Event', 'delete', 'Event', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 18:12:50'),
(35, 1, 'admin', 'Created new Forum Topic', 'create', 'Forum Topic', 'Item: Test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 18:24:00'),
(36, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 18:26:59'),
(37, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 03:53:59'),
(38, 1, 'admin', 'Updated Announcement', 'update', 'Announcement', 'Item: MAGSABOT ta', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 03:54:31'),
(39, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 03:54:48'),
(40, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 03:55:01'),
(41, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: SCC', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 03:59:58'),
(42, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 04:00:03'),
(43, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 04:07:49'),
(44, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: tana', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 04:08:23'),
(45, 1, 'admin', 'Updated Announcement', 'update', 'Announcement', 'Item: tana', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 04:08:49'),
(46, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 08:54:08'),
(47, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: f49a812f-f2d1-404a-b729-62551a578837.jpg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 08:59:32'),
(48, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:04:29'),
(49, NULL, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:09:30'),
(50, NULL, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:09:54'),
(51, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:10:18'),
(52, 1, 'admin', 'Created new Event', 'create', 'Event', 'Item: ungo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:11:47'),
(53, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:12:15'),
(54, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:17:02'),
(55, 1, 'admin', 'Updated Alumni', 'update', 'Alumni', 'Item: test test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:22:39'),
(56, 1, 'admin', 'Updated Alumni', 'update', 'Alumni', 'Item: test test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:22:50'),
(57, 1, 'admin', 'Created new Event', 'create', 'Event', 'Item: hjh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:33:37'),
(58, 1, 'admin', 'Deleted Event', 'delete', 'Event', 'Item: hjh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:34:06'),
(59, 1, 'admin', 'Deleted Event', 'delete', 'Event', 'Item: ungo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:34:13'),
(60, 1, 'admin', 'Deleted Alumni', 'delete', 'Alumni', 'Item: test test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 09:48:18'),
(61, 1, 'admin', 'Updated Alumni', 'update', 'Alumni', 'Item: John Rey Pangan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:29:59'),
(62, 1, 'admin', 'Updated Alumni', 'update', 'Alumni', 'Item: John Rey Pangan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:35:37'),
(63, 1, 'admin', 'Updated Alumni', 'update', 'Alumni', 'Item: John Rey Pangan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:38:25'),
(64, 1, 'admin', 'Deleted Event', 'delete', 'Event', 'Item: Alumni Awards Night', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:38:37'),
(65, 1, 'admin', 'Updated Event', 'update', 'Event', 'Item: graduate', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:41:09'),
(66, 1, 'admin', 'Deleted Event', 'delete', 'Event', 'Item: graduate', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:41:16'),
(67, 1, 'admin', 'Deleted Gallery', 'delete', 'Gallery', 'Item: gallery_73ad7b3c6462.jpg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:42:54'),
(68, 1, 'admin', 'Updated Announcement', 'update', 'Announcement', 'Item: MAGSABOT ta', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:45:28'),
(69, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: 341601636_210739885005819_1989066404318602535_n.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:48:26'),
(70, 1, 'admin', 'Created new Event', 'create', 'Event', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:49:28'),
(71, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: scc3.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:49:55'),
(72, 1, 'admin', 'Deleted Gallery', 'delete', 'Gallery', 'Item: gallery_f6a2e3af5905.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 17:58:36'),
(73, 1, 'admin', 'Updated Course', 'update', 'Course', 'Item: BS Criminology', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:02:49'),
(74, 1, 'admin', 'Updated Course', 'update', 'Course', 'Item: BS Education', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:02:59'),
(75, 1, 'admin', 'Updated Course', 'update', 'Course', 'Item: BS Hospitality Management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:03:08'),
(76, 1, 'admin', 'Updated Course', 'update', 'Course', 'Item: BS Hospitality Management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:03:16'),
(77, 1, 'admin', 'Updated Course', 'update', 'Course', 'Item: BS Information Technology', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:03:24'),
(78, 1, 'admin', 'Updated Course', 'update', 'Course', 'Item: BS Nursing', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:03:45'),
(79, 1, 'admin', 'Updated Course', 'update', 'Course', 'Item: BS Nursing', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:03:52'),
(80, 1, 'admin', 'Updated Course', 'update', 'Course', 'Item: BS Tourism', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:03:59'),
(81, 1, 'admin', 'Updated Course', 'update', 'Course', 'Item: BS Business Administration', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:04:39'),
(82, 1, 'admin', 'Updated Alumni', 'update', 'Alumni', 'Item: John Rey Pangan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:11:41'),
(83, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:12:12'),
(84, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:23:11'),
(85, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:27:14'),
(86, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:29:28'),
(87, 1, 'admin', 'Deleted Gallery', 'delete', 'Gallery', 'Item: gallery_76f3d105e072.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:35:32'),
(88, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:35:46'),
(89, NULL, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:36:02'),
(90, NULL, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:36:15'),
(91, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:36:26'),
(92, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:36:55'),
(93, NULL, 'testing2', 'User logged in', 'login', 'Authentication', 'User: testing2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-18 18:38:11'),
(94, NULL, 'testing2', 'User logged out', 'logout', 'Authentication', 'User: testing2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 05:33:51'),
(95, NULL, 'testing2', 'User logged in', 'login', 'Authentication', 'User: testing2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 05:34:30'),
(96, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 05:58:14'),
(97, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:24:05'),
(98, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:24:14'),
(99, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:24:18'),
(100, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:24:23'),
(101, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:30:00'),
(102, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:30:03'),
(103, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:30:07'),
(104, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:30:09'),
(105, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:30:11'),
(106, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:30:15'),
(107, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:30:16'),
(108, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:30:18'),
(109, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:30:20'),
(110, NULL, 'testing2', 'Created new testing2', 'create', 'testing2', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:33:14'),
(111, NULL, 'testing2', 'User logged out', 'logout', 'Authentication', 'User: testing2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:49:42'),
(112, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 06:55:46'),
(113, 7, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 07:28:24'),
(114, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 07:28:41'),
(115, 7, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 07:31:54'),
(116, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 07:32:04'),
(117, 7, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 07:34:22'),
(118, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 07:34:29'),
(119, 7, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 07:37:20'),
(120, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 07:38:59'),
(121, 7, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 07:39:50'),
(122, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 07:41:38'),
(123, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 08:20:34'),
(124, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 08:20:38'),
(125, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 08:20:39'),
(126, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 08:20:41'),
(127, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 09:54:43'),
(128, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 09:54:52'),
(129, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 10:23:14'),
(130, 1, 'admin', 'Deleted Announcement', 'delete', 'Announcement', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 10:23:52'),
(131, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 10:24:14'),
(132, 1, 'admin', 'Deleted Announcement', 'delete', 'Announcement', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 10:24:17'),
(133, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 11:08:26'),
(134, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 11:08:29'),
(135, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 11:08:30'),
(136, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 11:08:32'),
(137, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 13:53:46'),
(138, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 13:54:05'),
(139, 7, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 14:11:18'),
(140, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 14:18:21'),
(141, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 14:20:55'),
(142, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 14:38:03'),
(143, 7, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 15:11:02'),
(144, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 15:11:55'),
(145, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: scc3.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 15:16:14'),
(146, 1, 'admin', 'Deleted Gallery', 'delete', 'Gallery', 'Item: gallery_d0b6dfd50acc.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 15:16:29'),
(147, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 15:17:58'),
(148, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 15:18:05'),
(149, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 15:24:46'),
(150, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 15:25:48'),
(151, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: Screenshot 2025-10-19 233715.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 16:46:12'),
(152, 1, 'admin', 'Deleted Gallery', 'delete', 'Gallery', 'Item: gallery_2a786439eef4.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 16:49:13'),
(153, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: ssss.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 16:49:20'),
(154, 1, 'admin', 'Deleted Gallery', 'delete', 'Gallery', 'Item: gallery_efffae1e37ac.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 16:49:27'),
(155, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: ssss.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 16:49:34'),
(156, 1, 'admin', 'Created new Event', 'create', 'Event', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 16:50:55'),
(157, 1, 'admin', 'Deleted Gallery', 'delete', 'Gallery', 'Item: gallery_64ddd89577b6.png', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:20:02'),
(158, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: 476621296_597419956503604_3282956720028780702_n.jpg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:23:01'),
(159, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: 476812605_597419936503606_6416594849800342684_n.jpg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:23:11'),
(160, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: 476781883_597416036503996_5658251955859757140_n.jpg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:23:22'),
(161, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: 469095112_550779324501001_1904901893320015482_n.jpg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:23:30'),
(162, 1, 'admin', 'Created new Gallery', 'create', 'Gallery', 'Item: 490572559_643935458518720_7411181996478252180_n.jpg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:23:42'),
(163, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:25:49'),
(164, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:25:52'),
(165, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:26:02'),
(166, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:28:55'),
(167, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:29:00'),
(168, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:29:55'),
(169, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:32:42'),
(170, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:36:29'),
(171, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:37:08'),
(172, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:38:57'),
(173, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:39:18'),
(174, 1, 'admin', 'Deleted Forum Topic', 'delete', 'Forum Topic', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:39:34'),
(175, 1, 'admin', 'Deleted Forum Topic', 'delete', 'Forum Topic', 'Item: test2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:39:36'),
(176, 1, 'admin', 'Deleted Forum Topic', 'delete', 'Forum Topic', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:39:38'),
(177, 1, 'admin', 'Deleted Forum Topic', 'delete', 'Forum Topic', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:39:41'),
(178, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:41:50'),
(179, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:41:58'),
(180, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:42:00'),
(181, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:42:01'),
(182, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:46:09'),
(183, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:46:35'),
(184, 1, 'admin', 'Deleted Forum Topic', 'delete', 'Forum Topic', 'Item: Job Hunting on IT park', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:47:40'),
(185, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:48:01'),
(186, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:51:46'),
(187, 7, 'wangska', 'Created new wangska', 'create', 'wangska', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-19 17:57:19'),
(188, 8, 'johnrey2001', 'User logged in', 'login', 'Authentication', 'User: johnrey2001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 18:11:45'),
(189, 8, 'johnrey2001', 'Created new johnrey2001', 'create', 'johnrey2001', 'Item: Forum Comment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 18:12:10'),
(190, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:57:43'),
(191, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:07:06'),
(192, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:09:39'),
(193, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:25:07'),
(194, 1, 'admin', 'Deleted Event', 'delete', 'Event', 'Item: test', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:25:12'),
(195, 1, 'admin', 'Created new Event', 'create', 'Event', 'Item: Tree Planting Activity', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:28:28'),
(196, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 06:07:14'),
(197, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 06:07:20'),
(198, 7, 'wangska', 'User logged out', 'logout', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 06:08:18'),
(199, 1, 'admin', 'User logged in', 'login', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 06:08:23'),
(200, 1, 'admin', 'User logged out', 'logout', 'Authentication', 'User: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 06:12:38'),
(201, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 06:12:46'),
(202, 8, 'johnrey2001', 'User logged in', 'login', 'Authentication', 'User: johnrey2001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-20 06:22:08'),
(203, 8, 'johnrey2001', 'User logged in', 'login', 'Authentication', 'User: johnrey2001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-20 06:26:07'),
(204, 7, 'wangska', 'User logged in', 'login', 'Authentication', 'User: wangska', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 06:29:55'),
(205, 1, 'admin', 'Updated Event', 'update', 'Event', 'Item: Tree Planting Activity', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 06:45:38'),
(206, 1, 'admin', 'Updated Event', 'update', 'Event', 'Item: Tree Planting Activity', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 06:48:23'),
(207, 1, 'admin', 'Created new Event', 'create', 'Event', 'Item: Clean Up Drive', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 07:12:18'),
(208, 1, 'admin', 'Created new Event', 'create', 'Event', 'Item: Environmental Awareness Seminar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 07:14:32'),
(209, 1, 'admin', 'Updated Event', 'update', 'Event', 'Item: Tree Planting Activity', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 07:24:40'),
(210, 1, 'admin', 'Created new Job Posting', 'create', 'Job Posting', 'Item: IT tech', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 07:45:52'),
(211, 7, 'wangska', 'Created new Job Application', 'create', 'Job Application', 'Item: Applied for job ID: 5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:04:12'),
(212, 8, 'johnrey2001', 'Created new Job Application', 'create', 'Job Application', 'Item: Applied for job ID: 5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-20 08:18:28'),
(213, 1, 'admin', 'Updated Job Application', 'update', 'Job Application', 'Item: Status updated to: rejected', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:29:33'),
(214, 1, 'admin', 'Updated Job Application', 'update', 'Job Application', 'Item: Status updated to: accepted', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:29:43'),
(215, 1, 'admin', 'Updated Job Application', 'update', 'Job Application', 'Item: Status updated to: rejected', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:29:51'),
(216, 1, 'admin', 'Updated Job Application', 'update', 'Job Application', 'Item: Status updated to: reviewed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:29:56'),
(217, 1, 'admin', 'Updated Job Application', 'update', 'Job Application', 'Item: Status updated to: rejected', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:30:07');
INSERT INTO `user_logs` (`id`, `user_id`, `username`, `action`, `action_type`, `module`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(218, 1, 'admin', 'Updated Job Application', 'update', 'Job Application', 'Item: Status updated to: reviewed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:36:27'),
(219, 1, 'admin', 'Updated Job Application', 'update', 'Job Application', 'Item: Status updated to: accepted', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:45:50'),
(220, 8, 'johnrey2001', 'Created new Job Application', 'create', 'Job Application', 'Item: Applied for job ID: 5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-20 08:46:24'),
(221, 1, 'admin', 'Updated Job Application', 'update', 'Job Application', 'Item: Status updated to: accepted', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:46:34'),
(222, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: General Alumni Gathering Announcement', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:56:29'),
(223, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: Career Opportunity Announcement', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 08:57:59'),
(224, 1, 'admin', 'Created new Job Posting', 'create', 'Job Posting', 'Item: Administrative Assistant', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 09:27:10'),
(225, 1, 'admin', 'Created new Job Posting', 'create', 'Job Posting', 'Item: Accountant', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 09:28:03'),
(226, 1, 'admin', 'Created new Announcement', 'create', 'Announcement', 'Item: Alumni Spotlight Announcement', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 09:29:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumni_documents`
--
ALTER TABLE `alumni_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_documents_alumnus` (`alumnus_id`),
  ADD KEY `fk_documents_verifier` (`verified_by`);

--
-- Indexes for table `alumnus_bio`
--
ALTER TABLE `alumnus_bio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_email` (`email`),
  ADD KEY `idx_course_id` (`course_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `careers`
--
ALTER TABLE `careers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_career_user` (`user_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_commits`
--
ALTER TABLE `event_commits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event` (`event_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_participation` (`event_id`,`user_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fc_topic` (`topic_id`),
  ADD KEY `idx_fc_user` (`user_id`);

--
-- Indexes for table `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ft_user` (`user_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_read` (`is_read`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `success_stories`
--
ALTER TABLE `success_stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ss_user` (`user_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ts_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_username` (`username`),
  ADD KEY `idx_alumnus_id` (`alumnus_id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `action_type` (`action_type`),
  ADD KEY `created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumni_documents`
--
ALTER TABLE `alumni_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `alumnus_bio`
--
ALTER TABLE `alumnus_bio`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `careers`
--
ALTER TABLE `careers`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `event_commits`
--
ALTER TABLE `event_commits`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `success_stories`
--
ALTER TABLE `success_stories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alumni_documents`
--
ALTER TABLE `alumni_documents`
  ADD CONSTRAINT `fk_documents_alumnus` FOREIGN KEY (`alumnus_id`) REFERENCES `alumnus_bio` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_documents_verifier` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `alumnus_bio`
--
ALTER TABLE `alumnus_bio`
  ADD CONSTRAINT `fk_alumni_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `careers`
--
ALTER TABLE `careers`
  ADD CONSTRAINT `fk_career_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_commits`
--
ALTER TABLE `event_commits`
  ADD CONSTRAINT `fk_commit_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_commit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_event_fk` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_participants_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD CONSTRAINT `fk_comment_topic` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD CONSTRAINT `fk_topic_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `success_stories`
--
ALTER TABLE `success_stories`
  ADD CONSTRAINT `fk_ss_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `fk_ts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_alumnus` FOREIGN KEY (`alumnus_id`) REFERENCES `alumnus_bio` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_logs_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
