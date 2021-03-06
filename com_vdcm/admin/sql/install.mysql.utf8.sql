-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 20, 2013 at 08:04 PM
-- Server version: 5.5.33
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `#__vjeecdcm_attachment`
--

DROP TABLE IF EXISTS `#__vjeecdcm_attachment`;
CREATE TABLE IF NOT EXISTS `#__vjeecdcm_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_path` varchar(250) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__vjeecdcm_diploma`
--

DROP TABLE IF EXISTS `#__vjeecdcm_diploma`;
CREATE TABLE IF NOT EXISTS `#__vjeecdcm_diploma` (
  `id` int(11) NOT NULL,
  `serial` varchar(25) DEFAULT NULL,
  `reference` varchar(25) DEFAULT NULL,
  `holder_name` varchar(50) DEFAULT NULL,
  `holder_birthday` date DEFAULT NULL,
  `holder_identity_type` varchar(50) DEFAULT NULL,
  `holder_identity_value` varchar(50) DEFAULT NULL COMMENT 'SIN number as an example',
  `degree_id` int(11) NOT NULL DEFAULT '0',
  `issuer` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `major_id` int(11) DEFAULT NULL,
  `study_mode` varchar(25) DEFAULT NULL,
  `electronic_version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__vjeecdcm_diploma`
--

INSERT INTO `#__vjeecdcm_diploma` (`id`, `serial`, `reference`, `holder_name`, `holder_birthday`, `holder_identity_type`, `holder_identity_value`, `degree_id`, `issuer`, `issue_date`, `major_id`, `study_mode`, `electronic_version`) VALUES
(0, '4224639', '08/403/33030272', 'NGUYỄN THỊ HẠNH', '1990-05-26', NULL, NULL, 0, 'THPT NGHI LỘC 3', '2008-07-15', NULL, NULL, NULL),
(1, NULL, NULL, 'NGÔ VĂN TRỌNG', NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `#__vjeecdcm_diploma_certification_request`
--

DROP TABLE IF EXISTS `#__vjeecdcm_diploma_certification_request`;
CREATE TABLE IF NOT EXISTS `#__vjeecdcm_diploma_certification_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `diploma_id` int(11) NOT NULL,
  `request_type` int(11) NOT NULL,
  `created_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `processing_status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `#__vjeecdcm_diploma_certification_request`
--

INSERT INTO `#__vjeecdcm_diploma_certification_request` (`id`, `user_id`, `diploma_id`, `request_type`, `created_date`, `completed_date`, `processing_status`) VALUES
(1, 585, 0, 0, '2013-06-28', '2013-08-08', 'VJEECDCM_REQUEST_PROCESS_SENT'),
(2, 585, 1, 0, '2013-06-28', '2013-08-08', 'VJEECDCM_REQUEST_PROCESS_SENT');

-- --------------------------------------------------------

--
-- Table structure for table `#__vjeecdcm_diploma_degree`
--

DROP TABLE IF EXISTS `#__vjeecdcm_diploma_degree`;
CREATE TABLE IF NOT EXISTS `#__vjeecdcm_diploma_degree` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `degree` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__vjeecdcm_diploma_degree`
--

INSERT INTO `#__vjeecdcm_diploma_degree` (`id`, `name`, `degree`) VALUES
(0, 'VJEEC_DCM_HIGH_SCHOOL_DIPLOMA_NAME', 'VJEEC_DCM_HIGH_SCHOOL_DEGREE'),
(1, 'VJEEC_DCM_UNIVERSITY_DIPLOMA_NAME', 'VJEEC_DCOM_UNIVERSITY_DEGREE'),
(2, 'VJEEC_DCM_PROFESSIONAL_DIPLOMA_NAME', 'VJEEC_DCM_PROFESSIONAL_DEGREE');

-- --------------------------------------------------------

--
-- Table structure for table `#__vjeecdcm_identification_info`
--

DROP TABLE IF EXISTS `#__vjeecdcm_identification_info`;
CREATE TABLE IF NOT EXISTS `#__vjeecdcm_identification_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `note` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__vjeecdcm_request_observer`
--

DROP TABLE IF EXISTS `#__vjeecdcm_request_observer`;
CREATE TABLE IF NOT EXISTS `#__vjeecdcm_request_observer` (
  `request_id` int(11) NOT NULL,
  `observer_id` int(11) NOT NULL,
  `Note` text,
  PRIMARY KEY (`request_id`,`observer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__vjeecdcm_request_observer`
--

INSERT INTO `#__vjeecdcm_request_observer` (`request_id`, `observer_id`, `Note`) VALUES
(1, 586, NULL),
(2, 586, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `#__vjeecdcm_request_processing_step`
--

DROP TABLE IF EXISTS `#__vjeecdcm_request_processing_step`;
CREATE TABLE IF NOT EXISTS `#__vjeecdcm_request_processing_step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) DEFAULT NULL,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `result` varchar(50) DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__vjeecdcm_school`
--

DROP TABLE IF EXISTS `#__vjeecdcm_school`;
CREATE TABLE IF NOT EXISTS `#__vjeecdcm_school` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `website` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__vjeecdcm_user_diploma`
--

DROP TABLE IF EXISTS `#__vjeecdcm_user_diploma`;
CREATE TABLE IF NOT EXISTS `#__vjeecdcm_user_diploma` (
  `user_id` int(11) NOT NULL,
  `diploma_id` int(11) NOT NULL,
  `registration_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `certified_by_vjeec` varchar(100) DEFAULT NULL,
  `certification_date` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `#__vjeecdcm_user_diploma`
--

INSERT INTO `#__vjeecdcm_user_diploma` (`user_id`, `diploma_id`, `registration_date`, `expiry_date`, `certified_by_vjeec`, `certification_date`, `id`) VALUES
(585, 1, '2013-07-26', NULL, 'VJEECDCM_DIPLOMA_CERTIFICATED_YES', '2013-08-06', 4),
(585, 0, '2013-07-26', '2014-02-08', 'VJEECDCM_DIPLOMA_CERTIFICATED_YES', '2013-08-08', 3);

-- --------------------------------------------------------

--
-- Table structure for table `#__vjeecdcm_user_info`
--

DROP TABLE IF EXISTS `#__vjeecdcm_user_info`;
CREATE TABLE IF NOT EXISTS `#__vjeecdcm_user_info` (
  `id` int(11) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__vjeecdcm_user_info`
--

INSERT INTO `#__vjeecdcm_user_info` (`id`, `address`) VALUES
(582, 'Hello World!'),
(584, 'Good bye World!');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
