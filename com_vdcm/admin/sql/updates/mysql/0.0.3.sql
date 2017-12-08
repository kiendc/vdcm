DROP TABLE IF EXISTS `#__vjeecdcm_user_info`;
 
CREATE TABLE `#__vjeecdcm_user_info` (
  `id` int(11) NOT NULL,
  `address` varchar(100),
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 
INSERT INTO `#__vjeecdcm_user_info` (`id`,`address`) VALUES
        (582, 'Hello World!'),
        (584, 'Good bye World!');
