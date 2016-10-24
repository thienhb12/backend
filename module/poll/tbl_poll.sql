/*
MySQL Data Transfer
Source Host: localhost
Source Database: hocvientaichinh
Target Host: localhost
Target Database: hocvientaichinh
Date: 2/19/2012 12:52:18 PM
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for tbl_poll
-- ----------------------------
DROP TABLE IF EXISTS `tbl_poll`;
CREATE TABLE `tbl_poll` (
  `id` int(11) NOT NULL auto_increment,
  `question` varchar(225) NOT NULL,
  `total_poll` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0' COMMENT '0:Radio\r\n1:Checkbox',
  `LangID` tinyint(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tbl_poll_option
-- ----------------------------
DROP TABLE IF EXISTS `tbl_poll_option`;
CREATE TABLE `tbl_poll_option` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(225) NOT NULL,
  `number_poll` int(11) NOT NULL default '0',
  `poll_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `tbl_poll` VALUES ('16', 'Báº¡n tháº¥y cháº¥t lÆ°á»£ng phá»¥c vá»¥ cá»§a chÃºng tÃ´i tháº¿ nÃ o?', '33', '1', '0', '1');
INSERT INTO `tbl_poll` VALUES ('17', 'Báº¡n mong muá»‘n cáº£i thiá»‡n bá»¯a Äƒn nhÆ° tháº¿ nÃ o?', '9', '1', '1', '2');
INSERT INTO `tbl_poll_option` VALUES ('50', 'ThÃªm thá»©c Äƒn máº·n', '5', '17');
INSERT INTO `tbl_poll_option` VALUES ('49', 'ThÃªm rau cá»§ quáº£', '4', '17');
INSERT INTO `tbl_poll_option` VALUES ('48', 'Ráº¥t tá»‡', '6', '16');
INSERT INTO `tbl_poll_option` VALUES ('44', 'Ráº¥t tá»‘t', '16', '16');
INSERT INTO `tbl_poll_option` VALUES ('46', 'BÃ¬nh thÆ°á»ng', '11', '16');
