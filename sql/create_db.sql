 DROP TABLE `company`;

CREATE TABLE `company` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cid` bigint(20) DEFAULT 0,
  `name` varchar(45) DEFAULT NULL,
  `vatnumber` varchar(45) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `townid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE `userprofile`;

CREATE TABLE `userprofile` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cid` bigint(20) DEFAULT 0,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `passwd` varchar(50) NOT NULL,
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE `country`;

CREATE TABLE `country` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cid` bigint(20) NOT NULL,
  `code` varchar(5) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `currencyid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


INSERT INTO `habis`.`country` (`cid`, `code`, `name`) VALUES ('1', 'HR', 'Hrvatska');
INSERT INTO `habis`.`country` (`cid`, `code`, `name`) VALUES ('2', 'AT', 'Austrija');

CREATE TABLE `habis`.`currency` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `code` NVARCHAR(5) NULL,
  `name` NVARCHAR(45) NULL,
  `numcode` VARCHAR(5) NULL,
  PRIMARY KEY (`id`));

INSERT INTO `habis`.`currency` (`code`, `name`, `numcode`) VALUES ('EUR', 'Euro', '888');
INSERT INTO `habis`.`currency` (`code`, `name`, `numcode`) VALUES ('HRK', 'Kuna', '385');

UPDATE `habis`.`country` SET `currencyid`='1' WHERE `id`='2';
UPDATE `habis`.`country` SET `currencyid`='1' WHERE `id`='8';
UPDATE `habis`.`country` SET `currencyid`='1' WHERE `id`='385';
UPDATE `habis`.`country` SET `currencyid`='1' WHERE `id`='389';
UPDATE `habis`.`country` SET `currencyid`='2' WHERE `id`='1';
