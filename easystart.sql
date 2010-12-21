-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 21 2010 г., 17:18
-- Версия сервера: 5.1.41
-- Версия PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `easystart`
--

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `title`) VALUES
(3, 'admin', 'Администратор'),
(13, 'manager', 'Менеджер');

-- --------------------------------------------------------

--
-- Структура таблицы `site_articles`
--

DROP TABLE IF EXISTS `site_articles`;
CREATE TABLE IF NOT EXISTS `site_articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(150) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `teaser` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `author` varchar(150) NOT NULL DEFAULT 'Администратор',
  `created_at` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_main` tinyint(1) NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0',
  `lighting` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `count_views` int(11) unsigned NOT NULL DEFAULT '0',
  `seo_title` varchar(150) NOT NULL DEFAULT 'Title',
  `seo_descriptions` varchar(300) NOT NULL,
  `seo_keywords` varchar(500) NOT NULL,
  `small_img` varchar(255) DEFAULT NULL,
  `big_img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `site_articles`
--

INSERT INTO `site_articles` (`id`, `url`, `name`, `link`, `teaser`, `content`, `date`, `author`, `created_at`, `is_active`, `is_main`, `is_hot`, `lighting`, `count_views`, `seo_title`, `seo_descriptions`, `seo_keywords`, `small_img`, `big_img`) VALUES
(1, 'article', 'Статья тестовая', '', '<p>\r\n	Suspendisse vulputate enim sed velit vehicula at tincidunt mauris placerat. Proin nec libero vel velit ultrices cursus ut feugiat neque. Vestibulum felis augue, viverra non venenatis varius, convallis vel odio. Duis egestas, ligula at viverra vulputate, quam metus tristique nulla, nec rutrum sem velit vel lectus. Nullam tincidunt mi eget tellus congue sodales. Etiam viverra purus ut nisl fermentum fringilla. Donec consequat, augue ut vulputate mattis, nisi mi adipiscing odio, id dapibus eros erat ac erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus placerat massa neque. Nam id felis magna. Morbi pellentesque aliquam felis quis ultrices. Pellentesque fringilla lacus eget orci bibendum fringilla.</p>', '<p>\r\n	Suspendisse vulputate enim sed velit vehicula at tincidunt mauris placerat. Proin nec libero vel velit ultrices cursus ut feugiat neque. Vestibulum felis augue, viverra non venenatis varius, convallis vel odio. Duis egestas, ligula at viverra vulputate, quam metus tristique nulla, nec rutrum sem velit vel lectus. Nullam tincidunt mi eget tellus congue sodales. Etiam viverra purus ut nisl fermentum fringilla. Donec consequat, augue ut vulputate mattis, nisi mi adipiscing odio, id dapibus eros erat ac erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus placerat massa neque. Nam id felis magna. Morbi pellentesque aliquam felis quis ultrices. Pellentesque fringilla lacus eget orci bibendum fringilla.</p>\r\n<p>\r\n	Suspendisse vulputate enim sed velit vehicula at tincidunt mauris placerat. Proin nec libero vel velit ultrices cursus ut feugiat neque. Vestibulum felis augue, viverra non venenatis varius, convallis vel odio. Duis egestas, ligula at viverra vulputate, quam metus tristique nulla, nec rutrum sem velit vel lectus. Nullam tincidunt mi eget tellus congue sodales. Etiam viverra purus ut nisl fermentum fringilla. Donec consequat, augue ut vulputate mattis, nisi mi adipiscing odio, id dapibus eros erat ac erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus placerat massa neque. Nam id felis magna. Morbi pellentesque aliquam felis quis ultrices. Pellentesque fringilla lacus eget orci bibendum fringilla.</p>\r\n<p>\r\n	<img alt="" src="/pics/images/hot_1.png" style="width: 16px; height: 16px;" /></p>', '2010-12-15 00:00:00', 'author', '2010-12-15', 1, 1, 1, 2, 11, 'seo_title', 'seo_descriptions', 'seo_keywords', '1_pejo_308.jpg', '1_pejo_308_3.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `site_blocks`
--

DROP TABLE IF EXISTS `site_blocks`;
CREATE TABLE IF NOT EXISTS `site_blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `content` text,
  `priority` int(5) DEFAULT '500',
  `active` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_name` (`system_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=183 ;

--
-- Дамп данных таблицы `site_blocks`
--

INSERT INTO `site_blocks` (`id`, `system_name`, `title`, `type`, `content`, `priority`, `active`) VALUES
(1, 'header_work_time', 'Время работы в заголовке', 'text', 'с 10 до 24 часов ежедневно', 500, 1),
(136, 'footer_contacts', 'Контактные данные в подвале', 'html', '<p class="phone">Контактный тел:  +375 (17) 294-09-13</p>\r\n<p class="adress">г. Минск, ул. З.Бядули ,15</p>', 500, 1),
(143, 'header_phones', 'Телефоны в заголовке на главной', 'text', '233-00-00, 657-00-00', 500, 1),
(175, 'top_left_contact', 'Контактная информация лево верх под Лого', 'html', '<div class="contact_block"><span style="font-size: larger;"><span style="color: rgb(51, 102, 255);"><span class="phone"><i>+375 (17)</i></span></span></span><span class="phone"> </span><span style="color: rgb(153, 51, 102);"><span style="font-size: larger;"><span class="phone">294-09-13</span></span></span></div>\r\n<div class="contact_block"><span style="font-size: smaller;"><em><span class="adress">г. Минск , ул. З. Бядули,15</span></em></span></div>', 500, 1),
(176, 'akciya_main', 'Акция на главной странице внизу', 'html', '<p><span class="w_250 b i font_32 f_left">МЕГА АКЦИЯ!!!</span> <span class="f_right b i w_215 font_14">\r\n<p>Cоздание сайта от  <i>700 $</i></p>\r\n<p>Продвижение сайта от <i>100 $</i></p>\r\n</span></p>\r\n<div class="clear">&nbsp;</div>\r\n<p><span class="phone font_31 i b">+375 (17) 294-09-13</span></p>\r\n<div class="clear">&nbsp;</div>', 500, 1),
(177, 'zamanuha_main', 'Главная страница замануха вверху', 'html', '<span class="first">Лёгкий старт в сети интернет с минимальными вложениями.</span> Мы предлагаем выгодные условия по созданию и продвижению сайта.<span> Наша компания разработает сайт от 700 $. </span>', 500, 1),
(178, 'akciya_left', 'Акция слева на всех страницах', 'html', '<span class="b i font_28">МЕГА АКЦИЯ!!!</span>\r\n<span class=" b i font_13"><p>Cоздание сайта от  <i>700 $</i></p>\r\n<p>Продвижение сайта от <i>100 $</i></p></span>\r\n<span class="phone font_23 i b"><b class="font_17">+375 (17)</b> 294-09-13</span>', 500, 1),
(179, 'zamanuha_left', 'Замануха слева на всех страницах', 'html', '<span class="font_28">"Легкий Старт"</span>\r\n<div class="pad_l_20">в сети\r\n  интернет с минимальными вложениями.\r\n  <span class="font_19">Мы предлагаем выгодные условия по <a href="#">созданию</a><br /> и <a href="#">продвижению</a> сайтов.</span>\r\n  \r\n  <span class="font_19">Наша компания <a href="#">разработает сайт</a> от  <i class="font_23">700</i> $</span>\r\n  </div>', 500, 1),
(180, 'top_top', 'Самая верхняя строка', 'html', '<span class="first">Лёгкий старт в сети интернет с минимальными вложениями.</span> Мы предлагаем выгодные условия по созданию и продвижению сайта.<span> Наша компания разработает сайт от 700 $. </span>', 500, 1),
(181, 'main_banner', 'Рекламный текст главная страница верх под меню', 'html', '<span class="font_42">"Легкий Старт"</span> - в сети<br /> \r\n  интернет с минимальными вложениями.\r\n  <span class="font_17">Мы предлагаем выгодные условия по <a href="#">созданию</a><br /> и <a href="#">продвижению</a> сайтов.</span>\r\n  \r\n  <span class="font_21">Наша компания <a href="#">разработает сайт</a> от  <i class="font_23">700</i> $</span>', 500, 1),
(182, 'contact_top_left', 'Контактные данные слева вверху', 'html', '<span class="phone"><i>+375 (17)</i> 294-09-13</span>\r\n<span class="adress">г. Минск , ул. З. Бядули,15</span>', 500, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `site_catalog_division`
--

DROP TABLE IF EXISTS `site_catalog_division`;
CREATE TABLE IF NOT EXISTS `site_catalog_division` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_page` int(11) unsigned NOT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned DEFAULT '0',
  `products_count` int(11) NOT NULL DEFAULT '0',
  `sortid` int(5) unsigned DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `intro` text,
  `description` text,
  `img` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` tinytext,
  `seo_keywords` tinytext,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `sortid` (`sortid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='таблица разделов товаров' AUTO_INCREMENT=174 ;

--
-- Дамп данных таблицы `site_catalog_division`
--

INSERT INTO `site_catalog_division` (`id`, `id_page`, `parent_id`, `level`, `products_count`, `sortid`, `name`, `intro`, `description`, `img`, `seo_title`, `seo_description`, `seo_keywords`) VALUES
(63, 0, 0, 0, 0, 1, 'Пицца', '<p>ПиццаПиццаПиццаПиццаПицца ПиццаПиццаПицца ПиццаПиццаПицца Вкусная пицца</p>', '<p>ПиццаПиццаПицца ПиццаПиццаПицца Вкусная пицца</p>', '63_img.jpg', 'Пицца', 'Пицца', 'Пицца'),
(73, 0, 0, 0, 0, 2, 'Бюргеры', '<p>&nbsp;БюргерыБюргеры БюргерыБюргерыБюргеры БюргерыБюргерыБюргеры Бюргеры</p>', '<p>БюргерыБюргеры БюргерыБюргеры БюргерыБюргеры БюргерыБюргеры1</p>', '73_img.jpg', 'Бюргеры', 'Бюргеры', 'Бюргеры'),
(83, 0, 63, 1, 3, 1, 'Открытые пиццы', '<p>Открытая пицца &mdash; пицца классическая, чаще всего круглой формы, покрытая  помидорами, расплавленным сыром, а также другими ингредиентами.  Профессиональное название таких начинок &mdash; топпинг.</p>', '<p>Открытая пицца &mdash; пицца классическая, чаще всего круглой формы, покрытая  помидорами, расплавленным сыром, а также другими ингредиентами.  Профессиональное название таких начинок &mdash; топпинг.</p>', '83_img.jpg', 'Открытые пиццы', 'Открытые пиццы', 'Открытые пиццы'),
(93, 0, 63, 1, 0, 2, 'Закрытые пиццы', '<p>Открытая пицца &mdash; пицца классическая, чаще всего круглой формы, покрытая  помидорами, расплавленным сыром, а также другими ингредиентами.  Профессиональное название таких начинок &mdash; топпинг.</p>', '<p>Открытая пицца &mdash; пицца классическая, чаще всего круглой формы, покрытая  помидорами, расплавленным сыром, а также другими ингредиентами.  Профессиональное название таких начинок &mdash; топпинг.</p>', '93_img.jpg', 'Закрытые пиццы', 'Закрытые пиццы', 'Закрытые пиццы'),
(103, 0, 0, 0, 3, 2, 'Суши', '<p>История возникновения суши берёт начало в Южной Азии, где варёный рис стали применять для приготовления и консервации рыбы. </p>', '<p>Очищенная и разрезанная на небольшие кусочки рыба посыпалась солью и смешивалась с рисом, после чего помещалась под пресс из камней, которые через несколько недель заменялись крышкой. В течение нескольких месяцев происходил процесс молочнокислой ферментации риса и рыбы, благодаря чему рыба оставалась годной к употреблению в течение года</p>', '103_img.jpg', 'Суши', 'Суши', 'Суши'),
(123, 0, 0, 0, 0, 2, 'Буритос', '<p>Для приготовления 4 порций <b>буритоса</b> возьмите: 4 тонких лаваша. 2 помидора. 1 соленый огурец. 100 г филе копченой (или соленой) красной рыбы. соль. острый соус или майонез. зелень.</p>', '<p><b>Буритос</b> - это блюдо мексиканской кухни, однако в исполнении eda.by Вы сможете остаться верными принципам славянского стола!  Для доставки вы можете выбрать мясной <b>буритос</b> (жареное мясо с картошкой и овощами, завернутое в лаваш) или овощной <b>буритос</b>...</p>', '123_img.jpg', 'Буритос', 'Буритос', 'Буритос'),
(133, 0, 123, 1, 0, 1, 'Вкусные', '<p>Довольно вкусные буритос, цена соответсвует вкусу.</p>', '<p>Довольно вкусные буритос, цена соответсвует вкусу.</p>', '133_img.jpg', 'Вкусные', 'Вкусные', 'Вкусные'),
(143, 0, 123, 1, 0, 0, 'Очень вкусные', '', '', '143_img.jpg', 'Очень вкусные', 'Очень вкусные', 'Очень вкусные'),
(153, 0, 123, 1, 0, 1, 'Неимоверно вкусные', '', '', '153_img.jpg', 'Неимоверно вкусные', 'Неимоверно вкусные', 'Неимоверно вкусные'),
(163, 0, 133, 2, 1, 0, 'Буритос с фасолью', '<p>Праздничная закуска с креветками. Паста под соусом из арахисового масла. Куриный <b>буритос</b> с овощами. Кукурузная суперпицца. Волшебная лазанья.</p>', '<p>Праздничная закуска с креветками. Паста под соусом из арахисового масла. Куриный <b>буритос</b> с овощами. Кукурузная суперпицца. Волшебная лазанья.</p>', '163_img.jpg', 'Буритос с фасолью', 'Буритос с фасолью', 'Буритос с фасолью'),
(173, 0, 0, 0, 1, 2, 'Вкусняшки', '<p>Очень вкусные штуки</p>', '<p>Очень-очень вкусные</p>', NULL, 'Вкусняшки', 'Вкусняшки', 'Вкусняшки');

-- --------------------------------------------------------

--
-- Структура таблицы `site_catalog_orders`
--

DROP TABLE IF EXISTS `site_catalog_orders`;
CREATE TABLE IF NOT EXISTS `site_catalog_orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned DEFAULT '0',
  `id_manager` int(11) unsigned DEFAULT '0',
  `status` tinyint(3) unsigned DEFAULT '0',
  `restoran_num` tinyint(2) DEFAULT '0',
  `price` int(5) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `content` text,
  `added_time` datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'заказ упал в базу',
  `processed_time` datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'заказ обработан менеджером',
  `completed_time` datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'заказ готов, отправлен заказчику',
  `edited_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'время последнего изменения заказа',
  `user_name` varchar(255) DEFAULT '',
  `user_street` tinytext,
  `user_house` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_house_block` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_flat` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_phone` varchar(255) DEFAULT '',
  `user_email` varchar(255) DEFAULT '',
  `cook_comment` text COMMENT 'коментарий поверу',
  `courier_comment` text COMMENT 'коментарий курьеру',
  `manager_comment` text COMMENT 'комент менеджера, добавляется через админку',
  `discount` int(10) unsigned DEFAULT '0' COMMENT 'скидка клиента',
  `comment_title` varchar(255) DEFAULT NULL,
  `comment_text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=258 ;

--
-- Дамп данных таблицы `site_catalog_orders`
--

INSERT INTO `site_catalog_orders` (`id`, `id_user`, `id_manager`, `status`, `restoran_num`, `price`, `title`, `content`, `added_time`, `processed_time`, `completed_time`, `edited_time`, `user_name`, `user_street`, `user_house`, `user_house_block`, `user_flat`, `user_phone`, `user_email`, `cook_comment`, `courier_comment`, `manager_comment`, `discount`, `comment_title`, `comment_text`) VALUES
(53, 0, 0, 1, 0, 0, '', 'a:1:{s:15:"constr_13231393";a:7:{s:5:"title";s:25:"Большая Пицца";s:5:"count";i:1;s:5:"price";i:2;s:11:"total_price";i:2;s:10:"id_product";s:15:"constr_13231393";s:8:"id_price";i:2;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"23";s:5:"items";a:2:{i:0;s:2:"13";i:1;s:2:"93";}s:5:"count";i:1;}}}', '2010-04-26 14:53:53', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-04-26 14:53:53', 'user_name', 'user_address', NULL, NULL, NULL, '154548', 'example@example.com', 'cook_comment', 'courier_comment', NULL, 0, NULL, NULL),
(63, 0, 0, 1, 0, 0, '', 'a:1:{s:14:"constr_2363193";a:7:{s:5:"title";s:29:"Обычный Бюргеры";s:5:"count";i:142;s:5:"price";i:25;s:11:"total_price";i:3550;s:10:"id_product";s:14:"constr_2363193";s:8:"id_price";i:25;s:5:"order";a:4:{s:7:"type_id";s:2:"23";s:4:"size";s:2:"63";s:5:"items";a:1:{i:0;s:3:"193";}s:5:"count";i:142;}}}', '2010-04-26 15:04:47', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-04-26 15:04:47', 'user_name', 'user_address', NULL, NULL, NULL, '2020327', 'example@example.com', 'cook_comment', 'courier_comment', NULL, 0, NULL, NULL),
(73, 0, 0, 1, 0, 78, '', 'a:1:{s:28:"constr_134313438393103536373";a:7:{s:5:"title";s:29:"Маленькая Пицца";s:5:"count";i:13;s:5:"price";i:6;s:11:"total_price";i:78;s:10:"id_product";s:28:"constr_134313438393103536373";s:8:"id_price";i:6;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"43";s:5:"items";a:8:{i:0;s:2:"13";i:1;s:2:"43";i:2;s:2:"83";i:3;s:2:"93";i:4;s:3:"103";i:5;s:2:"53";i:6;s:2:"63";i:7;s:2:"73";}s:5:"count";i:13;}}}', '2010-04-26 15:19:39', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-04-26 15:19:39', 'user_name', 'user_address', NULL, NULL, NULL, '2020327', 'example@example.com', 'cook_comment', 'courier_comment', NULL, 0, NULL, NULL),
(83, 0, 0, 1, 0, 624, '', 'a:1:{s:16:"constr_133334393";a:7:{s:5:"title";s:25:"Средняя Пицца";s:5:"count";s:3:"156";s:5:"price";i:4;s:11:"total_price";i:624;s:10:"id_product";s:16:"constr_133334393";s:8:"id_price";i:4;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"33";s:5:"items";a:3:{i:0;s:1:"3";i:1;s:2:"43";i:2;s:2:"93";}s:5:"count";i:15;}}}', '2010-04-26 15:40:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-04-28 14:19:12', 'user_name', 'user_address', NULL, NULL, NULL, '3332233', 'example@example.com', 'cook_comment', 'courier_comment', 'Очень срочный заказ!', 40, NULL, NULL),
(93, 0, 0, 1, 0, 34000, '', 'a:2:{s:5:"43_13";a:6:{s:5:"title";s:37:"Капричеза Маленькая";s:5:"count";i:1;s:5:"price";s:5:"12000";s:11:"total_price";i:12000;s:10:"id_product";s:2:"43";s:8:"id_price";s:2:"13";}s:5:"43_33";a:6:{s:5:"title";s:33:"Капричеза Большая";s:5:"count";i:1;s:5:"price";s:5:"22000";s:11:"total_price";i:22000;s:10:"id_product";s:2:"43";s:8:"id_price";s:2:"33";}}', '2010-04-26 16:48:45', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-04-26 16:48:45', 'user_name', 'user_address', NULL, NULL, NULL, '5554457', 'example@example.com', 'cook_comment', 'courier_comment', NULL, 0, NULL, NULL),
(103, 0, 0, 1, 0, 73000, '', 'a:1:{s:27:"constr_83243213233243253263";a:7:{s:5:"title";s:29:"Среднее Ассорти";s:5:"count";i:1;s:5:"price";i:73000;s:11:"total_price";i:73000;s:10:"id_product";s:27:"constr_83243213233243253263";s:8:"id_price";i:73000;s:5:"order";a:4:{s:7:"type_id";s:2:"83";s:4:"size";s:3:"243";s:5:"items";a:5:{i:0;s:3:"213";i:1;s:3:"233";i:2;s:3:"243";i:3;s:3:"253";i:4;s:3:"263";}s:5:"count";i:1;}}}', '2010-04-27 12:57:08', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-04-27 12:57:08', 'name', 'adress', NULL, NULL, NULL, 'phone', 'mail@mail.ru', 'sdelaite wto bi bilo vkysno', 'dostavte gor9chim!', NULL, 0, NULL, NULL),
(113, 0, 0, 1, 0, 700, '', 'a:4:{s:5:"43_33";a:6:{s:5:"title";s:33:"Капричеза Большая";s:5:"count";i:1;s:5:"price";s:5:"22000";s:11:"total_price";i:22000;s:10:"id_product";s:2:"43";s:8:"id_price";s:2:"33";}s:5:"43_13";a:6:{s:5:"title";s:37:"Капричеза Маленькая";s:5:"count";i:1;s:5:"price";s:5:"12000";s:11:"total_price";i:12000;s:10:"id_product";s:2:"43";s:8:"id_price";s:2:"13";}s:28:"constr_134313338393103536373";a:7:{s:5:"title";s:29:"Маленькая Пицца";s:5:"count";i:2;s:5:"price";i:6;s:11:"total_price";i:12;s:10:"id_product";s:28:"constr_134313338393103536373";s:8:"id_price";i:6;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"43";s:5:"items";a:8:{i:0;s:2:"13";i:1;s:2:"33";i:2;s:2:"83";i:3;s:2:"93";i:4;s:3:"103";i:5;s:2:"53";i:6;s:2:"63";i:7;s:2:"73";}s:5:"count";i:2;}}s:14:"constr_2353193";a:7:{s:5:"title";s:29:"Двойной Бюргеры";s:5:"count";i:14;s:5:"price";i:50;s:11:"total_price";i:700;s:10:"id_product";s:14:"constr_2353193";s:8:"id_price";i:50;s:5:"order";a:4:{s:7:"type_id";s:2:"23";s:4:"size";s:2:"53";s:5:"items";a:1:{i:0;s:3:"193";}s:5:"count";i:14;}}}', '2010-04-27 14:05:51', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-04-28 15:46:01', 'user_name', 'user_address', NULL, NULL, NULL, '5554477', 'example@example.com', 'cook_comment', 'courier_comment', 'ЫЫЫ', 0, NULL, NULL),
(123, 83, 43, 3, 0, 66000, '', 'a:3:{i:53;a:6:{s:5:"title";s:20:"с Ветчиной1";s:5:"count";i:1;s:5:"price";s:5:"18000";s:11:"total_price";i:18000;s:10:"id_product";s:2:"53";s:8:"id_price";N;}s:6:"43_113";a:6:{s:5:"title";s:14:"Большая";s:5:"count";i:1;s:5:"price";s:5:"26000";s:11:"total_price";i:26000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"113";}s:6:"43_123";a:6:{s:5:"title";s:14:"Средняя";s:5:"count";i:1;s:5:"price";s:5:"22000";s:11:"total_price";i:22000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"123";}}', '2010-05-03 14:44:04', '2010-05-06 14:05:43', '2010-10-07 12:54:54', '2010-10-07 12:54:54', 'Иван', 'Челябинск', NULL, NULL, NULL, '2023214', 'krasnye@truselja.org', 'xcxvcxvcx', 'vcxvxczvzxcvxzcv', '', 10, 'Огромное спасибо!', 'Еда у вас просто великолепна! Доставили быстро, курьер очень вежливый, блюда горячие и ароматные! Буду рекомендовать вас всем своим знакомым!'),
(133, 83, 43, 3, 0, 12012, '', 'a:1:{s:15:"constr_13231373";a:7:{s:5:"title";s:25:"Большая Пицца";s:5:"count";i:12;s:5:"price";i:1001;s:11:"total_price";i:12012;s:10:"id_product";s:15:"constr_13231373";s:8:"id_price";i:1001;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"23";s:5:"items";a:2:{i:0;s:2:"13";i:1;s:2:"73";}s:5:"count";i:12;}}}', '2010-05-04 11:59:05', '2010-05-06 14:31:42', '2010-10-07 12:53:48', '2010-10-07 12:53:48', 'Иван', 'Челябинск', NULL, NULL, NULL, '1112211', 'krasnye@truselja.org', '', '', '', 10, '32424', 'fdadfgfgafg'),
(143, 93, 33, 4, 0, 79000, '', 'a:2:{s:7:"103_103";a:6:{s:5:"title";s:6:"360 г";s:5:"count";i:1;s:5:"price";s:5:"53000";s:11:"total_price";i:53000;s:10:"id_product";s:3:"103";s:8:"id_price";s:3:"103";}s:6:"43_113";a:6:{s:5:"title";s:14:"Большая";s:5:"count";i:1;s:5:"price";s:5:"26000";s:11:"total_price";i:26000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"113";}}', '2010-05-11 10:48:39', '2010-05-11 10:53:00', '2010-05-11 11:21:40', '2010-05-11 11:22:26', 'my name', 'my adress', NULL, NULL, NULL, '1', 'nickpro@tut.by', '', '', 'бла-бла-бла, бла-бла, бла-бла-бла-бла', 0, NULL, NULL),
(153, 93, 33, 2, 0, 0, '', 'a:0:{}', '2010-05-11 10:49:04', '2010-05-11 11:21:10', '0000-00-00 00:00:00', '2010-05-11 11:21:10', 'my name', 'my adress', NULL, NULL, NULL, '1', 'nickpro@tut.by', '', '', '', 0, NULL, NULL),
(163, 0, 33, 3, 0, 80000, '', 'a:5:{s:6:"43_113";a:6:{s:5:"title";s:14:"Большая";s:5:"count";i:1;s:5:"price";s:5:"26000";s:11:"total_price";i:26000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"113";}s:6:"103_93";a:6:{s:5:"title";s:7:"32 см";s:5:"count";i:2;s:5:"price";s:5:"12000";s:11:"total_price";i:24000;s:10:"id_product";s:3:"103";s:8:"id_price";s:2:"93";}s:6:"53_143";a:6:{s:5:"title";s:14:"Большая";s:5:"count";i:2;s:5:"price";s:5:"19000";s:11:"total_price";i:38000;s:10:"id_product";s:2:"53";s:8:"id_price";s:3:"143";}s:7:"103_103";a:6:{s:5:"title";s:6:"360 г";s:5:"count";i:1;s:5:"price";s:5:"53000";s:11:"total_price";i:53000;s:10:"id_product";s:3:"103";s:8:"id_price";s:3:"103";}s:16:"constr_103273293";a:7:{s:5:"title";s:23:"средний Тако";s:5:"count";i:8;s:5:"price";i:10000;s:11:"total_price";i:80000;s:10:"id_product";s:16:"constr_103273293";s:8:"id_price";i:10000;s:5:"order";a:4:{s:7:"type_id";s:3:"103";s:4:"size";s:3:"273";s:5:"items";a:1:{i:0;s:3:"293";}s:5:"count";i:8;}}}', '2010-05-11 12:32:53', '2010-05-11 12:35:09', '2010-05-11 12:40:39', '2010-05-11 12:40:39', '1', '1', NULL, NULL, NULL, '1', '1@tut.by', '1', '1', 'sadfsadfsadfsadfsafd', 0, NULL, NULL),
(173, 0, 0, 1, 0, 0, '', 'a:0:{}', '2010-05-11 12:33:59', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-05-11 12:33:59', '1', '1', NULL, NULL, NULL, '1', '1@tut.by', '1', '1', NULL, 0, NULL, NULL),
(183, 0, 0, 1, 0, 3000, '', 'a:1:{s:7:"123_183";a:6:{s:5:"title";s:14:"Большая";s:5:"count";i:1;s:5:"price";s:4:"3000";s:11:"total_price";i:3000;s:10:"id_product";s:3:"123";s:8:"id_price";s:3:"183";}}', '2010-05-11 17:20:19', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-05-11 17:20:19', 'Вася', 'БОМЖ', NULL, NULL, NULL, '1234567', 'ugenk@tut.by', 'хочу кушать', 'быстро', NULL, 0, NULL, NULL),
(193, 0, 0, 1, 0, 26630, '', 'a:2:{s:28:"constr_132313238393103536373";a:7:{s:5:"title";s:25:"Большая Пицца";s:5:"count";i:1;s:5:"price";i:630;s:11:"total_price";i:630;s:10:"id_product";s:28:"constr_132313238393103536373";s:8:"id_price";i:630;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"23";s:5:"items";a:8:{i:0;s:2:"13";i:1;s:2:"23";i:2;s:2:"83";i:3;s:2:"93";i:4;s:3:"103";i:5;s:2:"53";i:6;s:2:"63";i:7;s:2:"73";}s:5:"count";i:1;}}s:6:"43_113";a:6:{s:5:"title";s:14:"Большая";s:5:"count";i:1;s:5:"price";s:5:"26000";s:11:"total_price";i:26000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"113";}}', '2010-05-11 18:58:43', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-05-11 18:58:43', 'user_name', 'user_address', NULL, NULL, NULL, 'user_phone', 'example@example.com', 'cook_comment', 'courier_comment', NULL, 0, NULL, NULL),
(203, 0, 0, 1, 0, 26000, '', 'a:1:{s:6:"43_113";a:6:{s:5:"title";s:14:"Большая";s:5:"count";i:1;s:5:"price";s:5:"26000";s:11:"total_price";i:26000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"113";}}', '2010-05-11 19:00:14', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-05-11 19:00:14', 'user_name', 'user_address', NULL, NULL, NULL, 'user_phone', 'example@example.com', 'cook_comment', 'courier_comment', NULL, 0, NULL, NULL),
(213, 123, 14, 2, 0, 7293, '', 'a:3:{s:6:"43_113";a:6:{s:5:"title";s:14:"Большая";s:5:"count";i:1;s:5:"price";s:5:"26000";s:11:"total_price";i:26000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"113";}s:6:"53_153";a:6:{s:5:"title";s:14:"Средняя";s:5:"count";i:1;s:5:"price";s:5:"21000";s:11:"total_price";i:21000;s:10:"id_product";s:2:"53";s:8:"id_price";s:3:"153";}s:20:"constr_1323343839363";a:7:{s:5:"title";s:25:"Большая Пицца";s:5:"count";i:13;s:5:"price";i:561;s:11:"total_price";i:7293;s:10:"id_product";s:20:"constr_1323343839363";s:8:"id_price";i:561;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"23";s:5:"items";a:5:{i:0;s:1:"3";i:1;s:2:"43";i:2;s:2:"83";i:3;s:2:"93";i:4;s:2:"63";}s:5:"count";i:13;}}}', '2010-05-12 10:33:53', '2010-05-12 10:36:48', '0000-00-00 00:00:00', '2010-05-12 10:36:48', 'Максим', 'Скрипникова', '35', '1', '136', '2020327', 'maks@gmail.com', 'scfdfdsfd', 'dsfadsf', NULL, 0, NULL, NULL),
(223, 0, 0, 1, 0, 3432, '', 'a:4:{s:7:"103_103";a:6:{s:5:"title";s:66:"Буритос с маринованными овощами 360 г";s:5:"count";i:1;s:5:"price";s:5:"53000";s:11:"total_price";i:53000;s:10:"id_product";s:3:"103";s:8:"id_price";s:3:"103";}s:6:"43_123";a:6:{s:5:"title";s:33:"Капричеза Средняя";s:5:"count";i:1;s:5:"price";s:5:"22000";s:11:"total_price";i:22000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"123";}s:6:"53_163";a:6:{s:5:"title";s:39:"с Ветчиной1 Маленькая";s:5:"count";i:1;s:5:"price";s:5:"10000";s:11:"total_price";i:10000;s:10:"id_product";s:2:"53";s:8:"id_price";s:3:"163";}s:21:"constr_13331333839373";a:7:{s:5:"title";s:25:"Средняя Пицца";s:5:"count";i:13;s:5:"price";i:264;s:11:"total_price";i:3432;s:10:"id_product";s:21:"constr_13331333839373";s:8:"id_price";i:264;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"33";s:5:"items";a:5:{i:0;s:2:"13";i:1;s:2:"33";i:2;s:2:"83";i:3;s:2:"93";i:4;s:2:"73";}s:5:"count";i:13;}}}', '2010-05-12 11:21:09', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-05-12 11:21:09', 'user_name', 'user_street', '1', '2', '2', '2221122', 'example@example.com', 'cook_comment', 'courier_comment', NULL, 0, NULL, NULL),
(233, 0, 0, 1, 0, 1072, '', 'a:1:{s:19:"constr_132313238363";a:7:{s:5:"title";s:25:"Большая Пицца";s:5:"count";i:2;s:5:"price";i:536;s:11:"total_price";i:1072;s:10:"id_product";s:19:"constr_132313238363";s:8:"id_price";i:536;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"23";s:5:"items";a:4:{i:0;s:2:"13";i:1;s:2:"23";i:2;s:2:"83";i:3;s:2:"63";}s:5:"count";i:2;}}}', '2010-05-12 14:48:34', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-05-12 14:48:34', 'dasdasd', 'dasdasd', '32', '', '', '2221122', 'example@example.com', 'dsdsd', 'asdasd', NULL, 0, NULL, NULL),
(243, 83, 0, 1, 0, 852, '', 'a:1:{s:21:"constr_13433338310363";a:7:{s:5:"title";s:29:"Маленькая Пицца";s:5:"count";s:1:"6";s:5:"price";i:142;s:11:"total_price";i:852;s:10:"id_product";s:21:"constr_13433338310363";s:8:"id_price";i:142;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"43";s:5:"items";a:5:{i:0;s:1:"3";i:1;s:2:"33";i:2;s:2:"83";i:3;s:3:"103";i:4;s:2:"63";}s:5:"count";i:3;}}}', '2010-05-12 14:50:28', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-10-07 12:50:25', 'dasdasd2', 'dasdasd', '1', '2', '2', '2221122', 'example@example.com', 'dsdfds', 'fdsfdsf', NULL, 0, NULL, NULL),
(253, 83, 14, 4, 0, 771, '', 'a:1:{s:14:"constr_1333393";a:7:{s:5:"title";s:25:"Средняя Пицца";s:5:"count";s:1:"3";s:5:"price";i:257;s:11:"total_price";i:771;s:10:"id_product";s:14:"constr_1333393";s:8:"id_price";i:257;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"33";s:5:"items";a:2:{i:0;s:1:"3";i:1;s:2:"93";}s:5:"count";i:1;}}}', '2010-05-12 15:01:58', '2010-10-07 12:50:43', '2010-10-07 12:51:49', '2010-10-07 12:52:22', 'dasdasd334234', 'dasdasd', '1', '', '', '1112211', 'example@example.com', 'вфыв', 'вфыв', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam quis mi eu elit tempor facilisis id et neque. Nulla sit amet sem sapien. Vestibulum imperdiet porta ante ac ornare. Nulla et lorem eu nibh adipiscing ultricies nec at lacus. Cras laoreet ultricies sem, at blandit mi eleifend aliquam. Nunc enim ipsum, vehicula non pretium varius, cursus ac tortor. Vivamus fringilla congue laoreet. Quisque ultrices sodales orci, quis rhoncus justo auctor in. Phasellus dui eros, bibendum eu feugiat ornare, faucibus eu mi. Nunc aliquet tempus sem, id aliquam diam varius ac. Maecenas nisl nunc, molestie vitae eleifend vel, iaculis sed magna. Aenean tempus lacus vitae orci posuere porttitor eget non felis. Donec lectus elit, aliquam nec eleifend sit amet, vestibulum sed nunc. ', 0, NULL, NULL),
(254, 0, 14, 2, 1, 10000, 'с Ветчиной1 Маленькая', 'a:4:{s:6:"43_123";a:6:{s:5:"title";s:33:"Капричеза Средняя";s:5:"count";s:1:"1";s:5:"price";s:5:"22000";s:11:"total_price";i:22000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"123";}s:6:"53_153";a:6:{s:5:"title";s:35:"с Ветчиной1 Средняя";s:5:"count";s:1:"1";s:5:"price";s:5:"21000";s:11:"total_price";i:21000;s:10:"id_product";s:2:"53";s:8:"id_price";s:3:"153";}s:6:"43_133";a:6:{s:5:"title";s:37:"Капричеза Маленькая";s:5:"count";s:1:"3";s:5:"price";s:5:"18000";s:11:"total_price";i:54000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"133";}s:6:"53_163";a:6:{s:5:"title";s:39:"с Ветчиной1 Маленькая";s:5:"count";s:1:"3";s:5:"price";s:5:"10000";s:11:"total_price";i:30000;s:10:"id_product";s:2:"53";s:8:"id_price";s:3:"163";}}', '2010-10-06 16:00:32', '2010-10-08 11:18:04', '0000-00-00 00:00:00', '2010-10-08 11:18:54', 'user_name', 'user_street', 'user_house', 'user_house_block', 'user_flat', 'user_phone', '', 'cook_comment', 'courier_comment', '', 0, NULL, NULL),
(255, 83, 0, 1, 0, 1198, '', 'a:1:{s:25:"constr_132313338393536373";a:7:{s:5:"title";s:25:"Большая Пицца";s:5:"count";i:2;s:5:"price";i:599;s:11:"total_price";i:1198;s:10:"id_product";s:25:"constr_132313338393536373";s:8:"id_price";i:599;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"23";s:5:"items";a:7:{i:0;s:2:"13";i:1;s:2:"33";i:2;s:2:"83";i:3;s:2:"93";i:4;s:2:"53";i:5;s:2:"63";i:6;s:2:"73";}s:5:"count";i:2;}}}', '2010-10-08 11:31:02', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-10-08 11:31:02', 'Иван', 'Челябинск', 'user_house', '', '', '2020327', 'krasnye@truselja.org', '', '', NULL, 10, NULL, NULL),
(256, 83, 0, 1, 0, 6255, 'Средняя Пицца', 'a:1:{s:25:"constr_132313438393536373";a:7:{s:5:"title";s:25:"Большая Пицца";s:5:"count";s:1:"2";s:5:"price";i:17003;s:11:"total_price";i:34006;s:10:"id_product";s:25:"constr_132313438393536373";s:8:"id_price";i:25003;s:5:"order";a:4:{s:7:"type_id";s:2:"13";s:4:"size";s:2:"23";s:5:"items";a:5:{i:0;s:2:"13";i:1;s:2:"43";i:2;s:2:"83";i:4;s:2:"53";i:5;s:2:"63";}s:5:"count";i:1;}}}', '2010-10-08 11:47:12', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-10-08 15:08:52', 'Иван', 'Челябинск', 'dsdsd', '', '', '2020327', 'krasnye@truselja.org', '', '', NULL, 10, NULL, NULL),
(257, 83, 0, 1, 1, 52000, 'Капричеза Большая', 'a:1:{s:6:"43_113";a:6:{s:5:"title";s:33:"Капричеза Большая";s:5:"count";s:1:"2";s:5:"price";s:5:"26000";s:11:"total_price";i:52000;s:10:"id_product";s:2:"43";s:8:"id_price";s:3:"113";}}', '2010-10-08 15:16:22', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2010-10-08 15:37:36', 'Иван', 'Челябинск', 'wwww', '', '', '2020327', 'krasnye@truselja.org', '', '', '', 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `site_catalog_product`
--

DROP TABLE IF EXISTS `site_catalog_product`;
CREATE TABLE IF NOT EXISTS `site_catalog_product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_division` int(11) NOT NULL,
  `priority` int(5) DEFAULT '0',
  `active` tinyint(1) DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `price` float DEFAULT NULL,
  `intro` text NOT NULL,
  `description` text,
  `is_new` int(1) DEFAULT '0',
  `popular` smallint(1) NOT NULL DEFAULT '0',
  `seo_title` varchar(255) DEFAULT '',
  `seo_keywords` tinytext,
  `seo_description` tinytext,
  PRIMARY KEY (`id`),
  KEY `division_id` (`id_division`,`price`),
  KEY `prior` (`priority`),
  KEY `is_action` (`popular`),
  FULLTEXT KEY `fulltext_fields` (`title`,`intro`,`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='таблица основных полей для товара' AUTO_INCREMENT=234 ;

--
-- Дамп данных таблицы `site_catalog_product`
--

INSERT INTO `site_catalog_product` (`id`, `id_division`, `priority`, `active`, `title`, `price`, `intro`, `description`, `is_new`, `popular`, `seo_title`, `seo_keywords`, `seo_description`) VALUES
(123, 173, 0, 1, 'Няма', 1000, '<p>Няма</p>', '<p>Няма</p>', 0, 0, 'Няма', 'Няма', 'Няма'),
(233, 83, 0, 1, 'title1', 0, '', '', 0, 0, 'seo_title', 'seo_keywords', 'seo_description'),
(43, 83, 50, 1, 'Капричеза', 0, '<p>Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза !</p>', '<p>Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза  Капричеза КапричезаКапричеза Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза  Капричеза КапричезаКапричеза Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза  Капричеза Капричеза !!!</p>', 0, 1, 'Капричеза', 'Капричеза', 'Капричеза'),
(53, 83, 40, 1, 'с Ветчиной1', 0, '<p>с Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчиной</p>', '<p>с Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчиной</p>', 0, 1, 'с Ветчиной1', 'с Ветчиной1', 'с Ветчиной1'),
(73, 103, 0, 1, 'отвар 1 отвар 1 отвар 1', 20000, '<p>краткое описание товара</p>', '<p>полное описание товара</p>', 0, 0, 'отвар 1', 'отвар 1', 'отвар 1'),
(83, 103, 0, 1, 'отвар 2', NULL, '<p>ыфва</p>', '<p>фыва</p>', 0, 0, 'отвар 2', 'отвар 2', 'отвар 2'),
(93, 103, 0, 1, 'Хосомаки', NULL, '<p>Маленькие, цилиндрической формы, с <i>нори</i> снаружи. Обычно <i>хосомаки</i> толщиной и шириной около 2&nbsp;см. Они обычно делаются лишь с одним видом начинки.</p>', '<p>Маленькие, цилиндрической формы, с <i>нори</i> снаружи. Обычно <i>хосомаки</i> толщиной и шириной около 2&nbsp;см. Они обычно делаются лишь с одним видом начинки.</p>', 0, 0, 'отвар 3', 'отвар 3', 'отвар 3'),
(103, 163, 0, 1, 'Буритос с маринованными овощами', 0, '', '', 0, 1, 'Буритос с маринованными овощами', 'Буритос с маринованными овощами', 'Буритос с маринованными овощами');

-- --------------------------------------------------------

--
-- Структура таблицы `site_catalog_product_default`
--

DROP TABLE IF EXISTS `site_catalog_product_default`;
CREATE TABLE IF NOT EXISTS `site_catalog_product_default` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `system_name` varchar(255) DEFAULT '',
  `active` tinyint(1) DEFAULT '0',
  `form_type` varchar(50) DEFAULT 'input',
  `default_value` varchar(255) DEFAULT '',
  `priority` int(5) DEFAULT '500',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94 ;

--
-- Дамп данных таблицы `site_catalog_product_default`
--

INSERT INTO `site_catalog_product_default` (`id`, `title`, `system_name`, `active`, `form_type`, `default_value`, `priority`) VALUES
(93, 'Артикул', 'articul', 1, 'input', '123456', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `site_catalog_product_default_values`
--

DROP TABLE IF EXISTS `site_catalog_product_default_values`;
CREATE TABLE IF NOT EXISTS `site_catalog_product_default_values` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_default` int(11) DEFAULT '0',
  `id_product` int(11) DEFAULT '0',
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=374 ;

--
-- Дамп данных таблицы `site_catalog_product_default_values`
--

INSERT INTO `site_catalog_product_default_values` (`id`, `id_default`, `id_product`, `value`) VALUES
(303, 93, 43, '123456'),
(313, 93, 53, ''),
(323, 93, 73, '123456'),
(333, 93, 83, '123456'),
(343, 93, 93, '123456'),
(353, 93, 103, '123456'),
(363, 93, 123, '123456'),
(373, 93, 233, '123456');

-- --------------------------------------------------------

--
-- Структура таблицы `site_catalog_product_images`
--

DROP TABLE IF EXISTS `site_catalog_product_images`;
CREATE TABLE IF NOT EXISTS `site_catalog_product_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(11) DEFAULT '0',
  `priority` int(5) DEFAULT '0',
  `active` int(1) DEFAULT '1',
  `main` int(1) DEFAULT '0',
  `title` varchar(255) CHARACTER SET cp1251 DEFAULT '',
  `img` varchar(255) CHARACTER SET cp1251 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=144 ;

--
-- Дамп данных таблицы `site_catalog_product_images`
--

INSERT INTO `site_catalog_product_images` (`id`, `id_product`, `priority`, `active`, `main`, `title`, `img`) VALUES
(2, 20, 5, 1, 1, '', '2_small.jpg'),
(3, 20, 6, 1, 0, '', NULL),
(4, 24, 12, 1, 0, '', NULL),
(5, 26, 12, 1, 0, '', '5_small.jpg'),
(19, 28, 0, 1, 0, 'спецназ', 'phpD8_img.jpg'),
(20, 28, 0, 1, 0, '2222', 'phpD9_img.jpg'),
(21, 28, 50, 1, 1, 'test', 'phpDA_img.jpg'),
(22, 28, 0, 1, 0, '3333', 'phpDB_img.jpg'),
(23, 28, 0, 1, 0, 'trdt1111', 'phpE0_img.jpg'),
(24, 28, 0, 1, 0, 'пежо 1', 'php56_img.jpg'),
(93, 43, 0, 1, 1, '', '20100408_12_43_55_img.jpg'),
(103, 53, 0, 1, 1, '', '20100408_12_48_35_img.jpg'),
(113, 73, 0, 1, 1, '', '20100414_02_51_51_img.jpg'),
(123, 83, 0, 1, 1, '', '20100414_02_59_24_img.jpg'),
(133, 93, 0, 1, 1, '', '20100415_12_58_22_img.jpg'),
(143, 103, 0, 1, 1, '', '20100428_10_44_24_img.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `site_catalog_product_options`
--

DROP TABLE IF EXISTS `site_catalog_product_options`;
CREATE TABLE IF NOT EXISTS `site_catalog_product_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

--
-- Дамп данных таблицы `site_catalog_product_options`
--

INSERT INTO `site_catalog_product_options` (`id`, `title`) VALUES
(4, 'Вес'),
(10, 'Размер блюда'),
(73, 'Толщина');

-- --------------------------------------------------------

--
-- Структура таблицы `site_catalog_product_options_enabled`
--

DROP TABLE IF EXISTS `site_catalog_product_options_enabled`;
CREATE TABLE IF NOT EXISTS `site_catalog_product_options_enabled` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `priority` int(11) unsigned DEFAULT '0',
  `priority_site` int(11) unsigned DEFAULT '0',
  `id_option` int(11) unsigned DEFAULT '0',
  `id_product` int(11) unsigned DEFAULT '0',
  `required` tinyint(1) unsigned DEFAULT '0',
  `type` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_option` (`id_option`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94 ;

--
-- Дамп данных таблицы `site_catalog_product_options_enabled`
--

INSERT INTO `site_catalog_product_options_enabled` (`id`, `priority`, `priority_site`, `id_option`, `id_product`, `required`, `type`) VALUES
(7, 0, 0, 10, 28, 0, ''),
(13, 100, 100, 10, 43, 0, ''),
(23, 50, 50, 4, 43, 0, ''),
(53, 10, 50, 73, 73, 0, ''),
(63, 0, 0, 10, 103, 0, ''),
(73, 0, 0, 4, 103, 0, ''),
(83, 100, 50, 10, 53, 0, ''),
(93, 1, 1, 10, 123, 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `site_catalog_product_options_prices`
--

DROP TABLE IF EXISTS `site_catalog_product_options_prices`;
CREATE TABLE IF NOT EXISTS `site_catalog_product_options_prices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(11) unsigned DEFAULT '0',
  `id_option` int(11) unsigned DEFAULT '0',
  `id_value` int(11) unsigned DEFAULT '0',
  `price` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=184 ;

--
-- Дамп данных таблицы `site_catalog_product_options_prices`
--

INSERT INTO `site_catalog_product_options_prices` (`id`, `id_product`, `id_option`, `id_value`, `price`) VALUES
(83, 73, 73, 83, 200000),
(93, 103, 10, 93, 12000),
(103, 103, 4, 103, 53000),
(113, 43, 10, 113, 26000),
(123, 43, 10, 123, 22000),
(133, 43, 10, 133, 18000),
(143, 53, 10, 143, 19000),
(153, 53, 10, 153, 21000),
(163, 53, 10, 163, 10000),
(173, 123, 10, 173, 1500),
(183, 123, 10, 183, 3000);

-- --------------------------------------------------------

--
-- Структура таблицы `site_catalog_product_options_values`
--

DROP TABLE IF EXISTS `site_catalog_product_options_values`;
CREATE TABLE IF NOT EXISTS `site_catalog_product_options_values` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `priority` int(11) unsigned DEFAULT '0',
  `id_product` int(11) unsigned DEFAULT '0',
  `id_option` int(11) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `description` tinytext,
  `active` tinyint(1) unsigned DEFAULT '0',
  `default` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=184 ;

--
-- Дамп данных таблицы `site_catalog_product_options_values`
--

INSERT INTO `site_catalog_product_options_values` (`id`, `priority`, `id_product`, `id_option`, `title`, `description`, `active`, `default`) VALUES
(83, 0, 73, 73, 'толстая', '', 0, 0),
(93, 0, 103, 10, '32 см', '<p>описание</p>', 0, 0),
(103, 0, 103, 4, '360 г', '<p>описание</p>', 0, 0),
(113, 100, 43, 10, 'Большая', '<p>Большая пица</p>', 0, 0),
(123, 60, 43, 10, 'Средняя', '<p>Средняя пица</p>', 0, 0),
(133, 0, 43, 10, 'Маленькая', '<p>Маленькая пица</p>', 0, 0),
(143, 50, 53, 10, 'Большая', '', 0, 0),
(153, 30, 53, 10, 'Средняя', '', 0, 0),
(163, 20, 53, 10, 'Маленькая', '', 0, 0),
(173, 0, 123, 10, 'Маленькая', '<p>Малышка</p>', 0, 0),
(183, 2, 123, 10, 'Большая', '<p>Большая няма</p>', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `site_companies`
--

DROP TABLE IF EXISTS `site_companies`;
CREATE TABLE IF NOT EXISTS `site_companies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_category` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `priority` int(5) NOT NULL DEFAULT '100',
  `title` varchar(255) DEFAULT '',
  `intro` text,
  `content` longtext,
  `adress` varchar(255) DEFAULT '',
  `phone` varchar(255) DEFAULT '',
  `phone_alter` varchar(255) DEFAULT '',
  `email` varchar(100) DEFAULT '',
  `icq` varchar(50) DEFAULT '',
  `skype` varchar(50) DEFAULT '',
  `city` varchar(100) DEFAULT '',
  `country` varchar(100) DEFAULT '',
  `logo` varchar(255) DEFAULT NULL,
  `site` varchar(255) DEFAULT '',
  `added` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=144 ;

--
-- Дамп данных таблицы `site_companies`
--

INSERT INTO `site_companies` (`id`, `id_category`, `active`, `priority`, `title`, `intro`, `content`, `adress`, `phone`, `phone_alter`, `email`, `icq`, `skype`, `city`, `country`, `logo`, `site`, `added`) VALUES
(13, 33, 1, 10, 'vertex', '<p>Региональные различия в ценах на многие основные продукты питания в Беларуси превышают 50%. Объяснить это географическими или климатическими проблемами в рамках такой небольшой страны невозможно.</p>', '<p>Региональные различия в ценах на многие основные продукты питания в Беларуси превышают 50%. Объяснить это географическими или климатическими проблемами в рамках такой небольшой страны невозможно.Региональные различия в ценах на многие основные продукты питания в Беларуси превышают 50%. Объяснить это географическими или климатическими проблемами в рамках такой небольшой страны невозможно.Региональные различия в ценах на многие основные продукты питания в Беларуси превышают 50%. Объяснить это географическими или климатическими проблемами в рамках такой небольшой страны невозможно.</p>', 'sdfdfsdfsdf', '', '', 'example@example.com', '34123123123', '3123123', 'Минск', '', '13_logo.jpg', '', NULL),
(23, 33, 1, 0, 'edit[title]', '', '', 'edit[adress]', '', '', 'example@example.com', 'edit[icq]', 'edit[skype]', 'edit[city]', '', '23_logo.jpg', '', NULL),
(33, 33, 1, 0, 'edit[title]', '', '', 'edit[adress]', '', '', '', 'edit[icq]', 'edit[skype]', 'edit[city]', '', NULL, '', NULL),
(83, 0, 0, 100, 'title', 'intro', 'content', 'adress', 'phone', 'phone_alter', 'example@example.com', '123', 'skype', '1', '', '', 'http://site.com', NULL),
(93, 33, 1, 100, 'title', 'intro', 'content', 'adress', 'phone', 'phone_alter', 'example@example.com', '4234234', 'skype', '1', '', '93_logo.jpeg', 'http://site.com', NULL),
(103, 33, 0, 100, 'title', 'intro', 'content', 'adress', 'phone', 'phone_alter', 'example@example.com', '1321321', 'skype', '1', '', NULL, 'http://site.com', NULL),
(113, 33, 0, 100, 'title_test', 'intro', 'content', 'adress', 'phone', 'phone_alter', 'example@example.com', '12332131', 'skype', '1', '', '113_logo.jpeg', 'http://site.com', NULL),
(123, 53, 1, 100, 'title_test', '		 			 			 	intro		 		 		 ', '		 			 			 	content		 		 		 ', 'adress', 'phone', 'phone_alter', 'example@example.com', '23123123', 'skype', '1', '', NULL, 'http://site.com', NULL),
(133, 53, 1, 100, 'Whiskey&amp;Milk', 'Строим дома		 			 ', '		 			 выавыавыа', 'Скрипникова 35-136', '2020327', '2001122', 'maksim.sherstobitow@gmail.com', '196844375', 'maksim.sherstobitow', '1', '', NULL, 'http://www.wm.by', NULL),
(143, 53, 0, 100, 'title_added', 'intro', 'content', 'adress', 'phone', 'phone_alter', 'example@example.com', '11213123', 'skype', '2', '', NULL, 'http://www.sitename.com', '2010-02-18 17:10:10');

-- --------------------------------------------------------

--
-- Структура таблицы `site_content`
--

DROP TABLE IF EXISTS `site_content`;
CREATE TABLE IF NOT EXISTS `site_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) unsigned DEFAULT NULL,
  `level` int(11) unsigned NOT NULL DEFAULT '0',
  `id_div_type` int(10) DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  `is_active` int(1) unsigned DEFAULT NULL,
  `path` varchar(150) NOT NULL,
  `title` varchar(255) NOT NULL,
  `intro` text NOT NULL,
  `content` longtext NOT NULL,
  `module` varchar(50) NOT NULL,
  `allow_delete` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `deleted` int(1) unsigned NOT NULL DEFAULT '0',
  `show_in_sitemap` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `show_childs` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `img` varchar(255) DEFAULT NULL,
  `inside_items` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'переход в модуль',
  PRIMARY KEY (`id`),
  KEY `show_childs` (`show_childs`),
  KEY `FK_site_content` (`id_parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=1085 ;

--
-- Дамп данных таблицы `site_content`
--

INSERT INTO `site_content` (`id`, `id_parent`, `level`, `id_div_type`, `priority`, `is_active`, `path`, `title`, `intro`, `content`, `module`, `allow_delete`, `deleted`, `show_in_sitemap`, `show_childs`, `img`, `inside_items`) VALUES
(1, NULL, 0, 0, 0, 0, '', 'Pokushat', '', '', '', 1, 0, 1, 0, NULL, 0),
(844, 1, 1, 424, 1, 1, '', 'Главная', '<h1>Система управления сайтом (CMS)</h1>\r\n<p>Система позволяет легко и быстро создавать и редактировать страницы вашего сайта за считанные минуты. Многие сайты-визитки организаций порой работают на громоздких системах управления сайтом, которые стоили значительных вложений в их покупку и разработку шаблона сайта. С нашей CMS системой мы можем предложить Вам недорогой и рабочий проект сайта компании, ничуть не уступающий по быстроте работы и управляемости.</p>\r\n\r\n<h2>Основные возможности системы:</h2>\r\n<ul>\r\n  <li>Создание категорий и страниц для материалов сайта.</li>\r\n  <li>Генерация ЧПУ для страниц сайта с возможностю указать вручную имя ссылки на страницу.</li>\r\n  <li>Встроенный визуальный редактор для редактирования страниц.</li>\r\n  <li>Загрузка изображений, файлов на сайт с последующей вставкой в материал.</li>\r\n  <li>Возможность выводить ссылки в список меню.</li>\r\n  <li>Редактрирование HTML-кода шаблона прямо в админке.</li>\r\n  <li>Добавление новых модулей для сайта.</li>\r\n  <li>Страница обратной связи пользователей с вами.</li>\r\n</ul>\r\n<h2>Благодаря нашей системе Вы сможете получить дополнительные\r\nпреимущества работы Вашего сайта, а это:</h2>\r\n<ul>\r\n  <li>Сайт можно разместить даже на бесплатном хостинге с поддержкой только PHP.</li>\r\n  <li>Сайт создает небольшую нагрузку на сервер.</li>\r\n  <li>Сайт продолжает работать на сервере хостера даже если у него дает сбой сервис, обслуживающий базу данных.</li>\r\n  <li>Сайт легко перенести с одного сервера на другой при помощи простого копирования файлов.</li>\r\n  <li>Сайт очень быстро загружается.</li>\r\n</ul>', '<h1>Система управления сайтом (CMS)</h1>\r\n<p>Система позволяет легко и быстро создавать и редактировать страницы вашего сайта за считанные минуты. Многие сайты-визитки организаций порой работают на громоздких системах управления сайтом, которые стоили значительных вложений в их покупку и разработку шаблона сайта. С нашей CMS системой мы можем предложить Вам недорогой и рабочий проект сайта компании, ничуть не уступающий по быстроте работы и управляемости.</p>\r\n\r\n<h2>Основные возможности системы:</h2>\r\n<ul>\r\n  <li>Создание категорий и страниц для материалов сайта.</li>\r\n  <li>Генерация ЧПУ для страниц сайта с возможностю указать вручную имя ссылки на страницу.</li>\r\n  <li>Встроенный визуальный редактор для редактирования страниц.</li>\r\n  <li>Загрузка изображений, файлов на сайт с последующей вставкой в материал.</li>\r\n  <li>Возможность выводить ссылки в список меню.</li>\r\n  <li>Редактрирование HTML-кода шаблона прямо в админке.</li>\r\n  <li>Добавление новых модулей для сайта.</li>\r\n  <li>Страница обратной связи пользователей с вами.</li>\r\n</ul>\r\n<h2>Благодаря нашей системе Вы сможете получить дополнительные\r\nпреимущества работы Вашего сайта, а это:</h2>\r\n<ul>\r\n  <li>Сайт можно разместить даже на бесплатном хостинге с поддержкой только PHP.</li>\r\n  <li>Сайт создает небольшую нагрузку на сервер.</li>\r\n  <li>Сайт продолжает работать на сервере хостера даже если у него дает сбой сервис, обслуживающий базу данных.</li>\r\n  <li>Сайт легко перенести с одного сервера на другой при помощи простого копирования файлов.</li>\r\n  <li>Сайт очень быстро загружается.</li>\r\n</ul>', 'pages', 1, 0, 1, 1, NULL, 0),
(845, 1, 1, 2, 7, 1, 'about', 'О компании', '', '<p>Компания &quot;Catering Team&quot; - это динамично развивающаяся компания в сфере корпоративного питания Москвы и Московской области. Приоритетными направлениями работы нашей компании являются:</p>\r\n<ul>\r\n    <li>Организация таких видов корпоративного питания, как кафе, буфетов, столовых на предприятиях и последующее обслуживание.</li>\r\n    <li>Кейтеринг: ресторан выездного обслуживания, фуршеты, организация корпоративных мероприятий и частных торжеств, организация банкетов.</li>\r\n    <li>Мы имеем большой опыт организации как крупных мероприятий до нескольких тысяч участников, так и небольших корпоративных мероприятий и праздников!</li>\r\n</ul>\r\n<h3>Как мы работаем</h3>\r\n<p>Организация банкетов, организация фуршета, свадебных торжеств, ресторан выездного обслуживания. Кейтеринг. Наша компания возьмет на себя весь комплекс услуг по выездному обслуживанию: организация фуршета, проведение свадеб, семейных праздников, корпоративных мероприятий. Специалисты высшего класса помогут вам в подготовке праздника, выборе меню и музыки для вашего мероприятия. Сотрудничество с нашей компанией &ndash; прекрасная возможность отпраздновать значительное событие, произошедшее в личной жизни или связанное<img width="133" height="105" class="f_left" style="padding-right: 10px;" src="/img/test_img.jpg" alt="" /> с профессиональной деятельностью. Мы поможем, если вам необходимо, отпраздновать Новый год, Международный женский день, 9 мая или другую дату. У вас наверняка останется в памяти праздник, организованный нашей компанией. Стоимость банкета в ресторане компании Catering Team, цены на банкет в ресторане вам подскажут наши специалисты. У нас работают отличные официанты и повара, обладающие большим опытом ресторанного обслуживания, а так же шоумены, мастера декора и музыканты.</p>\r\n<p><img width="133" height="105" class="f_right" src="/img/test_img.jpg" alt="" /></p>\r\n<p>Будем рады видеть вас в новом ресторане города Москвы &laquo;Драфт&raquo;! Для вас разнообразное меню с конкурентными ценами. Повара высшего класса, внимательный и вежливый персонал. Лучший выбор отдыха в Москве ресторан &laquo;Драфт&raquo;!</p>', 'pages', 1, 0, 1, 1, NULL, 0),
(846, 1, 1, 567, 5, 1, 'newslist', 'Новости', '', '', 'pages', 1, 0, 1, 1, NULL, 0),
(847, 1, 1, 2, 11, 0, 'menu', 'Меню', '', '', '', 1, 1, 1, 1, NULL, 0),
(848, 1, 1, 2, 15, 0, 'service', 'Услуги', '', '<p>developer@proweb.by</p>', 'pages', 1, 1, 1, 1, NULL, 0),
(849, 1, 1, 443, 9, 1, 'contacts', 'Контакты', '', '<div class="f_left width_480">\r\n<p>Компания &quot;Pokushat.by&quot; - это динамично развивающаяся компания в сфере корпоративного питания Минска.</p>\r\n<p class="pad_left_50">119270, Минск<br />\r\nпр. Скорины 100<br />\r\nТелефоны: +(37517) 288-66-88, +(37517) 288-66-88<br />\r\n<a href="mailto:sale@pokushat.by">sale@pokushat.by</a></p>\r\n<p>Резюме направляйте по адресу или по факсу:</p>\r\n<p class="pad_left_50"><a href="mailto:sale@figaro.ru ">sale@figaro.ru</a><br />\r\n+7 (495) 788-66-88<br />\r\nПредложения для отдела маркетинга, рекламы и PR направляйте по адресу:<br />\r\nVinogradovaTV@corpusgroup.ru<br />\r\nПожелания нашему ресторану направляйте по адресу:<br />\r\n<a href="mailto:wish@figaro.ru">wish@figaro.ru</a></p>\r\n</div>\r\n<div class="f_right width_310"><img height="240" width="299" src="/img/map.jpg" alt="" /></div>', 'pages', 1, 0, 1, 0, NULL, 0),
(851, 1, 1, 425, 22, 1, 'sitemap', 'Карта сайта', '', '', 'pages', 1, 0, 0, 1, NULL, 0),
(853, 1, 1, 433, 23, 1, 'search', 'Поиск', '', '', '', 1, 0, 1, 1, NULL, 0),
(923, 1, 1, 493, 24, 1, 'profile', 'Кабинет пользователя', '', '', 'pages', 1, 0, 0, 0, NULL, 0),
(933, 923, 2, 453, 1, 1, 'registration', 'Регистрация', '', '', 'pages', 1, 0, 0, 0, NULL, 0),
(943, 923, 2, 463, 2, 1, 'login', 'Авторизация пользователя', '', '', 'pages', 1, 0, 0, 1, NULL, 0),
(953, 923, 2, 473, 3, 1, 'logout', 'Выход из авторизации', '', '', 'pages', 1, 0, 0, 1, NULL, 0),
(963, 923, 2, 483, 4, 1, 'rememberpassword', 'Восстановление пароля', '', '', 'pages', 1, 0, 0, 1, NULL, 0),
(973, 1, 1, 503, 25, 1, 'katalog', 'Каталог', '', '<p>Phasellus nulla ante, commodo quis pulvinar consequat, tincidunt vel  tortor. Cras cursus quam quis tortor faucibus posuere. Nunc a nibh et  ipsum congue sodales. Phasellus mattis, odio a mollis varius, urna  turpis aliquam orci, nec volutpat sem dui ut nibh. Nunc eget fringilla  erat. Quisque volutpat auctor orci sit amet sagittis. Curabitur rutrum  tellus nec eros venenatis sodales. Donec bibendum dictum auctor.  Suspendisse a felis sapien, sed pretium elit. Aliquam a erat ante.  Aenean ut mi sed massa vulputate semper.</p>', 'pages', 1, 0, 1, 1, NULL, 0),
(983, 1, 1, 2, 26, 0, 'test_eeeeeeeee', 'test_eeeeeeeee', '<p>test_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeee</p>', '<p>test_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeeetest_eeeeeeeee</p>', '', 1, 1, 1, 1, NULL, 0),
(993, 973, 2, 513, 1, 1, 'cart', 'Корзина', '', '', 'pages', 1, 0, 1, 1, NULL, 0),
(1003, 847, 2, 2, 1, 0, 'pod-menyu', 'под-меню', '', '', '', 1, 1, 1, 1, NULL, 0),
(1013, 1, 1, 523, 26, 1, 'dishmake', 'Конструктор', '', '', '', 1, 0, 1, 1, NULL, 0),
(1033, 923, 2, 543, 5, 1, 'editprofile', 'Редактирование данных', '', '', 'pages', 1, 0, 0, 1, NULL, 0),
(1053, 923, 2, 553, 6, 1, 'ordershistory', 'История заказов', '', '', '', 1, 0, 1, 1, NULL, 0),
(1063, 923, 2, 563, 7, 1, 'orderdetails', 'Детализация заказа', '', '', '', 1, 0, 1, 1, NULL, 0),
(1075, 1, 1, 565, 2, 1, 'portfoliolist', 'Портфолио', '', '', 'pages', 1, 0, 1, 1, NULL, 0),
(1076, 1, 1, 564, 6, 1, 'articleslist', 'Статьи', '', '', 'pages', 1, 0, 1, 1, NULL, 0),
(1077, 1, 1, 566, 8, 1, 'otzivyview', 'Отзывы', '', '', 'pages', 1, 0, 1, 1, NULL, 0),
(1078, 1, 1, 2, 10, 1, 'legkij_start', 'Сайт - легкий старт', '', '', 'pages', 1, 0, 1, 1, NULL, 0),
(1079, 1, 1, 2, 17, 1, 'cms', 'CMS ', '', '', 'pages', 1, 0, 1, 1, NULL, 0),
(1080, 1, 1, 2, 18, 1, 'podderzhka_sajta', 'Поддержка сайта', '<p>\r\n	Поддержка сайтаПоддержка сайтаПоддержка сайтаПоддержка сайтаПоддержка сайтаПоддержка сайта</p>\r\n', '<p>\r\n	Поддержка сайтаПоддержка сайтаПоддержка сайтаПоддержка сайтаПоддержка сайтаПоддержка сайтаПоддержка сайта</p>\r\n', 'pages', 1, 0, 1, 1, NULL, 0),
(1081, 1, 1, 2, 19, 1, 'prodvizhenie_sajta', 'Продвижение сайта', '', '', 'pages', 1, 0, 1, 1, NULL, 0),
(1082, 1, 1, 2, 20, 1, 'zakazat_sajt', 'Заказать сайт', '', '', 'pages', 1, 0, 1, 1, NULL, 0),
(1083, 1, 1, 2, 21, 1, 'fufelok_fuflovy', 'Вторая запись в Блоге', '', '<p>dddddddddddddddddddddddddd dddddddddddddddd</p>', 'default', 1, 0, 1, 1, NULL, 0),
(1084, 1, 1, 2, 1, 1, 'path', 'title', '<p>\r\n	fsdfsdf</p>\r\n', '<p>\r\n	sвафывафывафыа</p>\r\n', 'pages', 1, 0, 1, 1, NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `site_divisions_type`
--

DROP TABLE IF EXISTS `site_divisions_type`;
CREATE TABLE IF NOT EXISTS `site_divisions_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_name` varchar(100) NOT NULL,
  `title` varchar(128) NOT NULL,
  `module` varchar(128) NOT NULL,
  `controller_frontend` varchar(128) NOT NULL,
  `action_frontend` varchar(128) NOT NULL,
  `controller_backend` varchar(128) NOT NULL,
  `action_backend` varchar(128) NOT NULL,
  `priority` int(5) NOT NULL DEFAULT '50',
  `active` tinyint(1) DEFAULT '0',
  `go_to_module` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=570 ;

--
-- Дамп данных таблицы `site_divisions_type`
--

INSERT INTO `site_divisions_type` (`id`, `system_name`, `title`, `module`, `controller_frontend`, `action_frontend`, `controller_backend`, `action_backend`, `priority`, `active`, `go_to_module`) VALUES
(2, 'pages', 'Внутренняя страница', 'pages', 'index', 'page', '', '', 500, 1, 0),
(424, 'main_page', 'Главная страница', 'pages', 'index', 'main', '', '', 0, 1, 0),
(425, 'sitemap', 'Карта сайта', 'pages', 'index', 'sitemap', '', '', 0, 1, 0),
(433, 'search_results', 'Результаты поиска', 'search', 'index', 'index', '', '', 0, 1, 0),
(443, 'contacts', 'Контакты (форма обратной связи)', 'forms', 'forms', 'feedback', '', '', 0, 1, 0),
(453, 'registration', 'Регистрация пользователя', 'users', 'index', 'registration', '', '', 0, 1, 0),
(463, 'login', 'Авторизация пользователя', 'users', 'index', 'login', '', '', 0, 1, 0),
(473, 'logout', 'Выход из авторизации', 'users', 'index', 'logout', '', '', 0, 1, 1),
(483, 'rememberpassword', 'Восстановление пароля', 'users', 'index', 'forgot', '', '', 0, 1, 0),
(493, 'profile', 'Кабинет пользователя', 'users', 'index', 'userarea', '', '', 0, 1, 1),
(503, 'division', 'Каталог товаров', 'catalog', 'index', 'index', '', '', 0, 1, 0),
(513, 'catalog_cart', 'Корзина', 'catalog', 'cart', 'index', 'index', '', 0, 1, 0),
(523, 'frontend_constructor', 'Конструктор', 'constructor', 'index', 'index', 'admin_types', 'index', 0, 1, 1),
(543, 'editprofile', 'Редактировать данные', 'users', 'index', 'editprofile', '', '', 0, 1, 0),
(553, 'orders_history', 'История заказов', 'users', 'index', 'ordershistory', '', '', 0, 1, 0),
(563, 'order_details', 'Детализация заказа', 'users', 'index', 'orderdetails', '', '', 0, 1, 0),
(564, 'articleslist', 'Список статей', 'articles', 'articles', 'index', 'admin_articles', 'index', 0, 1, 1),
(565, 'portfolio', 'Портфолио организации', 'portfolio', 'portfoliolist', 'index', 'admin_portfolio', 'index', 0, 1, 1),
(566, 'otzivy', 'Отзывы и предложения', 'otzivy', 'otzivy', 'index', 'admin_otzivy', 'index', 0, 1, 1),
(567, 'newslist', 'Список новостей', 'news', 'news', 'index', 'admin_news', 'index', 0, 1, 1),
(569, '404', 'Ошибка, страница не найдена', 'pages', 'pages', 'page', '', '', 0, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `site_faq`
--

DROP TABLE IF EXISTS `site_faq`;
CREATE TABLE IF NOT EXISTS `site_faq` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_page` int(11) unsigned NOT NULL DEFAULT '0',
  `question` text,
  `answer` text,
  `active` int(1) NOT NULL DEFAULT '0',
  `number` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `site_faq`
--

INSERT INTO `site_faq` (`id`, `id_page`, `question`, `answer`, `active`, `number`) VALUES
(1, 0, '<p>Не помню точно, что это был за день, но вероятнее всего &ndash; суббота. Ибо только этот день недели тревожит утренней тоской, сухостью <br />\r\n&nbsp;</p>', '<p>Не помню точно, что это был за день, но вероятнее всего &ndash; суббота. Ибо только этот день недели тревожит утренней тоской, сухостью <br />\r\nво рту и головной болью, которая царь-колоколом бьётся внутри черепной коробки. Я хмуро пялился в ящик, решая про себя вопрос о <br />\r\n(возможности) телепортации пары банок холодного пива из ближайшего супермаркета ко мне в квартиру. </p>', 1, 10),
(2, 0, '<p>Опытный прораб Петрович, как безумный, гнал грузовик по разбитой ухабистой дороге. Лупил сплошной нереальный дождь- дворники <br />\r\nбезуспешно пытались очистить залепленное смесью грязи и воды лобовое стекло, по которому бешено хлестали мокрые ветки .', '<p>Опытный прораб Петрович, как безумный, гнал грузовик по разбитой ухабистой дороге. Лупил сплошной нереальный дождь- дворники <br />\r\nбезуспешно пытались очистить залепленное смесью грязи и воды лобовое стекло, по которому бешено хлестали мокрые ветки .</p>', 1, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `site_feedback`
--

DROP TABLE IF EXISTS `site_feedback`;
CREATE TABLE IF NOT EXISTS `site_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET cp1251 NOT NULL DEFAULT '',
  `title` varchar(255) CHARACTER SET cp1251 NOT NULL DEFAULT '',
  `description` tinytext CHARACTER SET cp1251 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `site_feedback`
--

INSERT INTO `site_feedback` (`id`, `name`, `title`, `description`) VALUES
(1, 'admin', 'developer@proweb.by', 'urists');

-- --------------------------------------------------------

--
-- Структура таблицы `site_feedback_templates`
--

DROP TABLE IF EXISTS `site_feedback_templates`;
CREATE TABLE IF NOT EXISTS `site_feedback_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_name` varchar(255) DEFAULT '',
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  `fields` text NOT NULL,
  PRIMARY KEY (`id`,`name`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=134 ;

--
-- Дамп данных таблицы `site_feedback_templates`
--

INSERT INTO `site_feedback_templates` (`id`, `system_name`, `name`, `content`, `active`, `fields`) VALUES
(1, 'feedback', 'Обратная связь', '<p><strong>Имя</strong> - {fio} <br />\r\n<strong>Адрес</strong> - {adress} <br />\r\n<strong>Телефон</strong> - {phone} <br />\r\n<strong>E</strong><strong>-</strong><strong>mail</strong> - {email} <br />\r\n<strong>Текст</strong> - {text}</p>', 0, '<p>&nbsp;{fio}</p>\r\n<p>{company}</p>\r\n<p>{phone]</p>\r\n<p>{email}</p>\r\n<p>{text}</p>'),
(2, 'template', 'Шаблон', '<p>Вопрос  ФИО - {fio} <br />\r\nКомпания - {company} <br />\r\nТелефон - {phone} <br />\r\nE-mail - {email} <br />\r\nТема вопроса - {question} <br />\r\nТекст - {text}</p>', 1, ''),
(3, 'forgot_pass', 'Восстановление пароля пользователя', '<h3>Восстановление пароля </h3>\r\n<p>логин: {login}</p>\r\n<p>пароль: {password}</p>\r\n<p>&nbsp;</p>\r\n<p>С уважением,<br />\r\nслужба поддержки<br />\r\n&laquo;www.pokushat.by&raquo;</p>', 0, '<p>login</p>\r\n<p>password</p>'),
(133, 'new_order_comment', 'Добавлен отзыв о заказе', '<h3>Новый отзыв о заказе №{order_number}</h3>\r\n<p>Тема: {theme}</p>\r\n<p>Текст отзыва: {comment}</p>\r\n<p>&nbsp;</p>\r\n<p&laquo;www.pokushat.by&raquo;</p>', 0, '<p>№ {order_number}<br />\r\nТема:&nbsp; {theme}<br />\r\nТекст отзыва:&nbsp; {comment}</p>');

-- --------------------------------------------------------

--
-- Структура таблицы `site_items2tags`
--

DROP TABLE IF EXISTS `site_items2tags`;
CREATE TABLE IF NOT EXISTS `site_items2tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tag` int(10) NOT NULL DEFAULT '0',
  `id_object` int(10) NOT NULL DEFAULT '0',
  `object_type` varchar(100) NOT NULL,
  `is_main` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1910 ;

--
-- Дамп данных таблицы `site_items2tags`
--

INSERT INTO `site_items2tags` (`id`, `id_tag`, `id_object`, `object_type`, `is_main`) VALUES
(130, 2, 33, 'advt', 1),
(131, 2, 35, 'advt', 0),
(133, 4, 35, 'advt', 0),
(134, 5, 35, 'advt', 0),
(135, 6, 35, 'advt', 0),
(164, 6, 17, 'printed', 0),
(174, 4, 170, 'pages', 0),
(413, 2, 249, 'pages', 1),
(733, 2, 33, 'printed', 0),
(753, 4, 33, 'printed', 0),
(763, 5, 33, 'printed', 0),
(773, 6, 33, 'printed', 0),
(813, 2, 3, 'peoples', 0),
(883, 2, 16, 'printed', 0),
(943, 2, 231, 'pages', 1),
(963, 2, 27, 'advt', 1),
(973, 2, 43, 'advt', 1),
(983, 2, 23, 'printed', 0),
(1003, 5, 192, 'pages', 0),
(1013, 2, 192, 'pages', 1),
(1243, 2, 2, 'peoples', 1),
(1263, 5, 2, 'peoples', 0),
(1283, 2, 250, 'pages', 1),
(1383, 2, 232, 'pages', 1),
(1423, 2, 243, 'pages', 1),
(1503, 2, 233, 'pages', 1),
(1563, 6, 343, 'pages', 0),
(1573, 4, 343, 'pages', 1),
(1623, 4, 173, 'news', 0),
(1653, 4, 53, 'advt', 0),
(1663, 6, 53, 'advt', 0),
(1673, 2, 53, 'advt', 1),
(1683, 2, 63, 'advt', 0),
(1703, 4, 63, 'advt', 0),
(1713, 5, 63, 'advt', 0),
(1723, 6, 63, 'advt', 0),
(1733, 2, 73, 'advt', 0),
(1753, 4, 73, 'advt', 0),
(1763, 5, 73, 'advt', 0),
(1773, 6, 73, 'advt', 0),
(1833, 2, 53, 'printed', 1),
(1853, 4, 53, 'printed', 0),
(1863, 5, 53, 'printed', 0),
(1873, 6, 53, 'printed', 0),
(1882, 6, 230, 'pages', 0),
(1883, 2, 230, 'pages', 1),
(1885, 2, 226, 'pages', 1),
(1909, 2, 225, 'pages', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `site_mailer_letters`
--

DROP TABLE IF EXISTS `site_mailer_letters`;
CREATE TABLE IF NOT EXISTS `site_mailer_letters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` text,
  `reply_address` varchar(255) DEFAULT NULL COMMENT 'обратный адрес',
  `body` text,
  `file` varchar(255) DEFAULT NULL,
  `is_send` tinyint(1) NOT NULL DEFAULT '0',
  `date_send` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `site_mailer_letters`
--


-- --------------------------------------------------------

--
-- Структура таблицы `site_mailer_queue`
--

DROP TABLE IF EXISTS `site_mailer_queue`;
CREATE TABLE IF NOT EXISTS `site_mailer_queue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subscribe_id` int(11) NOT NULL,
  `mail_id` int(11) NOT NULL,
  PRIMARY KEY (`subscribe_id`,`mail_id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `site_mailer_queue`
--


-- --------------------------------------------------------

--
-- Структура таблицы `site_mailer_subscribers`
--

DROP TABLE IF EXISTS `site_mailer_subscribers`;
CREATE TABLE IF NOT EXISTS `site_mailer_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_subscribe` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `site_mailer_subscribers`
--

INSERT INTO `site_mailer_subscribers` (`id`, `name`, `email`, `is_subscribe`) VALUES
(3, 'Компания', 'byblik@gmail.com', b'0');

-- --------------------------------------------------------

--
-- Структура таблицы `site_menu`
--

DROP TABLE IF EXISTS `site_menu`;
CREATE TABLE IF NOT EXISTS `site_menu` (
  `pageId` int(11) unsigned NOT NULL,
  `typeId` int(11) unsigned NOT NULL,
  PRIMARY KEY (`pageId`,`typeId`),
  KEY `typeId` (`typeId`),
  KEY `pageId` (`pageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `site_menu`
--

INSERT INTO `site_menu` (`pageId`, `typeId`) VALUES
(844, 1),
(845, 1),
(846, 1),
(847, 1),
(848, 1),
(849, 1),
(1003, 1),
(1075, 1),
(1076, 1),
(1077, 1),
(1084, 1),
(1078, 7),
(1079, 7),
(1080, 7),
(1081, 7),
(1082, 7),
(1084, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `site_menu_types`
--

DROP TABLE IF EXISTS `site_menu_types`;
CREATE TABLE IF NOT EXISTS `site_menu_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `site_menu_types`
--

INSERT INTO `site_menu_types` (`id`, `name`, `title`) VALUES
(1, 'horizontal_menu', 'Горизонтальное меню в заголовке'),
(7, 'vertical_menu', 'Вертикальное слева');

-- --------------------------------------------------------

--
-- Структура таблицы `site_modules`
--

DROP TABLE IF EXISTS `site_modules`;
CREATE TABLE IF NOT EXISTS `site_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `module_ver` varchar(20) NOT NULL DEFAULT '0.0.1a',
  `title` varchar(128) NOT NULL,
  `describe` varchar(500) NOT NULL,
  `add_in_sys` date NOT NULL,
  `instdata` datetime DEFAULT NULL,
  `priority` int(5) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `installed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`(30))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Дамп данных таблицы `site_modules`
--

INSERT INTO `site_modules` (`id`, `name`, `module_ver`, `title`, `describe`, `add_in_sys`, `instdata`, `priority`, `active`, `installed`) VALUES
(1, 'admin', '0.2', 'Админ', 'Используется для работы с новостями', '2010-11-30', NULL, 0, 1, 1),
(2, 'articles', '0.2', 'Статьи', 'Используется для работы со статьями', '2010-11-30', NULL, 0, 1, 1),
(3, 'blocks', '0.2', 'Блоки', 'Используется для работы с блоками', '2010-11-30', NULL, 0, 1, 0),
(4, 'catalog', '0.2', 'Каталог', 'Используется для работы с каталогом', '2010-11-30', NULL, 0, 1, 0),
(5, 'common', '0.2', 'Общий', 'Используется для работы с новостями', '2010-11-30', NULL, 0, 1, 1),
(6, 'constructor', '0.2', 'Конструктор', 'Используется для работы с новостями', '2010-11-30', NULL, 0, 1, 0),
(7, 'feedbacktemplates', '0.2', 'Шаблоны обратной связи', 'Используется для работы с новостями', '2010-11-30', NULL, 0, 1, 1),
(8, 'forms', '0.2', 'Формы', 'Используется для работы с новостями', '2010-11-30', NULL, 0, 1, 1),
(9, 'menu', '0.2', 'Меню', 'Используется для работы с меню', '2010-11-30', NULL, 0, 1, 0),
(10, 'news', '0.2', 'Новости', 'Используется для работы с новостями', '2010-11-30', NULL, 0, 1, 1),
(11, 'orders', '0.2', 'Заказы', 'Используется для работы с заказами', '2010-11-30', NULL, 0, 1, 0),
(12, 'otzivy', '0.2', 'Отзывы и Предложения', 'Используется для работы с отзывами и предложениями', '2010-11-30', NULL, 0, 1, 0),
(13, 'pages', '0.2', 'Структура', 'Используется для работы со структурой сайта', '2010-11-30', NULL, 0, 1, 0),
(15, 'portfolio', '0.2', 'Портфолио', 'Используется для работы с портфолио', '2010-11-30', NULL, 0, 1, 0),
(16, 'search', '0.2', 'Поиск', 'Используется для поиска по сайту с учетом морфологии', '2010-11-30', NULL, 0, 1, 0),
(17, 'users', '0.2', 'Пользователи', 'Используется для работы с пользователями', '2010-11-30', NULL, 0, 1, 0),
(19, 'polls', '0.2', 'Голосование (опросы)', 'Используется для работы с опросами', '2010-11-30', NULL, 0, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `site_news`
--

DROP TABLE IF EXISTS `site_news`;
CREATE TABLE IF NOT EXISTS `site_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(150) NOT NULL DEFAULT '',
  `link` varchar(255) DEFAULT NULL,
  `teaser` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `date_news` datetime NOT NULL,
  `author` varchar(150) NOT NULL DEFAULT 'Администратор',
  `created_at` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_main` tinyint(1) NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0',
  `lighting` int(1) unsigned NOT NULL DEFAULT '0',
  `count_views` int(11) unsigned NOT NULL DEFAULT '0',
  `seo_title` varchar(150) NOT NULL DEFAULT 'Title',
  `seo_descriptions` varchar(300) NOT NULL,
  `seo_keywords` varchar(500) NOT NULL,
  `small_img` varchar(255) DEFAULT NULL,
  `big_img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Дамп данных таблицы `site_news`
--

INSERT INTO `site_news` (`id`, `name`, `url`, `link`, `teaser`, `content`, `date_news`, `author`, `created_at`, `is_active`, `is_main`, `is_hot`, `lighting`, `count_views`, `seo_title`, `seo_descriptions`, `seo_keywords`, `small_img`, `big_img`) VALUES
(39, 'lorem lipsum1', 'testovayanovost2', 'http://easystart/news/ru/admin_news/add/id_page/', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nec nisl sapien. Ut vel tempor purus. Curabitur gravida tellus sed justo varius placerat. Donec vehicula justo nec libero tincidunt tempor. Nulla ultricies nisi vel lacus suscipit vel ornare massa suscipit. Nullam at metus sem, ut porta leo. Quisque et mi felis, eget congue est. Sed quam magna, ullamcorper id iaculis sit amet, vehicula a velit. Duis tempor erat et turpis ornare porta. In arcu risus, imperdiet vel porttitor sit amet, semper ut massa. Pellentesque hendrerit tellus egestas nisl tincidunt vestibulum. Duis turpis sem, elementum nec placerat sed, egestas vitae nibh.</p>', '<p>\r\n	Maecenas tempus scelerisque sem nec accumsan. Sed at elit at dolor volutpat facilisis. Aenean quis massa magna. Mauris ac metus enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eu posuere arcu. Nullam eget justo ipsum, ut pellentesque ipsum. Aliquam et ligula velit, sed tincidunt turpis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non magna odio, pretium elementum libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;</p>\r\n<p>\r\n	Sed magna enim, malesuada ut elementum eget, gravida vitae quam. In ac nulla eu augue faucibus lobortis eu quis sem. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque faucibus metus et nibh molestie ut sagittis lorem porta. Phasellus dignissim erat nec turpis varius interdum. Aenean et massa quis erat ultricies pellentesque sed a nisl. Donec quam odio, porttitor sit amet porta vitae, dignissim sed ligula. Nunc a nisi a urna facilisis blandit. Fusce semper magna vel erat tempor ut viverra ipsum pretium. Curabitur ultrices, metus vel scelerisque iaculis, augue urna ornare orci, et mattis velit lorem non dui. Suspendisse potenti.</p>', '2010-12-10 00:00:00', 'author', '2010-12-20', 1, 0, 1, 0, 0, 'seotitleseotitleseotitleseotitle', 'seotitleseotitle', 'seotitleseotitleseotitleseotitle', '39_pejo_308.jpg', '39_pejo_308_2.jpg'),
(41, 'ЧП международного масштаба', 'test_news', '', '<p>\r\n	Maecenas tempus scelerisque sem nec accumsan. Sed at elit at dolor volutpat facilisis. Aenean quis massa magna. Mauris ac metus enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eu posuere arcu. Nullam eget justo ipsum, ut pellentesque ipsum. Aliquam et ligula velit, sed tincidunt turpis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non magna odio, pretium elementum libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;</p>', '<p>\r\n	Maecenas tempus scelerisque sem nec accumsan. Sed at elit at dolor volutpat facilisis. Aenean quis massa magna. Mauris ac metus enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eu posuere arcu. Nullam eget justo ipsum, ut pellentesque ipsum. Aliquam et ligula velit, sed tincidunt turpis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non magna odio, pretium elementum libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;</p>', '2010-12-14 00:00:00', 'author', '2010-12-14', 1, 0, 1, 0, 0, 'seotitleseotitleseotitleseotitle', 'Описание страницы', 'Ключевые слова страницы', '41_pejo_308.jpg', '41_pejo_308_2.jpg'),
(45, 'ЧП международного масштаба4', '1test-1', '', '<p>\r\n	Maecenas tempus scelerisque sem nec accumsan. Sed at elit at dolor volutpat facilisis. Aenean quis massa magna. Mauris ac metus enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eu posuere arcu. Nullam eget justo ipsum, ut pellentesque ipsum. Aliquam et ligula velit, sed tincidunt turpis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non magna odio, pretium elementum libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;</p>\r\n<p>\r\n	&nbsp;</p>\r\n<div firebugversion="1.5.4" id="_firebugConsole" style="display: none;">\r\n	&nbsp;</div>', '<p>\r\n	Maecenas tempus scelerisque sem nec accumsan. Sed at elit at dolor volutpat facilisis. Aenean quis massa magna. Mauris ac metus enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eu posuere arcu. Nullam eget justo ipsum, ut pellentesque ipsum. Aliquam et ligula velit, sed tincidunt turpis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non magna odio, pretium elementum libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas tempus scelerisque sem nec accumsan. Sed at elit at dolor volutpat facilisis. Aenean quis massa magna. Mauris ac metus enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eu posuere arcu. Nullam eget justo ipsum, ut pellentesque ipsum. Aliquam et ligula velit, sed tincidunt turpis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non magna odio, pretium elementum libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;<img alt="" src="/pics/images/pejo_308_large.jpg" style="width: 180px; height: 135px;" /></p>\r\n<div firebugversion="1.5.4" id="_firebugConsole" style="display: none;">\r\n	&nbsp;</div>', '2010-12-10 00:00:00', 'author', '2010-12-10', 1, 0, 1, 0, 2, 'Заголовок страницы', 'Описание страницы', 'seotitleseotitleseotitleseotitle', '45_pejo_308.jpg', '45_pejo_308_2.jpg'),
(46, 'тестовая новость1', 'testovayanovost1', '', '<p>\r\n	Suspendisse vulputate enim sed velit vehicula at tincidunt mauris placerat. Proin nec libero vel velit ultrices cursus ut feugiat neque. Vestibulum felis augue, viverra non venenatis varius, convallis vel odio. Duis egestas, ligula at viverra vulputate, quam metus tristique nulla, nec rutrum sem velit vel lectus. Nullam tincidunt mi eget tellus congue sodales. Etiam viverra purus ut nisl fermentum fringilla. Donec consequat, augue ut vulputate mattis, nisi mi adipiscing odio, id dapibus eros erat ac erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus placerat massa neque. Nam id felis magna. Morbi pellentesque aliquam felis quis ultrices. Pellentesque fringilla lacus eget orci bibendum fringilla.</p>\r\n<p>\r\n	&nbsp;</p>', '<p>\r\n	Suspendisse vulputate enim sed velit vehicula at tincidunt mauris placerat. Proin nec libero vel velit ultrices cursus ut feugiat neque. Vestibulum felis augue, viverra non venenatis varius, convallis vel odio. Duis egestas, ligula at viverra vulputate, quam metus tristique nulla, nec rutrum sem velit vel lectus. Nullam tincidunt mi eget tellus congue sodales. Etiam viverra purus ut nisl fermentum fringilla. Donec consequat, augue ut vulputate mattis, nisi mi adipiscing odio, id dapibus eros erat ac erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus placerat massa neque. Nam id felis magna. Morbi pellentesque aliquam felis quis ultrices. Pellentesque fringilla lacus eget orci bibendum fringilla.</p>\r\n<p>\r\n	Quisque tristique, tellus a consectetur rhoncus, lorem diam pharetra dui, eget feugiat est nulla eget quam. Aliquam ac erat massa. Vivamus id tellus et erat porta fringilla. Proin eget turpis nec elit tempus posuere at ut ipsum. Aliquam turpis lorem, auctor vel condimentum sed, posuere sed orci. Donec blandit, dolor in placerat pulvinar, orci velit ornare diam, vitae dapibus turpis erat tincidunt velit. Ut eleifend, leo non accumsan mattis, elit dui auctor sem, sit amet dignissim augue lorem eget lacus. Suspendisse quis augue et metus consectetur auctor. Vestibulum laoreet ligula vitae velit scelerisque facilisis. Sed tellus augue, congue non sodales ac, euismod eu turpis. Sed a dolor quam, non scelerisque tellus. Pellentesque tristique placerat venenatis. Etiam et dui nisi, eget pulvinar mauris. Vestibulum ac sollicitudin leo. Fusce enim erat, tincidunt eu pellentesque id, pellentesque vitae leo. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras adipiscing nibh non sapien ullamcorper convallis. Nulla mollis massa mi, at ultricies risus. Aliquam ullamcorper, lorem sed placerat varius, nisl ipsum hendrerit elit, in iaculis sapien ante at nulla.</p>', '2010-12-15 00:00:00', 'Авторитетный Автор', '2010-12-15', 1, 1, 0, 0, 1, 'тестовая новость1', 'тестовая новость1', 'тестовая новость1', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `site_otzivy`
--

DROP TABLE IF EXISTS `site_otzivy`;
CREATE TABLE IF NOT EXISTS `site_otzivy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  `added` date NOT NULL,
  `prizn` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` varchar(3000) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_main` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Дамп данных таблицы `site_otzivy`
--

INSERT INTO `site_otzivy` (`id`, `name`, `email`, `added`, `prizn`, `content`, `is_active`, `is_main`) VALUES
(8, 'serg', 'avenger999@gmail.com', '2010-11-12', 1, 'asdfasdfasdfasd\r\nvcxbxcvbcvx', 1, 0),
(9, 'sergio', 'avenger999@gmail.com', '2010-11-12', 1, 'sadfasfasd\r\nsdafasdfsdf', 1, 0),
(10, 'fufelok', 'avenger999@gmail.com', '2010-11-12', 1, 'dasfasdffs\r\njhhfjgjfggjg', 1, 0),
(12, 'Sergey', 'avenger999@gmail.com', '2010-11-14', 1, 'dssssssssssssssssgfsdg\r\nfdgsdgfdsdgfdfsgfds', 1, 0),
(14, 'ghjhhgjlk', 'ghjhg@gmail.com', '2010-11-14', 1, 'hgjkdjghdkgj dkjghgdkjgdh', 1, 0),
(15, 'ghjhhgjlk', 'ghjhg@gmail.com', '2010-11-14', 1, 'hgjkdjghdkgj dkjghgdkjgdh', 1, 0),
(16, 'Sergey', 'avenger999@gmail.com', '2010-11-14', 0, 'aaaaaaaaaaaaaadsdf\r\nsasdasds\r\nsadasdas', 1, 0),
(18, 'name1212', 'example@example.com', '2010-12-06', 1, 'content', 0, 0),
(19, 'name1212', 'example@example.com', '2010-12-06', 1, 'content', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `site_pages_options`
--

DROP TABLE IF EXISTS `site_pages_options`;
CREATE TABLE IF NOT EXISTS `site_pages_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` int(11) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '',
  `keywords` text,
  `tags` text,
  `h1` varchar(255) DEFAULT '',
  `descriptions` text,
  `title` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=25 ;

--
-- Дамп данных таблицы `site_pages_options`
--

INSERT INTO `site_pages_options` (`id`, `pageId`, `item_id`, `type`, `keywords`, `tags`, `h1`, `descriptions`, `title`) VALUES
(9, 845, 0, '', '', NULL, '', '', ''),
(10, 1083, 0, '', '', NULL, 'Заказать сайт', '', ''),
(18, 1077, 0, '', 'Отзывы', NULL, 'Отзывы', 'Отзывы', 'Отзывы'),
(20, 846, 0, '', 'Новости', NULL, '', 'Новости', 'Новости'),
(21, 1076, 0, '', 'Статьи', NULL, 'Статьи', 'Статьи', 'Статьи'),
(22, 1080, 0, '', 'Поддержка сайта', NULL, 'Поддержка сайта', 'Поддержка сайта', 'Поддержка сайта'),
(24, 1084, 0, '', 'keywords', NULL, 'h1', 'descriptions', 'page_title');

-- --------------------------------------------------------

--
-- Структура таблицы `site_poll`
--

DROP TABLE IF EXISTS `site_poll`;
CREATE TABLE IF NOT EXISTS `site_poll` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votecount` mediumint(9) NOT NULL DEFAULT '0',
  `priority` int(5) DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Дамп данных таблицы `site_poll`
--

INSERT INTO `site_poll` (`id`, `title`, `timestamp`, `votecount`, `priority`, `active`) VALUES
(13, 'Когда станет тепло???', '0000-00-00 00:00:00', 0, 2, 1),
(53, 'Кто станет королем?', '0000-00-00 00:00:00', 0, 1, 1),
(63, 'Каким мейлом вы пользуетесь?', '0000-00-00 00:00:00', 0, 10, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `site_poll_items`
--

DROP TABLE IF EXISTS `site_poll_items`;
CREATE TABLE IF NOT EXISTS `site_poll_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_poll` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `votecount` int(11) NOT NULL DEFAULT '0',
  `active` varchar(6) DEFAULT NULL,
  `priority` int(5) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_poll` (`id_poll`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `site_poll_items`
--


-- --------------------------------------------------------

--
-- Структура таблицы `site_portfolio`
--

DROP TABLE IF EXISTS `site_portfolio`;
CREATE TABLE IF NOT EXISTS `site_portfolio` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT 'portfolio',
  `title` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `teaser` varchar(1000) NOT NULL,
  `content` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `main` int(1) NOT NULL DEFAULT '0',
  `date_project` date DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `pub_date` datetime DEFAULT NULL,
  `seo_title` text,
  `seo_keywords` text,
  `seo_descriptions` text,
  `small_img` varchar(255) DEFAULT NULL,
  `big_img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `site_portfolio`
--

INSERT INTO `site_portfolio` (`id`, `type`, `title`, `url`, `teaser`, `content`, `is_active`, `main`, `date_project`, `created_at`, `pub_date`, `seo_title`, `seo_keywords`, `seo_descriptions`, `small_img`, `big_img`) VALUES
(1, 'portfolio', 'Сайт РО Белагросервис', 'http://belagroservice.by', '<p>gfffffdhdhdhdhdhdhdhdhdhdhdhdhdhdhdhdhdhdhdhgjkghkklghjkdkdh</p>', '<p>ghfgjhgjghj hgfjghfjfg ghjjjjjjfgk ghfkfg</p>', 1, 0, NULL, '2010-11-11', '2010-11-11 10:51:01', '', '', '', '1_small.jpg', NULL),
(2, 'portfolio', 'наш сайт', NULL, '<p>gfffffdhdhdhdhdhdhdhdhdhdhdhdhdhdhdhdhdhdhdhgjkghkklghjkdkdh</p>', '<p>ghfgjhgjghj hgfjghfjfg ghjjjjjjfgk ghfkfg</p>', 1, 0, NULL, '2010-11-11', '2010-11-11 10:52:44', '', '', '', '2_small.jpg', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `site_search_index`
--

DROP TABLE IF EXISTS `site_search_index`;
CREATE TABLE IF NOT EXISTS `site_search_index` (
  `id_item` int(11) unsigned NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `original_content` text,
  PRIMARY KEY (`id_item`,`type`),
  FULLTEXT KEY `content` (`content`,`original_content`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `site_search_index`
--

INSERT INTO `site_search_index` (`id_item`, `type`, `url`, `title`, `content`, `original_content`) VALUES
(1013, 'pages', '/dishmake', 'Конструктор', 'КОНСТРУКТОР', 'Конструктор  '),
(1033, 'pages', '/editprofile', 'Редактирование данных', 'ДАННЫЙ ДАННЫЕ ДАТЬ ЕДАКТИРОВАНИЕ', 'Редактирование данных  '),
(1053, 'pages', '/ordershistory', 'История заказов', 'ИСТОРИЯ ЗАКАЗ', 'История заказов  '),
(1063, 'pages', '/orderdetails', 'Детализация заказа', 'ЗАКАЗ ДЕТАЛИЗАЦИЯ', 'Детализация заказа  '),
(1075, 'pages', '/portfoliolist', 'Портфолио', 'ПОРТФОЛИО', 'Портфолио  '),
(1076, 'pages', '/articleslist', 'Статьи', 'СТАТЬЯ', 'Статьи  '),
(1077, 'pages', '/otzivyview', 'Отзывы', 'ОТЗЫВ', 'Отзывы  '),
(1078, 'pages', '/legkij_start', 'Сайт - легкий старт', 'СТАРТ САЙТ', 'Сайт - легкий старт  '),
(993, 'pages', '/cart', 'Корзина', 'КОРЗИНА', 'Корзина  '),
(923, 'pages', '/profile', 'Кабинет пользователя', 'ПОЛЬЗОВАТЕЛЬ КАБИНЕТ', 'Кабинет пользователя  '),
(933, 'pages', '/registration', 'Регистрация', 'ЕГИСТРАЦИЯ', 'Регистрация  '),
(943, 'pages', '/login', 'Авторизация пользователя', 'ПОЛЬЗОВАТЕЛЬ АВТОРИЗАЦИЯ', 'Авторизация пользователя  '),
(953, 'pages', '/logout', 'Выход из авторизации', 'ВЫХОД АВТОРИЗАЦИЯ', 'Выход из авторизации  '),
(963, 'pages', '/rememberpassword', 'Восстановление пароля', 'ПАРОЛЬ ВОССТАНОВЛЕНИЕ', 'Восстановление пароля  '),
(973, 'pages', '/katalog', 'Каталог', 'КАТАЛОГ', 'Каталог Phasellus nulla ante, commodo quis pulvinar consequat, tincidunt vel  tortor. Cras cursus quam quis tortor faucibus posuere. Nunc a nibh et  ipsum congue sodales. Phasellus mattis, odio a mollis varius, urna  turpis aliquam orci, nec volutpat sem dui ut nibh. Nunc eget fringilla  erat. Quisque volutpat auctor orci sit amet sagittis. Curabitur rutrum  tellus nec eros venenatis sodales. Donec bibendum dictum auctor.  Suspendisse a felis sapien, sed pretium elit. Aliquam a erat ante.  Aenean ut mi sed massa vulputate semper. '),
(853, 'pages', '/search', 'Поиск', 'ПОИСК', 'Поиск  '),
(851, 'pages', '/sitemap', 'Карта сайта', 'САЙТ КАРТА', 'Карта сайта  '),
(845, 'pages', '/about', 'О компании', 'ЯВЛЯТЬСЯ ШОУМЕН СПЕЦИАЛИСТ ОФИЦИАНТ МУЗЫКАНТ ЧАСТНЫЙ ЧАСТНОЕ ЦЕНА РАБОТА ФУРШЕТ КЛАСС ДЕКОР БАНКЕТ УЧАСТНИК УСЛУГА ТЫСЯЧА ТОРЖЕСТВО ТАКОЙ СФЕРА СТОЛОВЫЙ СТОЛОВАЯ СТОИМОСТЬ ВОЗМОЖНОСТЬ СОТРУДНИЧЕСТВО СОБЫТИЕ ПРОВЕДЕНИЕ СЕМЕЙНЫЙ СЕБЯ СВЯЗАТЬ СВЯЗАННЫЙ СВАДЬБА СВАДЕБНЫЙ РЕСТОРАН ПЕРСОНАЛ ОПЫТ РЕСТОРАННЫЙ КОРПОРАТИВНЫЙ РАЗНООБРАЗНЫЙ РАЗВИВАЮЩИЙСЯ РАЗВИВАТЬСЯ РАДА РАД РАБОТАТЬ ПРОФЕССИОНАЛЬНЫЙ ПРОИЗОЙТИ ПРИОРИТЕТНЫЙ ПРЕКРАСНЫЙ ПРЕДПРИЯТИЕ ПРАЗДНИК ОТДЫХ ПОСЛЕДУЮЩИЙ СЛЕДУЮЩИЙ ПОМОЧЬ ПОДСКАЗАТЬ ПОДГОТОВКА ПОВАР МАСТЕР ПИТАНИЕ МЕРОПРИЯТИЕ ПАМЯТЬ ОБЛАСТЬ ЖИЗНЬ ОТПРАЗДНОВАТЬ ОТЛИЧНЫЙ ОСТАТЬСЯ ОРГАНИЗОВАТЬ ОРГАНИЗОВАННЫЙ ОРГАНИЗАЦИЯ КОМПАНИЯ ОБСЛУЖИВАНИЕ ОБЛАДАТЬ НОВЫЙ НОВОЕ НЕСКОЛЬКО НЕОБХОДИМЫЙ НЕОБХОДИМО НЕБОЛЬШОЙ НАШ НАШИТЬ НАПРАВЛЕНИЕ НАВЕРНЯКА МУЗЫКА МОСКОВСКИЙ МОСКВА МЕНЮ МЕНЬ МЕЖДУНАРОДНЫЙ ХОРОШИЙ ЛИЧНЫЙ ЛИЧНОЙ КРУПНЫЙ КОНКУРЕНТНЫЙ КОМПЛЕКС КАФЕ КАФ ИМЕТЬ ЗНАЧИТЕЛЬНЫЙ ЖЕНСКИЙ ЕСЛИ ДРУГОЙ ДИНАМИЧНО ДИНАМИЧНЫЙ ДЕЯТЕЛЬНОСТЬ ДЕТЬ ДЕНЬ ДАТА ГОРОД ВЫСОКИЙ ВЫЕЗДНОЙ ВЫБОР ВНИМАТЕЛЬНЫЙ ВИД ВИДЕТЬ ВЕСЬ ВЕСИТЬ ВЕЖЛИВЫЙ ВАШ БУФЕТ БЫТЬ БОЛЬШОЙ БОЛЬШИЙ КЕЙТЕРИНГ ДРАФТ ВОЗЬМЕТ', 'О компании Компания &quot;Catering Team&quot; - это динамично развивающаяся компания в сфере корпоративного питания Москвы и Московской области. Приоритетными направлениями работы нашей компании являются:\r\n\r\n    Организация таких видов корпоративного питания, как кафе, буфетов, столовых на предприятиях и последующее обслуживание.\r\n    Кейтеринг: ресторан выездного обслуживания, фуршеты, организация корпоративных мероприятий и частных торжеств, организация банкетов.\r\n    Мы имеем большой опыт организации как крупных мероприятий до нескольких тысяч участников, так и небольших корпоративных мероприятий и праздников!\r\n\r\nКак мы работаем\r\nОрганизация банкетов, организация фуршета, свадебных торжеств, ресторан выездного обслуживания. Кейтеринг. Наша компания возьмет на себя весь комплекс услуг по выездному обслуживанию: организация фуршета, проведение свадеб, семейных праздников, корпоративных мероприятий. Специалисты высшего класса помогут вам в подготовке праздника, выборе меню и музыки для вашего мероприятия. Сотрудничество с нашей компанией &ndash; прекрасная возможность отпраздновать значительное событие, произошедшее в личной жизни или связанное с профессиональной деятельностью. Мы поможем, если вам необходимо, отпраздновать Новый год, Международный женский день, 9 мая или другую дату. У вас наверняка останется в памяти праздник, организованный нашей компанией. Стоимость банкета в ресторане компании Catering Team, цены на банкет в ресторане вам подскажут наши специалисты. У нас работают отличные официанты и повара, обладающие большим опытом ресторанного обслуживания, а так же шоумены, мастера декора и музыканты.\r\n\r\nБудем рады видеть вас в новом ресторане города Москвы &laquo;Драфт&raquo;! Для вас разнообразное меню с конкурентными ценами. Повара высшего класса, внимательный и вежливый персонал. Лучший выбор отдыха в Москве ресторан &laquo;Драфт&raquo;! '),
(846, 'pages', '/newslist', 'Новости', 'НОВОСТЬ', 'Новости  '),
(849, 'pages', '/contacts', 'Контакты', 'ФАКС РЕСТОРАН ТЕЛЕФОН КОНТАКТ СФЕРА РЕКЛАМА РАЗВИВАЮЩИЙСЯ РАЗВИВАТЬСЯ ПРЕДЛОЖЕНИЕ ПОЖЕЛАНИЕ ПИТАНИЕ ОТДЕЛ НАШ НАПРАВЛЯТЬ МИНСК МАРКЕТИНГ КОРПОРАТИВНЫЙ КОМПАНИЯ ДИНАМИЧНО ДИНАМИЧНЫЙ АДРЕС СКОРИН СКОРИНА ЕЗЮМ', 'Контакты \r\nКомпания &quot;Pokushat.by&quot; - это динамично развивающаяся компания в сфере корпоративного питания Минска.\r\n119270, Минск\r\nпр. Скорины 100\r\nТелефоны: +(37517) 288-66-88, +(37517) 288-66-88\r\nsale@pokushat.by\r\nРезюме направляйте по адресу или по факсу:\r\nsale@figaro.ru\r\n+7 (495) 788-66-88\r\nПредложения для отдела маркетинга, рекламы и PR направляйте по адресу:\r\nVinogradovaTV@corpusgroup.ru\r\nПожелания нашему ресторану направляйте по адресу:\r\nwish@figaro.ru\r\n\r\n '),
(4, 'news', '/newslist/item/4', 'У нас новое блюдо - 4!', 'ШТУКА СВИНОЙ ПО-ДЕРЕВЕНСКИ НОВЫЙ НОВОЕ МЕНЮ МЕНЬ КАРТОФЕЛЬ ДОБАВИТЬ БЛЮДО БЕЗУМНО БЕЗУМНЫЙ РЕБРЫШКА ОБОЛДЕННОЙ', 'У нас новое блюдо - 4! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! '),
(5, 'news', '/newslist/item/5', 'У нас новое блюдо - 5!', 'ШТУКА СВИНОЙ ПО-ДЕРЕВЕНСКИ НОВЫЙ НОВОЕ МЕНЮ МЕНЬ КАРТОФЕЛЬ ДОБАВИТЬ БЛЮДО БЕЗУМНО БЕЗУМНЫЙ РЕБРЫШКА ОБОЛДЕННОЙ', 'У нас новое блюдо - 5! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! '),
(6, 'news', '/newslist/item/6', 'У нас новое блюдо - 7!', 'ШТУКА СВИНОЙ ПО-ДЕРЕВЕНСКИ НОВЫЙ НОВОЕ МЕНЮ МЕНЬ КАРТОФЕЛЬ ДОБАВИТЬ БЛЮДО БЕЗУМНО БЕЗУМНЫЙ РЕБРЫШКА ОБОЛДЕННОЙ', 'У нас новое блюдо - 7! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! '),
(7, 'news', '/newslist/item/7', 'У нас новое блюдо - 8!', 'ШТУКА СВИНОЙ ПО-ДЕРЕВЕНСКИ НОВЫЙ НОВОЕ МЕНЮ МЕНЬ КАРТОФЕЛЬ ДОБАВИТЬ БЛЮДО БЕЗУМНО БЕЗУМНЫЙ РЕБРЫШКА ОБОЛДЕННОЙ', 'У нас новое блюдо - 8! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! '),
(8, 'news', '/newslist/item/8', 'У нас новое блюдо - 9!', 'ШТУКА СВИНОЙ ПО-ДЕРЕВЕНСКИ НОВЫЙ НОВОЕ МЕНЮ МЕНЬ КАРТОФЕЛЬ ДОБАВИТЬ БЛЮДО БЕЗУМНО БЕЗУМНЫЙ РЕБРЫШКА ОБОЛДЕННОЙ', 'У нас новое блюдо - 9! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! '),
(9, 'news', '/newslist/item/9', 'У нас новое блюдо - 10!', 'ШТУКА СВИНОЙ ПО-ДЕРЕВЕНСКИ НОВЫЙ НОВОЕ МЕНЮ МЕНЬ КАРТОФЕЛЬ ДОБАВИТЬ БЛЮДО БЕЗУМНО БЕЗУМНЫЙ РЕБРЫШКА ОБОЛДЕННОЙ', 'У нас новое блюдо - 10! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! '),
(13, 'news', '/newslist/item/13', 'Еда', 'АЫВ', 'Еда jhg dfvdf еда аыва '),
(23, 'news', '/newslist/item/23', '123', '', '123 выавыа '),
(33, 'news', '/newslist/item/33', 'Очередное новое блюдо!', 'ТЕЛО БЛЮДО ОЧЕРЕДНОЙ НОВЫЙ НОВОЕ', 'Очередное новое блюдо! тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело\r\nтело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело тело '),
(88, 'news', '/newslist/item/88', 'name test', '', 'name test \r\n	asdasdasd '),
(53, 'news', '/newslist/item/53', 'Новость дня', 'НОВОСТЬ', 'Новость дня Ура новость дня '),
(83, 'news', '/newslist/item/83', '5445', '', '5445  '),
(84, 'news', '/newslist/item/84', 'name test', '', 'name test \r\n	asdasdasd '),
(85, 'news', '/newslist/item/85', 'name test', '', 'name test \r\n	asdasdasd '),
(86, 'news', '/newslist/item/86', 'name test', '', 'name test \r\n	asdasdasd '),
(87, 'news', '/newslist/item/87', 'name test', '', 'name test \r\n	asdasdasd '),
(2, 'news', '/newslist/item/2', 'У нас новое блюдо - 2!', 'ШТУКА СВИНОЙ ПО-ДЕРЕВЕНСКИ НОВЫЙ НОВОЕ МЕНЮ МЕНЬ КАРТОФЕЛЬ ДОБАВИТЬ БЛЮДО БЕЗУМНО БЕЗУМНЫЙ РЕБРЫШКА ОБОЛДЕННОЙ', 'У нас новое блюдо - 2! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! '),
(3, 'news', '/newslist/item/3', 'У нас новое блюдо - 3!', 'ШТУКА СВИНОЙ ПО-ДЕРЕВЕНСКИ НОВЫЙ НОВОЕ МЕНЮ МЕНЬ КАРТОФЕЛЬ ДОБАВИТЬ БЛЮДО БЕЗУМНО БЕЗУМНЫЙ РЕБРЫШКА ОБОЛДЕННОЙ', 'У нас новое блюдо - 3! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! '),
(844, 'pages', '', 'Главная', 'ШАБЛОН САЙТ ХОСТИНГ ФАЙЛ УСТУПАТЬ УПРАВЛЯЕМОСТЬ УПРАВЛЕНИЕ РЕДАКТИРОВАНИЕ УКАЗАТЬ ТОЛЬКО ДАЖЕ СЧИТАТЬ СТРАНИЦА СТОИТЬ ССЫЛКА СПИСОК СОЗДАНИЕ СОЗДАВАТЬ СМОЧЬ СИСТЕМА РАБОТА МИНУТА СЕРВИС ПРОЕКТ СЕРВЕР СВЯЗЬ СБОЙ РЕДАКТОР РЕДАКТИРОВАТЬ РАЗРАБОТКА ПОКУПКА НАГРУЗКА РАЗМЕСТИТЬ РАБОЧИЙ РАБОТАТЬ ПРЯМО ПРЯМОЙ ПРОСТОЙ ПРОДОЛЖАТЬ ПОЗВОЛЯТЬ ПРЕИМУЩЕСТВО ПРЕДЛОЖИТЬ ПОЛУЧИТЬ ПОСЛЕДУЮЩИЙ СЛЕДУЮЩИЙ ПОРОЙ ПОРЫТЬ ПОРА ПОМОЩЬ ПОЛЬЗОВАТЕЛЬ ПОДДЕРЖКА ПЕРЕНЕСТИ ОЧЕНЬ НИЧУТЬ ВРУЧНУЮ ОСНОВНЫЙ ОСНОВНОЙ ОРГАНИЗАЦИЯ КАТЕГОРИЯ ОДИН ОБСЛУЖИВАЮЩИЙ ОБСЛУЖИВАТЬ ОБРАТНЫЙ НОВЫЙ НОВОЕ НЕДОРОГОЙ ОН ОНО НЕБОЛЬШОЙ НАШ НАШИТЬ МОЖНО МОЧЬ МОДУЛЬ МНОГИЙ МНОГИЕ МЕНЮ МЕНЬ МАТЕРИАЛ ЛЕГКО ЛЁГКИЙ КОТОРЫЙ КОПИРОВАНИЕ КОМПАНИЯ ИЗОБРАЖЕНИЕ ВЛОЖЕНИЕ ЗНАЧИТЕЛЬНЫЙ ЗАГРУЗКА ЗАГРУЖАТЬСЯ ЕСЛИ ДРУГОЙ ДОПОЛНИТЕЛЬНЫЙ ДОБАВЛЕНИЕ ДАННЫЙ ДАННЫЕ ДАТЬ ГРОМОЗДКИЙ ГЛАВНЫЙ ГЕНЕРАЦИЯ ВЫВОДИТЬ ВСТРОИТЬ ВСТАВКА ВОЗМОЖНОСТЬ ВИЗУАЛЬНЫЙ ВАШ ВЫ БЫСТРО БЫСТРЫЙ БЫСТРОТА БЛАГОДАРЯ БЛАГОДАРИТЬ БЕСПЛАТНЫЙ БАЗА БАЗ ХОСТЕР СОЗДАТЬ САЙТЫ-ВИЗИТКА ЕДАКТРИРОВАНИЕ АДМИНКА HTML-КОД', 'Главная Система управления сайтом (CMS)\r\nСистема позволяет легко и быстро создавать и редактировать страницы вашего сайта за считанные минуты. Многие сайты-визитки организаций порой работают на громоздких системах управления сайтом, которые стоили значительных вложений в их покупку и разработку шаблона сайта. С нашей CMS системой мы можем предложить Вам недорогой и рабочий проект сайта компании, ничуть не уступающий по быстроте работы и управляемости.\r\n\r\nОсновные возможности системы:\r\n\r\n  Создание категорий и страниц для материалов сайта.\r\n  Генерация ЧПУ для страниц сайта с возможностю указать вручную имя ссылки на страницу.\r\n  Встроенный визуальный редактор для редактирования страниц.\r\n  Загрузка изображений, файлов на сайт с последующей вставкой в материал.\r\n  Возможность выводить ссылки в список меню.\r\n  Редактрирование HTML-кода шаблона прямо в админке.\r\n  Добавление новых модулей для сайта.\r\n  Страница обратной связи пользователей с вами.\r\n\r\nБлагодаря нашей системе Вы сможете получить дополнительные\r\nпреимущества работы Вашего сайта, а это:\r\n\r\n  Сайт можно разместить даже на бесплатном хостинге с поддержкой только PHP.\r\n  Сайт создает небольшую нагрузку на сервер.\r\n  Сайт продолжает работать на сервере хостера даже если у него дает сбой сервис, обслуживающий базу данных.\r\n  Сайт легко перенести с одного сервера на другой при помощи простого копирования файлов.\r\n  Сайт очень быстро загружается.\r\n '),
(1, 'news', '/newslist/item/1', 'У нас новое блюдо - 1!', 'ШТУКА СВИНОЙ ПО-ДЕРЕВЕНСКИ НОВЫЙ НОВОЕ МЕНЮ МЕНЬ КАРТОФЕЛЬ ДОБАВИТЬ БЛЮДО БЕЗУМНО БЕЗУМНЫЙ РЕБРЫШКА ОБОЛДЕННОЙ', 'У нас новое блюдо - 1! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! В меню добавлены ребрышки свиные и картофель по-деревенски. Безумно оболденная штука! '),
(1079, 'pages', '/cms', 'CMS ', '', 'CMS   '),
(233, 'products', '/katalog/product/233', 'title1', '', 'title1  '),
(43, 'products', '/katalog/product/43', 'Капричеза', 'КАПРИЧЕЗ КАПРИЧЕЗАКАПРИЧЕЗ', 'Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза  Капричеза КапричезаКапричеза Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза  Капричеза КапричезаКапричеза Капричеза Капричеза Капричеза Капричеза Капричеза Капричеза  Капричеза Капричеза !!! '),
(53, 'products', '/katalog/product/53', 'с Ветчиной1', 'ВЕТЧИНА ВЕТЧИНОЙС', 'с Ветчиной1 с Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчинойс Ветчиной '),
(73, 'products', '/katalog/product/73', 'отвар 1 отвар 1 отвар 1', 'ТОВАР ПОЛНЫЙ ОТВАР ОПИСАНИЕ', 'отвар 1 отвар 1 отвар 1 полное описание товара '),
(83, 'products', '/katalog/product/83', 'отвар 2', 'ОТВАР ФЫВ', 'отвар 2 фыва '),
(93, 'products', '/katalog/product/93', 'Хосомаки', 'ШИРИНА ЦИЛИНДРИЧЕСКИЙ ФОРМА ТОЛЩИНА СНАРУЖИ ОКОЛО ОДИН ОБЫЧНО ОБЫЧНЫЙ НАЧИНКА МАЛЕНЬКАЯ МАЛЕНЬКИЙ ЛИШЬ ДЕЛАТЬСЯ ВИД ХОСОМАК НОРИТЬ', 'Хосомаки Маленькие, цилиндрической формы, с нори снаружи. Обычно хосомаки толщиной и шириной около 2&nbsp;см. Они обычно делаются лишь с одним видом начинки. '),
(103, 'products', '/katalog/product/103', 'Буритос с маринованными овощами', 'ОВОЩ МАРИНОВАТЬ БУРИТОС', 'Буритос с маринованными овощами  ');

-- --------------------------------------------------------

--
-- Структура таблицы `site_tags`
--

DROP TABLE IF EXISTS `site_tags`;
CREATE TABLE IF NOT EXISTS `site_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `weight` int(10) DEFAULT '0',
  `priority` int(5) DEFAULT '0',
  `active` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `site_tags`
--

INSERT INTO `site_tags` (`id`, `title`, `weight`, `priority`, `active`) VALUES
(2, 'Туризм', 10, 15, 1),
(3, 'Отдых', 24, 10, 1),
(4, 'Банерная реклама', 11, 20, 1),
(5, 'Реклама в каталогах', 10, 10, 1),
(6, 'Печатная продукция', 20, 11, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `site_users`
--

DROP TABLE IF EXISTS `site_users`;
CREATE TABLE IF NOT EXISTS `site_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `login` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `street` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `house` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_block` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `flat` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT '',
  `phone` varchar(255) DEFAULT '',
  `gender` tinyint(1) DEFAULT '0' COMMENT 'пол',
  `birthdate` varchar(20) DEFAULT NULL COMMENT 'дата рождения',
  `subscribe` tinyint(1) DEFAULT '0',
  `discount` int(10) unsigned DEFAULT '0',
  `active` tinyint(1) DEFAULT '0',
  `role` varchar(255) DEFAULT '',
  `added` datetime DEFAULT '0000-00-00 00:00:00',
  `regular_customer` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=124 ;

--
-- Дамп данных таблицы `site_users`
--

INSERT INTO `site_users` (`id`, `first_name`, `last_name`, `login`, `password`, `email`, `street`, `house`, `house_block`, `flat`, `mobile_phone`, `phone`, `gender`, `birthdate`, `subscribe`, `discount`, `active`, `role`, `added`, `regular_customer`) VALUES
(73, 'Макс', 'Шерстобитов', 'lesowik', '123456', 'maksim.sherstobitow@gmail.com', 'Скрипникова 35-136', NULL, NULL, NULL, '2020327', '', 1, '18.01.1988', 1, 0, 1, 'power_user', '2010-04-06 09:55:04', 0),
(83, 'Иван', 'Дулин', 'test111', '123456', 'krasnye@truselja.org', 'Челябинск', '12', '', '', '2020327', '', 1, '09.03.1945', 1, 11, 1, 'power_user', '2010-10-07 13:12:31', 1),
(93, 'my name', 'my last name', 'pizzalover', 'pizzalover', 'nickpro@tut.by', 'my adress', NULL, NULL, NULL, '2389416', '', 1, '11.04.1990', 1, 0, 1, 'power_user', '2010-04-30 17:58:05', 0),
(103, '1111', '', '111111', '111111', '1@tut.by', '', NULL, NULL, NULL, '111', '', 1, '', 0, 0, 1, 'power_user', '2010-05-11 11:57:55', 0),
(123, 'Максим', 'Шерстобитов', 'Maksim', '123456', 'maks@gmail.com', 'Скрипникова', '35', '1', '136', '2020327', '', 1, '18.01.1988', 0, 0, 1, 'power_user', '2010-05-12 10:14:04', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(45) NOT NULL DEFAULT '',
  `lastName` varchar(45) NOT NULL DEFAULT '',
  `username` varchar(45) NOT NULL DEFAULT '',
  `password` varchar(45) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL,
  `activity` int(11) NOT NULL,
  `deletable` int(11) NOT NULL DEFAULT '1',
  `role` varchar(45) NOT NULL DEFAULT '',
  `send_message` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `username`, `password`, `email`, `activity`, `deletable`, `role`, `send_message`) VALUES
(14, 'proweb', 'proweb', 'proweb', 'ganzaliz', 'nickpro@tut.by', 1, 0, 'admin', 1),
(43, 'manager', 'manager', 'manager', 'manager', 'maksim.sherstobitow@gmail.com', 1, 1, 'manager', 1),
(53, 'Василий', 'Крэйзенштокс', 'vasya', 'vasya', 'vasya@vasya.ru', 1, 1, 'manager', 1),
(63, 'Сергей', 'Петухов', 'sergey', 'sergey', 'sergey@proweb.by', 1, 1, 'manager', 0);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `site_content`
--
ALTER TABLE `site_content`
  ADD CONSTRAINT `FK_site_content` FOREIGN KEY (`id_parent`) REFERENCES `site_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `site_menu`
--
ALTER TABLE `site_menu`
  ADD CONSTRAINT `site_menu_fk` FOREIGN KEY (`pageId`) REFERENCES `site_content` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `site_menu_fk1` FOREIGN KEY (`typeId`) REFERENCES `site_menu_types` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `site_pages_options`
--
ALTER TABLE `site_pages_options`
  ADD CONSTRAINT `site_pages_options_fk` FOREIGN KEY (`pageId`) REFERENCES `site_content` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `site_poll_items`
--
ALTER TABLE `site_poll_items`
  ADD CONSTRAINT `site_poll_items_fk` FOREIGN KEY (`id_poll`) REFERENCES `site_poll` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
