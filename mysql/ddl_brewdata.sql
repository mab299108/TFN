use dataLogger;

CREATE TABLE `brewData` (
  `brewID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `style` varchar(100) DEFAULT NULL,
  `desc` text,
  `og` decimal(11,3) DEFAULT NULL,
  `fg` decimal(11,3) DEFAULT NULL,
  `abv` decimal(11,3) DEFAULT NULL,
  `ibu` decimal(11,3) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `brewNotes` text,
  `styleFlag` varchar(1) DEFAULT NULL,
  `calories` decimal(11,0) DEFAULT NULL,
  PRIMARY KEY (`brewID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;