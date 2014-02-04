DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The unique user ID.',
  `email` varchar(50) NOT NULL COMMENT 'The e-moe of the user.',
  `password` varchar(100) NOT NULL COMMENT 'The hash of the user password.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='API sample project.';

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'admin@example.com', 'admin');
