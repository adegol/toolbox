delimiter $$

CREATE TABLE `toolbox`.`pastebin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `source` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `slug` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `revision` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci$$

