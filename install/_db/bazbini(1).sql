-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 23, 2011 at 01:45 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bazbini`
--

-- --------------------------------------------------------

--
-- Table structure for table `jf_rbac_permissions`
--

CREATE TABLE IF NOT EXISTS `jf_rbac_permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Left` int(11) NOT NULL,
  `Right` int(11) NOT NULL,
  `Title` char(64) NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Left` (`Left`),
  KEY `Right` (`Right`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `jf_rbac_permissions`
--

INSERT INTO `jf_rbac_permissions` (`ID`, `Left`, `Right`, `Title`, `Description`) VALUES
(0, 1, 22, 'root', 'root'),
(1, 2, 21, 'Review', ''),
(2, 19, 20, 'CreateUser', ''),
(3, 17, 18, 'Reports', ''),
(4, 15, 16, 'Correspondence', ''),
(5, 13, 14, 'Reviewer', ''),
(6, 11, 12, 'Archive', ''),
(7, 9, 10, 'MasterHand', 'who can view manager menu'),
(8, 7, 8, 'Raked', 'Raked Archive '),
(9, 5, 6, 'Reassign', 'Reassigns Files to Reviewers '),
(10, 3, 4, 'CotagBook', 'daftare cotags Permision ');

-- --------------------------------------------------------

--
-- Table structure for table `jf_rbac_rolepermissions`
--

CREATE TABLE IF NOT EXISTS `jf_rbac_rolepermissions` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`RoleID`,`PermissionID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jf_rbac_rolepermissions`
--

INSERT INTO `jf_rbac_rolepermissions` (`RoleID`, `PermissionID`, `AssignmentDate`) VALUES
(0, 0, 2009),
(2, 1, 1313222310),
(3, 5, 1313222320),
(4, 6, 1313222327),
(5, 4, 1313222332),
(2, 0, 1313223004),
(8, 10, 1316077935),
(7, 8, 1316077945);
