-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2012 at 02:14 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

truncate table jf_rbac_rolepermissions;

truncate table jf_rbac_permissions;

truncate table jf_rbac_roles;

--
-- Dumping data for table `jf_rbac_roles`
--

INSERT INTO `jf_rbac_roles` (`ID`, `Left`, `Right`, `Title`, `Description`) VALUES
(0, 1, 22, 'root', 'root'),
(1, 4, 21, 'Review', ''),
(2, 19, 20, 'Review_Admin', ''),
(3, 17, 18, 'Review_Reviewer', ''),
(4, 15, 16, 'Review_Archive', ''),
(5, 13, 14, 'Review_Correspondence', ''),
(7, 11, 12, 'Review_Raked', ''),
(8, 9, 10, 'Review_CotagBook', ''),
(9, 2, 3, 'Programmer', ''),
(10, 7, 8, 'Review_Typist', ''),
(11, 5, 6, 'Review_Nazer', 'who cant change but view only');

--
-- Dumping data for table `jf_rbac_permissions`
--

INSERT INTO `jf_rbac_permissions` (`ID`, `Left`, `Right`, `Title`, `Description`) VALUES
(0, 1, 52, 'root', 'root'),
(1, 2, 51, 'Review', ''),
(2, 49, 50, 'CreateUser', ''),
(3, 31, 48, 'Reports', ''),
(4, 29, 30, 'Correspondence', ''),
(5, 27, 28, 'Reviewer', ''),
(6, 25, 26, 'Archive', ''),
(7, 23, 24, 'MasterHand', 'who can view manager menu'),
(8, 21, 22, 'Raked', 'Raked Archive '),
(9, 19, 20, 'Reassign', 'Reassigns Files to Reviewers '),
(10, 17, 18, 'CotagBook', 'daftare cotags Permision '),
(17, 46, 47, 'CotagList', 'see list of cotages and their last progress'),
(18, 44, 45, 'ProgressList', 'see list of progress for a spc Cotag '),
(19, 42, 43, 'AssignedList', 'see LIst of Assigned Declares and their Reviewer '),
(20, 40, 41, 'NotReceivedInCotagBook', 'see cotages which not received in cotag book yet '),
(21, 38, 39, 'AssignableList', ''),
(22, 36, 37, 'ExitInfo', 'see information from exit door server'),
(23, 34, 35, 'NotArchivedList', ''),
(24, 3, 16, 'Alarm', ''),
(25, 14, 15, 'AlarmToAdmin', ''),
(26, 12, 13, 'AlarmToArchive', ''),
(27, 10, 11, 'AlarmToCotagBook', ''),
(28, 8, 9, 'AlarmToReviewer', ''),
(29, 6, 7, 'AlarmToRaked', ''),
(30, 4, 5, 'AlarmToCorrespondence', ''),
(31, 32, 33, 'TypistHelp', '');



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
(11, 3, 1338038851);

