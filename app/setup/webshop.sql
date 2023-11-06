SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `product` (
    `product_ID` int(11) NOT NULL AUTO_INCREMENT,
    `product_name` varchar(255) NOT NULL,
    `product_description` varchar(255) NOT NULL,
    `product_price` int(11) NOT NULL,
    `product_weight` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,
    `category_ID` int(11) NOT NULL,
    `discount_ID` int(11) NOT NULL,

    PRIMARY KEY (`product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `product` (`product_ID`, `product_name`, `product_description`, `product_price`, `quantity`, `product_weight`, `category_ID`, `discount_ID`) VALUES
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

CREATE TABLE `category` (
    `category_ID` int(11) NOT NULL AUTO_INCREMENT,
    `category_name` varchar(255) NOT NULL,

    PRIMARY KEY (`category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `category` (`category_ID`, `category_name`) VALUES
(1, 'Clothing'),
(2, 'Accessories'),
(3, 'Electronics'),
(4, 'Toys'),
(5, 'Home'),
(6, 'Other');

CREATE TABLE `discount` (
    `discount_ID` int(11) NOT NULL AUTO_INCREMENT,
    `discount_name` varchar(255) NOT NULL,
    `discount_percentage` int(11) NOT NULL,
    `active` boolean NOT NULL,

    PRIMARY KEY (`discount_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `discount` (`discount_ID`, `discount_name`, `discount_percentage`, `active`) VALUES
(1, 'No Discount', 0, 1),
(2, 'Black Friday', 50, 0),
(3, 'Happy New Year', 25, 0),
(4, 'Christmas', 25, 0),
(5, 'Haloween', 5, 1);

CREATE TABLE `order` (
    `order_ID` int(11) NOT NULL AUTO_INCREMENT,
    `product_ID` int(11) NOT NULL,

    PRIMARY KEY (`order_ID`),
    KEY `product_ID` (`product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `shopping_session` (
    `session_ID` int(11) NOT NULL AUTO_INCREMENT,
    `total_price` int(11) NOT NULL,

    PRIMARY KEY (`session_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cart` (
    `cart_ID` int(11) NOT NULL AUTO_INCREMENT,
    `product_ID` int(11) NOT NULL,
    `session_ID` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,

    PRIMARY KEY (`cart_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `product`
    ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_ID`) REFERENCES `category` (`category_ID`),
    ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`discount_ID`) REFERENCES `discount` (`discount_ID`);

ALTER TABLE `order`
    ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`product_ID`) REFERENCES `product` (`product_ID`);

ALTER TABLE `cart`
    ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_ID`) REFERENCES `product` (`product_ID`),
    ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`session_ID`) REFERENCES `shopping_session` (`session_ID`); 