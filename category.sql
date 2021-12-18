/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 10.4.21-MariaDB : Database - metashop
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`metashop` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `metashop`;

/*Table structure for table `wiz_category` */

DROP TABLE IF EXISTS `wiz_category`;

CREATE TABLE `wiz_category` (
  `nIdx` int(11) NOT NULL AUTO_INCREMENT,
  `catcode` varchar(8) NOT NULL DEFAULT '',
  `depthno` int(1) DEFAULT NULL,
  `priorno01` int(2) DEFAULT NULL,
  `priorno02` int(2) DEFAULT NULL,
  `priorno03` int(2) DEFAULT NULL,
  `priorno04` int(2) DEFAULT NULL,
  `catname` varchar(255) DEFAULT NULL,
  `catuse` enum('Y','N') DEFAULT NULL,
  `catimg` varchar(20) DEFAULT NULL,
  `catimg_over` varchar(20) DEFAULT NULL,
  `subimg` mediumtext DEFAULT NULL,
  `subimg_type` varchar(3) DEFAULT NULL,
  `prd_tema` varchar(10) DEFAULT NULL,
  `prd_num` int(3) DEFAULT NULL,
  `prd_width` varchar(3) DEFAULT NULL,
  `prd_height` varchar(3) DEFAULT NULL,
  `recom_use` enum('Y','N') DEFAULT NULL,
  `recom_tema` varchar(10) DEFAULT NULL,
  `recom_num` int(3) DEFAULT NULL,
  `cms_rate` int(3) DEFAULT NULL,
  PRIMARY KEY (`nIdx`,`catcode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `wiz_category` */

insert  into `wiz_category`(`nIdx`,`catcode`,`depthno`,`priorno01`,`priorno02`,`priorno03`,`priorno04`,`catname`,`catuse`,`catimg`,`catimg_over`,`subimg`,`subimg_type`,`prd_tema`,`prd_num`,`prd_width`,`prd_height`,`recom_use`,`recom_tema`,`recom_num`,`cms_rate`) values (1,'10000000',1,1,0,0,0,'여성의류','','','','','NON','',20,'','','Y','',0,10),(2,'11000000',1,3,0,0,0,'남성의류','','','','','NON','',20,'','','N','',0,0),(3,'12000000',1,6,0,0,0,'출산/완구/아동용품','','','','','NON','',20,'','','N','',0,0),(4,'13000000',1,4,0,0,0,'패션잡화','','','','','NON','',20,'','','Y','',0,0),(5,'14000000',1,5,0,0,0,'화장품/뷰티','','','','','NON','',20,'','','Y','',0,0),(6,'15000000',1,7,0,0,0,'가구/인테리어/리빙','','','','','NON','',20,'','','N','',0,0),(7,'16000000',1,8,0,0,0,'취미/스포츠/자동차','','','','','NON','',20,'','','N','',0,0),(8,'17000000',1,9,0,0,0,'디지털/가전','','','','','NON','',20,'','','N','',0,0),(9,'18000000',1,10,0,0,0,'도서/상품권','','','','','NON','',20,'','','N','',0,0),(10,'10100000',2,1,1,0,0,'하위분류 1','','','','','NON','',20,'','','Y','',0,10),(11,'10110000',2,1,2,0,0,'하위분류 2','','','','','NON','',20,'','','N','',0,10),(12,'10120000',2,1,3,0,0,'하위분류 3','','','','','NON','',20,'','','N','',0,10),(13,'11100000',2,3,1,0,0,'하위분류 1','','','','','HTM','',20,'','','N','',0,NULL),(14,'11110000',2,3,2,0,0,'하위분류 2','Y','','','','NON','',20,'','','N','',0,NULL),(15,'11120000',2,3,3,0,0,'하위분류 3','Y','','','','NON','',20,'','','N','',0,NULL),(16,'11101000',3,3,1,1,0,'하위분류 11','','','','','HTM','',20,'','','N','',0,NULL),(17,'11101100',3,3,1,2,0,'하위분류 12','','','','','NON','',20,'','','N','',0,3),(18,'11101200',3,3,1,3,0,'하위분류 13','Y','','','','NON','',20,'','','N','',0,NULL),(19,'10101000',3,1,1,1,0,'하위분류 11','N','','','','NON','',20,'','','Y','',0,10),(20,'10111000',3,1,2,1,0,'하위분류 23','','','','','NON','',20,'','','N','',0,13),(29,'11101012',4,3,1,3,1,'하위분류 131','','','','','NON','',20,'','','N','',0,13),(28,'11101011',4,3,1,2,2,'하위분류 111','','','','','NON','',20,'','','Y','',0,13),(31,'19000000',1,11,0,0,0,'종합','','','','','NON','',20,'','','Y','',0,10);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
