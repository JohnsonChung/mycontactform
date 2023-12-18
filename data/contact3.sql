-- Adminer 4.2.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `enquiry_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiry_id` (`enquiry_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiry` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

INSERT INTO `comment` (`id`, `enquiry_id`, `user_id`, `comment`, `created_at`) VALUES
(91,	179,	40,	'',	'2015-07-03 11:14:43'),
(92,	179,	49,	'こんにちは。',	'2015-07-03 11:17:19'),
(93,	179,	49,	'対応中。',	'2015-07-03 11:17:38'),
(94,	179,	47,	'',	'2015-07-03 11:17:52'),
(95,	179,	40,	'あばよ',	'2015-07-03 11:18:13'),
(96,	179,	49,	'ここは？',	'2015-07-03 11:18:30'),
(97,	179,	48,	'すごい',	'2015-07-03 11:18:37'),
(98,	179,	44,	'中嶋さんが対応します',	'2015-07-03 11:19:17'),
(99,	179,	43,	'添付できるといい',	'2015-07-03 11:19:25'),
(100,	179,	48,	'https://www.j-quest.jp/contact2/jquest/allenquiries.php?eqid=179',	'2015-07-03 11:21:42'),
(101,	179,	49,	'対応します。',	'2015-07-03 14:39:54'),
(102,	179,	49,	'ＯＫ牧場！',	'2015-07-03 14:48:01'),
(103,	179,	49,	'対応します',	'2015-07-07 17:38:44')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `enquiry_id` = VALUES(`enquiry_id`), `user_id` = VALUES(`user_id`), `comment` = VALUES(`comment`), `created_at` = VALUES(`created_at`);

DROP TABLE IF EXISTS `enquiry`;
CREATE TABLE `enquiry` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `opinions_enquiries` longtext NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `building_name` varchar(255) DEFAULT NULL,
  `contact_method` varchar(255) NOT NULL,
  `telephone_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_updater` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `enquiry_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;

INSERT INTO `enquiry` (`id`, `created_at`, `updated_at`, `store_id`, `opinions_enquiries`, `name`, `postal_code`, `state`, `city`, `building_name`, `contact_method`, `telephone_number`, `email`, `status`, `status_updater`) VALUES
(1,	'2015-07-02 16:54:45',	'2015-07-02 16:54:45',	20,	'通常、満タンで38リットル以下しか入らないのに、43.05リットル入った。<br />\r\nメーカー公表のタンク容量も42リットルということで給油の機械にトラブルがあると思い、佐久平店の若林氏にその旨伝える。<br />\r\n同氏は機械のトラブルは決してないとの一点張り。<br />\r\n私の車のタンクが肥大したのだろうか。<br />\r\nそれとも、客に対する対応が素晴らしすぎるのだろうか。<br />\r\n今後更なるトラブルを招かないように注意を喚起する。<br />\r\n',	'日本太郎',	'456-7890',	'群馬県',	'伊勢崎市宮子456',	'',	'telephone',	'0286-225-6659',	'',	NULL,	NULL),
(177,	'2015-07-02 17:06:51',	'2015-07-02 17:06:51',	5,	'販売している灯油が何号か教えてください。',	'鈴木一朗',	'464-7979',	'茨城県',	'',	'',	'mail',	'',	'dummy@dummy.com',	NULL,	NULL),
(178,	'2015-07-02 17:16:54',	'2015-07-02 17:16:54',	2,	'タバコの銘柄について<br />\r\n手巻煙草は販売してますか？',	'長嶋茂雄',	'456-7497',	'宮城県',	'',	'',	'mail',	'',	'dummy@dummy.jp',	NULL,	NULL),
(179,	'2015-07-03 11:10:00',	'2015-07-03 11:10:00',	8,	'開店時より利用していますが、最近店長が変わりお店の雰囲気が悪くなったので、改善をお願いします。<br />\r\n一ヶ月以内に改善出来ない場合は、二度と利用しないつもりです。<br />\r\n<br />\r\nテスト',	'山田　太郎',	'103-0006',	'茨城県',	'真岡市',	'',	'telephone',	'03-6855-4014',	'',	NULL,	NULL)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`), `store_id` = VALUES(`store_id`), `opinions_enquiries` = VALUES(`opinions_enquiries`), `name` = VALUES(`name`), `postal_code` = VALUES(`postal_code`), `state` = VALUES(`state`), `city` = VALUES(`city`), `building_name` = VALUES(`building_name`), `contact_method` = VALUES(`contact_method`), `telephone_number` = VALUES(`telephone_number`), `email` = VALUES(`email`), `status` = VALUES(`status`), `status_updater` = VALUES(`status_updater`);

DROP TABLE IF EXISTS `enquiry_responses`;
CREATE TABLE `enquiry_responses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `enquiry_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `responsible_party` varchar(128) NOT NULL,
  `message` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiry_id` (`enquiry_id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `enquiry_responses_ibfk_1` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiry` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enquiry_responses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enquiry_responses_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `enquiry_response_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `enquiry_response_categories`;
CREATE TABLE `enquiry_response_categories` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `enquiry_response_categories` (`id`, `name`) VALUES
(1,	'クレーム'),
(2,	'リクルート'),
(3,	'商品問合せ')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `name` = VALUES(`name`);

DROP TABLE IF EXISTS `mailer`;
CREATE TABLE `mailer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

INSERT INTO `mailer` (`id`, `email`) VALUES
(17,	'g.gachapon@gmail.com'),
(20,	'nobusuzuki@pse.or.jp'),
(37,	'k-kinoshita@jqst.jp'),
(38,	'furuta@jqst.jp'),
(39,	'e-kanai@jqst.jp'),
(40,	'kinoshita@jqst.jp'),
(41,	'takanohara@jqst.jp'),
(42,	'fujiwara@jqst.jp'),
(43,	'furutani@jqst.jp'),
(44,	'nakamura@jqst.jp'),
(45,	'kiyosawa@jqst.jp'),
(46,	'okamura@jqst.jp')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `email` = VALUES(`email`);

DROP TABLE IF EXISTS `prefectures`;
CREATE TABLE `prefectures` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `prefectures` (`id`, `name`) VALUES
(1,	'北海道'),
(2,	'青森県'),
(3,	'岩手県'),
(4,	'宮城県'),
(5,	'秋田県'),
(6,	'山形県'),
(7,	'福島県'),
(8,	'茨城県'),
(9,	'栃木県'),
(10,	'群馬県'),
(11,	'埼玉県'),
(12,	'千葉県'),
(13,	'東京都'),
(14,	'神奈川県'),
(15,	'新潟県'),
(16,	'富山県'),
(17,	'石川県'),
(18,	'福井県'),
(19,	'山梨県'),
(20,	'長野県'),
(21,	'岐阜県'),
(22,	'静岡県'),
(23,	'愛知県'),
(24,	'三重県'),
(25,	'滋賀県'),
(26,	'京都府'),
(27,	'大阪府'),
(28,	'兵庫県'),
(29,	'奈良県'),
(30,	'和歌山県'),
(31,	'鳥取県'),
(32,	'島根県'),
(33,	'岡山県'),
(34,	'広島県'),
(35,	'山口県'),
(36,	'徳島県'),
(37,	'香川県'),
(38,	'愛媛県'),
(39,	'高知県'),
(40,	'福岡県'),
(41,	'佐賀県'),
(42,	'長崎県'),
(43,	'熊本県'),
(44,	'大分県'),
(45,	'宮崎県'),
(46,	'鹿児島県'),
(47,	'沖縄県')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `name` = VALUES(`name`);

DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefecture_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prefecture_id` (`prefecture_id`),
  CONSTRAINT `stores_ibfk_1` FOREIGN KEY (`prefecture_id`) REFERENCES `prefectures` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;

INSERT INTO `stores` (`id`, `prefecture_id`, `name`) VALUES
(1,	1,	'大曲店'),
(2,	4,	'名取店'),
(3,	4,	'富谷店'),
(4,	8,	'北茨城店'),
(5,	8,	'岩瀬店'),
(6,	8,	'玉造店'),
(7,	9,	'今市店'),
(8,	9,	'真岡店'),
(9,	9,	'大田原店'),
(10,	9,	'アクロスプラザ西那須野店'),
(11,	9,	'黒磯店'),
(12,	9,	'西那須野店'),
(13,	9,	'益子店'),
(14,	9,	'大平店'),
(15,	9,	'さくら氏家店'),
(16,	10,	'前橋店'),
(17,	10,	'前橋みなみ店'),
(18,	10,	'宮子カインズ前店'),
(19,	10,	'赤堀店'),
(20,	10,	'佐波東店'),
(21,	10,	'宮子ベイシア前店'),
(22,	10,	'尾島店'),
(23,	10,	'沼田店'),
(24,	10,	'渋川店'),
(25,	10,	'鯉沢店'),
(26,	10,	'富岡店'),
(27,	10,	'富士見店'),
(28,	10,	'吉井店'),
(29,	10,	'甘楽店'),
(30,	10,	'月夜野店'),
(31,	10,	'大泉店'),
(32,	10,	'高崎新保店'),
(33,	11,	'行田店'),
(34,	11,	'川本店'),
(35,	11,	'鶴ヶ島店'),
(36,	11,	'滑川店'),
(37,	11,	'嵐山店'),
(38,	11,	'川島店'),
(39,	11,	'栗橋店'),
(40,	11,	'大利根店'),
(41,	12,	'ちば古市場店'),
(42,	12,	'銚子店'),
(43,	12,	'佐倉店'),
(44,	12,	'鴨川店'),
(45,	12,	'富津店'),
(46,	12,	'八日市場店'),
(47,	12,	'大原店'),
(48,	12,	'小見川店'),
(49,	12,	'大網白里店'),
(50,	12,	'長生店'),
(51,	12,	'市原五井店'),
(52,	12,	'酒々井店'),
(53,	13,	'町田店'),
(54,	15,	'豊栄店'),
(55,	15,	'新津店'),
(56,	15,	'長岡店'),
(57,	16,	'大沢野店'),
(58,	16,	'高岡中曽根店'),
(59,	16,	'黒部店'),
(60,	20,	'小諸店'),
(61,	20,	'大町店'),
(62,	20,	'飯山店'),
(63,	20,	'佐久平店'),
(64,	20,	'堀金店'),
(65,	20,	'豊科店'),
(66,	20,	'中野小布施店'),
(67,	20,	'長野稲田店'),
(68,	21,	'関店'),
(69,	22,	'浜松雄踏店'),
(70,	22,	'浜松都田店'),
(71,	22,	'大東店'),
(72,	23,	'蒲郡店'),
(73,	23,	'常滑店'),
(74,	23,	'三好店'),
(75,	24,	'桑名店'),
(76,	24,	'みえ川越店'),
(77,	25,	'彦根店'),
(78,	26,	'綾部店'),
(79,	26,	'木津川店'),
(80,	28,	'篠山店'),
(81,	28,	'氷上店')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `prefecture_id` = VALUES(`prefecture_id`), `name` = VALUES(`name`);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `screen_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `email`, `name`, `screen_name`, `password`, `role`) VALUES
(1,	'suzukinobufumi@gmail.com',	'admin',	'admin',	'21232f297a57a5a743894a0e4a801fc3',	'admin'),
(33,	'',	'suzuki',	'鈴木',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(36,	'nitisha.ddtech@gmail.com',	'testuser',	'testuser',	'5d9c68c6c50ed3d02a2fcf54f63993b6',	'admin'),
(37,	'',	'johnson',	'chung',	'd1a346df2019a0c0fd79b4808e502cee',	''),
(38,	'',	'kanai',	'金井',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(40,	'',	'k-kinoshita',	'木下健治',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(41,	'',	'kinoshita',	'木下功',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(42,	'',	'takanohara',	'鷹野原',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(43,	'',	'fujiwara',	'藤原',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(44,	'',	'nakamura',	'中村',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(45,	'',	'okamura',	'岡村',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(46,	'',	'furutani',	'古谷',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(47,	'',	'kiyosawa',	'清沢',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(48,	'',	'furuta',	'古田',	'81dc9bdb52d04dc20036dbd8313ed055',	''),
(49,	'',	'e-kanai',	'金井',	'81dc9bdb52d04dc20036dbd8313ed055',	'')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `email` = VALUES(`email`), `name` = VALUES(`name`), `screen_name` = VALUES(`screen_name`), `password` = VALUES(`password`), `role` = VALUES(`role`);

-- 2015-07-21 16:38:54
