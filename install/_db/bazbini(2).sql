-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 23, 2011 at 05:44 PM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.2

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
-- Table structure for table `app_MyUser`
--

CREATE TABLE IF NOT EXISTS `app_MyUser` (
  `Firstname` varchar(255) COLLATE utf8_bin NOT NULL,
  `Lastname` varchar(255) COLLATE utf8_bin NOT NULL,
  `isReviewer` tinyint(1) NOT NULL,
  `ID` int(11) NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `app_MyUser`
--

INSERT INTO `app_MyUser` (`Firstname`, `Lastname`, `isReviewer`, `ID`, `Enabled`) VALUES
('Ø¹Ø¨Ø§Ø³', 'Ù†Ø§Ø¯Ø±ÛŒ', 1, 45, 0),
('bazbin', 'bazbin', 1, 46, 1),
('cotag', 'cotag', 0, 47, 1),
('raked', 'raked', 0, 48, 1),
('archive', 'archive', 0, 49, 1),
('mokatebat', 'mokatebat', 0, 51, 1),
('Ø¢Ù‚Ø§ÛŒ', 'Ú©Ù…Ø§Ù† ØªØ±Ú©ÛŒ', 0, 53, 1),
('Ø§Ø­Ù…Ø¯ Ø±Ø¶Ø§', 'ÙƒÙ…Ø§Ù† ØªØ±ÙƒÙŠ', 0, 54, 1),
('Ù…Ø­Ù…ÙˆØ¯', 'Ù…Ø­Ø¨ÛŒ', 0, 55, 1),
('Ø¹Ø§Ø´ÙˆØ±', 'Ø¹Ø±Ø§Ø²Ù¾ÙˆØ±', 0, 56, 1),
('Ú©Ø§Ù…Ø¨ÛŒØ²', 'Ú¯ÙˆØ¯Ø±Ø²ÛŒ', 0, 57, 1),
('Ù…Ø±ØªØ¶ÙŠ', 'ÙƒÙˆØ§ÙƒØ¨ÙŠ', 0, 58, 1),
('Ø§Ø³Ù…Ø§Ø¹ÙŠÙ„', 'Ù…ÙˆØ³ÙˆÙŠ', 1, 59, 1),
('Ù…Ø­Ø³Ù†', 'ØµØ¨Ø±ÙŠ', 1, 60, 1),
('Ø¬ÙˆØ§Ø¯', 'Ø§Ù…ÙŠØ¯ÙˆØ§Ø±', 1, 62, 1),
('Ø°Ø¨ÙŠØ­ Ø§Ù„Ù‡', 'ÙƒØ±ÙŠÙ…ÙŠ', 1, 63, 1),
('Ù…Ø±ØªØ¶ÙŠ', 'ÙƒØ¨ÙŠØ±ÙŠ', 1, 64, 1),
('Ø§Ø¨ÙˆØ§Ù„ÙØ¶Ù„', 'ÙƒØ§Ø±Ú¯Ø± ÙØ±Ø¯', 1, 65, 1),
('Ø®Ø§Ø·Ø±Ù‡ Ø§Ù…Ù†Ù‡', 'Ù…Ø­Ù…ÙˆØ¯ÙŠ', 1, 66, 1),
('Ø§Ù„Ù‡Ø§Ù…', 'Ø­Ù‚ÙŠ', 1, 67, 1),
('Ù…Ø­Ø³Ù†', 'Ú¯Ø±Ø§Ø¦Ù„ÙŠ', 1, 68, 1),
('ØµÙ…Ø¯', 'Ø«Ø±ÙˆØªÙŠ', 1, 69, 1),
('Ø¬Ù…Ø§Ù„', 'Ø¢Ù‚Ø§ÙŠÙŠ', 1, 70, 1),
('Ù…Ø¬ÙŠØ¯', 'Ø§ÙØªØ®Ø§Ø±ÙŠ Ù†Ú˜Ø§Ø¯', 1, 71, 1),
('ÙØ±ÙŠØ¨Ø§', 'Ø³Ù„Ø¬ÙˆÙ‚ÙŠ', 1, 72, 1),
('Ù…Ù‡Ø¯ÙŠ', 'ÙƒØ§Ù…Ø±Ø§Ù†ÙŠ ÙØ±Ø§Ø²', 1, 73, 1),
('Ø­Ø³ÙŠÙ†', 'Ø¯Ù„ÙŠØ±Ø§Ù†ÙŠÙˆÙ†', 1, 74, 1),
('Ø¹Ù„ÙŠØ±Ø¶Ø§', 'Ú¯ÙˆØ¯Ø±Ø²ÙŠ', 1, 75, 1),
('Ø¹Ø¨Ø¯Ø§Ù„Ø±Ø´ÛŒØ¯', 'Ú©Ù…', 0, 76, 1),
('Ø§ØµØºØ±', 'Ø§ØµÙ„Ø§Ù†ÙŠ ØªØ¨Ø§Ø±', 0, 77, 1);

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
(0, '01e06c39t4grojv8061uma5h13_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"d3iu3n6tujep5f36rpnvisg3e7";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1316991514),
(0, '1vu8panvmktve6sstobshnu097_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"tcp6soltjfulv3st1q7pull7i2";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1317300993),
(0, '4qtqa5o9p6peg0jl4au78sd6p1_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"dq2fqh4sftqo0moklrc0n306m2";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1317284175),
(0, '5f250modqobgvvk69c2vkoo553_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"tb71ppmvihhbdushrsrkmfqu93";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1317281962),
(0, '5r4ttrpdprf2vk4ef01to34a74_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"jo2u0ean4ukg524hkhf6484sa6";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1317284274),
(0, '6m5741h8f907jgbevqt6v1lf33_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"3dashd66f74eq7op36m5vucoi2";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1316939633),
(0, 'ReviewModelCronTimestamp', 'i:1313696787;', 2147483647),
(0, 'ectdnf15u5js78e1oraljm2s00_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"5o106dek271emanpac06dmjba6";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1317282615),
(0, 'h1l2mcld24hnoe9d3ghmelum56_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"02b1r2bjmssflkrkug1n0i0gm7";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1317286702),
(0, 'monflnkgegu7gdntn6u7pdq3n6_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"3j64qjsk68gme9favbr8mr2bd1";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1317285773),
(0, 'qnsm5l4pl00m169nlkbv6h9kh4_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"k58ksk7kt02du1u6tkh7jg8197";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1317016206),
(0, 's5o5lahq459l2fdcjhn2sntp93_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"9tm3oc81gbfa72e5n594ft89m6";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1316895111),
(0, 'ts67niljkkcq8r10j7q5cnpcl4_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"4jmnf5uqii7ls2gg7eabnadlv3";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1317019021),
(0, 'un5pmnm9pkd2vqkfmfegta3o72_https://10.32.0.19_cookies', 'a:1:{i:0;a:6:{s:4:"name";s:17:"jFrameworkSession";s:5:"value";s:26:"2a7hf135po52gm1mk3ookeh8h2";s:6:"domain";s:0:"";s:4:"path";s:1:"/";s:7:"expires";s:0:"";s:6:"secure";b:0;}}', 1317285303);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `jf_rbac_permissions`
--

INSERT INTO `jf_rbac_permissions` (`ID`, `Left`, `Right`, `Title`, `Description`) VALUES
(0, 1, 36, 'root', 'root'),
(1, 2, 35, 'Review', ''),
(2, 33, 34, 'CreateUser', ''),
(3, 17, 32, 'Reports', ''),
(4, 15, 16, 'Correspondence', ''),
(5, 13, 14, 'Reviewer', ''),
(6, 11, 12, 'Archive', ''),
(7, 9, 10, 'MasterHand', 'who can view manager menu'),
(8, 7, 8, 'Raked', 'Raked Archive '),
(9, 5, 6, 'Reassign', 'Reassigns Files to Reviewers '),
(10, 3, 4, 'CotagBook', 'daftare cotags Permision '),
(17, 30, 31, 'CotagList', 'see list of cotages and their last progress'),
(18, 28, 29, 'ProgressList', 'see list of progress for a spc Cotag '),
(19, 26, 27, 'AssignedList', 'see LIst of Assigned Declares and their Reviewer '),
(20, 24, 25, 'NotReceivedInCotagBook', 'see cotages which not received in cotag book yet '),
(21, 22, 23, 'AssignableList', ''),
(22, 20, 21, 'ExitInfo', 'see information from exit door server'),
(23, 18, 19, 'NotArchivedList', '');

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
(7, 8, 1316077945),
(3, 17, 1316778499),
(4, 17, 1316778499),
(5, 17, 1316778499),
(7, 17, 1316778499),
(8, 17, 1316778499),
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
(2, 23, 1316786601);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `jf_rbac_roles`
--

INSERT INTO `jf_rbac_roles` (`ID`, `Left`, `Right`, `Title`, `Description`) VALUES
(0, 1, 16, 'root', 'root'),
(1, 2, 15, 'Review', ''),
(2, 13, 14, 'Review_Admin', ''),
(3, 11, 12, 'Review_Reviewer', ''),
(4, 9, 10, 'Review_Archive', ''),
(5, 7, 8, 'Review_Correspondence', ''),
(7, 5, 6, 'Review_Raked', ''),
(8, 3, 4, 'Review_CotagBook', '');

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
(45, 0, 1313617832),
(46, 3, 1316076853),
(47, 8, 1316077142),
(48, 7, 1316077781),
(49, 4, 1316077810),
(50, 2, 1316077832),
(51, 5, 1316077862),
(52, 2, 1316234970),
(52, 7, 1316234970),
(52, 4, 1316234970),
(52, 8, 1316234970),
(52, 5, 1316234970),
(52, 3, 1316234970),
(53, 2, 1316290254),
(53, 7, 1316290254),
(53, 4, 1316290254),
(53, 8, 1316290254),
(53, 5, 1316290254),
(53, 3, 1316290254),
(54, 2, 1316325416),
(54, 7, 1316325416),
(54, 4, 1316325416),
(54, 8, 1316325416),
(54, 5, 1316325416),
(54, 3, 1316325416),
(55, 7, 1316333860),
(55, 4, 1316333860),
(55, 8, 1316333860),
(55, 5, 1316333860),
(55, 3, 1316333860),
(56, 8, 1316493634),
(57, 4, 1316504601),
(58, 7, 1316606712),
(58, 4, 1316606712),
(58, 8, 1316606712),
(58, 5, 1316606712),
(58, 3, 1316606712),
(59, 2, 1316673974),
(60, 3, 1316674083),
(61, 3, 1316674126),
(62, 3, 1316674161),
(63, 3, 1316674201),
(64, 3, 1316674240),
(65, 3, 1316674287),
(66, 3, 1316674352),
(67, 3, 1316674383),
(68, 3, 1316674430),
(69, 3, 1316674462),
(70, 3, 1316674514),
(72, 3, 1316674592),
(73, 3, 1316674621),
(74, 3, 1316674693),
(75, 3, 1316674731),
(76, 4, 1316675257),
(77, 3, 1316678617);

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
) ENGINE=MEMORY  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=65 ;

--
-- Dumping data for table `jf_sessions`
--

INSERT INTO `jf_sessions` (`ID`, `SessionID`, `UserID`, `IP`, `LoginDate`, `LastAccess`, `AccessCount`, `CurrentRequest`) VALUES
(59, 'uhqq3q2q3ttvmi8jrp4oseu6t7', 45, '10.32.32.202', 1316786376, 1316786761, 34, 'sys/panel/dashboard'),
(64, '69o20qhnl24stll8mbafcq5ha2', 56, '10.32.32.192', 1316786710, 1316787170, 31, 'cotag/deliver'),
(63, 'vcaib1n01rf70mrfp5t1ntgv30', 0, '10.32.32.192', 1316786678, 1316786678, 3, 'user/logout'),
(62, '5ofrfpag9vm4n4mi9oicjsts12', 0, '10.32.32.192', 1316786678, 1316786678, 2, 'user/logout'),
(61, '9kt8chibvhs08mbgfe5rppder0', 0, '10.32.32.192', 1316786677, 1316786678, 4, 'user/logout'),
(60, '6bt16gerem9uqoeq48j33les46', 56, '10.32.32.192', 1316786638, 1316786677, 7, 'user/logout');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=78 ;

--
-- Dumping data for table `jf_users`
--

INSERT INTO `jf_users` (`ID`, `Username`, `Password`, `discriminator`) VALUES
(1, 'root', '119ba00fd73711a09fa82177f48f4e4ac32b1e1d73925fc4f654851b617b2a96fd5a5b3095d59b59e5cdfd71312ba3f61195414758478feced69544447360003', 'User'),
(45, 'admin', 'bf4bbd656d9743ccfa1d18d6361a5f91e2551a1af3c4afa143de08904ac232592327b1af1b21b5773ba68e4f62fa77157e76b35a6be5fca6db87089ac3ec3f10', 'MyUser'),
(46, 'bazbin', '4c95af5285691a975a49eac526b066bbc2ed8b5acdcfe80790395d50c1c761cb561f16bb05012302ddc77c487920c0aad9a160d310119158ee4bcb27c405215e', 'MyUser'),
(47, 'cotag', '3cfdfc82869ff8e060b5d5db94e040de03be0090997411596694176dbd6e84590c04cef20f7f8c80a04bf520eff570a25f951cccd7d44873d798c3c40865a0f9', 'MyUser'),
(48, 'raked', '1d7fd275d2ebc3c6681262c8bde1b35841c338ea760f9a9ab10e4e86b6e85bc50317d8ee2ef1d16414ba48939dac5fb15f47829230e632d57fa681a680048cd5', 'MyUser'),
(49, 'archive', 'b2abd13d7a54977c9817db109b9e57ded61fbad07f9bd66b5ad428e29d614351994711ceb7e507cd28a5663af47e12675ba91aa4a0c3093cf757ccb86776ef77', 'MyUser'),
(51, 'mokatebat', 'f73cec3e972902a6b1ddbd34d5ac495bd0c29ec4f28e4bc301f426015c30582c0cb3fdb27598406d8dac885a6d0a899a572ee22dcdf1884ba8bbb7c163e3a68c', 'MyUser'),
(53, 'modir', '75b721f031380f690db4954535f11fc7e033ba8ae53df949417be20e5370d042ebbc273d11d83efdddb5156ab95df39e622cc51ac37f65a85d22d9983402a565', 'MyUser'),
(54, 'kamantorki', 'b551ff3f7ea47631de531a616ca78941b2f0ebcc3409dd9f09cb5275215f5bdedd31d65d66743ddcf3efbdf1032a08989e23c0f3c0fdcb6dda03787f6270103a', 'MyUser'),
(55, 'mmohebi', '239342db59e4d19f135f09d83c6b1bb1942484f5402b4aca1ec23e89331a2aa96a0bcf96e525ca2aed7d6fc8ed36f1fd88942158218b7595e09974e4b50cf41a', 'MyUser'),
(56, 'daftar', '83dfee68a0d63f08e6f10f7160e158f291765b44d42bb5ce88e1519812f9e81ce1e5edeaa1e73aec251e7535ab84479dbbf84e38a481ee71aed3f2eaa929bd44', 'MyUser'),
(57, 'godarzi', '172fe03c9e5b799417ab9ab3edbf8e7638228d56828a1e03798600fa6404ac66ddf3e88e85b0e686127327c98933320bc4f89d43cbeb6cd3386e0a00cd6c90e3', 'MyUser'),
(58, 'keva', '50f9aca16d6e1cf56431852a5b0bb7f7161927973e2eec55502ed0312578d67f517acce45dc6a8b2846cd2ec95ff1e86c6eaa8eec1a7ba264d74143eac432a96', 'MyUser'),
(59, 'mosavi', 'b415c0cebf29b390095826b45e4dac75f98d4d3fd42cdec46ff746d05d1ee6f4605755547736eb05c94ad2fce35d59ad3d8b75b196c4243d597837808eef32ad', 'MyUser'),
(60, 'sabri', 'd8c4396ac97750592ce220f4e13653217fabc29c4e9c7d756934ee74a625e18b366f15fa53c77ccde23b42f7be1e8285fadefa058f618244eea1043b0e9234b2', 'MyUser'),
(62, 'omidvar', '4e11860125585ff22bde86bd6687e63d146a8695a8a12708ec6c80b9e9dc2490c687a9a4b94908bbd0ad51805d4ed7e53810decb82adeb34e03025f247b022ea', 'MyUser'),
(63, 'karimi', '9dddbf69bee06ee971762a1d90dab82dfaf8e83ffb66d74b776e1a5eea7584c5754233e060ba4da03b3ee02f3bbc8c707cf57750cc786e74352db7065bba3b5e', 'MyUser'),
(64, 'kabiri', 'e7909997516bfd533dc6f41bb36302b746ac08ed23099bee8e59c735f1e34edb1c61b628e8e704c60d6788334d04130e2db65e952e2e49f11c98f77f6dc4af30', 'MyUser'),
(65, 'kargar', '41559408573ad83828bc4a729e7c51ff03b80c92883f6ec6b42bb3dfc24fe4f7f189674bcce83868b02500a741777a3df945324ad9d5b89518daab708647ced9', 'MyUser'),
(66, 'mahmoodi', 'c2b212bb85be1db4e02c29f2130008d45b834717070c2f98275769950cf3751182d38dbdd829976a8d3d153d245c4ef51a8adc8b28b40a7f5b98db6d94b415d9', 'MyUser'),
(67, 'haghi', '34e5709ccbadafd4a3b790ce897cca694b27be07a910836eec6c60ae07edf1c16e5d3f3f479cf5ead39bb7b6cc7be6f5867924f1ece64bb9faa4e56d13a981cf', 'MyUser'),
(68, 'mohsen', '34e7d7314d1fd3161e301eb477fa266e412e74c930063cf0dd9040a612e0609ae00e4a460ed23cc771d3232a6aecdae97022a8f41356cca05476e0b5e23276de', 'MyUser'),
(69, 'servati', 'ec82dfc354414400af5eeeb6ee52b9c15864cc56cd203a948e11543b3afce2d5b9dda5896e06bf9fd03d15b6a71a236bf8232000531e72467c14abf058d3de12', 'MyUser'),
(70, 'jamal', '60952ad43aacbd1f0ccc29288defbf9511aa21068fc5c8d941659141c74731fc91ce509e7d6b9f2ea00e43019a9dcf465382b7d119f4c92d230d6a27f8a80605', 'MyUser'),
(71, 'majid', 'f753ed6641af63c6ba2518a1c464d018ad2ed24ecdfef7803b515da443bb479a9ba0dd5830a11162b67e0537f95da550e46e57f73f722dde94c0d2364ff09c2f', 'MyUser'),
(72, 'saljooghi', '3af96983ffd9d14b1320c6282c0e50fc94fa1e0e8cf27806d8aa496e64c72fdb73aaa013351a0aa16faa79571b4b8874c461b2f9524be4b2de181af2df25c6e6', 'MyUser'),
(73, 'kamrani', 'c0d1356649331fac409d02aea9c2ed78b99e4354defbc34e482ec2f6d417492773c335cf07edba471969a3e9f1ca74d7e94a380bfa2142f6c92af6ddd5ef9586', 'MyUser'),
(74, 'hosein', 'b8b2be799a1b1b1135340b11c05a7c492a0158149afd4fd3cc4b7cea9e71522c05bb6db98d3476044eb64d509a351f9eddf40eb7ac5ab760fb8bc2211782b44d', 'MyUser'),
(75, 'goodarzi', '4c96ebed491436377b96665d717c516ec65576f94adba50d3dbb4fa88f320cd3e0d8aaf43a4e6239e51aeaa905befa9b5c023bc2f5a8a5f3a14c94f6d54c4b8e', 'MyUser'),
(76, 'kam', '8fc44c4b14713a15a4299b0a6ece124ef34aa3e43d2947413ff68d8d62386b9061a06aceb99c0c02865f243a70686ca393a574bef98c9b86f8206ee5c5200fdf', 'MyUser'),
(77, 'aslani', '85154732e0bfed15e9c003026e223639221aab10c84d3ee4bdc044d4cb337b8554e28f2c8a229c94c63afa9a1bb05c8ea54de4edf1a5f299fd745c55e24186ca', 'MyUser');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `app_MyUser`
--
ALTER TABLE `app_MyUser`
  ADD CONSTRAINT `app_myuser_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `jf_users` (`ID`) ON DELETE CASCADE;
