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

DELIMITER //

CREATE PROCEDURE AddSizesToProduct(
    IN productName VARCHAR(255),
    IN sizeList VARCHAR(255)
)
BEGIN
    DECLARE sizeExists INT;
    DECLARE colorIdValue INT;
    DECLARE sizeIdValue INT;
    DECLARE newProductID INT;

    -- Get the distinct color_IDs for the given product name
    CREATE TEMPORARY TABLE tempColors (colorID INT);
    INSERT INTO tempColors (colorID) SELECT DISTINCT color_ID FROM products WHERE product_name = productName;

    -- Loop through each color_ID in the temporary table
    colorLoop: LOOP
        -- Get the color_ID for the current iteration
        SET colorIdValue = (SELECT colorID FROM tempColors LIMIT 1);

        -- Check if "no size" exists for the given product name and color
        SELECT COUNT(*) INTO sizeExists FROM products
        WHERE product_name = productName AND color_ID = colorIdValue AND size_ID = 1;

        -- If "no size" exists, set the sizeIdValue to the first size in the size list
        IF sizeExists > 0 THEN
            SET sizeIdValue = (SELECT size_ID FROM sizes WHERE size_name = TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(sizeList, ',', 1), ',', -1)));
            -- Update "no size" entry with the new size
            UPDATE products
            SET size_ID = sizeIdValue
            WHERE product_name = productName AND color_ID = colorIdValue AND size_ID = 1;
            SET newProductID = (SELECT product_ID FROM products WHERE product_name = productName AND color_ID = colorIdValue AND size_ID = sizeIdValue LIMIT 1);
        ELSE
            SET sizeIdValue = (SELECT size_ID FROM sizes WHERE size_name = TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(sizeList, ',', 1), ',', -1)));
            SET newProductID = NULL;
        END IF;

        -- Split the comma-separated list of sizes into rows
        CREATE TEMPORARY TABLE tempSizes (sizeName VARCHAR(255));
        INSERT INTO tempSizes (sizeName) SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(sizeList, ',', n.digit), ',', -1)) AS sizeName FROM
        (SELECT @row := @row + 1 AS digit FROM information_schema.COLUMNS, (SELECT @row:=0) r WHERE @row < 100) n
        WHERE LENGTH(sizeList) + 1 - LENGTH(REPLACE(sizeList, ',', '')) >= n.digit;

        -- Loop through each size in the temporary table
        sizeLoop: LOOP
            -- Get the size_ID for the current iteration
            SET sizeIdValue = (SELECT size_ID FROM sizes WHERE size_name = (SELECT sizeName FROM tempSizes LIMIT 1));

            -- Check if the size already exists for the given product name and color
            SELECT COUNT(*) INTO sizeExists FROM products
            WHERE product_name = productName AND color_ID = colorIdValue AND size_ID = sizeIdValue;

            -- If the size does not exist, insert a new product entry with that size
            IF sizeExists = 0 THEN
                IF newProductID IS NULL THEN
                    INSERT INTO products (product_name, product_description, product_price, product_weight, category_ID, color_ID, size_ID, discount_ID, quantity)
                    SELECT productName, product_description, product_price, product_weight, category_ID, colorIdValue, sizeIdValue, discount_ID, FLOOR(RAND() * (30 - 10 + 1)) + 10
                    FROM products WHERE product_name = productName AND color_ID = colorIdValue LIMIT 1;
                    SET newProductID = LAST_INSERT_ID();
                END IF;

                UPDATE products
                SET size_ID = sizeIdValue
                WHERE product_ID = newProductID;
            END IF;

            -- Remove the processed size from the temporary table
            DELETE FROM tempSizes LIMIT 1;

            -- Exit the size loop if there are no more sizes
            IF (SELECT COUNT(*) FROM tempSizes) = 0 THEN
                LEAVE sizeLoop;
            END IF;
        END LOOP;

        -- Drop the temporary size table
        DROP TEMPORARY TABLE IF EXISTS tempSizes;

        -- Remove the processed color from the temporary table
        DELETE FROM tempColors LIMIT 1;

        -- Exit the color loop if there are no more colors
        IF (SELECT COUNT(*) FROM tempColors) = 0 THEN
            LEAVE colorLoop;
        END IF;
    END LOOP;

    -- Drop the temporary color table
    DROP TEMPORARY TABLE IF EXISTS tempColors;
END //

DELIMITER ;

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