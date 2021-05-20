-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 04, 2019 at 12:36 PM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fms`
--

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

DROP TABLE IF EXISTS `competitions`;
CREATE TABLE IF NOT EXISTS `competitions` (
  `comp_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_name` text NOT NULL,
  PRIMARY KEY (`comp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fixtures`
--

DROP TABLE IF EXISTS `fixtures`;
CREATE TABLE IF NOT EXISTS `fixtures` (
  `fixture_id` int(11) NOT NULL AUTO_INCREMENT,
  `fixture_date` date NOT NULL,
  `fixture_time` varchar(5) NOT NULL,
  `home_teamID` int(11) NOT NULL,
  `away_teamID` int(11) NOT NULL,
  `comp_ID` int(11) NOT NULL,
  PRIMARY KEY (`fixture_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `playerfixtures`
--

DROP TABLE IF EXISTS `playerfixtures`;
CREATE TABLE IF NOT EXISTS `playerfixtures` (
  `fixture_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `goals_scored` int(11) NOT NULL,
  PRIMARY KEY (`fixture_id`,`player_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `playerposition`
--

DROP TABLE IF EXISTS `playerposition`;
CREATE TABLE IF NOT EXISTS `playerposition` (
  `position_id` int(11) NOT NULL,
  `position_descr` text NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `player_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `player_name` text NOT NULL,
  `player_sqd_num` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  PRIMARY KEY (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_name` text NOT NULL,
  `team_email` text NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
