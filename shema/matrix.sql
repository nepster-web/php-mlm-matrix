-- ----------------------------
-- Table structure for matrix_type
-- ----------------------------
DROP TABLE IF EXISTS `matrix_type`;
CREATE TABLE `matrix_type` (
  `type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Название типа матриц',
  `price_enter` decimal(12,5) NOT NULL DEFAULT '0.00000' COMMENT 'Стоимость входа',
  `price_profit` decimal(12,5) NOT NULL DEFAULT '0.00000' COMMENT 'Стоимость награды',
  `division_algorithm` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Алгоритм деления матриц',
  `date_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата создания',
  `date_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата редактирования',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Типы матриц';

-- ----------------------------
-- Table structure for matrix
-- ----------------------------
DROP TABLE IF EXISTS `matrix`;
CREATE TABLE `matrix` (
  `matrix_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `levels` tinyint(4) unsigned NOT NULL DEFAULT '3' COMMENT 'Уровни матрицы',
  `view` tinyint(4) unsigned NOT NULL DEFAULT '2' COMMENT 'Вид матрицы (например двоичная)',
  `type_id` int(11) unsigned NOT NULL COMMENT 'Идентификатор типа матрицы',
  `count_users` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Кол-во пользователей в матрице',
  `date_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата создания',
  `date_close` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата закрытия',
  `users` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Список пользователей в поделенной матрице',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Статус',
  `filled` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Матрица поделена',
  `alias_id` int(11) unsigned DEFAULT NULL COMMENT 'Идентификатор родительской матрицы',
  PRIMARY KEY (`matrix_id`),
  KEY `matrix_type_id` (`type_id`),
  KEY `matrix_alias_id` (`alias_id`),
  CONSTRAINT `FK_matrix_alias_id` FOREIGN KEY (`alias_id`) REFERENCES `matrix` (`matrix_id`),
  CONSTRAINT `FK_matrix_type_id` FOREIGN KEY (`type_id`) REFERENCES `matrix_type` (`type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Матрицы';

-- ----------------------------
-- Table structure for matrix_users
-- ----------------------------
DROP TABLE IF EXISTS `matrix_users`;
CREATE TABLE `matrix_users` (
  `matrix_users_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `matrix_id` int(11) unsigned NOT NULL COMMENT 'Матрица',
  `user_id` int(11) unsigned NOT NULL COMMENT 'Пользователь',
  `rating` int(11) NOT NULL DEFAULT '0' COMMENT 'Рейтинг пользователя',
  `level` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Уровень пользователя в матрице',
  `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Номер пользователя в уровне в матрице',
  `sponsor_id` int(11) unsigned DEFAULT NULL COMMENT 'Спонсор пользователя',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата входа',
  PRIMARY KEY (`matrix_users_id`),
  KEY `matrix_users_matrix_id` (`matrix_id`),
  KEY `matrix_users_user_id` (`user_id`),
  KEY `matrix_users_sponsor_id` (`sponsor_id`),
  CONSTRAINT `FK_matrix_users_matrix_id` FOREIGN KEY (`matrix_id`) REFERENCES `matrix` (`matrix_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Пользователи в матрицах';