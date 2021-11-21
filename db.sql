DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT UNIQUE,
  `title` varchar(256) DEFAULT NULL,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`)
);
