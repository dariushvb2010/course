
CREATE TABLE IF NOT EXISTS `jfp_xuser` (
  `UserID` int(11) NOT NULL,
  `Email` varchar(256) COLLATE utf8_bin NOT NULL,
  `PasswordChangeTimestamp` int(11) NOT NULL DEFAULT '0',
  `TemporaryResetPassword` varchar(256) COLLATE utf8_bin NOT NULL DEFAULT '',
  `TemporaryResetPasswordTimeout` int(11) NOT NULL DEFAULT '0',
  `LastLoginTimestamp` int(11) NOT NULL DEFAULT '0',
  `FailedLoginAttempts` int(11) NOT NULL DEFAULT '0',
  `LockTimeout` int(16) NOT NULL DEFAULT '0',
  `Activated` int(11) NOT NULL DEFAULT '0',
  `CreateTimestamp` int(11) NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
