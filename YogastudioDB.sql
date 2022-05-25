-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 28, 2022 at 04:16 AM
-- Server version: 5.7.34
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `YogastudioDB`
--
CREATE DATABASE IF NOT EXISTS `YogastudioDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `YogastudioDB`;

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

DROP TABLE IF EXISTS `Courses`;
CREATE TABLE `Courses` (
  `courseId` int(11) NOT NULL,
  `courseTitle` varchar(255) NOT NULL,
  `courseLevel` varchar(255) NOT NULL,
  `courseDescription` varchar(255) NOT NULL,
  `courseFee` decimal(10,2) NOT NULL,
  `instructorId` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `frequency` varchar(50) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `studentLimit` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Courses`
--

INSERT INTO `Courses` (`courseId`, `courseTitle`, `courseLevel`, `courseDescription`, `courseFee`, `instructorId`, `startDate`, `endDate`, `startTime`, `endTime`, `frequency`, `created`, `studentLimit`) VALUES
(1, 'Yoga For Beginners', 'Beginner', 'This beginner friendly class focuses on basic yoga poses to improve flexibility and balance.', '100.00', 2, '2022-02-01', '2022-02-28', '11:00:00', '12:00:00', 'Daily', '2022-02-17 23:05:38', 10),
(2, 'All Things Core', 'Intermediate', 'This course includes core strengthening yoga poses for students with basic idea of Yoga.', '120.00', 4, '2022-02-20', '2022-03-20', '17:00:00', '18:00:00', 'Weekly', '2022-02-17 23:09:19', 2),
(3, 'Pilates', 'Expert', 'Pilates is a form of exercise which concentrates on strengthening the body with an emphasis on core strength.', '150.00', 8, '2022-04-01', '2022-05-01', '12:00:00', '13:00:00', 'Daily', '2022-02-23 00:27:50', 5),
(4, 'Yoga for Kids', 'Beginner', 'This is beginner course covering basic Yoga poses. This helps to improve general fitness and overall well-being.', '50.00', 2, '2022-03-01', '2022-03-20', '10:00:00', '11:00:00', 'Daily', '2022-02-23 01:26:35', 5);

-- --------------------------------------------------------

--
-- Table structure for table `Login`
--

DROP TABLE IF EXISTS `Login`;
CREATE TABLE `Login` (
  `userId` int(11) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Login`
--

INSERT INTO `Login` (`userId`, `userName`, `password`) VALUES
(1, 'alex', 'alex'),
(2, 'martha', 'martha'),
(3, 'jake', 'jake'),
(4, 'tracy', 'tracy'),
(5, 'melissa', 'melissa'),
(6, 'josh', 'josh'),
(7, 'julia', 'julia'),
(8, 'fiji', 'fiji'),
(9, 'stella', 'stella');

-- --------------------------------------------------------

--
-- Table structure for table `Registration`
--

DROP TABLE IF EXISTS `Registration`;
CREATE TABLE `Registration` (
  `userId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Registration`
--

INSERT INTO `Registration` (`userId`, `courseId`) VALUES
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `userId` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `emailId` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userId`, `firstName`, `lastName`, `phoneNumber`, `emailId`, `created`, `userType`) VALUES
(1, 'Alex', 'Williams', '31223487945', 'alex.williams@gmail.com', '2022-01-09 08:47:35', 'admin'),
(2, 'Martha', 'Turnwald', '9091238907', 'martha.turnwald@gmail.com', '2022-01-10 10:47:35', 'instructor'),
(3, 'Jake', 'Lion', '1234567890', 'jake@gmail.com', '2022-02-13 02:41:12', 'customer'),
(4, 'Tracy', 'Allan', '3456843219', 'tracy@gmail.com', '2022-02-13 12:36:18', 'instructor'),
(5, 'Melissa', 'Chen', '3125431538', 'melissa@gmail.com', '2022-02-14 23:54:23', 'customer'),
(6, 'Josh', 'Stewart', '1234567890', 'josh@gamci.com', '2022-02-15 00:04:56', 'admin'),
(7, 'Julia', 'Danu', '1234567890', 'julia@gmail.com', '2022-02-17 01:25:08', 'customer'),
(8, 'Fiji', 'Luz', '1726345290', 'fiji@gmail.com', '2022-02-17 01:43:17', 'instructor'),
(9, 'Stella', 'William', '3432512345', 'stella@gmail.com', '2022-02-17 23:37:49', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Courses`
--
ALTER TABLE `Courses`
  ADD PRIMARY KEY (`courseId`),
  ADD KEY `courses_employeeId` (`instructorId`);

--
-- Indexes for table `Login`
--
ALTER TABLE `Login`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- Indexes for table `Registration`
--
ALTER TABLE `Registration`
  ADD KEY `registration_userId` (`userId`),
  ADD KEY `registration_courseId` (`courseId`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Courses`
--
ALTER TABLE `Courses`
  MODIFY `courseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Courses`
--
ALTER TABLE `Courses`
  ADD CONSTRAINT `courses_employeeId` FOREIGN KEY (`instructorId`) REFERENCES `Users` (`userId`);

--
-- Constraints for table `Login`
--
ALTER TABLE `Login`
  ADD CONSTRAINT `users_userId` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`);

--
-- Constraints for table `Registration`
--
ALTER TABLE `Registration`
  ADD CONSTRAINT `registration_courseId` FOREIGN KEY (`courseId`) REFERENCES `Courses` (`courseId`),
  ADD CONSTRAINT `registration_userId` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
