-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 04:40 PM
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
-- Database: `odhsmd`
--

-- --------------------------------------------------------

--
-- Table structure for table `dbanimals`
--

CREATE TABLE `dbanimals` (
  `id` int(11) NOT NULL,
  `odhs_id` varchar(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `breed` varchar(256) DEFAULT NULL,
  `age` int(5) NOT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `spay_neuter_done` varchar(3) NOT NULL,
  `spay_neuter_date` date DEFAULT NULL,
  `rabies_given_date` date NOT NULL,
  `rabies_due_date` date DEFAULT NULL,
  `heartworm_given_date` date NOT NULL,
  `heartworm_due_date` date DEFAULT NULL,
  `distemper1_given_date` date NOT NULL,
  `distemper1_due_date` date DEFAULT NULL,
  `distemper2_given_date` date NOT NULL,
  `distemper2_due_date` date DEFAULT NULL,
  `distemper3_given_date` date NOT NULL,
  `distemper3_due_date` date DEFAULT NULL,
  `microchip_done` varchar(3) NOT NULL,
  `archived` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbanimals`
--

INSERT INTO `dbanimals` (`id`, `odhs_id`, `name`, `breed`, `age`, `gender`, `notes`, `spay_neuter_done`, `spay_neuter_date`, `rabies_given_date`, `rabies_due_date`, `heartworm_given_date`, `heartworm_due_date`, `distemper1_given_date`, `distemper1_due_date`, `distemper2_given_date`, `distemper2_due_date`, `distemper3_given_date`, `distemper3_due_date`, `microchip_done`, `archived`) VALUES
(1, '1234', 'Noodle', 'Schnoodle', 5, 'female', '', 'yes', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 'no', 'no'),
(2, '43221', 'Cin', 'Poodle', 18, 'female', ' | Bordetella: 2024-01-24', 'yes', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '2024-01-24', '2030-01-24', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 'no', 'no'),
(3, '543534', 'Rosie', 'Cat', 9, 'male', '', 'yes', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 'no', 'no'),
(4, '890890', 'George', 'Cat', 6, 'female', '', 'yes', '2024-01-05', '2024-01-26', '2026-01-02', '0000-00-00', '2024-01-22', '0000-00-00', '2024-01-29', '0000-00-00', '2024-01-24', '0000-00-00', '2024-01-25', 'no', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `dbeventmedia`
--

CREATE TABLE `dbeventmedia` (
  `id` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `file_name` text NOT NULL,
  `type` text NOT NULL,
  `file_format` text NOT NULL,
  `description` text NOT NULL,
  `altername_name` text NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbeventpersons`
--

CREATE TABLE `dbeventpersons` (
  `eventID` int(11) NOT NULL,
  `userID` varchar(256) NOT NULL,
  `position` text NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbevents`
--

CREATE TABLE `dbevents` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `date` char(10) NOT NULL,
  `startTime` char(5) NOT NULL,
  `endTime` char(5) NOT NULL,
  `description` text NOT NULL,
  `capacity` int(11) NOT NULL,
  `completed` text NOT NULL,
  `event_type` text NOT NULL,
  `restricted_signup` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbevents`
--

INSERT INTO `dbevents` (`id`, `name`, `date`, `startTime`, `endTime`, `description`, `capacity`, `completed`, `event_type`, `restricted_signup`) VALUES
(4, 'Jennifer', '2024-01-23', '15:00', '23:59', 'I am testing this shit', 0, '', '', 0),
(6, 'Waching TV', '2024-01-27', '09:00', '23:59', 'I am testing PHP and watching TV', 0, 'no', '', 0),
(7, 'Sleeping', '2024-01-21', '00:00', '23:59', 'We all like to sleep', 0, 'no', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbpersonhours`
--

CREATE TABLE `dbpersonhours` (
  `personID` varchar(256) NOT NULL,
  `eventID` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbpersons`
--

CREATE TABLE `dbpersons` (
  `id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` text DEFAULT NULL,
  `venue` text DEFAULT NULL,
  `first_name` text NOT NULL,
  `last_name` text DEFAULT NULL,
  `street_address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip_code` text DEFAULT NULL,
  `phone1` varchar(12) NOT NULL,
  `phone1type` text DEFAULT NULL,
  `emergency_contact_phone` varchar(12) DEFAULT NULL,
  `emergency_contact_phone_type` text DEFAULT NULL,
  `birthday` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `emergency_contact_first_name` text NOT NULL,
  `contact_num` varchar(12) NOT NULL,
  `emergency_contact_relation` text NOT NULL,
  `contact_method` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `profile_pic` text NOT NULL,
  `gender` varchar(6) NOT NULL,
  `tshirt_size` text NOT NULL,
  `how_you_heard_of_stepva` text NOT NULL,
  `sensory_sensitivities` text NOT NULL,
  `disability_accomodation_needs` text NOT NULL,
  `school_affiliation` text NOT NULL,
  `race` text NOT NULL,
  `preferred_feedback_method` text NOT NULL,
  `hobbies` text NOT NULL,
  `professional_experience` text NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `emergency_contact_last_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dbpersons`
--

INSERT INTO `dbpersons` (`id`, `start_date`, `venue`, `first_name`, `last_name`, `street_address`, `city`, `state`, `zip_code`, `phone1`, `phone1type`, `emergency_contact_phone`, `emergency_contact_phone_type`, `birthday`, `email`, `emergency_contact_first_name`, `contact_num`, `emergency_contact_relation`, `contact_method`, `type`, `status`, `notes`, `password`, `profile_pic`, `gender`, `tshirt_size`, `how_you_heard_of_stepva`, `sensory_sensitivities`, `disability_accomodation_needs`, `school_affiliation`, `race`, `preferred_feedback_method`, `hobbies`, `professional_experience`, `archived`, `emergency_contact_last_name`) VALUES
('brianna@gmail.com', '2024-01-22', 'portland', 'Brianna', 'Wahl', '212 Latham Road', 'Mineola', 'VA', '11501', '1234567890', 'cellphone', '', '', '2004-04-04', 'brianna@gmail.com', 'Mom', '1234567890', 'Mother', 'text', 'admin', 'Active', '', '$2y$10$jNbMmZwq.1r/5/oy61IRkOSX4PY6sxpYEdWfu9tLRZA6m1NgsxD6m', '', 'Female', '', '', '', '', '', '', '', '', '', 0, ''),
('bum@gmail.com', '2024-01-24', 'portland', 'bum', 'bum', '1345 Strattford St.', 'Mineola', 'VA', '22401', '1234567890', 'home', '', '', '1111-11-11', 'bum@gmail.com', 'Mom', '1234567890', 'Mom', 'text', 'admin', 'Active', '', '$2y$10$Ps8FnZXT7d4uiU/R5YFnRecIRbRakyVtbXP9TVqp7vVpuB3yTXFIO', '', 'Male', '', '', '', '', '', '', '', '', '', 0, ''),
('mom@gmail.com', '2024-01-22', 'portland', 'Lorraine', 'Egan', '212 Latham Road', 'Mineola', 'NY', '11501', '5167423832', 'home', '', '', '1910-10-10', 'mom@gmail.com', 'Mom', '5167423832', 'Dead', 'phone', 'admin', 'Active', '', '$2y$10$of1CkoNXZwyhAMS5GQ.aYuAW1SHptF6z31ONahnF2qK4Y/W9Ty2h2', '', 'Male', '', '', '', '', '', '', '', '', '', 0, ''),
('oliver@gmail.com', '2024-01-22', 'portland', 'Oliver', 'Wahl', '1345 Strattford St.', 'Fredericksburg', 'VA', '22401', '1234567890', 'home', '', '', '2011-11-11', 'oliver@gmail.com', 'Mom', '1234567890', 'Mother', 'text', 'admin', 'Active', '', '$2y$10$tgIjMkXhPzdmgGhUgbfPRuXLJVZHLiC0pWQQwOYKx8p8H8XY3eHw6', '', 'Other', '', '', '', '', '', '', '', '', '', 0, ''),
('peter@gmail.com', '2024-01-22', 'portland', 'Peter', 'Polack', '1345 Strattford St.', 'Mineola', 'VA', '12345', '1234567890', 'cellphone', '', '', '1968-09-09', 'peter@gmail.com', 'Mom', '1234567890', 'Mom', 'email', 'admin', 'Active', '', '$2y$10$j5xJ6GWaBhnb45aktS.kruk05u./TsAhEoCI3VRlNs0SRGrIqz.B6', '', 'Male', '', '', '', '', '', '', '', '', '', 0, ''),
('polack@um.edu', '2024-01-22', 'portland', 'Jennifer', 'Polack', '15 Wallace Farms Lane', 'Fredericksburg', 'VA', '22406', '1234567890', 'cellphone', '', '', '1970-05-01', 'polack@um.edu', 'Mom', '1234567890', 'Mom', 'email', 'admin', 'Active', '', '$2y$10$mp18j4WqhlQo7MTeS/9kt.i08n7nbt0YMuRoAxtAy52BlinqPUE4C', '', 'Female', '', '', '', '', '', '', '', '', '', 0, ''),
('tom@gmail.com', '2024-01-22', 'portland', 'tom', 'tom', '1345 Strattford St.', 'Mineola', 'NY', '12345', '1234567890', 'home', '', '', '1920-02-02', 'tom@gmail.com', 'Dad', '9876543210', 'Father', 'phone', 'admin', 'Active', '', '$2y$10$1Zcj7n/prdkNxZjxTK1zUOF7391byZvsXkJcN8S8aZL57sz/OfxP.', '', 'Male', '', '', '', '', '', '', '', '', '', 0, ''),
('vmsroot', 'N/A', 'portland', 'vmsroot', '', 'N/A', 'N/A', 'VA', 'N/A', '', 'N/A', 'N/A', 'N/A', 'N/A', 'vmsroot', 'N/A', 'N/A', 'N/A', 'N/A', '', 'N/A', 'N/A', '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', '', '', '', '', '', '', '', '', '', '', '', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbanimals`
--
ALTER TABLE `dbanimals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbeventmedia`
--
ALTER TABLE `dbeventmedia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKeventID2` (`eventID`);

--
-- Indexes for table `dbeventpersons`
--
ALTER TABLE `dbeventpersons`
  ADD KEY `FKeventID` (`eventID`),
  ADD KEY `FKpersonID` (`userID`);

--
-- Indexes for table `dbevents`
--
ALTER TABLE `dbevents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbpersonhours`
--
ALTER TABLE `dbpersonhours`
  ADD KEY `FkpersonID2` (`personID`),
  ADD KEY `FKeventID3` (`eventID`);

--
-- Indexes for table `dbpersons`
--
ALTER TABLE `dbpersons`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbanimals`
--
ALTER TABLE `dbanimals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dbeventmedia`
--
ALTER TABLE `dbeventmedia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbevents`
--
ALTER TABLE `dbevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dbeventmedia`
--
ALTER TABLE `dbeventmedia`
  ADD CONSTRAINT `FKeventID2` FOREIGN KEY (`eventID`) REFERENCES `dbevents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbeventpersons`
--
ALTER TABLE `dbeventpersons`
  ADD CONSTRAINT `FKeventID` FOREIGN KEY (`eventID`) REFERENCES `dbevents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKpersonID` FOREIGN KEY (`userID`) REFERENCES `dbpersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbpersonhours`
--
ALTER TABLE `dbpersonhours`
  ADD CONSTRAINT `FKeventID3` FOREIGN KEY (`eventID`) REFERENCES `dbevents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FkpersonID2` FOREIGN KEY (`personID`) REFERENCES `dbpersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
