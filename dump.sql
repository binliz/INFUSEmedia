DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(39) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `view_date` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `page_url` varchar(255) NOT NULL,
  `views_count` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_address` (`ip_address`,`page_url`,`user_agent`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
