-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 08:40 AM
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
-- Database: `e_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `consultation`
--

CREATE TABLE `consultation` (
  `c_id` int(11) NOT NULL,
  `pnt_name` varchar(50) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `c_date` date DEFAULT NULL,
  `bp` varchar(50) DEFAULT NULL,
  `temp` varchar(50) DEFAULT NULL,
  `HR` varchar(50) DEFAULT NULL,
  `RR` varchar(50) DEFAULT NULL,
  `O2_sat` varchar(50) DEFAULT NULL,
  `medicine` varchar(50) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultation`
--

INSERT INTO `consultation` (`c_id`, `pnt_name`, `division`, `company`, `c_date`, `bp`, `temp`, `HR`, `RR`, `O2_sat`, `medicine`, `qty`, `remarks`) VALUES
(7, 'Mark Covi Del Rosario', 'IT Department', 'HEPC', '2024-11-20', '180/90', '36.5', '60', '11', '99%', 'Paracetamol', 1, 'Kaka Lulu'),
(8, 'Nealbert Dingcol', 'IT Department', 'HEPC', '2024-11-20', '170/90', '39.9', '60', '11', '99%', 'Paracetamol', 2, 'High Fever'),
(10, 'Robert Villamor', 'IT Department', 'HEPC', '2024-11-20', '170/90', '36.5', '60', '11', '99%', 'Paracetamol', 2, 'Kaka Watch dog'),
(11, 'Michael Pechay', 'IT Department', 'HEPC', '2024-11-20', '180/90', '36.5', '60', '11', '99%', 'Paracetamol', 3, 'Walang Tulog'),
(12, 'Christian Solano', 'IT Department', 'HEPC', '2024-11-20', '180/90', '39.9', '60', '11', '99%', 'Paracetamol', 1, 'Palong palo'),
(13, 'Joshua Cabunton', 'IT Department', 'HEPC', '2024-11-20', '180/90', '36.5', '60', '11', '99%', 'Paracetamol', 2, 'Kaka selpon yan'),
(14, 'Aivan Miranda', 'IT Department', 'HEPC', '2024-11-20', '180/90', '36.5', '60', '11', '99%', 'Paracetamol', 1, 'Baby face');

-- --------------------------------------------------------

--
-- Table structure for table `fit_to_work`
--

CREATE TABLE `fit_to_work` (
  `f_date` date DEFAULT NULL,
  `f_name` varchar(50) NOT NULL,
  `diagnosis` varchar(50) DEFAULT NULL,
  `ftw` varchar(50) DEFAULT NULL,
  `date_ofabs` date DEFAULT NULL,
  `Med_name` varchar(50) DEFAULT NULL,
  `nod` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `med_id` int(11) NOT NULL,
  `Med_name` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `date_receive` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`med_id`, `Med_name`, `quantity`, `date_receive`) VALUES
(32, 'Lorazepan', 50, '2024-11-19'),
(33, 'Bupremol', 20, '2024-11-19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `account_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `account_type`) VALUES
(1, 'admin_24', 'eme', 'Covi Zabala', 'Admin'),
(2, 'Clinic-10', 'user', 'Lana Del Rey', 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`med_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `consultation`
--
ALTER TABLE `consultation`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `med_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
