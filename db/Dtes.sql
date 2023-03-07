/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 5.7.36 : Database - mundopaquete
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`mundopaquete` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `mundopaquete`;

/*Table structure for table `dtes` */

DROP TABLE IF EXISTS `dtes`;

CREATE TABLE `dtes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CodeMH` varchar(10) COLLATE latin1_spanish_ci NOT NULL,
  `Description` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Data for the table `dtes` */

insert  into `dtes`(`id`,`CodeMH`,`Description`) values 
(1,'01','Factura Consumidor Final'),
(2,'03','Comprobante de Crédito Fiscal');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

