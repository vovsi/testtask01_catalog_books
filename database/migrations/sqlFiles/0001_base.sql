-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июн 16 2019 г., 12:40
-- Версия сервера: 10.1.32-MariaDB
-- Версия PHP: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `catalog_books`
--

-- --------------------------------------------------------

--
-- Структура таблицы `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `middle_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `authors`
--

INSERT INTO `authors` (`id`, `first_name`, `last_name`, `middle_name`) VALUES
(1, 'Петр', 'Киселёв', 'Богуславович'),
(2, 'Владлен', 'Дьячков', 'Авксентьевич'),
(3, 'Дональд', 'Гришин', 'Аркадьевич'),
(4, 'Мартын', 'Воронов', 'Аристархович');

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `date_publishing` date NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `heading_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `name`, `date_publishing`, `publisher_id`, `heading_id`) VALUES
(103, '451° по Фаренгейту', '2012-05-15', 4, 11),
(104, 'Шантарам', '2008-05-23', 2, 16),
(105, 'Мастер и Маргарита', '2005-08-24', 5, 21),
(107, 'Вино из одуванчиков', '2018-05-18', 4, 2),
(108, 'Над пропастью во ржи', '2018-06-16', 3, 13),
(109, 'Анна Каренина', '2015-05-16', 5, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `books_authors`
--

CREATE TABLE `books_authors` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `books_authors`
--

INSERT INTO `books_authors` (`id`, `book_id`, `author_id`) VALUES
(220, 103, 1),
(221, 103, 3),
(222, 104, 1),
(223, 105, 1),
(224, 105, 3),
(226, 107, 1),
(227, 108, 3),
(228, 109, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `books_photos`
--

CREATE TABLE `books_photos` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `books_photos`
--

INSERT INTO `books_photos` (`id`, `book_id`, `photo_id`) VALUES
(110, 103, 138),
(111, 103, 139),
(112, 103, 140),
(113, 104, 141),
(114, 104, 142),
(115, 104, 143),
(116, 105, 144),
(117, 105, 145),
(118, 107, 146),
(119, 107, 147),
(120, 108, 148);

-- --------------------------------------------------------

--
-- Структура таблицы `headings`
--

CREATE TABLE `headings` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `parent_heading_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `headings`
--

INSERT INTO `headings` (`id`, `name`, `parent_heading_id`) VALUES
(1, 'ДЕТЕКТИВЫ', NULL),
(2, 'Детские детективы', 1),
(3, 'Иронические детективы', 1),
(4, 'Исторические детективы', 1),
(5, 'Классические детективы', 1),
(6, 'Криминальные детективы', 1),
(8, 'Полицейские детективы', 1),
(9, 'ДЕТСКИЕ', NULL),
(10, 'Детская образовательная', 9),
(11, 'Детская проза', 9),
(12, 'Детская фантастика', 9),
(13, 'Детские остросюжетные', 9),
(14, 'ДОКУМЕНТАЛЬНОЕ', NULL),
(15, 'Биографии и Мемуары', 14),
(16, 'Документальное: Прочее', 14),
(17, 'Искусство', 14),
(18, 'Критика', 14),
(19, 'ДОМ И СЕМЬЯ', NULL),
(20, 'ГеоПутеводитель', 19),
(21, 'Дом и Семья: Прочее', 19),
(22, 'Домашние животные', 19),
(23, 'Здоровье', 19),
(24, 'ИСТОРИЯ', NULL),
(25, 'Мировая', 24),
(26, 'Средних веков', 24),
(27, 'Страны', 24),
(28, 'Казахстан', 27),
(29, 'США', 27),
(30, 'ПРОГРАММИРОВАНИЕ', NULL),
(31, 'PHP', 30),
(32, 'JAVA', 30);

-- --------------------------------------------------------

--
-- Структура таблицы `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `photos`
--

INSERT INTO `photos` (`id`, `path`) VALUES
(138, 'database/dbStorage/bookImages/5d0608f5294bb.jpg'),
(139, 'database/dbStorage/bookImages/5d0608f59ad3e.jpg'),
(140, 'database/dbStorage/bookImages/5d0608f63ae39.jpg'),
(141, 'database/dbStorage/bookImages/5d06096e1a238.jpg'),
(142, 'database/dbStorage/bookImages/5d06096e8dde4.jpg'),
(143, 'database/dbStorage/bookImages/5d06096eda886.jpg'),
(144, 'database/dbStorage/bookImages/5d060a9cde048.jpg'),
(145, 'database/dbStorage/bookImages/5d060a9d42815.jpg'),
(146, 'database/dbStorage/bookImages/5d060b28b1d3d.jpg'),
(147, 'database/dbStorage/bookImages/5d060b28b4c1e.jpg'),
(148, 'database/dbStorage/bookImages/5d060b8954f03.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `publishers`
--

CREATE TABLE `publishers` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `phone` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `publishers`
--

INSERT INTO `publishers` (`id`, `name`, `address`, `phone`) VALUES
(1, 'АСТ/Астрель ', 'Москва, Звездный бульв., д.21 ', '61-260-796'),
(2, 'Азбука', 'Россия, г. Санкт-Петербург, ул. Кирова 34, д. 34', '23-423-123'),
(3, 'Детская литература', 'Россия, г. Москва, ул. Ткачева 47, д. 88', '46-325-678'),
(4, 'Художественная литература', 'Россия, г. Москва, ул. Арийского 12, д. 21', '34-125-545'),
(5, 'Азбука-Аттикус', 'Россия, г. Москва, ул. Армянского 33, д. 43', '45-222-555');

-- --------------------------------------------------------

--
-- Структура таблицы `versions`
--

CREATE TABLE `versions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `heading_id` (`heading_id`),
  ADD KEY `publisher_id` (`publisher_id`);

--
-- Индексы таблицы `books_authors`
--
ALTER TABLE `books_authors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Индексы таблицы `books_photos`
--
ALTER TABLE `books_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `photo_id` (`photo_id`);

--
-- Индексы таблицы `headings`
--
ALTER TABLE `headings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_heading_id` (`parent_heading_id`);

--
-- Индексы таблицы `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `versions`
--
ALTER TABLE `versions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT для таблицы `books_authors`
--
ALTER TABLE `books_authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT для таблицы `books_photos`
--
ALTER TABLE `books_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT для таблицы `headings`
--
ALTER TABLE `headings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT для таблицы `publishers`
--
ALTER TABLE `publishers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `versions`
--
ALTER TABLE `versions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`heading_id`) REFERENCES `headings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `books_authors`
--
ALTER TABLE `books_authors`
  ADD CONSTRAINT `books_authors_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `books_authors_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `books_photos`
--
ALTER TABLE `books_photos`
  ADD CONSTRAINT `books_photos_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `books_photos_ibfk_2` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `headings`
--
ALTER TABLE `headings`
  ADD CONSTRAINT `headings_ibfk_1` FOREIGN KEY (`parent_heading_id`) REFERENCES `headings` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
