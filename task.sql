-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2024 at 12:35 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Id` int(11) NOT NULL,
  `unique_id` int(255) NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(20) NOT NULL,
  `img` varchar(500) NOT NULL,
  `status` varchar(200) NOT NULL,
  `number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Id`, `unique_id`, `fname`, `lname`, `email`, `password`, `img`, `status`, `number`) VALUES
(1, 825387835, 'samy', 'ahmed', 'alfy22@yahoo.com', '1234', '1708397183user-icon.png\r\n', 'Offline now', ''),
(3, 873013880, 'waled', 'ali', 's@gmail.com', 'ff', '1708397183user-icon.png', 'Offline now', ''),
(7, 1271973615, 'qq', 'qq', 'qq@aaaa', '12345', '1708397183user-icon.png\r\n', 'Offline now', ''),
(8, 1575126720, 'Omar', 'Admin', 'omadmin@yahoo.com', '123456', 'user-icon.png', 'Active now', '01096525194'),
(9, 787391066, 'Omar', 'Saber', 'omarsaber@yahoo.com', '1234', '', '', ''),
(12, 1056704309, 'jurgen', 'klopp', 'koppo@yahoo.com', '1234', 'user-icon.png', 'Active now', '095124532169');

-- --------------------------------------------------------

--
-- Table structure for table `projectdetails`
--

CREATE TABLE `projectdetails` (
  `dimension` varchar(50) NOT NULL,
  `count` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `task` varchar(50) NOT NULL,
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projectdetails`
--

INSERT INTO `projectdetails` (`dimension`, `count`, `total`, `task`, `id`, `project_id`) VALUES
('1*4', 2, 8, 'boko', 4, 56),
('2*3', 2, 12, 'بيان', 5, 57),
('1', 2, 2, 'dfw', 8, 60),
('2*6', 2, 24, 'lareg', 9, 61),
('1*3', 2, 6, 'bian ', 10, 63),
('4', 1, 4, '55', 11, 64);

-- --------------------------------------------------------

--
-- Table structure for table `projectfiles`
--

CREATE TABLE `projectfiles` (
  `id` int(11) NOT NULL,
  `file_path` varchar(50) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projectfiles`
--

INSERT INTO `projectfiles` (`id`, `file_path`, `project_id`) VALUES
(5, 'Screenshot 2024-03-19 122648.png', 56),
(6, 'Screenshot 2024-03-20 125417.png', 57),
(7, 'Screenshot 2024-03-20 224404.png', 57),
(12, '7.psd', 60),
(13, '4.psd', 61),
(14, 'ObjectOrientedProgramminginC4thEdition.pdf', 63),
(15, 'OOP.pdf', 63),
(16, 'IMG-20210521-WA0004.jpg', 64),
(18, 'IMG-20210521-WA0006.jpg', 66),
(19, 'IMG-20210521-WA0000.jpg', 67),
(20, 'IMG-20210521-WA0009.jpg', 68),
(21, 'IMG-20210521-WA0010.jpg', 68);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `admin_Id` int(100) NOT NULL,
  `admin_name` text NOT NULL,
  `client_name` text NOT NULL,
  `description` varchar(300) NOT NULL,
  `image` varchar(500) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `status` text NOT NULL DEFAULT 'Pending',
  `print_id` int(11) NOT NULL,
  `deadline` date DEFAULT NULL,
  `receipt` int(11) NOT NULL,
  `shipping_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `admin_Id`, `admin_name`, `client_name`, `description`, `image`, `placed_on`, `status`, `print_id`, `deadline`, `receipt`, `shipping_id`) VALUES
(24, 0, 'safa ahmed', 'samir', 'eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', 'Audit Instructions testing version 1.pdf', '2024-02-27', 'Completed', 0, NULL, 0, 0),
(28, 0, 'safa ahmed', 'samir', 'eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', 'ipnd-reference-sheet-css-vocabulary.pdf', '2024-02-28', 'Completed', 0, NULL, 0, 0),
(37, 0, 'Omar', 'Karma', 'alves to pope', 'encyclopedia-خطوات-انشاء-موقع-الكتروني.jpg', '2024-03-04', 'Completed', 0, '2024-03-04', 20, 0),
(38, 7, 'mora', 'ssss', 'fdsss', 'download.png', '2024-03-04', '', 0, '2024-03-03', 22, 0),
(40, 0, 'Omar', 'Sewa', 'koko  reds', 'Rodina_mahmoud.pdf', '2024-03-04', 'Completed', 0, '2024-03-05', 22, 0),
(42, 1, 'kewy', 'Sewa', 'No Edit', 'Rana Ehab Moselhy Abobakr-2-1.pdf', '2024-03-05', '', 0, '2024-03-06', 123, 0),
(43, 1, 'sera', 'dada', 'drew', 'thumbnail_image001 (1).jpg', '2024-03-08', '', 0, '2024-03-08', 234, 0),
(44, 1, 'kewy', 'dora', 'oppe', 'MicrosoftTeams-image (1).png', '2024-03-08', '', 0, '2024-03-08', 123, 0),
(45, 1, 'sora', 'der', 'xsssss', 'PrintData.pdf', '2024-03-08', '', 0, '2024-03-09', 12, 0),
(46, 1, 'errr', 'sss', 'ddss', 'Grinta_Integration Document_V10.0.pdf', '2024-03-08', '', 0, '2024-03-08', 23, 0),
(47, 1, 'errr', 'sss', 'ddss', 'Cairo-Hurghada1.pdf', '2024-03-08', '', 0, '2024-03-08', 23, 0),
(48, 0, 'www', 'eee', 'sssss', 'Change TFS Server.pdf', '2024-03-08', '', 0, '2024-03-09', 222, 0),
(56, 8, 'Omar Admin', 'andy', '', '', '2024-03-25', 'Pending', 0, '2024-03-27', 120, 0),
(57, 8, 'Omar Admin', 'dad', '', '', '2024-03-25', 'Pending', 0, '2024-03-26', 202, 0),
(60, 0, 'Omar Admin', 'psd item', '', '', '2024-03-25', 'Pending', 0, '2024-03-26', 147, 2),
(61, 0, 'Omar Admin', 'Rome', '', '', '2024-03-25', 'Pending', 0, '2024-03-26', 145, 1),
(62, 8, 'Omar Admin', 'ahmed', '', '', '2024-03-25', 'Pending', 0, '2024-03-26', 100, 0),
(63, 0, 'Omar Admin', 'ahmed', '', '', '2024-03-25', 'Completed', 0, '2024-03-26', 100, 0),
(64, 8, 'Omar Admin', 'fxg', '', '', '2024-05-30', 'Pending', 0, '2024-05-01', 545, 0),
(66, 12, 'jurgen klopp', '', '', '', '2024-05-31', 'Pending', 0, NULL, 0, 0),
(67, 12, 'jurgen klopp', '', '', '', '2024-05-31', 'Pending', 0, NULL, 0, 0),
(68, 12, 'jurgen klopp', '', '', '', '2024-05-31', 'Pending', 0, NULL, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `projectdetails`
--
ALTER TABLE `projectdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projectfiles`
--
ALTER TABLE `projectfiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `projectdetails`
--
ALTER TABLE `projectdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `projectfiles`
--
ALTER TABLE `projectfiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
