DROP DATABASE IF EXISTS `university_db`;
CREATE DATABASE `university_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `university_db`;

-- Table structure for table `news_feed`
CREATE TABLE `news_feed` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `news_feed` (`id`, `title`, `description`, `created_at`) VALUES
(2, 'New Computer Lab Available', 'A new state-of-the-art computer lab has been opened in the Science Building for student use.', '2025-08-08 10:46:12'),
(3, 'Scholarship Application Deadline', 'Reminder: The deadline to apply for scholarships for the 2025 academic year is August 15th.', '2025-08-08 10:46:12'),
(4, 'Guest Lecture Series', 'A series of guest lectures on Artificial Intelligence will be held every Friday this semester.', '2025-08-08 10:46:12'),
(5, 'Campus Wi-Fi Upgrade', 'Campus-wide Wi-Fi is being upgraded to improve speed and reliability for all students and staff.', '2025-08-08 10:46:12');

-- Table structure for table `tblstudents`
CREATE TABLE `tblstudents` (
  `id` int(11) NOT NULL,
  `idcard` varchar(50) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `class` varchar(50) NOT NULL,
  `ranks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tblstudents` (`id`, `idcard`, `firstname`, `lastname`, `gender`, `address`, `class`, `ranks`) VALUES
(1, '123', 'hi', 'hi', 'Male', '1231', '123123', 1);

-- Table structure for table `tblusers`
CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tblusers` (`id`, `firstname`, `lastname`, `username`, `password`) VALUES
(1, 'Admin', 'User', 'admin', '123'),
(2, 'Admin', 'User', 'admin@gmail.com', 'A#@2025$');

-- Indexes
ALTER TABLE `news_feed` ADD PRIMARY KEY (`id`);
ALTER TABLE `tblstudents` ADD PRIMARY KEY (`id`);
ALTER TABLE `tblusers` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);

-- Auto increment values
ALTER TABLE `news_feed` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
ALTER TABLE `tblstudents` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `tblusers` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
