SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `users` (
    `uid` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(20) NOT NULL,
    `password` varchar(255) NOT NULL,
    `name` varchar(50) NOT NULL,
    `is_guest` boolean NOT NULL,
    `joined` datetime NOT NULL,    
    `email` varchar(255) NOT NULL,
    `group_ID` int(11) NOT NULL,

    PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `groups` (
    `group_ID` int(11) NOT NULL AUTO_INCREMENT,
    `permissions` json NOT NULL,

    PRIMARY KEY (`group_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `groups` (`group_ID`, `permissions`) VALUES
(1, '{"admin":1,"moderator":1,"user":1,"guest":1}'),
(2, '{"admin":0,"moderator":0,"user":1,"guest":1}'),
(3, '{"admin":0,"moderator":0,"user":0,"guest":1}');

CREATE TABLE `products` (
    `product_ID` int(11) NOT NULL AUTO_INCREMENT,
    `product_name` varchar(255) NOT NULL,
    `product_description` varchar(255) NOT NULL,
    `product_price` decimal(10,2) NOT NULL,
    `product_weight` int(11) NOT NULL,
    `quantity` int(11) DEFAULT 0,
    `category_ID` int(11) NOT NULL,
    `color_ID` int(11) NOT NULL DEFAULT 1,
    `size_ID` int(11) NOT NULL DEFAULT 1,
    `discount_ID` int(11) NOT NULL DEFAULT 1,

    PRIMARY KEY (`product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `categories` (
    `category_ID` int(11) NOT NULL AUTO_INCREMENT,
    `category_name` varchar(255) NOT NULL,

    PRIMARY KEY (`category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`category_ID`, `category_name`) VALUES
(1, 'T-Shirts'),
(2, 'Hats'),
(3, 'Hoodies'),
(4, 'Mouse Pads'),
(5, 'Mugs'),
(6, 'Accessories'),
(7, 'Plushies'),
(8, 'Posters'),
(9, 'Stickers');

CREATE TABLE `colors` (
    `color_ID` int(11) NOT NULL AUTO_INCREMENT,
    `color_name` varchar(255) NOT NULL,

    PRIMARY KEY (`color_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `colors` (`color_ID`, `color_name`) VALUES
(1, 'No Color'),
(2, 'White'),
(3, 'Black'),
(4, 'Red'),
(5, 'Blue'),
(6, 'Green'),
(7, 'Yellow'),
(8, 'Orange'),
(9, 'Purple'),
(10, 'Pink');

CREATE TABLE `sizes` (
    `size_ID` int(11) NOT NULL AUTO_INCREMENT,
    `size_name` varchar(255) NOT NULL,

    PRIMARY KEY (`size_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sizes` (`size_ID`, `size_name`) VALUES
(1, 'No Size'),
(2, 'XS'),
(3, 'S'),
(4, 'M'),
(5, 'L'),
(6, 'XL'),
(7, 'XXL');

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

CREATE TABLE `carts` (
    `cart_ID` int(11) NOT NULL AUTO_INCREMENT,    
    `uid` int(11) NOT NULL,
    `total_price` int(11) NOT NULL,

    PRIMARY KEY (`cart_ID`),
    UNIQUE KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `order_items` (
    `order_item_ID` int(11) NOT NULL AUTO_INCREMENT,
    `order_ID` int(11) NOT NULL,
    `product_ID` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,

    PRIMARY KEY (`order_item_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `orders` (
    `order_ID` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL,
    `total` int(11) NOT NULL,
    `order_date` datetime NOT NULL,

    PRIMARY KEY (`order_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users`
    ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`group_ID`) REFERENCES `groups` (`group_ID`);

ALTER TABLE `products`
    ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_ID`) REFERENCES `categories` (`category_ID`),
    ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`color_ID`) REFERENCES `colors` (`color_ID`),
    ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`size_ID`) REFERENCES `sizes` (`size_ID`),
    ADD CONSTRAINT `product_ibfk_4` FOREIGN KEY (`discount_ID`) REFERENCES `discounts` (`discount_ID`);
    
ALTER TABLE `cart_items`
    ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`product_ID`) REFERENCES `products` (`product_ID`),
    ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`cart_ID`) REFERENCES `carts` (`cart_ID`); 

ALTER TABLE `carts`
    ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);

ALTER TABLE `order_items`
    ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_ID`) REFERENCES `orders` (`order_ID`),
    ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_ID`) REFERENCES `products` (`product_ID`);

ALTER TABLE `orders`
    ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);

DELIMITER //

CREATE TRIGGER after_user_insert
AFTER INSERT ON users FOR EACH ROW
BEGIN
    INSERT INTO carts (uid, total_price) VALUES (NEW.uid, 0);
END;

//

DELIMITER ;

-- Trigger to update total price when a new cart item is inserted
DELIMITER //

CREATE TRIGGER after_cart_item_insert
AFTER INSERT ON cart_items FOR EACH ROW
BEGIN
    DECLARE itemPrice DECIMAL(10, 2);
    
    -- Get the price of the product for the inserted cart item
    SELECT product_price * NEW.quantity INTO itemPrice
    FROM products
    WHERE product_ID = NEW.product_ID;

    -- Update the total price of the cart
    UPDATE carts
    SET total_price = total_price + itemPrice
    WHERE cart_ID = NEW.cart_ID;
END;

//

-- Trigger to update total price when a cart item is removed
CREATE TRIGGER after_cart_item_delete
AFTER DELETE ON cart_items FOR EACH ROW
BEGIN
    DECLARE itemPrice DECIMAL(10, 2);

    -- Get the price of the product for the deleted cart item
    SELECT product_price * OLD.quantity INTO itemPrice
    FROM products
    WHERE product_ID = OLD.product_ID;

    -- Update the total price of the cart
    UPDATE carts
    SET total_price = total_price - itemPrice
    WHERE cart_ID = OLD.cart_ID;
END;

//

DELIMITER ;