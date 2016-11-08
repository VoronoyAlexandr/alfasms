CREATE TABLE `PREFIX_smsphone` (
  `id_subscriber` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_subscriber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;