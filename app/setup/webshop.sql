SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `users` (
    `user_ID` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(20) NOT NULL,
    `password` varchar(255) NOT NULL,
    `name` varchar(50) NOT NULL,
    `is_guest` boolean NOT NULL,
    `joined` datetime NOT NULL,    
    `email` varchar(255) NOT NULL,
    `group_ID` int(11) NOT NULL,

    PRIMARY KEY (`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`user_ID`, `username`, `password`, `name`, `is_guest`, `joined`, `email`, `group_ID`) VALUES
(1, 'admin', '$2y$10$qaEEEemHqZoY0K7CzylbfuXs4CeG2v9jHAjC4uQKPFUgaO3y4NS6O', 'Admin', 0, '2019-11-20 00:00:00', '', 1),
(2, 'Casper', '$2y$10$qaEEEemHqZoY0K7CzylbfuXs4CeG2v9jHAjC4uQKPFUgaO3y4NS6O', 'Casper', 0, '2019-11-20 00:00:00', '', 1),
(3, 'Gunnar', '$2y$10$qaEEEemHqZoY0K7CzylbfuXs4CeG2v9jHAjC4uQKPFUgaO3y4NS6O', 'Gunnar', 0, '2019-11-20 00:00:00', '', 1),
(4, 'Jesper', '$2y$10$qaEEEemHqZoY0K7CzylbfuXs4CeG2v9jHAjC4uQKPFUgaO3y4NS6O', 'Jesper', 0, '2019-11-20 00:00:00', '', 1),
(5, 'Mihnea', '$2y$10$qaEEEemHqZoY0K7CzylbfuXs4CeG2v9jHAjC4uQKPFUgaO3y4NS6O', 'Mihnea', 0, '2019-11-20 00:00:00', '', 1),
(6, 'Jeppe', '$2y$10$qaEEEemHqZoY0K7CzylbfuXs4CeG2v9jHAjC4uQKPFUgaO3y4NS6O', 'Jeppe', 0, '2019-11-20 00:00:00', '', 1);

CREATE TABLE `groups` (
    `group_ID` int(11) NOT NULL AUTO_INCREMENT,
    `group_name` varchar(20) NOT NULL,

    PRIMARY KEY (`group_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `groups` (`group_ID`, `group_name`) VALUES
(1, 'Admin'),
(2, 'User');

CREATE TABLE `products` (
    `product_ID` int(11) NOT NULL AUTO_INCREMENT,
    `product_name` varchar(255) NOT NULL,
    `product_description` varchar(255) NOT NULL,
    `product_price` decimal(10,2) NOT NULL,
    `product_weight` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,
    `category_ID` int(11) NOT NULL,
    `discount_ID` int(11) NOT NULL,

    PRIMARY KEY (`product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `products` (`product_ID`, `product_name`, `product_description`, `product_price`, `quantity`, `product_weight`, `category_ID`, `discount_ID`) VALUES
(1, 'Finite Abys tshirt', 'A tshirt with the Finite Abys logo on it', 100, 10, 10, 1, 1),
(2, 'Finite Abys hat', 'A hat with the Finite Abys logo on it', 50, 50, 30, 1, 1),
(3, 'Finite Abys mug', 'A mug with the Finite Abys logo on it', 25, 25, 100, 5, 1),
(4, 'Finite Abys keychain', 'A keychain with the Finite Abys logo on it', 10, 35, 5, 2, 1),
(5, 'Finite Abys sticker', 'A sticker with the Finite Abys logo on it', 5, 5, 1, 6, 1),
(6, 'Finite Abys poster', 'A poster with the Finite Abys logo on it', 15, 15, 10, 5, 1),
(7, 'Finite Abys plushie', 'A plushie with the Finite Abys logo on it', 200, 20, 150, 4, 1),
(8, 'Finite Abys socks', 'Socks with the Finite Abys logo on it', 10, 10, 20, 1, 1),
(9, 'Finite Abys pants', 'Pants with the Finite Abys logo on it', 50, 50, 50, 1, 1),
(10, 'Finite Abys shoes', 'Shoes with the Finite Abys logo on it', 100, 100, 50, 1, 1),
(11, 'Finite Abys underwear', 'Underwear with the Finite Abys logo on it', 25, 25, 10, 1, 1),
(12, 'Finite Abys belt', 'A belt with the Finite Abys logo on it', 25, 25, 30, 1, 1);

CREATE TABLE `categories` (
    `category_ID` int(11) NOT NULL AUTO_INCREMENT,
    `category_name` varchar(255) NOT NULL,

    PRIMARY KEY (`category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`category_ID`, `category_name`) VALUES
(1, 'Clothing'),
(2, 'Accessories'),
(3, 'Electronics'),
(4, 'Toys'),
(5, 'Home'),
(6, 'Other');

CREATE TABLE `discounts` (
    `discount_ID` int(11) NOT NULL AUTO_INCREMENT,
    `discount_name` varchar(255) NOT NULL,
    `discount_percentage` int(11) NOT NULL,
    `active` boolean NOT NULL,

    PRIMARY KEY (`discount_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `discounts` (`discount_ID`, `discount_name`, `discount_percentage`, `active`) VALUES
(1, 'No Discount', 0, 1),
(2, 'Black Friday', 50, 0),
(3, 'Happy New Year', 25, 0),
(4, 'Christmas', 25, 0),
(5, 'Haloween', 5, 1);

CREATE TABLE `cart_items` (
    `cart_item_ID` int(11) NOT NULL AUTO_INCREMENT,
    `cart_ID` int(11) NOT NULL,
    `product_ID` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,

    PRIMARY KEY (`cart_item_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cart` (
    `cart_ID` int(11) NOT NULL AUTO_INCREMENT,    
    `user_ID` int(11) NOT NULL,
    `total_price` int(11) NOT NULL,

    PRIMARY KEY (`cart_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `order_items` (
    `order_item_ID` int(11) NOT NULL AUTO_INCREMENT,
    `order_ID` int(11) NOT NULL,
    `product_ID` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,

    PRIMARY KEY (`order_item_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `order` (
    `order_ID` int(11) NOT NULL AUTO_INCREMENT,
    `user_ID` int(11) NOT NULL,
    `total` int(11) NOT NULL,
    `order_date` datetime NOT NULL,

    PRIMARY KEY (`order_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users`
    ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`group_ID`) REFERENCES `groups` (`group_ID`);

ALTER TABLE `products`
    ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_ID`) REFERENCES `categories` (`category_ID`),
    ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`discount_ID`) REFERENCES `discounts` (`discount_ID`);
    
ALTER TABLE `cart_items`
    ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`product_ID`) REFERENCES `products` (`product_ID`),
    ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`cart_ID`) REFERENCES `cart` (`cart_ID`); 

ALTER TABLE `cart`
    ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`user_ID`);

ALTER TABLE `order_items`
    ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_ID`) REFERENCES `order` (`order_ID`),
    ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_ID`) REFERENCES `products` (`product_ID`);

ALTER TABLE `order`
    ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`user_ID`);
