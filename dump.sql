CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The unique user ID.',
  `email` varchar(50) NOT NULL COMMENT 'The e-moe of the user.',
  `password` varchar(100) NOT NULL COMMENT 'The hash of the user password.',
  `session_token` varchar(100) DEFAULT NULL COMMENT 'The session token.',
  `session_expire` bigint(20) DEFAULT NULL COMMENT 'The session expiration time.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `session_token` (`session_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='API sample project.' AUTO_INCREMENT=52 ;

INSERT INTO `users` (`id`, `email`, `password`, `session_token`, `session_expire`) VALUES
(1, 'admin@example.com', '$2y$10$3ujtWcwuB6IQbKYNHo1tfOTCLUalnQcdcEDnt.iDcg0no/7tPSLe2', '13ab565643fd733ab8c15216f86c72289', 1390769467);

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(16) NOT NULL,
  `endpoint` varchar(256) NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `result` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
