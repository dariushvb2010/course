-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2012 at 02:14 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


truncate table jf_rbac_permissions;
--
-- Dumping data for table `jf_rbac_permissions`
--

INSERT INTO `jf_rbac_permissions` (`ID`, `Left`, `Right`, `Title`, `Description`) VALUES
(0, 1, 2, 'root', 'root');

-- --------------------------------------------------------

truncate table jf_rbac_roles;
--
-- Dumping data for table `jf_rbac_roles`
--

INSERT INTO `jf_rbac_roles` (`ID`, `Left`, `Right`, `Title`, `Description`) VALUES
(0, 1, 2, 'root', 'root');


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
(0, 0, 2009);

-- --------------------------------------------------------


