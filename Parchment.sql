-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 17, 2024 at 07:05 AM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Parchment`
--

-- --------------------------------------------------------

--
-- Table structure for table `Cards`
--

CREATE TABLE `Cards` (
  `CardID` int(11) NOT NULL,
  `DeckID` int(11) NOT NULL,
  `Front` blob NOT NULL,
  `Back` blob NOT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` varchar(10) DEFAULT 'new',
  `ReviewDate` date DEFAULT NULL,
  `EasyCount` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Cards`
--

INSERT INTO `Cards` (`CardID`, `DeckID`, `Front`, `Back`, `CreatedDate`, `ModifiedDate`, `Status`, `ReviewDate`, `EasyCount`) VALUES
(66, 9, 0x31, 0x31, '2024-06-15 20:44:37', '2024-06-15 20:44:37', 'new', NULL, 0),
(67, 9, 0x32, 0x32, '2024-06-15 20:44:40', '2024-06-15 20:44:40', 'new', NULL, 0),
(68, 76, 0x61, 0x61, '2024-07-17 07:01:14', '2024-07-17 07:01:14', 'to review', '2024-07-18', 0),
(69, 76, 0x62, 0x62, '2024-07-17 07:01:18', '2024-07-17 07:01:18', 'to review', '2024-07-18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE `Categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Categories`
--

INSERT INTO `Categories` (`CategoryID`, `CategoryName`) VALUES
(22, 'Arabic'),
(23, 'Chinese'),
(24, 'English'),
(25, 'French'),
(26, 'German'),
(27, 'Hebrew'),
(28, 'Japanese'),
(29, 'Korean'),
(30, 'Russian'),
(31, 'Spanish'),
(32, 'Anatomy'),
(33, 'Biology'),
(34, 'Chemistry'),
(35, 'Geography'),
(36, 'History'),
(37, 'Law'),
(38, 'Math'),
(39, 'Music'),
(40, 'Pathology'),
(41, 'Physics'),
(42, 'Computer Science'),
(43, 'Art'),
(44, 'Literature'),
(45, 'Philosophy'),
(46, 'Psychology'),
(47, 'Sociology'),
(48, 'Engineering'),
(49, 'Medicine'),
(50, 'Economics'),
(51, 'Political Science'),
(52, 'Environmental Science'),
(53, 'Astronomy'),
(54, 'Geology');

-- --------------------------------------------------------

--
-- Table structure for table `DeckCategories`
--

CREATE TABLE `DeckCategories` (
  `DeckID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Decks`
--

CREATE TABLE `Decks` (
  `DeckID` int(11) NOT NULL,
  `DeckName` varchar(100) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Visibility` enum('public','private') NOT NULL DEFAULT 'private',
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Decks`
--

INSERT INTO `Decks` (`DeckID`, `DeckName`, `UserID`, `Visibility`, `CreatedDate`, `ModifiedDate`) VALUES
(8, 'ML', 9, 'private', '2024-06-10 15:30:07', '2024-06-10 15:30:07'),
(9, 'Operating System', 1, 'private', '2024-06-10 17:43:56', '2024-06-10 17:43:56'),
(16, 't1', 11, 'private', '2024-06-11 17:43:22', '2024-06-11 17:43:22'),
(17, 't2', 11, 'private', '2024-06-11 17:43:24', '2024-06-11 17:43:24'),
(18, 't3', 11, 'private', '2024-06-11 17:43:26', '2024-06-11 17:43:26'),
(75, 'os2', 1, 'private', '2024-06-15 17:44:58', '2024-06-15 17:44:58'),
(76, 'test shared deck', 10, 'private', '2024-06-16 13:53:48', '2024-06-16 13:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `Progress`
--

CREATE TABLE `Progress` (
  `ProgressID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `DeckID` int(11) NOT NULL,
  `Date` datetime DEFAULT CURRENT_TIMESTAMP,
  `CardsReviewed` int(11) DEFAULT '0',
  `CardsLearned` int(11) DEFAULT '0',
  `ReviewDuration` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Reviews`
--

CREATE TABLE `Reviews` (
  `ReviewID` int(11) NOT NULL,
  `CardID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ReviewDate` datetime NOT NULL,
  `EaseFactor` float NOT NULL DEFAULT '2.5',
  `CardInterval` int(11) NOT NULL DEFAULT '1',
  `Repetitions` int(11) NOT NULL DEFAULT '0',
  `NextReviewDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SharedDecks`
--

CREATE TABLE `SharedDecks` (
  `SharedDeckID` int(11) NOT NULL,
  `DeckID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `SharedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `Rating` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SharedDecks`
--

INSERT INTO `SharedDecks` (`SharedDeckID`, `DeckID`, `UserID`, `SharedDate`, `Title`, `Description`, `CategoryID`, `Rating`) VALUES
(16, 9, 1, '2024-06-14 23:22:31', 'Operating System - chapter 5 , a.b.', 'end of the chapter questions', 42, 0),
(19, 76, 10, '2024-06-16 19:53:55', 'test shared deck', 'test cs deck', 42, 0);

-- --------------------------------------------------------

--
-- Table structure for table `UserRatings`
--

CREATE TABLE `UserRatings` (
  `UserRatingID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `DeckID` int(11) NOT NULL,
  `Rating` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `Userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`username`, `password`, `email`, `Userid`) VALUES
('vuralm19', 'vuralm19', 'vuralm19@itu.edu.tr', 1),
('mert123', 'mert123', 'mert123@gmail.com', 3),
('vuralm1', 'vuralm1', 'vuralm1@itu.edu.tr', 4),
('vuralm2', 'vuralm2', 'vuralm2@itu.edu.tr', 6),
('vuralm3', 'vuralm3', 'vuralm3@itu.edu.tr', 7),
('m1', '$2y$10$QKr5EC38wMaA0Klwd2M3eeh.S9MxodGkXXt5Boc7WFpk.PQB9O8Fi', 'm1@m1', 8),
('m2', 'm2', 'm2@m2', 9),
('m3', 'm3', 'm3@m3', 10),
('m6', 'm6', 'm6@m6', 11);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Cards`
--
ALTER TABLE `Cards`
  ADD PRIMARY KEY (`CardID`),
  ADD KEY `fk_deckid` (`DeckID`),
  ADD KEY `DeckID` (`DeckID`);

--
-- Indexes for table `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `DeckCategories`
--
ALTER TABLE `DeckCategories`
  ADD PRIMARY KEY (`DeckID`,`CategoryID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `Decks`
--
ALTER TABLE `Decks`
  ADD PRIMARY KEY (`DeckID`),
  ADD KEY `fk_userid` (`UserID`);

--
-- Indexes for table `Progress`
--
ALTER TABLE `Progress`
  ADD PRIMARY KEY (`ProgressID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `DeckID` (`DeckID`);

--
-- Indexes for table `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `fk_reviews_userid` (`UserID`),
  ADD KEY `fk_reviews_cardid` (`CardID`);

--
-- Indexes for table `SharedDecks`
--
ALTER TABLE `SharedDecks`
  ADD PRIMARY KEY (`SharedDeckID`),
  ADD KEY `DeckID` (`DeckID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `UserRatings`
--
ALTER TABLE `UserRatings`
  ADD PRIMARY KEY (`UserRatingID`),
  ADD UNIQUE KEY `UserID` (`UserID`,`DeckID`),
  ADD KEY `DeckID` (`DeckID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Userid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Cards`
--
ALTER TABLE `Cards`
  MODIFY `CardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `Categories`
--
ALTER TABLE `Categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `Decks`
--
ALTER TABLE `Decks`
  MODIFY `DeckID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `Progress`
--
ALTER TABLE `Progress`
  MODIFY `ProgressID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `SharedDecks`
--
ALTER TABLE `SharedDecks`
  MODIFY `SharedDeckID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `UserRatings`
--
ALTER TABLE `UserRatings`
  MODIFY `UserRatingID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `Userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Cards`
--
ALTER TABLE `Cards`
  ADD CONSTRAINT `fk_deckid` FOREIGN KEY (`DeckID`) REFERENCES `Decks` (`DeckID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `DeckCategories`
--
ALTER TABLE `DeckCategories`
  ADD CONSTRAINT `deckcategories_ibfk_1` FOREIGN KEY (`DeckID`) REFERENCES `Decks` (`DeckID`) ON DELETE CASCADE,
  ADD CONSTRAINT `deckcategories_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `Categories` (`CategoryID`) ON DELETE CASCADE;

--
-- Constraints for table `Decks`
--
ALTER TABLE `Decks`
  ADD CONSTRAINT `fk_userid` FOREIGN KEY (`UserID`) REFERENCES `Users` (`Userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Progress`
--
ALTER TABLE `Progress`
  ADD CONSTRAINT `progress_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`Userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `progress_ibfk_2` FOREIGN KEY (`DeckID`) REFERENCES `Decks` (`DeckID`) ON DELETE CASCADE;

--
-- Constraints for table `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `fk_reviews_cardid` FOREIGN KEY (`CardID`) REFERENCES `Cards` (`CardID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_userid` FOREIGN KEY (`UserID`) REFERENCES `Users` (`Userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `SharedDecks`
--
ALTER TABLE `SharedDecks`
  ADD CONSTRAINT `shareddecks_ibfk_1` FOREIGN KEY (`DeckID`) REFERENCES `Decks` (`DeckID`) ON DELETE CASCADE,
  ADD CONSTRAINT `shareddecks_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `Users` (`Userid`) ON DELETE CASCADE;

--
-- Constraints for table `UserRatings`
--
ALTER TABLE `UserRatings`
  ADD CONSTRAINT `userratings_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`Userid`),
  ADD CONSTRAINT `userratings_ibfk_2` FOREIGN KEY (`DeckID`) REFERENCES `SharedDecks` (`DeckID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
