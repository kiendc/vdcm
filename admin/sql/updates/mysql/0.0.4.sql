DROP TABLE IF EXISTS `#__vjeecdcm_user_info`;
 
CREATE TABLE `#__vjeecdcm_user_info` (
  `id` int(11) NOT NULL,
  `address` varchar(100),
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 
INSERT INTO `#__vjeecdcm_user_info` (`id`,`address`) VALUES
        (582, 'Hello World!'),
        (584, 'Good bye World!');



DROP TABLE IF EXISTS `#__vjeecdcm_diploma`;

CREATE TABLE `#__vjeecdcm_diploma` (
	`id` int(11) NOT NULL,
	`name` varchar(100),
	`degree` varchar(100),
	PRIMARY KEY (`id`)
) ENGINE=myISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__vjeecdcm_diploma` (`id`, `name`, `degree`) VALUES
       (0, 'VJEEC_DCM_HIGH_SCHOOL_DIPLOMA_NAME', 'VJEEC_DCM_HIGH_SCHOOL_DEGREE'),
       (1, 'VJEEC_DCM_UNIVERSITY_DIPLOMA_NAME', 'VJEEC_DCOM_UNIVERSITY_DEGREE');



DROP TABLE IF EXISTS `#__vjeecdcm_user_diploma`;

CREATE TABLE `#__vjeecdcm_user_diploma` (
       `user_id` int(11) NOT NULL,
       `diploma_id` int(11) NOT NULL,
       `issue_date` DATE,
       `expiry_date` DATE,
       `certified_by_vjeec` varchar(100),
       `certification_date` DATE,
       PRIMARY KEY (`user_id`, `diploma_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__vjeecdcm_user_diploma` (`user_id`, `diploma_id`, `issue_date`, `certified_by_vjeec`) VALUES 
       (582, 0, STR_TO_DATE('06-30-2002', '%m-%d-%y'), 'VJEEC_DCM_CERTIFIED_NO');
       
INSERT INTO `#__vjeecdcm_user_diploma` (`user_id`, `diploma_id`, `issue_date`, `certified_by_vjeec`, `certification_date`) VALUES 
       (582, 1, STR_TO_DATE('07-30-2007', '%m-%d-%y'), 'VJEEC_DCM_CERTIFIED_YES', STR_TO_DATE('06-30-2012', '%m-%d-%y'));



DROP TABLE IF EXISTS `#__vjeecdcm_diploma_certification_request`;

CREATE TABLE `#__vjeecdcm_diploma_certification_request`(
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `user_id` int(11) NOT NULL,
     `diploma_id` int(11) NOT NULL,
     `created_date` DATE,
     `processing_status` varchar(100),
     PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__vjeecdcm_diploma_certification_request` (`id`, `user_id`, `diploma_id`, `created_date`, `processing_status`) VALUES 
       (0, 582, 0, STR_TO_DATE('05-22-2013', '%m-%d-%y'), 'VJEEC_DCM_CERTIFICATION_PROCESS_RECEIVED');



DROP TABLE IF EXISTS `#__vjeecdcm_school`;

CREATE TABLE `#__vjeecdcm_school` (
       `id`int(11) NOT NULL,
       `name` varchar(100),
       `type` varchar(100),
       `website` varchar(250),
       PRIMARY KEY (`id`)
) ENGINE=myISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
       