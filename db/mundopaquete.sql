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

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CustomerName` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `Email` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `Phone` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `Address` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
  `TaxId` varchar(25) COLLATE latin1_spanish_ci NOT NULL,
  `Company` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `NIT` varchar(25) COLLATE latin1_spanish_ci DEFAULT '',
  `DUI` varchar(25) COLLATE latin1_spanish_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Data for the table `customers` */

insert  into `customers`(`id`,`CustomerName`,`Email`,`Phone`,`Address`,`TaxId`,`Company`,`NIT`,`DUI`) values 
(1,'Dimas','dimas4@hotmail.com','22222222','SAN MARCOS','2-7','PRUEBA','',''),
(2,'Dimas Ferman Argueta Hernandez','dimas4@hotmail.com','22222222','Col. San Antonio # 2 Block B Casa # 7 San Marcos','2-7','Almacenes Vidri','',''),
(3,'Sabrina Abigail Argueta','aby.argueta@prueba.com','22222222','San Salvador, San Marcos','2-7','Home Center','',''),
(4,'Samuel Argueta Bonilla','samu.argueta@gmail.com','33333333','San Salvador, San Marcos','2-7','Home Center','06140410851162','033202209');

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

/*Table structure for table `invoiceitems` */

DROP TABLE IF EXISTS `invoiceitems`;

CREATE TABLE `invoiceitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceId` int(11) NOT NULL,
  `ExcentSales` decimal(10,2) DEFAULT NULL,
  `NonSubjectsSales` decimal(10,2) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Description` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_invoiceId` (`InvoiceId`),
  CONSTRAINT `FK_invoiceId` FOREIGN KEY (`InvoiceId`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Data for the table `invoiceitems` */

insert  into `invoiceitems`(`id`,`InvoiceId`,`ExcentSales`,`NonSubjectsSales`,`Price`,`Quantity`,`Description`) values 
(9,6,0.00,0.00,45.00,1,''),
(10,7,0.00,0.00,150.00,2,''),
(11,8,0.00,0.00,4.00,2,'Prueba'),
(12,9,0.00,0.00,3.00,2,'Otra prueba'),
(13,10,0.00,0.00,1.00,1,'jdlaksjdklasj'),
(14,11,0.00,0.00,4.00,2,'');

/*Table structure for table `invoices` */

DROP TABLE IF EXISTS `invoices`;

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Invoice created date',
  `UpdatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CustomerName` varchar(500) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'NoRegistrado',
  `NIT` varchar(15) COLLATE latin1_spanish_ci NOT NULL DEFAULT '',
  `DUI` varchar(15) COLLATE latin1_spanish_ci NOT NULL DEFAULT '',
  `Address` varchar(800) COLLATE latin1_spanish_ci DEFAULT NULL,
  `TaxId` varchar(25) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'DUI o NIT',
  `AccountOf` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ExcentSales` decimal(10,2) NOT NULL DEFAULT '0.00',
  `NonSubjectsSales` decimal(10,2) NOT NULL DEFAULT '0.00',
  `SubTotal` decimal(10,2) DEFAULT '0.00',
  `IVA` decimal(10,2) DEFAULT '0.00',
  `Total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Description` varchar(500) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CustomerId` int(11) NOT NULL DEFAULT '1',
  `Status` varchar(200) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'WaitingPayment',
  `DtesId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Data for the table `invoices` */

insert  into `invoices`(`id`,`CreatedAt`,`UpdatedAt`,`CustomerName`,`NIT`,`DUI`,`Address`,`TaxId`,`AccountOf`,`ExcentSales`,`NonSubjectsSales`,`SubTotal`,`IVA`,`Total`,`Description`,`CustomerId`,`Status`,`DtesId`) values 
(6,'2023-03-02 21:53:13','2023-03-02 21:53:13','Dimas Ferman Argueta Hernandez','','','Col. San Antonio # 2 Block B Casa # 7 San Marcos','2-7','Almacenes Vidri',0.00,0.00,45.00,5.85,50.85,'',2,'PaymentDone',0),
(7,'2023-03-02 21:53:52','2023-03-02 21:53:52','Dimas Ferman Argueta Hernandez','','','Col. San Antonio # 2 Block B Casa # 7 San Marcos','2-7','Almacenes Vidri',0.00,0.00,300.00,39.00,339.00,'',2,'PaymentDone',0),
(8,'2023-03-04 09:29:09','2023-03-04 09:29:09','Samuel Argueta Bonilla','','','San Salvador, San Marcos','2-7','Home Center',0.00,0.00,8.00,1.04,9.04,'Prueba',4,'PaymentDone',2),
(9,'2023-03-04 09:35:48','2023-03-04 09:35:48','Sabrina Abigail Argueta','','','San Salvador, San Marcos','2-7','Home Center',0.00,0.00,6.00,0.78,6.78,'D',3,'PaymentDone',2),
(10,'2023-03-04 09:38:16','2023-03-04 09:38:16','Sabrina Abigail Argueta','','','San Salvador, San Marcos','2-7','Home Center',0.00,0.00,1.00,0.13,1.13,'',3,'PaymentDone',2),
(11,'2023-03-04 11:21:54','2023-03-04 11:21:54','Samuel Argueta Bonilla','','','San Salvador, San Marcos','2-7','Home Center',0.00,0.00,8.00,1.04,9.04,'Prueba',4,'PaymentDone',2);

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `value` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
  `dataType` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Data for the table `settings` */

insert  into `settings`(`id`,`name`,`value`,`dataType`) values 
(1,'IVA','13','decimal'),
(2,'Correlativo Factura Consumidor Final','36','decimal'),
(3,'Correlativo Comprobante de Credito Fiscal','13','decimal');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
