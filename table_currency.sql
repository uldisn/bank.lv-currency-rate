/*
SQLyog Ultimate v11.01 (32 bit)
MySQL - 5.5.29-MariaDB : Database - euroau
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`euroau` /*!40100 DEFAULT CHARACTER SET latin1 */;

/*Table structure for table `currency` */

DROP TABLE IF EXISTS `currency`;

CREATE TABLE `currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `currency1` varchar(64) NOT NULL,
  `currency2` varchar(64) NOT NULL,
  `currency_sm1` varchar(64) NOT NULL,
  `currency_sm2` varchar(64) NOT NULL,
  `currency1_eng` varchar(64) DEFAULT NULL,
  `currency2_eng` varchar(64) DEFAULT NULL,
  `currency_sm1_eng` varchar(64) DEFAULT NULL,
  `currency_sm2_eng` varchar(64) DEFAULT NULL,
  `currency_code` varchar(5) NOT NULL,
  `currency_rate` double NOT NULL,
  `currency_order` int(11) NOT NULL,
  `show_currency` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `currency` */

insert  into `currency`(`id`,`name`,`currency1`,`currency2`,`currency_sm1`,`currency_sm2`,`currency1_eng`,`currency2_eng`,`currency_sm1_eng`,`currency_sm2_eng`,`currency_code`,`currency_rate`,`currency_order`,`show_currency`) values (1,'Lats','lats','lati','santīms','santīmi','lat','lats','santim','santims','LVL',1,1,1),(2,'Eiro','eiro','eiro','cents','centi','euro','euros','cent','cents','EUR',0.702804,2,1),(3,'Dolārs','dolārs','dolāri','cents','centi','dollar','dollars','cent','cents','USD',0.499,3,1),(4,'Mārciņa','mārciņa','mārciņas','penss','pensi','pound','pounds','penny','pence','GBP',0.811,4,1),(5,'Zviedrijas krona','Zviedrijas krona','Zviedrijas kronas','ēra','ēras','krone','krone','er','ers','SEK',0.0692,5,1),(6,'Polijas zlots','zlots','zloti','grosz','groši','zloty','zloty','grosz','groszy','PLN',0.181,6,1),(7,'Lietuvas lits','lits','liti','cents','centi','litas','litai','cent','cents','LTL',0.204,7,1),(8,'Igaunijas krona','krona','kronas','sents','senti','kroon','kroons','sent','sents','EEK',0.0449,8,1),(9,'BYR','Baltkrievijas rublis','Baltrievijas rubļi','kapeika','kapeikas','Belarusian rubel','Belarusian rubels','kapeyka','kapeykas','BYR',0.000168,9,1),(10,'Krievijas rublis','rublis','rubļi','kapeika','kapeikas','Russian rubel','Russian rubels','kapeyka','kapeykas','RUB',0.0176,10,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
