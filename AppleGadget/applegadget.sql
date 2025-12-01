-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 01 2025 г., 03:50
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `applegadget`
--

-- --------------------------------------------------------

--
-- Структура таблицы `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `customers`
--

INSERT INTO `customers` (`id`, `login`, `password`, `phone`) VALUES
(1, '123', '123', '123'),
(3, 'вфыфв', 'фывфы', 'вфыв'),
(4, 'dasdas', 'dasdasd', 'asdasd'),
(5, '123123', '123123', '12312'),
(6, 'dsad', 'asdas', 'dasdas'),
(7, 'dsadwads', 'wadsawda', 'dasdwa'),
(8, 'sadasd', 'asdsa', 'dasdas');

-- --------------------------------------------------------

--
-- Структура таблицы `gadgets`
--

CREATE TABLE `gadgets` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `slogan` varchar(100) NOT NULL,
  `price` varchar(20) NOT NULL,
  `img` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `description` text NOT NULL,
  `data_add` datetime NOT NULL,
  `Категории` varchar(100) NOT NULL,
  `Color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `gadgets`
--

INSERT INTO `gadgets` (`id`, `name`, `slogan`, `price`, `img`, `quantity`, `description`, `data_add`, `Категории`, `Color`) VALUES
(1, 'iPhone 17 Pro', 'Стильный. Легкий. Прочный. Современный', '89 990', 'iPhone17pro.jpg', 25, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-06-15 20:15:42', 'iPhone', 'Белый'),
(2, 'MacBook Pro M3', 'Стильный. Легкий. Прочный. Современный', '109 990', 'MacBookProM3.jpg', 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-15 20:15:42', 'macbook', 'Белый'),
(3, 'Apple Watch 7', 'Стильный. Легкий. Прочный. Современный', '39 990', 'AppleWatch3.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-25 20:15:42', 'applewatch', 'Черный'),
(4, 'Air Pods Max', 'Стильный. Легкий. Прочный. Современный', '59 990', 'AirPodsMax.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-10-25 20:15:42', 'airpods', 'Синий'),
(5, 'iPhone 17 Pro', 'Стильный. Легкий. Прочный. Современный', '89 990', 'iPhone17pro.jpg', 25, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-06-15 20:15:42', 'iPhone', 'Белый'),
(6, 'MacBook Pro M3', 'Стильный. Легкий. Прочный. Современный', '109 990', 'MacBookProM3.jpg', 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-15 20:15:42', 'macbook', 'Белый'),
(7, 'Apple Watch 7', 'Стильный. Легкий. Прочный. Современный', '39 990', 'AppleWatch3.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-25 20:15:42', 'applewatch', 'Черный'),
(8, 'Air Pods Max', 'Стильный. Легкий. Прочный. Современный', '59 990', 'AirPodsMax.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-10-25 20:15:42', 'airpods', 'Синий'),
(9, 'iPhone 17 Pro', 'Стильный. Легкий. Прочный. Современный', '89 990', 'iPhone17pro.jpg', 25, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-06-15 20:15:42', 'iPhone', 'Белый'),
(10, 'MacBook Pro M3', 'Стильный. Легкий. Прочный. Современный', '109 990', 'MacBookProM3.jpg', 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-15 20:15:42', 'macbook', 'Белый'),
(11, 'Apple Watch 7', 'Стильный. Легкий. Прочный. Современный', '39 990', 'AppleWatch3.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-25 20:15:42', 'applewatch', 'Черный'),
(12, 'MacBook Pro M3', 'Стильный. Легкий. Прочный. Современный', '109 990', 'MacBookProM3.jpg', 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-15 20:15:42', 'macbook', 'Белый'),
(13, 'Apple Watch 7', 'Стильный. Легкий. Прочный. Современный', '39 990', 'AppleWatch3.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-25 20:15:42', 'applewatch', 'Черный');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gadgets`
--
ALTER TABLE `gadgets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `gadgets`
--
ALTER TABLE `gadgets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
