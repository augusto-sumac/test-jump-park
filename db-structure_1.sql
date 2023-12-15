CREATE DATABASE  IF NOT EXISTS `jump_park`;
USE `jump_park`;

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `service_orders`
--
DROP TABLE IF EXISTS `service_orders`;
CREATE TABLE `service_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehiclePlate` char(7) NOT NULL,
  `entryDateTime` datetime NOT NULL,
  `exitDateTime` datetime DEFAULT '0001-01-01 00:00:00',
  `priceType` varchar(55) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT '0.00',
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FkServiceOrderUser` (`userId`),
  CONSTRAINT `FkServiceOrderUser` FOREIGN KEY (`userId`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

