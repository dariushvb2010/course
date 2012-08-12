-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2012 at 02:14 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

truncate table jf_rbac_permissions;
--
-- Dumping data for table `jf_rbac_permissions`
--

INSERT INTO `jf_rbac_permissions` (`ID`, `Left`, `Right`, `Title`, `Description`) VALUES
(0, 1, 54, 'root', 'root'),
(1, 2, 53, 'Review', ''),
(2, 51, 52, 'CreateUser', ''),
(3, 33, 50, 'Reports', ''),
(4, 31, 32, 'Correspondence', ''),
(5, 29, 30, 'Reviewer', ''),
(6, 27, 28, 'Archive', ''),
(7, 25, 26, 'MasterHand', 'who can view manager menu'),
(8, 23, 24, 'Raked', 'Raked Archive '),
(9, 21, 22, 'Reassign', 'Reassigns Files to Reviewers '),
(10, 19, 20, 'CotagBook', 'daftare cotags Permision '),
(17, 48, 49, 'CotagList', 'see list of cotages and their last progress'),
(18, 46, 47, 'ProgressList', 'see list of progress for a spc Cotag '),
(19, 44, 45, 'AssignedList', 'see LIst of Assigned Declares and their Reviewer '),
(20, 42, 43, 'NotReceivedInCotagBook', 'see cotages which not received in cotag book yet '),
(21, 40, 41, 'AssignableList', ''),
(22, 38, 39, 'ExitInfo', 'see information from exit door server'),
(23, 36, 37, 'NotArchivedList', ''),
(24, 5, 18, 'Alarm', ''),
(25, 16, 17, 'AlarmToAdmin', ''),
(26, 14, 15, 'AlarmToArchive', ''),
(27, 12, 13, 'AlarmToCotagBook', ''),
(28, 10, 11, 'AlarmToReviewer', ''),
(29, 8, 9, 'AlarmToRaked', ''),
(30, 6, 7, 'AlarmToCorrespondence', ''),
(31, 34, 35, 'TypistHelp', ''),
(32, 3, 4, 'scan', '');

-- --------------------------------------------------------

--
-- Table structure for table `jf_rbac_roles`
--

CREATE TABLE IF NOT EXISTS `jf_rbac_roles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Left` int(11) NOT NULL,
  `Right` int(11) NOT NULL,
  `Title` varchar(128) NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Left` (`Left`),
  KEY `Right` (`Right`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

truncate table jf_rbac_roles;
--
-- Dumping data for table `jf_rbac_roles`
--

INSERT INTO `jf_rbac_roles` (`ID`, `Left`, `Right`, `Title`, `Description`) VALUES
(0, 1, 24, 'root', 'root'),
(1, 4, 23, 'Review', ''),
(2, 21, 22, 'Review_Admin', ''),
(3, 19, 20, 'Review_Reviewer', ''),
(4, 17, 18, 'Review_Archive', ''),
(5, 15, 16, 'Review_Correspondence', ''),
(7, 13, 14, 'Review_Raked', ''),
(8, 11, 12, 'Review_CotagBook', ''),
(9, 2, 3, 'Programmer', ''),
(10, 9, 10, 'Review_Typist', ''),
(11, 7, 8, 'Review_Nazer', 'who cant change but view only'),
(13, 5, 6, 'Review_Scan', '');


--
-- Table structure for table `jf_rbac_rolepermissions`
--

CREATE TABLE IF NOT EXISTS `jf_rbac_rolepermissions` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`RoleID`,`PermissionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

truncate table jf_rbac_rolepermissions;
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
(7, 8, 1316077945),
(3, 17, 1316778499),
(4, 17, 1316778499),
(5, 17, 1316778499),
(7, 17, 1316778499),
(8, 17, 1325064126),
(3, 18, 1316778479),
(4, 18, 1316778479),
(7, 18, 1316778479),
(4, 19, 1316778452),
(4, 20, 1316778440),
(8, 20, 1316778440),
(4, 21, 1316778430),
(3, 22, 1316778420),
(5, 22, 1316778420),
(2, 17, 1316778499),
(8, 23, 1316786663),
(4, 23, 1316786515),
(2, 23, 1316786601),
(8, 18, 1325062593),
(2, 24, 1329483076),
(5, 30, 1329483128),
(7, 29, 1329483092),
(3, 28, 1329483103),
(4, 26, 1329483114),
(10, 31, 1329512814),
(5, 18, 1330913100),
(11, 3, 1338038851),
(13, 32, 1344335440);

-- --------------------------------------------------------


