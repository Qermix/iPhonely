-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 02 2025 г., 22:22
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
(12, 'Тест', '12345', '12345'),
(13, 'джэилт', 'олд', 'бжд');

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
(1, 'iPhone 17 Pro', 'Стильный. Легкий. Прочный. Современный', '89990', 'iPhone17pro.jpg', 25, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-06-15 20:15:42', 'iPhone', 'Белый'),
(2, 'MacBook Pro M3', 'Стильный. Легкий. Прочный. Современный', '109990', 'MacBookProM3.jpg', 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-15 20:15:42', 'macbook', 'Белый'),
(3, 'Apple Watch 7', 'Стильный. Легкий. Прочный. Современный', '39990', 'iphone17pro.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-25 20:15:42', 'applewatch', 'Голубой'),
(4, 'Air Pods Max', 'Стильный. Легкий. Прочный. Современный', '59990', 'AirPodsMax.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-10-25 20:15:42', 'airpods', 'Синий'),
(5, 'iPhone 17 Pro', 'Стильный. Легкий. Прочный. Современный', '89990', 'iPhone17pro.jpg', 25, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-06-15 20:15:42', 'iPhone', 'Белый'),
(6, 'MacBook Pro M3', 'Стильный. Легкий. Прочный. Современный', '109990', 'MacBookProM3.jpg', 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-15 20:15:42', 'macbook', 'Белый'),
(7, 'Apple Watch 7', 'Стильный. Легкий. Прочный. Современный', '39990', 'AppleWatch3.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-25 20:15:42', 'applewatch', 'Черный'),
(8, 'Air Pods Max', 'Стильный. Легкий. Прочный. Современный', '59990', 'AirPodsMax.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-10-25 20:15:42', 'airpods', 'Синий'),
(9, 'iPhone 17 Pro', 'Стильный. Легкий. Прочный. Современный', '89990', 'iPhone17pro.jpg', 25, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-06-15 20:15:42', 'iPhone', 'Белый'),
(10, 'MacBook Pro M3', 'Стильный. Легкий. Прочный. Современный', '109990', 'MacBookProM3.jpg', 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-15 20:15:42', 'macbook', 'Белый'),
(11, 'Apple Watch 7', 'Стильный. Легкий. Прочный. Современный', '39990', 'AppleWatch3.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-25 20:15:42', 'applewatch', 'Черный'),
(12, 'MacBook Pro M3', 'Стильный. Легкий. Прочный. Современный', '109990', 'MacBookProM3.jpg', 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-15 20:15:42', 'macbook', 'Белый'),
(13, 'Apple Watch 7', 'Стильный. Легкий. Прочный. Современный', '39990', 'AppleWatch3.jpg', 31, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2025-08-25 20:15:42', 'applewatch', 'Черный');

-- --------------------------------------------------------

--
-- Структура таблицы `message`
--

CREATE TABLE `message` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `message`
--

INSERT INTO `message` (`id`, `name`, `text`) VALUES
(1, 'dsaa', 'dsadas'),
(2, 'dsaa', 'dsadas'),
(3, 'фсв', 'фывыф');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_login` varchar(50) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'в ожидании'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `customer_login`, `order_date`, `total_amount`, `status`) VALUES
(3, 'Тест', '2025-12-02 22:08:00', '39990.00', 'в ожидании'),
(4, 'Тест', '2025-12-02 22:19:31', '149980.00', 'в ожидании'),
(5, 'Тест', '2025-12-02 22:22:03', '39990.00', 'в ожидании');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `color` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `color`) VALUES
(6, 3, 13, 'Apple Watch 7', 1, '39990.00', 'Черный'),
(7, 4, 13, 'Apple Watch 7', 1, '39990.00', 'Черный'),
(8, 4, 12, 'MacBook Pro M3', 1, '109990.00', 'Белый'),
(9, 5, 13, 'Apple Watch 7', 1, '39990.00', 'Черный');

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
-- Индексы таблицы `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `gadgets`
--
ALTER TABLE `gadgets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `gadgets` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
