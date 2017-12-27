<?php
$wpdb->query("DROP TABLE IF EXISTS {$table_prefix}cw_rounds");
$wpdb->query("CREATE TABLE IF NOT EXISTS `{$table_prefix}cw_rounds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `match_id` int(10) unsigned NOT NULL,
  `group_n` int(11) NOT NULL,
  `map_id` int(10) unsigned NOT NULL,
  `tickets1` int(11) NOT NULL,
  `tickets2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`),
  KEY `group_n` (`group_n`),
  KEY `map_id` (`map_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=168 ;");
$wpdb->query("
INSERT INTO `{$table_prefix}cw_rounds` (`id`, `match_id`, `group_n`, `map_id`, `tickets1`, `tickets2`) VALUES
(1, 1, 1424537900, 8, 7, 7),
(2, 1, 1424537900, 8, 10, 5),
(3, 1, 1424537901, 5, 10, 5),
(4, 1, 1424537901, 5, 8, 6),
(5, 1, 1424537902, 7, 3, 12),
(6, 1, 1424537902, 7, 9, 4),
(7, 2, 1424706258, 3, 1, 8),
(8, 2, 1424706258, 3, 2, 9),
(9, 2, 1424706258, 3, 5, 7),
(10, 2, 1424706259, 4, 5, 9),
(11, 2, 1424706259, 4, 4, 7),
(12, 2, 1424706259, 4, 9, 8),
(13, 3, 1424706415, 3, 5, 9),
(14, 3, 1424706415, 3, 5, 8),
(15, 3, 1424706416, 2, 7, 1),
(16, 3, 1424706416, 2, 8, 2),
(17, 3, 1424706417, 4, 2, 6),
(18, 3, 1424706417, 4, 7, 9),
(41, 8, 1425140633, 4, 0, 0),
(40, 8, 1425140633, 4, 0, 0),
(21, 4, 1424706852, 9, 5, 9),
(22, 4, 1424706852, 9, 5, 0),
(23, 4, 1424706852, 9, 2, 7),
(24, 4, 1424706852, 9, 3, 8),
(25, 4, 1424706853, 12, 3, 6),
(26, 4, 1424706853, 12, 7, 7),
(27, 4, 1424706853, 12, 8, 8),
(28, 4, 1424706853, 12, 3, 4),
(29, 4, 1424706854, 10, 1, 5),
(30, 4, 1424706854, 10, 3, 6),
(31, 4, 1424706854, 10, 6, 7),
(32, 4, 1424706854, 10, 1, 8),
(33, 5, 1424706952, 5, 7, 8),
(34, 5, 1424706953, 7, 10, 5),
(35, 5, 1424706954, 6, 5, 10),
(36, 6, 1424707022, 5, 0, 0),
(37, 6, 1424707022, 5, 0, 0),
(38, 6, 1424707022, 5, 0, 0),
(39, 7, 1424707743, 20, 0, 0),
(42, 8, 1425140634, 3, 0, 0),
(43, 8, 1425140634, 3, 0, 0),
(44, 8, 1425140635, 2, 0, 0),
(45, 8, 1425140635, 2, 0, 0),
(46, 9, 1425140732, 9, 0, 0),
(47, 9, 1425140732, 9, 0, 0),
(48, 9, 1425140733, 11, 0, 0),
(49, 9, 1425140733, 11, 0, 0),
(50, 9, 1425140734, 10, 0, 0),
(51, 9, 1425140734, 10, 0, 0),
(52, 10, 1425140789, 1, 0, 0),
(53, 10, 1425140789, 1, 0, 0),
(54, 10, 1425140790, 3, 0, 0),
(55, 10, 1425140790, 3, 0, 0),
(56, 10, 1425140791, 4, 0, 0),
(57, 10, 1425140791, 4, 0, 0),
(58, 11, 1425140821, 15, 0, 0),
(59, 11, 1425140821, 15, 0, 0),
(60, 11, 1425140821, 15, 0, 0),
(61, 11, 1425140822, 14, 0, 0),
(62, 11, 1425140822, 14, 0, 0),
(63, 11, 1425140822, 14, 0, 0),
(64, 12, 1425140982, 20, 6, 2),
(65, 12, 1425140982, 20, 6, 3),
(66, 12, 1425140982, 20, 5, 4),
(67, 12, 1425140982, 20, 3, 65),
(68, 13, 1425141088, 4, 0, 0),
(69, 13, 1425141088, 4, 0, 0),
(70, 13, 1425141088, 4, 0, 0),
(71, 13, 1425141088, 4, 0, 0),
(72, 13, 1425141089, 2, 0, 0),
(73, 13, 1425141089, 2, 0, 0),
(74, 13, 1425141089, 2, 0, 0),
(75, 13, 1425141089, 2, 0, 0),
(76, 14, 1425141149, 4, 0, 0),
(77, 14, 1425141149, 4, 0, 0),
(78, 14, 1425141149, 4, 0, 0),
(79, 14, 1425141149, 4, 0, 0),
(80, 14, 1425141150, 1, 0, 0),
(81, 14, 1425141150, 1, 0, 0),
(82, 14, 1425141150, 1, 0, 0),
(83, 14, 1425141150, 1, 0, 0),
(84, 15, 1425141195, 9, 0, 0),
(85, 15, 1425141195, 9, 0, 0),
(86, 15, 1425141195, 9, 0, 0),
(87, 15, 1425141195, 9, 0, 0),
(88, 15, 1425141196, 12, 0, 0),
(89, 15, 1425141196, 12, 0, 0),
(90, 15, 1425141196, 12, 0, 0),
(91, 15, 1425141196, 12, 0, 0),
(92, 15, 1425141197, 10, 0, 0),
(93, 15, 1425141197, 10, 0, 0),
(94, 16, 1425141488, 3, 5, 2),
(95, 16, 1425141488, 3, 2, 4),
(96, 16, 1425141488, 3, 7, 2),
(97, 16, 1425141488, 3, 7, 1),
(98, 16, 1425141489, 1, 5, 7),
(99, 16, 1425141489, 1, 4, 9),
(100, 16, 1425141489, 1, 8, 1),
(101, 16, 1425141489, 1, 2, 3),
(102, 17, 1425141673, 1, 0, 0),
(103, 17, 1425141673, 1, 0, 0),
(104, 17, 1425141673, 1, 0, 0),
(105, 17, 1425141674, 2, 0, 0),
(106, 17, 1425141674, 2, 0, 0),
(107, 17, 1425141674, 2, 0, 0),
(108, 18, 1425142838, 20, 0, 0),
(109, 18, 1425142838, 20, 0, 0),
(110, 19, 1425142840, 19, 0, 0),
(111, 19, 1425142840, 19, 0, 0),
(112, 19, 1425142841, 18, 0, 0),
(113, 19, 1425142841, 18, 0, 0),
(114, 19, 1425142842, 17, 0, 0),
(115, 19, 1425142842, 17, 0, 0),
(116, 20, 1425142842, 13, 0, 0),
(117, 20, 1425142842, 13, 0, 0),
(118, 21, 1425142826, 13, 2, 1),
(119, 21, 1425142826, 13, 2, 1),
(120, 22, 1425142829, 15, 0, 0),
(121, 22, 1425142829, 15, 0, 0),
(122, 22, 1425142829, 15, 0, 0),
(123, 23, 1425142982, 3, 0, 0),
(124, 23, 1425142983, 1, 0, 0),
(125, 23, 1425142984, 2, 0, 0),
(126, 24, 1425143021, 20, 0, 0),
(127, 24, 1425143021, 20, 0, 0),
(128, 24, 1425143021, 20, 0, 0),
(129, 25, 1425143023, 20, 0, 0),
(130, 25, 1425143023, 20, 0, 0),
(131, 25, 1425143023, 20, 0, 0),
(132, 26, 1425143025, 20, 0, 0),
(133, 26, 1425143025, 20, 0, 0),
(134, 26, 1425143025, 20, 0, 0),
(135, 26, 1425143025, 20, 0, 0),
(136, 27, 1425143113, 3, 0, 0),
(137, 27, 1425143113, 3, 0, 0),
(138, 27, 1425143113, 3, 0, 0),
(139, 27, 1425143114, 4, 0, 0),
(140, 27, 1425143114, 4, 0, 0),
(141, 27, 1425143114, 4, 0, 0),
(142, 27, 1425143115, 1, 0, 0),
(143, 27, 1425143115, 1, 0, 0),
(144, 27, 1425143115, 1, 0, 0),
(145, 28, 1425143293, 20, 0, 0),
(146, 28, 1425143293, 20, 0, 0),
(147, 28, 1425143293, 20, 0, 0),
(148, 28, 1425143293, 20, 0, 0),
(149, 29, 1425143382, 13, 0, 0),
(150, 29, 1425143382, 13, 0, 0),
(151, 29, 1425143382, 13, 0, 0),
(152, 29, 1425143382, 13, 0, 0),
(153, 30, 1425143388, 6, 0, 0),
(154, 30, 1425143388, 6, 0, 0),
(155, 30, 1425143388, 6, 0, 0),
(156, 30, 1425143388, 6, 0, 0),
(157, 30, 1425143389, 8, 0, 0),
(158, 30, 1425143389, 8, 0, 0),
(159, 30, 1425143389, 8, 0, 0),
(160, 30, 1425143389, 8, 0, 0),
(161, 30, 1425143390, 7, 0, 0),
(162, 30, 1425143390, 7, 0, 0),
(163, 30, 1425143390, 7, 0, 0),
(164, 30, 1425143390, 7, 0, 0),
(165, 31, 1425143390, 16, 0, 0),
(166, 31, 1425143390, 16, 0, 0),
(167, 31, 1425143390, 16, 0, 0);");
?>