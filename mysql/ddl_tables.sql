CREATE TABLE `tempData` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `temp` decimal(5,2) NOT NULL,
  `time` datetime NOT NULL,
  `sensor` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

CREATE TABLE `flowData` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `flow` decimal(10,2) NOT NULL,
  `time` datetime NOT NULL,
  `sensor` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
