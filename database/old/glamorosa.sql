-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 12:41 PM
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
-- Database: `glamorosa`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblbook`
--

CREATE TABLE `tblbook` (
  `ID` int(10) NOT NULL,
  `ServiceID` int(11) NOT NULL,
  `UserID` int(10) DEFAULT NULL,
  `AptNumber` int(10) DEFAULT NULL,
  `AptDate` date DEFAULT NULL,
  `AptTimeStart` time DEFAULT NULL,
  `AptTimeEnd` time DEFAULT NULL,
  `Message` mediumtext DEFAULT NULL,
  `TransactionID` varchar(30) NOT NULL,
  `BookingDate` timestamp NULL DEFAULT current_timestamp(),
  `Remark` varchar(250) DEFAULT NULL,
  `Status` varchar(250) DEFAULT NULL,
  `RemarkDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblbook`
--

INSERT INTO `tblbook` (`ID`, `ServiceID`, `UserID`, `AptNumber`, `AptDate`, `AptTimeStart`, `AptTimeEnd`, `Message`, `TransactionID`, `BookingDate`, `Remark`, `Status`, `RemarkDate`) VALUES
(23, 39, 51, 592588204, '2025-03-14', '14:06:00', '16:06:00', 'kzjvlh bf jhdkldjfhgdjsfhl gjhsdf sdjkfhgasd', '', '2025-03-10 13:53:37', 'aSDF', 'Approved', '2025-03-10 14:00:24'),
(26, 39, 53, 824525706, '2025-05-30', '07:00:00', '08:59:00', '', '987985654', '2025-05-17 07:59:53', NULL, NULL, NULL),
(27, 39, 53, 358516023, '2025-05-20', '07:00:00', '08:59:00', '', '987985654', '2025-05-20 13:15:36', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblimages`
--

CREATE TABLE `tblimages` (
  `ID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL,
  `image` varchar(200) NOT NULL,
  `RegDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblinvoice`
--

CREATE TABLE `tblinvoice` (
  `id` int(11) NOT NULL,
  `appointmentNo` int(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `PostingDate` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblinvoice`
--

INSERT INTO `tblinvoice` (`id`, `appointmentNo`, `status`, `PostingDate`) VALUES
(9, 592588204, 'Approved', '2025-03-10');

-- --------------------------------------------------------

--
-- Table structure for table `tblpage`
--

CREATE TABLE `tblpage` (
  `ID` int(10) NOT NULL,
  `PageType` varchar(200) DEFAULT NULL,
  `PageTitle` mediumtext DEFAULT NULL,
  `PageDescription` mediumtext DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `UpdationDate` date DEFAULT NULL,
  `Timing` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpage`
--

INSERT INTO `tblpage` (`ID`, `PageType`, `PageTitle`, `PageDescription`, `Email`, `MobileNumber`, `UpdationDate`, `Timing`) VALUES
(1, 'aboutus', 'About Us', '        Our main focus is on quality and hygiene. Our Parlour is well equipped with advanced technology equipments and provides best quality services. Our staff is well trained and experienced, offering advanced services in Skin, Hair and Body Shaping that will provide you with a luxurious experience that leave you feeling relaxed and stress free. The specialities in the parlour are, apart from regular bleachings and Facials, many types of hairstyles, Bridal and cine make-up and different types of Facials &amp; fashion hair colourings.', NULL, NULL, NULL, ''),
(2, 'contactus', 'Contact Us', '890,Sector 62, Gyan Sarovar, ', 'info@gmail.com', 7896541236, NULL, '10:30 am to 7:30 pm');

-- --------------------------------------------------------

--
-- Table structure for table `tblservices`
--

CREATE TABLE `tblservices` (
  `ID` int(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ServiceName` varchar(200) DEFAULT NULL,
  `ServiceDescription` mediumtext DEFAULT NULL,
  `Cost` int(20) DEFAULT NULL,
  `Image` varchar(200) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblservices`
--

INSERT INTO `tblservices` (`ID`, `user_id`, `ServiceName`, `ServiceDescription`, `Cost`, `Image`, `CreationDate`) VALUES
(39, 50, 'juan', 'juan', 1499, 'eyelash.png', '2025-03-10 02:17:31');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `ID` int(10) NOT NULL,
  `qrcode` varchar(100) DEFAULT NULL,
  `FirstName` varchar(120) DEFAULT NULL,
  `LastName` varchar(250) DEFAULT NULL,
  `Age` int(50) DEFAULT NULL,
  `Gender` varchar(50) DEFAULT NULL,
  `MobileNumber` varchar(20) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `role` enum('Admin','Staff','Customer') NOT NULL,
  `RegDate` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`ID`, `qrcode`, `FirstName`, `LastName`, `Age`, `Gender`, `MobileNumber`, `Email`, `Password`, `role`, `RegDate`) VALUES
(1, NULL, 'Admin', 'Admin', NULL, NULL, '2147483647', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'Admin', '2025-02-28'),
(50, 'qrcode/50/qr_1747476479_68285fff0f428.png', 'Juan', 'Dela Cruz', NULL, NULL, '09123456789', 'juan@gmail.com', 'f5737d25829e95b9c234b7fa06af8736', 'Staff', '2025-03-10'),
(51, NULL, 'Sam', 'Macaranas', 26, 'Male', '0985687894', 'user@gmail.com', 'b5b73fae0d87d8b4e2573105f8fbe7bc', 'Customer', '2025-03-10'),
(53, NULL, 'Denver ', 'Macaranas', 21, 'Female', '09123321789', 'user011@gmail.com', '80ec08504af83331911f5882349af59d', 'Customer', '2025-05-17'),
(54, NULL, 'Colby', 'Castaneda', 95, 'Female', '279', 'qegyhun@mailinator.com', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', 'Customer', '2025-05-24');

-- --------------------------------------------------------

--
-- Table structure for table `tblvideo`
--

CREATE TABLE `tblvideo` (
  `ID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL,
  `video` varchar(200) NOT NULL,
  `RegDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblbook`
--
ALTER TABLE `tblbook`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblimages`
--
ALTER TABLE `tblimages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblinvoice`
--
ALTER TABLE `tblinvoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `tblservices`
--
ALTER TABLE `tblservices`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `tblvideo`
--
ALTER TABLE `tblvideo`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblbook`
--
ALTER TABLE `tblbook`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tblimages`
--
ALTER TABLE `tblimages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblinvoice`
--
ALTER TABLE `tblinvoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblservices`
--
ALTER TABLE `tblservices`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tblvideo`
--
ALTER TABLE `tblvideo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
