-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 12, 2011 at 09:50 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `gomrok`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_myuser`
--

CREATE TABLE IF NOT EXISTS `app_myuser` (
  `Firstname` varchar(255) COLLATE utf8_bin NOT NULL,
  `Lastname` varchar(255) COLLATE utf8_bin NOT NULL,
  `isReviewer` tinyint(1) NOT NULL,
  `ID` int(11) NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `app_myuser`
--

INSERT INTO `app_myuser` (`Firstname`, `Lastname`, `isReviewer`, `ID`, `Enabled`) VALUES
('Ø¹Ø¨Ø§Ø³', 'Ù†Ø§Ø¯Ø±ÛŒ', 1, 45, 0);

-- --------------------------------------------------------

--
-- Table structure for table `app_reviewcorrespondencetopic`
--

CREATE TABLE IF NOT EXISTS `app_reviewcorrespondencetopic` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Topic` varchar(255) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `app_reviewcorrespondencetopic`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewfile`
--

CREATE TABLE IF NOT EXISTS `app_reviewfile` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Cotag` int(11) NOT NULL,
  `CreateTimestamp` int(11) NOT NULL,
  `FinishTimestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `app_reviewfile`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogress`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogress` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CreateTimestamp` int(11) NOT NULL,
  `EditTimestamp` int(11) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  `FileID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Type` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_264F2FBC93076D5B` (`FileID`),
  KEY `IDX_264F2FBC58746832` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `app_reviewprogress`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressassign`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressassign` (
  `ReviewerID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_A4A0B57524C20335` (`ReviewerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressassign`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressdeliver`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressdeliver` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressdeliver`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressfinish`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressfinish` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressfinish`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressfinishcorrespondence`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressfinishcorrespondence` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressfinishcorrespondence`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressmanual`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressmanual` (
  `FinishTimestamp` int(11) NOT NULL,
  `FirstCreateTimestamp` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressmanual`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressmanualcorrespondence`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressmanualcorrespondence` (
  `Destination` varchar(255) NOT NULL,
  `Newmailnum` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressmanualcorrespondence`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressreceivefile`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressreceivefile` (
  `Sender` varchar(255) NOT NULL,
  `Receivemailnum` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressreceivefile`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressregisterarchive`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressregisterarchive` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressregisterarchive`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressregisterraked`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressregisterraked` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressregisterraked`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressreturn`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressreturn` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressreturn`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressreview`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressreview` (
  `Result` tinyint(1) NOT NULL,
  `Provision` varchar(255) NOT NULL,
  `Difference` varchar(255) NOT NULL,
  `Amount` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressreview`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogresssendfile`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogresssendfile` (
  `Requester` varchar(255) NOT NULL,
  `Operator` varchar(255) NOT NULL,
  `Sendmailnum` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogresssendfile`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewprogressstart`
--

CREATE TABLE IF NOT EXISTS `app_reviewprogressstart` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_reviewprogressstart`
--


-- --------------------------------------------------------

--
-- Table structure for table `app_reviewtopic`
--

CREATE TABLE IF NOT EXISTS `app_reviewtopic` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Topic` varchar(255) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  `Type` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `app_reviewtopic`
--


-- --------------------------------------------------------

--
-- Table structure for table `jfp_xuser`
--

CREATE TABLE IF NOT EXISTS `jfp_xuser` (
  `Email` varchar(255) COLLATE utf8_bin NOT NULL,
  `PasswordChangeTimestamp` int(11) NOT NULL,
  `TemporaryResetPassword` varchar(255) COLLATE utf8_bin NOT NULL,
  `TemporaryResetPasswordTimeout` int(11) NOT NULL,
  `LastLoginTimestamp` int(11) NOT NULL,
  `FailedLoginAttempts` int(11) NOT NULL,
  `LockTimeout` int(11) NOT NULL,
  `Activated` tinyint(1) NOT NULL,
  `CreateTimestamp` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jfp_xuser`
--

INSERT INTO `jfp_xuser` (`Email`, `PasswordChangeTimestamp`, `TemporaryResetPassword`, `TemporaryResetPasswordTimeout`, `LastLoginTimestamp`, `FailedLoginAttempts`, `LockTimeout`, `Activated`, `CreateTimestamp`, `ID`) VALUES
('', 0, '0', 1313676041, 1313676041, 0, 1313676041, 1, 1313612641, 45);

-- --------------------------------------------------------

--
-- Table structure for table `jf_i18n`
--

CREATE TABLE IF NOT EXISTS `jf_i18n` (
  `Language` varchar(32) NOT NULL,
  `Phrase` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Translation` text NOT NULL,
  `TimeAdded` int(11) NOT NULL,
  `TimeModified` int(11) NOT NULL,
  PRIMARY KEY (`Language`,`Phrase`(128))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jf_i18n`
--


-- --------------------------------------------------------

--
-- Table structure for table `jf_logs`
--

CREATE TABLE IF NOT EXISTS `jf_logs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(256) COLLATE utf8_bin NOT NULL,
  `Data` text COLLATE utf8_bin NOT NULL,
  `Severity` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `SessionID` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `Timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1183 ;

--
-- Dumping data for table `jf_logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `jf_options`
--

CREATE TABLE IF NOT EXISTS `jf_options` (
  `UserID` int(11) NOT NULL,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `Value` text COLLATE utf8_bin NOT NULL,
  `Expiration` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`Name`),
  KEY `Expiration` (`Expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jf_options`
--

INSERT INTO `jf_options` (`UserID`, `Name`, `Value`, `Expiration`) VALUES
(0, 'ReviewModelCronTimestamp', 0x693a313331333639363738373b, 2147483647);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

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
(8, 9, 10, 'CotagBook', 'daftare cotags Permision'),
(9, 7, 8, 'Reassign', 'Reassigns Files to Reviewers'),
(10, 5, 6, 'Raked', 'Raked Archive'),
(11, 3, 4, 'MasterHand', 'who can view manager menu');

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
(5, 4, 1313222332),
(2, 0, 1313223004),
(9, 8, 1316004487),
(10, 10, 1315813757);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `jf_rbac_roles`
--

INSERT INTO `jf_rbac_roles` (`ID`, `Left`, `Right`, `Title`, `Description`) VALUES
(0, 1, 16, 'root', ''),
(1, 2, 15, 'Review', ''),
(2, 13, 14, 'Review_Admin', ''),
(3, 11, 12, 'Review_Reviewer', ''),
(5, 9, 10, 'Review_Correspondence', ''),
(9, 7, 8, 'Review_CotagBook', ''),
(10, 5, 6, 'Review_Raked', ''),
(11, 3, 4, 'Review_Archive', 'baygani bazbini client');


-- --------------------------------------------------------

--
-- Table structure for table `jf_rbac_userroles`
--

CREATE TABLE IF NOT EXISTS `jf_rbac_userroles` (
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`RoleID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jf_rbac_userroles`
--

INSERT INTO `jf_rbac_userroles` (`UserID`, `RoleID`, `AssignmentDate`) VALUES
(1, 0, 2009),
(10, 3, 1313221589),
(11, 2, 1313222724),
(43, 0, 1313595777),
(45, 0, 1313617832);

-- --------------------------------------------------------

--
-- Table structure for table `jf_sessions`
--

CREATE TABLE IF NOT EXISTS `jf_sessions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` char(64) COLLATE utf8_bin NOT NULL,
  `UserID` int(11) NOT NULL,
  `IP` char(15) COLLATE utf8_bin NOT NULL,
  `LoginDate` int(11) NOT NULL,
  `LastAccess` int(11) NOT NULL,
  `AccessCount` int(11) NOT NULL DEFAULT '1',
  `CurrentRequest` varchar(1024) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SessionID` (`SessionID`),
  KEY `UserID` (`UserID`)
) ENGINE=MEMORY  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=123 ;

--
-- Dumping data for table `jf_sessions`
--

INSERT INTO `jf_sessions` (`ID`, `SessionID`, `UserID`, `IP`, `LoginDate`, `LastAccess`, `AccessCount`, `CurrentRequest`) VALUES
(122, 'l3r4c0ubi7uc74c05evncha0h3', 45, '127.0.0.1', 1315813008, 1315813756, 36, 'sys/rbac/assign');

-- --------------------------------------------------------

--
-- Table structure for table `jf_users`
--

CREATE TABLE IF NOT EXISTS `jf_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) COLLATE utf8_bin NOT NULL,
  `Password` varchar(255) COLLATE utf8_bin NOT NULL,
  `discriminator` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQ_E316F2131286421` (`Username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=46 ;

--
-- Dumping data for table `jf_users`
--

INSERT INTO `jf_users` (`ID`, `Username`, `Password`, `discriminator`) VALUES
(1, 'root', '119ba00fd73711a09fa82177f48f4e4ac32b1e1d73925fc4f654851b617b2a96fd5a5b3095d59b59e5cdfd71312ba3f61195414758478feced69544447360003', 'User'),
(45, 'admin', 'bf4bbd656d9743ccfa1d18d6361a5f91e2551a1af3c4afa143de08904ac232592327b1af1b21b5773ba68e4f62fa77157e76b35a6be5fca6db87089ac3ec3f10', 'MyUser');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `app_myuser`
--
ALTER TABLE `app_myuser`
  ADD CONSTRAINT `app_myuser_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `jf_users` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogress`
--
ALTER TABLE `app_reviewprogress`
  ADD CONSTRAINT `app_reviewprogress_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `app_myuser` (`ID`),
  ADD CONSTRAINT `app_reviewprogress_ibfk_1` FOREIGN KEY (`FileID`) REFERENCES `app_reviewfile` (`ID`);

--
-- Constraints for table `app_reviewprogressassign`
--
ALTER TABLE `app_reviewprogressassign`
  ADD CONSTRAINT `app_reviewprogressassign_ibfk_2` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `app_reviewprogressassign_ibfk_1` FOREIGN KEY (`ReviewerID`) REFERENCES `app_myuser` (`ID`);

--
-- Constraints for table `app_reviewprogressdeliver`
--
ALTER TABLE `app_reviewprogressdeliver`
  ADD CONSTRAINT `app_reviewprogressdeliver_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogressfinish`
--
ALTER TABLE `app_reviewprogressfinish`
  ADD CONSTRAINT `app_reviewprogressfinish_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogressfinishcorrespondence`
--
ALTER TABLE `app_reviewprogressfinishcorrespondence`
  ADD CONSTRAINT `app_reviewprogressfinishcorrespondence_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogressmanual`
--
ALTER TABLE `app_reviewprogressmanual`
  ADD CONSTRAINT `app_reviewprogressmanual_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogressmanualcorrespondence`
--
ALTER TABLE `app_reviewprogressmanualcorrespondence`
  ADD CONSTRAINT `app_reviewprogressmanualcorrespondence_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogressreceivefile`
--
ALTER TABLE `app_reviewprogressreceivefile`
  ADD CONSTRAINT `app_reviewprogressreceivefile_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogressregisterarchive`
--
ALTER TABLE `app_reviewprogressregisterarchive`
  ADD CONSTRAINT `app_reviewprogressregisterarchive_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogressregisterraked`
--
ALTER TABLE `app_reviewprogressregisterraked`
  ADD CONSTRAINT `app_reviewprogressregisterraked_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogressreturn`
--
ALTER TABLE `app_reviewprogressreturn`
  ADD CONSTRAINT `app_reviewprogressreturn_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogressreview`
--
ALTER TABLE `app_reviewprogressreview`
  ADD CONSTRAINT `app_reviewprogressreview_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogresssendfile`
--
ALTER TABLE `app_reviewprogresssendfile`
  ADD CONSTRAINT `app_reviewprogresssendfile_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `app_reviewprogressstart`
--
ALTER TABLE `app_reviewprogressstart`
  ADD CONSTRAINT `app_reviewprogressstart_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `app_reviewprogress` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `jfp_xuser`
--
ALTER TABLE `jfp_xuser`
  ADD CONSTRAINT `jfp_xuser_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `jf_users` (`ID`) ON DELETE CASCADE;
