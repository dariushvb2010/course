-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2012 at 03:48 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

--
-- Dumping data for table `app_Config`
--

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
(29, 0, '', NULL, 'Event');



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
