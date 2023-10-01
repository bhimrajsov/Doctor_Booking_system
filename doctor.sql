-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2023 at 07:11 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doctor`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_start_time` time NOT NULL,
  `appointment_end_time` time NOT NULL,
  `status` enum('RSVP','approved','cancelled','postponed') NOT NULL DEFAULT 'RSVP',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_start_time`, `appointment_end_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023-10-02', '02:30:00', '03:00:00', 'postponed', '2023-09-30 06:38:31', '2023-10-01 05:10:34'),
(2, 1, 1, '2023-10-01', '01:30:00', '02:00:00', 'postponed', '2023-09-30 06:38:31', '2023-09-30 08:08:56'),
(3, 1, 1, '2023-10-01', '12:30:00', '13:00:00', 'RSVP', '2023-10-01 05:09:50', '2023-10-01 05:09:50');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `speciality` varchar(255) NOT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `DOB` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `device_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `first_name`, `last_name`, `email`, `phone`, `image`, `speciality`, `languages`, `education`, `password`, `DOB`, `gender`, `device_token`, `created_at`, `updated_at`) VALUES
(1, 'Raju', 'Yadav', 'rajuyadav@gmail.com', 2147483647, './images/doctor/Doctor-1695923761.png', 'Teeth', 'English and Hindi', 'MBBS', '$2y$10$FSL.ObVIOuCB8.74w52bGO0n7zBrhtHayNLJHitbtJBKPV7bd/PJu', '2001-07-05', 'Male', 'eyJpdiI6InB1VWFKVXFyOFYyeU1SYTJJVGZoTlE9PSIsInZhbHVlIjoib3BjMDdhSWxTVnBER3dOSVBvWkthT1RMMHd6ZWwvRWRkZ1kwNms4VEp6ST0iLCJtYWMiOiI0ODIyNTFhNjczNjdhMDFkYmRhN2RlNTA0MDZjZTYxMDEwZDM5MzkxNTJiZmY3MzE2MTFlZDQzYzNkODMyOTAzIiwidGFnIjoiIn0=', '2023-09-28 09:25:00', '2023-09-28 12:26:01'),
(2, 'Kusum', 'Gupta', 'kusumgupta@gmail.com', 2147483647, './images/doctor/Doctor-1695913116.png', 'Skin', 'English and Hindi', 'MBBS', '$2y$10$cMcbvt3O1RAci6yLqjAzruJdpca0QlPfJcoK74lAiZ7.m.EOgw7Fa', '2002-12-13', 'Female', 'eyJpdiI6ImRDOXFiV0cxaVJBUUlyQTZzZjFCcXc9PSIsInZhbHVlIjoiRlJlR3BweGdYalVBRzB2cXNDbHpicTVTSlNYa0RTVGlnZFpaZ0FPR3JiMD0iLCJtYWMiOiI5MjI0ZDc2YzVlOTIwNjdmYjkxZjAwZDJkYzRhN2NjZjE4ZThmZTEzYmI1Y2JjZGQzN2Q4YjRlMDBkM2VkMjI1IiwidGFnIjoiIn0=', '2023-09-28 09:28:36', '2023-09-28 09:28:36'),
(4, 'Raj', 'Singh', 'rajs@gmail.com', 7738923111, NULL, 'Eyes', 'English and Hindi', 'MBBS', '$2y$10$6PcMvB1UuveudCf51q3bsuSL1Wo2cRfJ1Jkm1qzv8Fcs54POrSf4q', '2002-12-13', 'Female', 'eyJpdiI6IkFGWm5LUVFkbWRoUGo1U3dRV0w5VUE9PSIsInZhbHVlIjoiU250N1VxRzczeG9ZQUtVV0VCZDZpNDJNU0ZXLzBrN2xONGFGeHQ2Y3FSaz0iLCJtYWMiOiJjYTBiMjRhN2ViOWJmNzhhODJmMDgzNTQ5YTRjZWExNDRjNzY3YTcyOGI5NTVlZTdmYWU1NjFjMzZkZDQyYzM0IiwidGFnIjoiIn0=', '2023-10-01 05:08:22', '2023-10-01 05:08:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` bigint(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `DOB` date NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` varchar(255) NOT NULL,
  `height` double(8,2) DEFAULT NULL,
  `weight` double(8,2) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `device_token` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `image`, `DOB`, `address`, `gender`, `height`, `weight`, `password`, `device_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Ritik', 'Jaiswal', 'ritik@gmail.com', 2147483647, NULL, '2001-01-09', NULL, 'Male', 5.50, 75.50, '$2y$10$jLNIjzBzQV2ak5.d9cNpy.S6Dl9rTEkonprjIURDUPLwGjBY60yki', 'eyJpdiI6ImV3ZUZReHB4cUNGTWdsdGJwOGtJd1E9PSIsInZhbHVlIjoiZkNDS2gxWTI1ellBRXd0WGhPa3BXM1JKSGtQa2kxN0p3ais5aFA2THk2QT0iLCJtYWMiOiIyMTYyNmRiMjhjMTcxY2MyMmU5OGRkOTkzODVmMTNlYzI2Yzc1NWY5YTQ0YzkwMTA1MjNkNGUyNTA2MzI0ZjJmIiwidGFnIjoiIn0=', NULL, '2023-09-29 10:37:05', '2023-09-29 10:37:05'),
(2, 'Manju', 'Yadav', 'manju@gmail.com', 7715822605, NULL, '2004-05-24', 'Savarkar nagar', 'Male', 5.50, 75.50, '$2y$10$Ly7ZeUWI4A2tAHr69JfwJ.phryNR7AvebN0i4DpY1Ry2jYxoMdJ2W', 'eyJpdiI6IlBhNEpNZ2VDdmFMa0N2Ukd6aEF5REE9PSIsInZhbHVlIjoia0ZLcUQwWjdJc0pHakUvUDV1cXZ4anM5QzQ3d25aYk1US0RQak5iTmgyST0iLCJtYWMiOiJkZTY2MGZmMDY0ZTg5YjkwZDAzYzk3YzI4NjQ1YThmOGFjN2RiZGQ4ZTQxMGFkYzBiM2Y1OTA1MWMwZjU2ZWUwIiwidGFnIjoiIn0=', NULL, '2023-10-01 05:09:31', '2023-10-01 05:09:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctors_email_unique` (`email`);

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
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
