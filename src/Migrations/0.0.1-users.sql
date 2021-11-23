CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT UNIQUE,
  `username` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL UNIQUE,
  `passwordHash` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
