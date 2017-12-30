<?php
$wpdb->query("DROP TABLE IF EXISTS {$table_prefix}term_taxonomy");
$wpdb->query("CREATE TABLE IF NOT EXISTS `{$table_prefix}term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;");
$wpdb->query("
INSERT INTO `{$table_prefix}term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(1, 1, 'category', '', 0, 0),
(2, 2, 'product_type', '', 0, 21),
(3, 3, 'product_type', '', 0, 0),
(4, 4, 'product_type', '', 0, 2),
(5, 5, 'product_type', '', 0, 0),
(6, 6, 'pa_color', '', 0, 2),
(7, 7, 'pa_color', '', 0, 1),
(8, 8, 'pa_color', '', 0, 1),
(9, 9, 'product_cat', '', 0, 12),
(10, 10, 'product_cat', '', 9, 6),
(11, 11, 'product_cat', '', 0, 6),
(12, 12, 'product_cat', '', 0, 5),
(13, 13, 'product_cat', '', 11, 2),
(14, 14, 'product_cat', '', 9, 6),
(15, 15, 'product_cat', '', 11, 4),
(16, 16, 'nav_menu', '', 0, 30),
(17, 17, 'category', '', 0, 5),
(18, 18, 'category', '', 0, 7),
(19, 19, 'post_tag', '', 0, 4),
(20, 20, 'category', '', 0, 5),
(21, 21, 'post_tag', '', 0, 4),
(22, 22, 'category', '', 0, 5),
(23, 23, 'category', '', 0, 7),
(24, 24, 'post_tag', '', 0, 7),
(25, 25, 'post_tag', '', 0, 5),
(26, 26, 'category', '', 0, 6),
(27, 27, 'post_tag', '', 0, 6),
(28, 28, 'post_tag', '', 0, 1),
(29, 29, 'post_tag', '', 0, 1),
(30, 30, 'post_tag', '', 0, 1),
(31, 31, 'post_tag', '', 0, 3),
(32, 32, 'post_tag', '', 0, 3),
(33, 33, 'category', '', 0, 1),
(35, 35, 'category', '', 0, 0),
(36, 36, 'post_tag', '', 0, 0),
(37, 37, 'post_tag', '', 0, 0),
(38, 38, 'post_tag', '', 0, 0),
(39, 39, 'category', '', 0, 0),
(40, 40, 'category', '', 0, 1),
(41, 41, 'post_tag', '', 0, 0),
(42, 42, 'post_tag', '', 0, 0),
(34, 34, 'category', '', 0, 5),
(43, 43, 'post_tag', '', 0, 0),
(44, 44, 'post_tag', '', 0, 0),
(45, 45, 'post_tag', '', 0, 0),
(46, 46, 'category', '', 0, 0),
(47, 47, 'category', '', 0, 0),
(48, 48, 'post_tag', '', 0, 0),
(49, 49, 'category', '', 0, 0),
(50, 50, 'category', '', 0, 0),
(51, 51, 'post_tag', '', 0, 0),
(52, 52, 'category', '', 0, 0),
(53, 53, 'post_tag', '', 0, 0);");
?>