

DROP TABLE IF EXISTS `grado`;

CREATE TABLE `grado` (
  `idgra` int(11) NOT NULL AUTO_INCREMENT,
  `nombregra` varchar(20) CHARACTER SET latin1 NOT NULL,
  `nivel` varchar(1) CHARACTER SET latin1 DEFAULT NULL,
  `estadogra` char(1) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'i',
  PRIMARY KEY (`idgra`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
