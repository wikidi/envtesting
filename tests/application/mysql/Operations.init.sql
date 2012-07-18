DROP TABLE IF EXISTS `_envtesting_`;

CREATE TABLE `_envtesting_` (
  `data` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `_envtesting_` WRITE;

INSERT INTO `_envtesting_` VALUES ('deleteAllow'),('deleteNotAllow');
INSERT INTO `_envtesting_` VALUES ('insertAllow'), ('insertNotAllow');
INSERT INTO `_envtesting_` VALUES ('updateAllow'), ('updateNotAllow');
INSERT INTO `_envtesting_` VALUES ('selectAllow'), ('selectNotAllow');

UNLOCK TABLES;