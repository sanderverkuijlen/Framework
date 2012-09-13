/*
Navicat MySQL Data Transfer

Source Server         : XAMPP
Source Server Version : 50525
Source Host           : localhost:3306
Source Database       : password

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2012-09-13 22:18:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `customer`
-- ----------------------------
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `password` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of customer
-- ----------------------------
INSERT INTO `customer` VALUES ('1', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');
INSERT INTO `customer` VALUES ('2', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');
INSERT INTO `customer` VALUES ('3', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');
INSERT INTO `customer` VALUES ('4', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');
INSERT INTO `customer` VALUES ('5', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');
INSERT INTO `customer` VALUES ('6', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');
INSERT INTO `customer` VALUES ('7', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');
INSERT INTO `customer` VALUES ('8', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');
INSERT INTO `customer` VALUES ('9', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');
INSERT INTO `customer` VALUES ('10', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');
INSERT INTO `customer` VALUES ('11', '', 'êËFÛÖ‚ÕÅ?|¼»\ZPWG~ï;{4W‘vèky', 'W8ÂsÅW6ŠTaølÎ¢½');

-- ----------------------------
-- Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES ('1', '1', '2012-09-13');
INSERT INTO `order` VALUES ('2', '2', '2012-09-13');
INSERT INTO `order` VALUES ('3', '3', '2012-09-13');
INSERT INTO `order` VALUES ('4', '4', '2012-09-13');
INSERT INTO `order` VALUES ('5', '5', '2012-09-13');
INSERT INTO `order` VALUES ('6', '6', '2012-09-13');
INSERT INTO `order` VALUES ('7', '7', '2012-09-13');
INSERT INTO `order` VALUES ('8', '8', '2012-09-13');
INSERT INTO `order` VALUES ('9', '9', '2012-09-13');
INSERT INTO `order` VALUES ('10', '10', '2012-09-13');
INSERT INTO `order` VALUES ('11', '11', '2012-09-13');

-- ----------------------------
-- Table structure for `order_product`
-- ----------------------------
DROP TABLE IF EXISTS `order_product`;
CREATE TABLE `order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `product_id` (`product_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order_product
-- ----------------------------

-- ----------------------------
-- Table structure for `product`
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `brand` varchar(255) CHARACTER SET latin1 NOT NULL,
  `price` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES ('1', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('2', 'iPhone 5 cover', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('3', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('4', 'iPhone 5 cover', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('5', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('6', 'iPhone 5 cover', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('7', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('8', 'iPhone 5 cover', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('9', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('10', 'iPhone 5 cover', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('11', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('12', 'iPhone 5 cover', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('13', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('14', 'iPhone 5 cover', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('15', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('16', 'iPhone 5 cover', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('17', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('18', 'iPhone 5 cover', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('19', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('20', 'iPhone 5 cover', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('21', 'iPhone 5', 'Apple inc.', '0');
INSERT INTO `product` VALUES ('22', 'iPhone 5 cover', 'Apple inc.', '0');
