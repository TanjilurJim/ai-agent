-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 02, 2025 at 02:32 AM
-- Server version: 10.6.21-MariaDB-cll-lve
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itsomeza_ai_rafusoft`
--

-- --------------------------------------------------------

--
-- Table structure for table `bots`
--

CREATE TABLE `bots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `botAvatar` varchar(255) DEFAULT NULL,
  `botName` varchar(255) NOT NULL,
  `botColor` varchar(255) NOT NULL,
  `botDescription` text NOT NULL,
  `botToken` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bots`
--

INSERT INTO `bots` (`id`, `botAvatar`, `botName`, `botColor`, `botDescription`, `botToken`, `user_id`, `created_at`, `updated_at`) VALUES
(6, 'uploads/1737549267_message-3d-application-icon-png.webp', 'Smart Bot', '#23B53D', 'Rafusoft Smart Bot is an innovative, AI-powered solution designed to simplify tasks, enhance productivity,\r\n and provide intelligent automation. Developed by Rafusoft, the bot offers cutting-edge features such as natural language processing, real-time problem-solving, and seamless integration with various platforms.', 'XiEErW4upbO4CPXDfBuY', '3', '2025-01-18 04:21:32', '2025-01-22 06:47:44');

-- --------------------------------------------------------

--
-- Table structure for table `bot_contents`
--

CREATE TABLE `bot_contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bot_contents`
--

INSERT INTO `bot_contents` (`id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(1, '3', 'You are a kind and helpful customer service member IP Telephone services Provider. If the user asks how to get IP Number, refer them to our website at https://rafusoft.com/dir/IP-Telephony-Free-Number-in-Bangladesh  , if the user asks how to recharge , refer them our website Portal & Recharge Link , if the user says how to register refer our registration link Registration Form Link https://forms.gle/j5GyJjmrtWoYbrtd7.\r\n\r\n\r\n\r\nIf the user asks anything about Recharge, Register IP Number (09647XXXXXX), Installation One Time Charge, Incoming (All), IP Phone to IP Phone, IP Phone to Cell Phone/GSM, IP Phone to BTCL, Concurrent Calls Outgoing, Concurrent Receiving Calls Incoming, Minimum Recharge Amount, Internet Connection, input in the following page suggest options:', '2025-02-01 05:29:21', '2025-02-01 05:30:38');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_01_16_143620_create_bots_table', 2),
(6, '2025_01_18_110201_create_trains_table', 3),
(7, '2025_01_23_120619_create_widgets_table', 4),
(8, '2025_02_01_111323_create_bot_contents_table', 5),
(9, '2025_02_01_130557_create_pages_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'IP Telephone Page Details', 'Register IP Number - 09647XXXXXX\r\nInstallation One Time Charge - Free\r\nRecharge - Pay as you go\r\nIncoming (All) - Free \r\nIP Phone to IP Phone - Free\r\nIP Phone to Cell Phone/GSM - 0.37 paisa per minute with a 1-second pulse\r\nIP Phone to BTCL - 0.37 paisa per minute with a 1-second pulse\r\nConcurrent Calls Outgoing - 4 Channels\r\nConcurrent Receiving Calls Incoming - 1 Channel\r\nMinimum Recharge Amount - 20 Taka\r\nInternet Connection - Any wifi or Robi Internet\r\ngoogle form link - https://forms.gle/j5GyJjmrtWoYbrtd7\"', '3', '2025-02-01 07:15:45', '2025-02-01 07:15:45');

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `api_key` varchar(20) NOT NULL,
  `token` int(11) NOT NULL DEFAULT 10,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `status`, `api_key`, `token`, `created_at`, `updated_at`) VALUES
(39, 3, 'active', 'XiEErW4upbO4CPXDfBuY', 10, '2025-01-30 05:00:11', '2025-01-30 05:00:27'),
(40, 5, 'Pending', 'E5J0V1seQZ9riTMI29pg', 10, '2025-06-02 09:44:05', '2025-06-02 09:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `trains`
--

CREATE TABLE `trains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` longtext NOT NULL,
  `answer` longtext NOT NULL,
  `description` longtext NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trains`
--

INSERT INTO `trains` (`id`, `question`, `answer`, `description`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'What is FTP Forest?', 'File Transfer and Sharing Website Developed by Rafusoft', 'The File Transfer and Sharing Website developed by Rafusoft is a seamless platform designed for fast, secure, and efficient file sharing. This user-friendly solution allows individuals and businesses to easily upload, transfer, and share files of various sizes. With a focus on security and reliability, the platform ensures encrypted file transfers, ensuring user data privacy.', '1', '2025-01-18 05:27:13', '2025-01-18 08:18:39'),
(4, 'Who made you?', 'MD Shamim Islam - Programmer of Rafusoft', 'MD Shamim Islam - Programmer of Rafusoft', '1', '2025-01-18 09:11:24', '2025-01-18 09:11:24'),
(5, 'who is mishuk', 'Mishuk is shamim\'s younger brother', 'Mishuk is shamim\'s younger brother', '1', '2025-01-19 02:18:39', '2025-01-19 02:18:39'),
(6, 'What is FTP Forest?', 'File Transfer and Sharing Website Developed by Rafusoft', 'Our File Transfer and Sharing platform, developed by Rafusoft, provides a fast, secure, and user-friendly way to transfer and share files with anyone, anywhere. Whether you\'re sending large documents, images, videos, or other types of files, our platform ensures that your files are delivered swiftly and safely. With a clean and intuitive interface, users can easily upload, share, and download files with just a few clicks. We prioritize security, offering encrypted transfers to protect your sensitive data. Rafusoft’s file sharing solution is designed to meet the needs of individuals, businesses, and organizations, offering reliable performance, ease of use, and seamless file management.', '3', '2025-01-20 08:01:15', '2025-01-30 05:58:19'),
(7, 'What is your name ?', 'I am smart  bot Develop by Rafusoft', 'I am smart  bot Develop by Rafusoft', '3', '2025-01-20 08:07:14', '2025-01-20 08:07:14'),
(8, 'What services do you provide?', 'I am a virtual assistant capable of providing:  E-commerce Bot Support, Service Provider Bot Support,  Personal Assistant Bot Support', 'I am a virtual assistant capable of providing:  E-commerce Bot Support, Service Provider Bot Support,  Personal Assistant Bot Support', '3', '2025-01-20 08:14:53', '2025-01-20 08:14:53'),
(11, 'how to have ip telephony or ip number for call', 'ip telephony from rafusoft .. you can have a form which you have to fill up', 'Register IP Number (09647XXXXXX)	 Free\r\n Installation One Time Charge	 Free\r\n Recharge	 Pay as you go\r\nIncoming (All)	 Free\r\nIP Phone to IP Phone	 Free\r\nIP Phone to Cell Phone/GSM	 0.37 paisa per minute with a 1-second pulse\r\nIP Phone to BTCL	 0.37 paisa per minute with a 1-second pulse\r\nConcurrent Calls Outgoing	4 Channels\r\nConcurrent Receiving Calls Incomming	1 Channel\r\nMinimum Recharge Amount	20 Taka\r\nInternet Connection	Any wifi or Robi Internet\r\nPortal & Recharge Link \r\n⇒  https://webvoice.net\r\nRegistration Form Link\r\n⇒  https://forms.gle/j5GyJjmrtWoYbrtd7\r\n \r\n\r\nRafusoft IP Telephony Number Free\r\nFree credits by reviewing us on Google! \r\nhttps://g.page/r/CV46CyoD9xEVEB0/review\r\n♦\r\nNOTE: No monthly fees. NO EXTRA HIDDEN CHARGES\r\n \r\n\r\nRequired Documents For Free IP Number\r\n \r\n\r\n\r\nCopy of NID Card\r\nPlease provide clear copies of both sides of your national identity card.\r\n\r\n \r\n\r\n\r\nPassport Size Picture\r\nSubmit a recent passport-size photograph. Your appearance should be easily recognizable.\r\n\r\n\r\nTrade License Copy\r\nA copy of your trade license is optional, but please provide one if available.\r\n\r\n \r\n\r\nIf you require assistance in choosing the ideal solution for your business, please reach out to us at 01744333888 between 9:00 AM and 6:30 PM. Alternatively, you can initiate a live chat with us, as we are accessible around the clock via our live chat support.\r\n\r\n \r\n\r\n \r\n\r\nকিভাবে আইপি টেলিফোন নাম্বার নিতে হবে?\r\n** জাতীয় পরিচয় পত্রের উভয় পাশের ছবি এবং ফোন নাম্বার দিতে হবে।\r\n** ফ্রিতে নাম্বার দেয়া হবে এবং কোন মাসিক চার্জ নেই।\r\n** আপনার ফোন নম্বর মিলিয়ে নাম্বার দেয়া হবে।\r\n** অনলাইন চ্যাটের মাধ্যমে সাহায্য করা হবে।\r\n** রিভিও দিলে ফ্রি টকটাইম দেয়া হবে।\r\n\r\n \r\n\r\nDownload Dialer\r\n \r\n\r\nRafusoft Dialer Download	Zoiper Lite voip soft phone	Desktop voip soft phone\r\n \r\n\r\nVIRTUAL RECEPTIONIST\r\nNo need for a full-time receptionist anymore. Our Virtual Receptionist feature integrates a consistent automated voice for your brand, ensuring you never miss a lead.\r\n\r\nCALL FORWARDING\r\nEasily manage business calls with this handy feature. If your extension is busy or unavailable, you can seamlessly transfer or forward calls to your personal mobile or home phones.\r\n\r\nCALL ROUTING\r\nBoost profits with intelligent call routing. Customize routing patterns based on various factors to ensure every call reaches the right destination.\r\n\r\nCALL CONFERENCING\r\nExperience fast, flexible, and secure call conferencing to connect team members on the same voice call, anywhere, anytime. Internal calls are completely free of charge.\r\n\r\nCALL TRANSFERRING\r\nDeliver the best solutions to your callers with call transfer features. Effortlessly transfer ongoing calls to other departments or agents for enhanced client satisfaction.\r\n\r\nVOICE MESSAGES\r\nAfter business hours, allow callers to leave voice messages. Access these messages through your dashboard or have them sent to your email address.\r\n\r\nMULTIPLE IVR\r\nUtilize multiple IVR (Interactive Voice Response) options for your business, including various greetings, off-day information, special day announcements, and special offer recordings.\r\n\r\nCALL RECORDING\r\nNever miss critical information with automatic call recording. All calls are securely stored on a hard disk for later reference.\r\n\r\nMULTIPLE CALL ATTENDING\r\nAttend to multiple calls simultaneously with up to five concurrent call channels. Easily increase your concurrent call capacity to manage higher call volumes.\r\n\r\nMUSIC ON HOLD\r\nEnhance your corporate image with customized on-hold music. Use it to promote your next campaign or reinforce your brand\'s identity during wait times.\r\n\r\nCALL MONITORING\r\nEffortlessly monitor your outgoing and incoming calls through your dashboard, ensuring you can review your call history anytime, anywhere.\r\n\r\nUNLIMITED EXTENSIONS\r\nAssign unlimited extensions to strengthen your internal communication and allow more team members to handle business calls, contributing to business growth.\r\n\r\n \r\n\r\nFree call to Bangladesh & ip phone number provider in bangladesh\r\n\r\nIf you find yourself in need of information or assistance regarding your Bangladesh mobile number, the dedicated customer care number is your direct line to reliable support. Whether you have questions about the BD phone code, wish to explore options for making free calls to Bangladesh, or are interested in understanding IP prices for telecommunication services, the customer care team is there to provide comprehensive guidance. They can assist with inquiries related to telephone services in Bangladesh, ensuring you have the necessary details about your mobile number and access to affordable IP options. Feel free to reach out to the customer care number for prompt and helpful assistance tailored to your specific needs.\r\n\r\nRafusoft is recognized as the top Business Telephone System Provider in Bangladesh. Obtain a professional IP PBX Telephone System for your business effortlessly, without any upfront costs or equipment purchases. Benefit from a flexible, multi-location communication system where extensions can be easily routed to any smartphone, PC, or tablet.', '4', '2025-01-29 09:43:05', '2025-01-29 09:43:05'),
(12, 'about ip number or ip telephony', 'IP telephony', 'Features & Price Of IP Telephony SIP System PBX\r\n\r\n \r\n\r\n \r\nRegister IP Number (09647XXXXXX) 	\r\nFree\r\nInstallation One Time Charge 	\r\nFree\r\nRecharge 	\r\nPay as you go\r\nIncoming (All) 	\r\nFree\r\nIP Phone to IP Phone 	\r\nFree\r\nIP Phone to Cell Phone/GSM 	\r\n0.37 paisa per minute with a 1-second pulse\r\nIP Phone to BTCL 	\r\n0.37 paisa per minute with a 1-second pulse\r\nConcurrent Calls Outgoing 	4 Channels\r\nConcurrent Receiving Calls Incomming 	1 Channel\r\nMinimum Recharge Amount 	20 Taka\r\nInternet Connection 	Any wifi or Robi Internet\r\nPortal & Recharge Link \r\n⇒ \r\nhttps://webvoice.net\r\nRegistration Form Link\r\n⇒ \r\nhttps://forms.gle/j5GyJjmrtWoYbrtd7\r\n\r\n \r\n\r\nRafusoft IP Telephony Number Free\r\n\r\nFree credits by reviewing us on Google!\r\n\r\nhttps://g.page/r/CV46CyoD9xEVEB0/review\r\n\r\n♦\r\nNOTE: No monthly fees. NO EXTRA HIDDEN CHARGES\r\n\r\n \r\nRequired Documents For Free IP Number\r\n\r\n \r\n\r\nCopy of NID Card\r\n\r\nPlease provide clear copies of both sides of your national identity card.\r\n\r\n \r\n	\r\n\r\nPassport Size Picture\r\n\r\nSubmit a recent passport-size photograph. Your appearance should be easily recognizable.\r\n	\r\n\r\nTrade License Copy\r\n\r\nA copy of your trade license is optional, but please provide one if available.\r\n\r\n \r\n\r\nIf you require assistance in choosing the ideal solution for your business, please reach out to us at 01744333888 between 9:00 AM and 6:30 PM. Alternatively, you can initiate a live chat with us, as we are accessible around the clock via our live chat support.\r\n\r\n \r\n\r\n \r\nকিভাবে আইপি টেলিফোন নাম্বার নিতে হবে?\r\n\r\n** জাতীয় পরিচয় পত্রের উভয় পাশের ছবি এবং ফোন নাম্বার দিতে হবে।\r\n** ফ্রিতে নাম্বার দেয়া হবে এবং কোন মাসিক চার্জ নেই।\r\n** আপনার ফোন নম্বর মিলিয়ে নাম্বার দেয়া হবে।\r\n** অনলাইন চ্যাটের মাধ্যমে সাহায্য করা হবে।\r\n** রিভিও দিলে ফ্রি টকটাইম দেয়া হবে।\r\n\r\n \r\nDownload Dialer\r\n\r\n \r\nRafusoft Dialer Download 	Zoiper Lite voip soft phone 	Desktop voip soft phone\r\n\r\n \r\n\r\nVIRTUAL RECEPTIONIST\r\nNo need for a full-time receptionist anymore. Our Virtual Receptionist feature integrates a consistent automated voice for your brand, ensuring you never miss a lead.\r\n\r\nCALL FORWARDING\r\nEasily manage business calls with this handy feature. If your extension is busy or unavailable, you can seamlessly transfer or forward calls to your personal mobile or home phones.\r\n\r\nCALL ROUTING\r\nBoost profits with intelligent call routing. Customize routing patterns based on various factors to ensure every call reaches the right destination.\r\n\r\nCALL CONFERENCING\r\nExperience fast, flexible, and secure call conferencing to connect team members on the same voice call, anywhere, anytime. Internal calls are completely free of charge.\r\n\r\nCALL TRANSFERRING\r\nDeliver the best solutions to your callers with call transfer features. Effortlessly transfer ongoing calls to other departments or agents for enhanced client satisfaction.\r\n\r\nVOICE MESSAGES\r\nAfter business hours, allow callers to leave voice messages. Access these messages through your dashboard or have them sent to your email address.\r\n\r\nMULTIPLE IVR\r\nUtilize multiple IVR (Interactive Voice Response) options for your business, including various greetings, off-day information, special day announcements, and special offer recordings.\r\n\r\nCALL RECORDING\r\nNever miss critical information with automatic call recording. All calls are securely stored on a hard disk for later reference.\r\n\r\nMULTIPLE CALL ATTENDING\r\nAttend to multiple calls simultaneously with up to five concurrent call channels. Easily increase your concurrent call capacity to manage higher call volumes.\r\n\r\nMUSIC ON HOLD\r\nEnhance your corporate image with customized on-hold music. Use it to promote your next campaign or reinforce your brand\'s identity during wait times.\r\n\r\nCALL MONITORING\r\nEffortlessly monitor your outgoing and incoming calls through your dashboard, ensuring you can review your call history anytime, anywhere.\r\n\r\nUNLIMITED EXTENSIONS\r\nAssign unlimited extensions to strengthen your internal communication and allow more team members to handle business calls, contributing to business growth.\r\n\r\n \r\n\r\nFree call to Bangladesh & ip phone number provider in bangladesh\r\n\r\nIf you find yourself in need of information or assistance regarding your Bangladesh mobile number, the dedicated customer care number is your direct line to reliable support. Whether you have questions about the BD phone code, wish to explore options for making free calls to Bangladesh, or are interested in understanding IP prices for telecommunication services, the customer care team is there to provide comprehensive guidance. They can assist with inquiries related to telephone services in Bangladesh, ensuring you have the necessary details about your mobile number and access to affordable IP options. Feel free to reach out to the customer care number for prompt and helpful assistance tailored to your specific needs.\r\n\r\nRafusoft is recognized as the top Business Telephone System Provider in Bangladesh. Obtain a professional IP PBX Telephone System for your business effortlessly, without any upfront costs or equipment purchases. Benefit from a flexible, multi-location communication system where extensions can be easily routed to any smartphone, PC, or tablet.', '3', '2025-01-30 06:29:11', '2025-01-30 06:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Rafu', 'rafu@rafusoft.com', 'admin', NULL, '$2y$10$ILYg67vaUg.hgHFIjpIxyObXhQyzt8Dzct/oqdjIiDj/1ZCJyxF7m', NULL, '2025-01-19 08:32:39', '2025-01-19 08:32:39'),
(5, 'MD Shamim Islam', 'shamimislam@rafusoft.com', 'user', NULL, '$2y$12$XgkT22YRLKW4tmuZAtSuWO3sUGFvy.qmHeQW366BV2aq9rzdPfXyK', NULL, '2025-06-02 09:43:07', '2025-06-02 09:43:07'),
(6, 'Abdullah Al Masud', 'abdmasud@rafusoft.com', 'user', NULL, '$2y$12$e.w5fjTXTLEj3MF0.0OUo.IOTfmLvEhfoFmd4wYzfQl/qWoBdE0XG', NULL, '2025-06-02 09:45:55', '2025-06-02 09:45:55');

-- --------------------------------------------------------

--
-- Table structure for table `widgets`
--

CREATE TABLE `widgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `widgetName` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `welcomeMessage` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `widgets`
--

INSERT INTO `widgets` (`id`, `user_id`, `api_key`, `avatar`, `widgetName`, `name`, `welcomeMessage`, `color`, `created_at`, `updated_at`) VALUES
(3, 4, 'qC8dg9in56xNT2YBjnRf', 'http://bot.test/uploads/1738164406_woman-icon-md.png', '', 'Nancy', 'Hi! How can you?', '#205', '2025-01-29 09:26:20', '2025-01-29 09:35:21'),
(4, 3, 'XiEErW4upbO4CPXDfBuY', 'https://ai-agent.rafusoft.com/uploads/1739888595_woman-icon-md.png', 'Customer Support', 'Alexa', 'How can I assist you in providing information about the Rafusoft AI Agent?', '#502', '2025-01-30 05:00:11', '2025-02-18 19:23:15'),
(5, 5, 'E5J0V1seQZ9riTMI29pg', 'https://ai-agent.rafusoft.com/uploads/1748843045_300px-BKash.png', 'Customer Support', 'MD. Shamim', 'Hi! How can you?', '#A05', '2025-06-02 09:44:05', '2025-06-02 09:44:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bots`
--
ALTER TABLE `bots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bots_botname_unique` (`botName`),
  ADD UNIQUE KEY `bots_bottoken_unique` (`botToken`);

--
-- Indexes for table `bot_contents`
--
ALTER TABLE `bot_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trains`
--
ALTER TABLE `trains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `widgets`
--
ALTER TABLE `widgets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bots`
--
ALTER TABLE `bots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bot_contents`
--
ALTER TABLE `bot_contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `trains`
--
ALTER TABLE `trains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `widgets`
--
ALTER TABLE `widgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
