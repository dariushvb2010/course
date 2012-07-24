-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 18, 2012 at 05:13 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bazbini`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_Alarm`
--

CREATE TABLE IF NOT EXISTS `app_Alarm` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CreateTimestamp` int(11) NOT NULL,
  `FileID` int(11) DEFAULT NULL,
  `MotherUserID` int(11) DEFAULT NULL,
  `MotherEventID` int(11) DEFAULT NULL,
  `Type` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_638DE41493076D5B` (`FileID`),
  KEY `IDX_638DE414466B29D` (`MotherUserID`),
  KEY `IDX_638DE41499C80E9B` (`MotherEventID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=14240 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_AlarmAuto`
--

CREATE TABLE IF NOT EXISTS `app_AlarmAuto` (
  `ConfigAlarmID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_ED8EE494496BB17A` (`ConfigAlarmID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_AlarmFree`
--

CREATE TABLE IF NOT EXISTS `app_AlarmFree` (
  `Title` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Context` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `Moratorium` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_AlarmFree_Group`
--

CREATE TABLE IF NOT EXISTS `app_AlarmFree_Group` (
  `AlarmFreeID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  PRIMARY KEY (`AlarmFreeID`,`GroupID`),
  KEY `IDX_43FA28A7B06B98F3` (`AlarmFreeID`),
  KEY `IDX_43FA28A7195291E4` (`GroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_AlarmFree_KillerEvent`
--

CREATE TABLE IF NOT EXISTS `app_AlarmFree_KillerEvent` (
  `AlarmFreeID` int(11) NOT NULL,
  `KillerID` int(11) NOT NULL,
  PRIMARY KEY (`AlarmFreeID`,`KillerID`),
  KEY `IDX_1F01126AB06B98F3` (`AlarmFreeID`),
  KEY `IDX_1F01126A27889743` (`KillerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_AlarmFree_User`
--

CREATE TABLE IF NOT EXISTS `app_AlarmFree_User` (
  `AlarmFreeID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`AlarmFreeID`,`UserID`),
  KEY `IDX_3F23534B06B98F3` (`AlarmFreeID`),
  KEY `IDX_3F2353458746832` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_Config`
--

CREATE TABLE IF NOT EXISTS `app_Config` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DeleteAccess` tinyint(1) NOT NULL,
  `Comment` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `Style` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `Type` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQ_362547CAF27C976E` (`Style`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_ConfigAlarm`
--

CREATE TABLE IF NOT EXISTS `app_ConfigAlarm` (
  `Title` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Context` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `Moratorium` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ConfigAlarm_Group`
--

CREATE TABLE IF NOT EXISTS `app_ConfigAlarm_Group` (
  `ConfigAlarmID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  PRIMARY KEY (`ConfigAlarmID`,`GroupID`),
  KEY `IDX_DBC96ED7496BB17A` (`ConfigAlarmID`),
  KEY `IDX_DBC96ED7195291E4` (`GroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ConfigAlarm_KillerEvent`
--

CREATE TABLE IF NOT EXISTS `app_ConfigAlarm_KillerEvent` (
  `ConfigAlarmID` int(11) NOT NULL,
  `KillerID` int(11) NOT NULL,
  PRIMARY KEY (`ConfigAlarmID`,`KillerID`),
  KEY `IDX_D20E0C45496BB17A` (`ConfigAlarmID`),
  KEY `IDX_D20E0C4527889743` (`KillerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ConfigAlarm_MakerEvent`
--

CREATE TABLE IF NOT EXISTS `app_ConfigAlarm_MakerEvent` (
  `ConfigAlarmID` int(11) NOT NULL,
  `MakerID` int(11) NOT NULL,
  PRIMARY KEY (`ConfigAlarmID`,`MakerID`),
  KEY `IDX_CAC26A83496BB17A` (`ConfigAlarmID`),
  KEY `IDX_CAC26A839E7D9BAE` (`MakerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ConfigAlarm_User`
--

CREATE TABLE IF NOT EXISTS `app_ConfigAlarm_User` (
  `ConfigAlarmID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`ConfigAlarmID`,`UserID`),
  KEY `IDX_E294F976496BB17A` (`ConfigAlarmID`),
  KEY `IDX_E294F97658746832` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ConfigEvent`
--

CREATE TABLE IF NOT EXISTS `app_ConfigEvent` (
  `EventName` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `PersianName` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQ_E893A02320448FB2` (`EventName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ConfigMain`
--

CREATE TABLE IF NOT EXISTS `app_ConfigMain` (
  `Name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Value` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ID` int(11) NOT NULL,
  `PersianName` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQ_5476B203FE11D138` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `app_ConfigMain`
--

INSERT INTO `app_ConfigMain` (`Name`, `Value`, `ID`, `PersianName`) VALUES
('ClassNum528', '41423', 31, 'شماره کلاسه 528'),
('ClassNum248', '5821', 32, 'شماره کلاسه 248'),
('ClassNum109', '4921', 33, 'شماره کلاسه 109');

-- --------------------------------------------------------

--
-- Table structure for table `app_FileState`
--

CREATE TABLE IF NOT EXISTS `app_FileState` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Num` int(11) NOT NULL,
  `Str` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Summary` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Place` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_FileStock`
--

CREATE TABLE IF NOT EXISTS `app_FileStock` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EditTimestamp` int(11) NOT NULL,
  `Act` tinyint(1) NOT NULL,
  `IfSaveGet` tinyint(1) NOT NULL,
  `Error` varchar(255) DEFAULT NULL,
  `FileID` int(11) DEFAULT NULL,
  `MailID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQ_74B0BC8793076D5B` (`FileID`),
  KEY `IDX_74B0BC87F1BF1DE4` (`MailID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36176 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_Mail`
--

CREATE TABLE IF NOT EXISTS `app_Mail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Num` varchar(255) NOT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `State` int(11) NOT NULL,
  `CloseTimestamp` int(11) DEFAULT NULL,
  `RetouchTimestamp` int(11) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Type` varchar(255) NOT NULL,
  `SenderGroupID` int(11) DEFAULT NULL,
  `ReceiverTopicID` int(11) DEFAULT NULL,
  `ReceiverGroupID` int(11) DEFAULT NULL,
  `SenderTopicID` int(11) DEFAULT NULL,
  `GiverGroupID` int(11) DEFAULT NULL,
  `GetterGroupID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_F43A26D6ADD4038D` (`SenderGroupID`),
  KEY `IDX_F43A26D6596A393D` (`ReceiverTopicID`),
  KEY `IDX_F43A26D6560B5B76` (`ReceiverGroupID`),
  KEY `IDX_F43A26D6A2B561C6` (`SenderTopicID`),
  KEY `IDX_F43A26D6935FD9EE` (`GiverGroupID`),
  KEY `IDX_F43A26D6B29DD266` (`GetterGroupID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1790 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_MyGroup`
--

CREATE TABLE IF NOT EXISTS `app_MyGroup` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `PersianTitle` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQ_13E38B5FEAF7576F` (`Title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `app_MyGroup`
--

INSERT INTO `app_MyGroup` (`ID`, `Title`, `PersianTitle`, `Description`) VALUES
(1, 'Admin', 'مدیریت', NULL),
(2, 'Archive', 'بایگانی بازبینی', NULL),
(3, 'CotagBook', 'دفتر کوتاژ', NULL),
(4, 'Raked', 'بایگانی راکد', NULL),
(5, 'Reviewer', 'کارشناسان', NULL),
(6, 'Correspondence', 'مکاتبات', NULL),
(7, 'Typist', 'تایپیست', NULL),
(8, 'Nazer', 'Nazer', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `app_MySetting`
--

CREATE TABLE IF NOT EXISTS `app_MySetting` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ShowFirstname` tinyint(1) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Type` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_1F017B0A58746832` (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `app_MySetting`
--

INSERT INTO `app_MySetting` (`ID`, `ShowFirstname`, `UserID`, `Type`) VALUES
(1, 1, 54, 'Manager'),
(2, 1, 45, 'Manager'),
(3, 1, 100000, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `app_MySettingManager`
--

CREATE TABLE IF NOT EXISTS `app_MySettingManager` (
  `ShowRetireds` tinyint(1) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `app_MySettingManager`
--

INSERT INTO `app_MySettingManager` (`ShowRetireds`, `ID`) VALUES
(0, 1),
(1, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `app_MyUser`
--

CREATE TABLE IF NOT EXISTS `app_MyUser` (
  `Firstname` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Lastname` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `State` int(11) NOT NULL,
  `isReviewer` tinyint(1) NOT NULL,
  `Group1ID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_1F20F144EB815951` (`Group1ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `app_MyUser`
--

INSERT INTO `app_MyUser` (`Firstname`, `Lastname`, `State`, `isReviewer`, `Group1ID`, `ID`, `gender`) VALUES
('عباس', 'نادری', 1, 1, 1, 45, 0);

-- --------------------------------------------------------

--
-- Table structure for table `app_ProcessAssign`
--

CREATE TABLE IF NOT EXISTS `app_ProcessAssign` (
  `ExpertID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_80E91BA343ECA10E` (`ExpertID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewCorrespondenceTopic`
--

CREATE TABLE IF NOT EXISTS `app_ReviewCorrespondenceTopic` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Topic` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Comment` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewDossier`
--

CREATE TABLE IF NOT EXISTS `app_ReviewDossier` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Andicator` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewFile`
--

CREATE TABLE IF NOT EXISTS `app_ReviewFile` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Cotag` int(11) NOT NULL,
  `CreateTimestamp` int(11) NOT NULL,
  `FinishTimestamp` int(11) NOT NULL,
  `Class` int(11) NOT NULL,
  `Gatecode` int(11) NOT NULL,
  `State` int(11) NOT NULL,
  `DossierID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQ_74B724B1A32F9E9D` (`DossierID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=84447 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewImages`
--

CREATE TABLE IF NOT EXISTS `app_ReviewImages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `PID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_A4BABAA2C3F78667` (`PID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessConfirm`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessConfirm` (
  `Confirm_ConfirmResult` tinyint(1) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessFeedback`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessFeedback` (
  `FeedbackOffice` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `feedback_FeedbackResult` tinyint(1) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessForward`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessForward` (
  `ForwardOffice` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Forward_Setad` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessJudgement`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessJudgement` (
  `Judgement_Result` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Judgment_JudgementSetad` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessP1415`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessP1415` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessPayment`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessPayment` (
  `PaymentValue` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessProphecy`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessProphecy` (
  `ProphecyStep` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessProtest`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessProtest` (
  `ProtestRequest` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessRefund`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessRefund` (
  `Refund_id` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessRegister`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessRegister` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProcessSenddemand`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProcessSenddemand` (
  `Senddemand_DemandStep` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgress`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgress` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CreateTimestamp` int(11) NOT NULL,
  `EditTimestamp` int(11) NOT NULL,
  `Comment` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `MailNum` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `PrevState` int(11) NOT NULL,
  `Dead` tinyint(1) NOT NULL,
  `FileID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Type` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_264F2FBC93076D5B` (`FileID`),
  KEY `IDX_264F2FBC58746832` (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=324588 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressAssign`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressAssign` (
  `ReviewerID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_A4A0B57524C20335` (`ReviewerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressClasseconfirm`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressClasseconfirm` (
  `Confirm` tinyint(1) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressCorrection`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressCorrection` (
  `OldCotag` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `NewCotag` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressEbtal`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressEbtal` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressGet`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressGet` (
  `ProgressGiveID` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQ_FD4D0BAEC21B6E7A` (`ProgressGiveID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressGive`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressGive` (
  `MailGiveID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_E565225896594426` (`MailGiveID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressReceive`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressReceive` (
  `MailReceiveID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_EE1BA5E044C846BF` (`MailReceiveID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressRegisterarchive`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressRegisterarchive` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressRemove`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressRemove` (
  `SlainID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNIQ_BE0201E45892EE` (`SlainID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressReview`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressReview` (
  `Result` tinyint(1) NOT NULL,
  `Provision` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Difference` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Amount` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressSend`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressSend` (
  `MailSendID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_C618B33B5F81FCED` (`MailSendID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewProgressStart`
--

CREATE TABLE IF NOT EXISTS `app_ReviewProgressStart` (
  `IsPrint` tinyint(1) NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_ReviewTopic`
--

CREATE TABLE IF NOT EXISTS `app_ReviewTopic` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Topic` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Comment` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `Type` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=65 ;

--
-- Dumping data for table `app_ReviewTopic`
--

INSERT INTO `app_ReviewTopic` (`ID`, `Topic`, `Comment`, `Type`) VALUES
(2, 'مکاتبات سرویس', '', 'rajaie'),
(3, 'مکاتبات ارزش', '', 'rajaie'),
(4, 'پته و پروانه', '', 'rajaie'),
(5, 'قضایی', '', 'rajaie'),
(6, 'حراست', '', 'rajaie'),
(7, 'صادرات', '', 'rajaie'),
(8, 'نظارت', '', 'rajaie'),
(9, 'احراز هویت', '', 'rajaie'),
(10, 'سرویس ارزیابی', '', 'rajaie'),
(11, 'بازبینی', '', 'iran'),
(12, 'دفتر ارزش', '', 'iran'),
(13, 'تعیین تعرفه', '', 'iran'),
(14, 'ارزیابی عملکرد', '', 'iran'),
(15, 'معاونت حقوقی', '', 'iran'),
(16, 'گمرک کرمان', '', 'othergates'),
(17, 'ترانزیت', 'ال', 'rajaie'),
(18, 'گمرک آستارا', 'آذربایجان شرقی', 'othergates'),
(19, 'گمرک آستارا', 'آذربایجان شرقی', 'othergates'),
(20, 'گمرک فارس', '', 'othergates'),
(21, 'استان مركزي', '', 'othergates'),
(22, 'سيرجان', '', 'othergates'),
(23, 'كنگان', '', 'othergates'),
(24, 'تهران', '', 'othergates'),
(25, 'البرز', '', 'othergates'),
(26, 'سرخس', '', 'othergates'),
(27, 'اصفهان', '', 'othergates'),
(28, 'مشهد', '', 'othergates'),
(29, 'يزد', '', 'othergates'),
(30, 'لار', '', 'othergates'),
(31, 'همدان', '', 'othergates'),
(32, 'اهواز', '', 'othergates'),
(33, 'لرستان', '', 'othergates'),
(34, 'قم', '', 'othergates'),
(35, 'ساري', '', 'othergates'),
(36, 'آذربايجان شرقي', '', 'othergates'),
(37, 'آبادان', '', 'othergates'),
(38, 'كهكيلويه و بوير احمد', '', 'othergates'),
(39, 'بوشهر', '', 'othergates'),
(40, 'شهركرد', '', 'othergates'),
(41, 'آذربايجان غربي', '', 'othergates'),
(42, 'بازرسي كل كشور', '', 'other'),
(45, 'بایگانی راکد', 'حیاتی ، برای ارسال ها ، پاک نشود', 'raked'),
(46, 'بایگانی بازبینی', 'حیاتی ، برای ارسال ها ، پاک نشود', 'archive'),
(47, 'امورمالي', '', 'rajaie'),
(48, 'بم', '', 'othergates'),
(49, 'قزوين', '', 'othergates'),
(50, 'دفتر واردات', '', 'iran'),
(51, 'بندر  امام خميني(ره)', '', 'othergates'),
(52, 'اردبيل', '', 'othergates'),
(53, 'دفتر صادرات', '', 'iran'),
(54, 'سمنان', '', 'othergates'),
(55, 'كردستان', '', 'othergates'),
(56, 'دوغارون', '', 'othergates'),
(57, 'بانه', '', 'othergates'),
(58, 'كرمانشاه', '', 'othergates'),
(59, 'زنجان', '', 'othergates'),
(60, 'نقده', '', 'othergates'),
(61, 'باجگیران', '', 'othergates'),
(62, 'مکاتبات بازبینی', '', 'rajaie'),
(63, 'زاهدان', '', 'othergates'),
(64, 'بیله سوار', '', 'othergates');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1775 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `jf_rbac_rolepermissions`
--

CREATE TABLE IF NOT EXISTS `jf_rbac_rolepermissions` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`RoleID`,`PermissionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `jf_rbac_userroles`
--

CREATE TABLE IF NOT EXISTS `jf_rbac_userroles` (
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`RoleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jf_rbac_userroles`
--

INSERT INTO `jf_rbac_userroles` (`UserID`, `RoleID`, `AssignmentDate`) VALUES
(1, 0, 2009),
(43, 0, 1313595777),
(45, 0, 1342615127);

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
) ENGINE=MEMORY  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

--
-- Dumping data for table `jf_sessions`
--

INSERT INTO `jf_sessions` (`ID`, `SessionID`, `UserID`, `IP`, `LoginDate`, `LastAccess`, `AccessCount`, `CurrentRequest`) VALUES
(1, 'dgkne6ffh9d71804vun9pcdb07', 45, '192.168.1.138', 1342614388, 1342615023, 17, 'user/logout'),
(2, '3skk614qjsigno37cge7c386j1', 0, '', 1342614922, 1342614922, 1, ''),
(3, 'kqutq79l6hj8g80f73q90k7q60', 1, '192.168.1.138', 1342615035, 1342615061, 9, 'user/logout'),
(4, '2pgdeo82uv4ir8ghknn7p2hbv1', 1, '192.168.1.138', 1342615095, 1342615140, 12, 'user/logout'),
(5, 'mb24flv0vc2006acrm322j7861', 45, '192.168.1.138', 1342615151, 1342615349, 7, 'img1/alarm/alarm-back.png');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=100014 ;

--
-- Dumping data for table `jf_users`
--

INSERT INTO `jf_users` (`ID`, `Username`, `Password`, `discriminator`) VALUES
(1, 'root', '119ba00fd73711a09fa82177f48f4e4ac32b1e1d73925fc4f654851b617b2a96fd5a5b3095d59b59e5cdfd71312ba3f61195414758478feced69544447360003', 'User'),
(45, 'abbas', 'e3110a4037859d441e7795c40ceced7c2e7360f72dc865253629d5ba7872dbd06bc36e6beffdbd0d8b4b7e722285e7eb2e81009343f11ab592c9503dd2d5e414', 'MyUser');       

INSERT INTO `app_Config` (`ID`, `DeleteAccess`, `Comment`, `Style`, `Type`) VALUES
(1, 1, NULL, NULL, 'Event'),
(2, 1, NULL, NULL, 'Event'),
(3, 1, NULL, NULL, 'Event'),
(4, 1, NULL, NULL, 'Event'),
(5, 1, NULL, NULL, 'Event'),
(6, 1, NULL, NULL, 'Event'),
(7, 1, NULL, NULL, 'Event'),
(8, 1, NULL, NULL, 'Event'),
(9, 1, NULL, NULL, 'Event'),
(10, 1, NULL, NULL, 'Event'),
(11, 1, NULL, NULL, 'Event'),
(12, 1, NULL, NULL, 'Event'),
(13, 1, NULL, NULL, 'Event'),
(14, 1, NULL, NULL, 'Event'),
(15, 1, NULL, NULL, 'Event'),
(16, 1, NULL, NULL, 'Event'),
(17, 1, NULL, NULL, 'Event'),
(18, 1, NULL, NULL, 'Event'),
(19, 1, NULL, NULL, 'Event'),
(20, 1, NULL, NULL, 'Event'),
(21, 1, NULL, NULL, 'Event'),
(22, 1, NULL, NULL, 'Event'),
(23, 1, NULL, NULL, 'Event'),
(24, 1, NULL, NULL, 'Event'),
(25, 1, NULL, NULL, 'Event'),
(26, 1, NULL, NULL, 'Event'),
(27, 1, NULL, NULL, 'Event'),
(28, 0, '', NULL, 'Event'),
(29, 0, '', NULL, 'Event'),
(30, 0, '', NULL, 'Alarm'),
(31, 0, 'Ø¨Ø®Ø´ Ù…Ú©Ø§ØªØ¨Ø§Øª', NULL, 'Main'),
(32, 0, 'Ø¨Ø®Ø´ Ù…Ú©Ø§ØªØ¨Ø§Øª', NULL, 'Main'),
(33, 0, 'Ø¨Ø®Ø´ Ù…Ú©Ø§ØªØ¨Ø§Øª', NULL, 'Main');

-- --------------------------------------------------------
--
-- Dumping data for table `app_ConfigEvent`
--

INSERT INTO `app_ConfigEvent` (`EventName`, `PersianName`, `ID`) VALUES
('Senddemand_demand', 'ارسال مطالبه نامه', 1),
('Senddemand_setad', 'ارسال رای دفاتر ستادی به صاحب کالا', 2),
('Senddemand_karshenas', 'ارسال نظر کارشناس', 3),
('Refund', 'استرداد', 4),
('Judgement_ok', 'قبول اعتراض', 5),
('Judgement_nok', 'رد اعتراض', 6),
('Judgement_commission', 'نظر کارشناس ارسال به کمیسیون', 7),
('Judgement_setad', 'نظر کارشناس ارسال به دفاتر ستادی', 8),
('Forward_commission', 'ارسال پرونده به کمیسیون', 9),
('Forward_setad', 'ارسال به دفاتر ستادی', 10),
('Forward_appeals', 'ارسال به کمیسیون تجدید نظر', 11),
('Feedback_appeals_toowner', 'رای کمیسون تجدید نظر به نفع صاحب کالا', 12),
('Feedback_appeals_togomrok', 'رای کمیسون تجدید نظر به نفع گمرک', 13),
('Feedback_commission_toowner', 'رای کمیسون به نفع صاحب کالا', 14),
('Feedback_commission_togomrok', 'رای کمیسون به نفع گمرک', 15),
('Feedback_setad_toowner', 'رای دفاتر ستادی به نفع صاحب کالا', 16),
('Feedback_setad_togomrok', 'رای دفاتر ستادی به نفع گمرک', 17),
('ProcessConfirm_ok', 'تایید مدیر', 18),
('ProcessConfirm_nok', 'عدم تایید مدیر', 19),
('Prophecy_first', 'ثبت ابلاغ مطالبه نامه', 20),
('Prophecy_second', 'ثبت ابلاغ ثانویه', 21),
('Prophecy_setad', 'ابلاغ رای دفاتر ستادی', 22),
('Prophecy_commission', 'ابلاغ رای کمیسیون', 23),
('ProcessRegister', 'ثبت کلاسه', 24),
('ProcessAssign', 'تحویل به کارشناس', 25),
('Payment', 'تمکین و پرداخت', 26),
('Protest', 'اعتراض صاحب کالا', 27),
('Start', 'وصول دفتر کوتاژ', 28),
('Registerarchive', 'وصول بایگانی بازبینی', 29);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
