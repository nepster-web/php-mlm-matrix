-- ----------------------------
-- Table structure for matrix
-- ----------------------------
DROP TABLE IF EXISTS `matrix`;
CREATE TABLE `matrix` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `depth` tinyint(4) unsigned NOT NULL DEFAULT '3' COMMENT 'Уровни матрицы',
  `pow` tinyint(4) unsigned NOT NULL DEFAULT '2' COMMENT 'Вид матрицы (например двоичная)',
  `date_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата создания',
  `date_close` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата закрытия',
  `filled` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Матрица поделена',
  `parent_id` int(11) unsigned DEFAULT NULL COMMENT 'Идентификатор родительской матрицы',
  PRIMARY KEY (`id`),
  KEY `matrix_alias_id` (`alias_id`),
  CONSTRAINT `FK_matrix_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `matrix` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Матрицы';

-- ----------------------------
-- Table structure for matrix_users
-- ----------------------------
DROP TABLE IF EXISTS `matrix_users`;
CREATE TABLE `matrix_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `matrix_id` int(11) unsigned NOT NULL COMMENT 'Матрица',
  `user_id` int(11) unsigned NOT NULL COMMENT 'Пользователь',
  `depth` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Уровень пользователя в матрице',
  `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Номер пользователя в уровне в матрице',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата входа',
  PRIMARY KEY (`id`),
  KEY `matrix_users_matrix_id` (`matrix_id`),
  KEY `matrix_users_user_id` (`user_id`),
  CONSTRAINT `FK_matrix_users_matrix_id` FOREIGN KEY (`matrix_id`) REFERENCES `matrix` (`matrix_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Пользователи в матрицах';