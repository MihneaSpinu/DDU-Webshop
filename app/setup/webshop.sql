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

INSERT INTO `users` (`uid`, `username`, `password`, `name`, `is_guest`, `joined`, `email`, `group_ID`) VALUES
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
    `quantity` int(11) DEFAULT 0,
    `category_ID` int(11) NOT NULL,
    `color_ID` int(11) NOT NULL DEFAULT 1,
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
    `uid` int(11) NOT NULL,
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
    ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`discount_ID`) REFERENCES `discounts` (`discount_ID`);
    
ALTER TABLE `cart_items`
    ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`product_ID`) REFERENCES `products` (`product_ID`),
    ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`cart_ID`) REFERENCES `cart` (`cart_ID`); 

ALTER TABLE `cart`
    ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);

ALTER TABLE `order_items`
    ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_ID`) REFERENCES `order` (`order_ID`),
    ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_ID`) REFERENCES `products` (`product_ID`);

ALTER TABLE `order`
    ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);

DELIMITER //

CREATE PROCEDURE AddColorsToProduct(
    IN productName VARCHAR(255),
    IN colorList VARCHAR(255)
)
BEGIN
    DECLARE colorExists INT;
    DECLARE noColorExists INT;

    -- Check if "no color" exists for the given product name
    SELECT COUNT(*) INTO noColorExists FROM products
    WHERE product_name = productName AND color_ID = 1;

    -- If "no color" exists, use the first color to edit the product
    IF noColorExists > 0 THEN
        UPDATE products
        SET color_ID = (SELECT color_ID FROM colors WHERE color_name = TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(colorList, ',', 1), ',', -1)))
        WHERE product_name = productName AND color_ID = 1;
        SET @editedProductID = (SELECT product_ID FROM products WHERE product_name = productName AND color_ID = 1 LIMIT 1);
    ELSE
        SET @editedProductID = NULL;
    END IF;

    -- Split the comma-separated list of colors into rows
    CREATE TEMPORARY TABLE tempColors (colorName VARCHAR(255));
    INSERT INTO tempColors (colorName) SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(colorList, ',', n.digit), ',', -1)) AS colorName FROM
    (SELECT @row := @row + 1 AS digit FROM information_schema.COLUMNS, (SELECT @row:=0) r WHERE @row < 100) n
    WHERE LENGTH(colorList) + 1 - LENGTH(REPLACE(colorList, ',', '')) >= n.digit;

    -- Loop through each color in the temporary table
    colorLoop: LOOP
        -- Check if the color already exists for the given product name
        SELECT COUNT(*) INTO colorExists FROM products
        WHERE product_name = productName AND color_ID = (SELECT color_ID FROM colors WHERE color_name = (SELECT colorName FROM tempColors LIMIT 1));

        -- If the color does not exist, insert a new product entry with that color
        IF colorExists = 0 THEN
            IF @editedProductID IS NULL THEN
                INSERT INTO products (product_name, product_description, product_price, product_weight, category_ID, color_ID, discount_ID, quantity)
                SELECT productName, product_description, product_price, product_weight, category_ID, color_ID, discount_ID, FLOOR(RAND() *(30 -10 + 1)) + 10
                FROM products WHERE product_name = productName LIMIT 1;
                SET @newProductID = LAST_INSERT_ID();
            ELSE
                SET @newProductID = @editedProductID;
            END IF;

            UPDATE products
            SET color_ID = (SELECT color_ID FROM colors WHERE color_name = (SELECT colorName FROM tempColors LIMIT 1))
            WHERE product_ID = @newProductID;
        END IF;

        -- Remove the processed color from the temporary table
        DELETE FROM tempColors LIMIT 1;

        -- Exit the loop if there are no more colors
        IF (SELECT COUNT(*) FROM tempColors) = 0 THEN
            LEAVE colorLoop;
        END IF;
    END LOOP;

    -- Drop the temporary table
    DROP TEMPORARY TABLE IF EXISTS tempColors;
END //

DELIMITER ;
