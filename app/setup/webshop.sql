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

CREATE TABLE `users_sessions` (
    `users_session_ID` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL,
    `hash` varchar(255) NOT NULL,

    PRIMARY KEY (`users_session_ID`)
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
(5, 'Halloween', 5, 1);

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
    `total_price` decimal(10,2) NOT NULL,

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

ALTER TABLE `users_sessions`
    ADD CONSTRAINT `users_sessions_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);    

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

-- Trigger to update total price when a new cart item is inserted
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

CREATE TRIGGER after_cart_item_update
AFTER UPDATE ON cart_items FOR EACH ROW
BEGIN
    DECLARE itemPrice DECIMAL(10, 2);
    DECLARE oldItemPrice DECIMAL(10, 2);

    -- Get the price of the product for the updated cart item
    SELECT product_price * NEW.quantity INTO itemPrice
    FROM products
    WHERE product_ID = NEW.product_ID;

    -- Get the price of the product for the old cart item
    SELECT product_price * OLD.quantity INTO oldItemPrice
    FROM products
    WHERE product_ID = OLD.product_ID;

    -- Update the total price of the cart
    UPDATE carts
    SET total_price = total_price - oldItemPrice + itemPrice
    WHERE cart_ID = NEW.cart_ID;
END;

//

CREATE TRIGGER after_product_discount_update
AFTER UPDATE ON products FOR EACH ROW
BEGIN
    DECLARE oldDiscountPercentage INT;
    DECLARE newDiscountPercentage INT;

    IF NEW.discount_ID <> OLD.discount_ID THEN
        SELECT discount_percentage INTO oldDiscountPercentage
        FROM discounts
        WHERE discount_ID = OLD.discount_ID;

        SELECT discount_percentage INTO newDiscountPercentage
        FROM discounts
        WHERE discount_ID = NEW.discount_ID;               

        -- If the discount ID is changed, update the product price in the cart items
        UPDATE products
        SET product_price = product_price / (1 - oldDiscountPercentage / 100) * (1 - newDiscountPercentage / 100)
        WHERE product_ID = NEW.product_ID;
    END IF;
END;

-- Create a trigger to update the total price of the cart when the product price is updated
CREATE TRIGGER after_product_price_update
AFTER UPDATE ON products FOR EACH ROW
BEGIN
    DECLARE cartID INT;
    DECLARE originalItemPrice DECIMAL(10, 2);
    DECLARE newItemPrice DECIMAL(10, 2);
    DECLARE itemQuantity INT;

    -- Check if the product price is being updated
    IF NEW.product_price <> OLD.product_price THEN
        -- Get the cart ID of the cart that contains the product
        SELECT cart_ID INTO cartID
        FROM cart_items
        WHERE product_ID = NEW.product_ID;

        -- Get the quantity of the product in the cart
        SELECT quantity INTO itemQuantity
        FROM cart_items
        WHERE product_ID = NEW.product_ID;

        -- Get the price of the product before the update
        SELECT OLD.product_price * quantity INTO originalItemPrice
        FROM cart_items
        WHERE product_ID = NEW.product_ID;

        -- Get the price of the product after the update
        SELECT NEW.product_price * quantity INTO newItemPrice
        FROM cart_items
        WHERE product_ID = NEW.product_ID;

        -- Update the total price of the cart
        UPDATE carts
        SET total_price = total_price - originalItemPrice + newItemPrice
        WHERE cart_ID = cartID;
    END IF;
END;

//

CREATE TRIGGER before_discount_id_update
BEFORE UPDATE ON products FOR EACH ROW
BEGIN
    DECLARE oldDiscountPercentage, newDiscountPercentage, oldActive, newActive INT(11);

    SELECT discount_percentage, active INTO oldDiscountPercentage, oldActive
    FROM discounts
    WHERE discount_ID = OLD.discount_ID;

    SELECT discount_percentage, active INTO newDiscountPercentage, newActive
    FROM discounts
    WHERE discount_ID = NEW.discount_ID;

    -- Check if the discount ID is being updated
    IF OLD.discount_ID <> NEW.discount_ID THEN
        -- Deactivate the old discount for the product
        IF OLD.discount_ID IS NOT NULL AND oldActive = 1 THEN
            SET NEW.product_price = NEW.product_price / (1 - oldDiscountPercentage / 100);
        END IF;

        -- Activate the new discount for the product
        IF NEW.discount_ID IS NOT NULL AND newActive = 1 THEN
            SET NEW.product_price = NEW.product_price * (1 - newDiscountPercentage / 100);
        END IF;
    END IF;
END;

//

CREATE TRIGGER before_discount_update
BEFORE UPDATE ON discounts FOR EACH ROW
BEGIN
    IF NEW.active <> OLD.active THEN
        -- If the active status is changed, update discount percentage in products with the corresponding discount ID
        IF OLD.active = 1 THEN
            -- If the old discount was active, revert the product prices to their original values
            UPDATE products
            SET product_price = product_price / (1 - OLD.discount_percentage / 100)
            WHERE discount_ID = OLD.discount_ID;
        END IF;

        IF NEW.active = 1 THEN
            -- If the new discount is active, apply the new discount percentage to the product prices
            UPDATE products
            SET product_price = product_price * (1 - NEW.discount_percentage / 100)
            WHERE discount_ID = NEW.discount_ID;
        END IF;
    END IF;

    IF NEW.discount_percentage <> OLD.discount_percentage THEN
        -- If the discount percentage is changed, update product prices with the corresponding discount ID
        UPDATE products
        SET product_price = product_price / (1 - OLD.discount_percentage / 100) * (1 - NEW.discount_percentage / 100)
        WHERE discount_ID = NEW.discount_ID;
    END IF;
END;

//

DELIMITER ;

DELIMITER //

CREATE PROCEDURE addColorsToProduct(IN productName VARCHAR(255), IN colorNames TEXT)
BEGIN
    DECLARE colorName VARCHAR(255);
    DECLARE sizeID INT;
    DECLARE categoryID INT;
    DECLARE colorCursor CURSOR FOR SELECT color_name FROM colors WHERE FIND_IN_SET(color_name, colorNames);
    DECLARE sizeCursor CURSOR FOR SELECT DISTINCT size_ID FROM products WHERE product_name = productName;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET @done = TRUE;

    OPEN sizeCursor;

    size_loop: LOOP
        FETCH sizeCursor INTO sizeID;
        IF @done THEN
            LEAVE size_loop;
        END IF;

        SET @done = FALSE;
        OPEN colorCursor;

        color_loop: LOOP
            FETCH colorCursor INTO colorName;
            IF @done THEN
                LEAVE color_loop;
            END IF;

            SET categoryID = (SELECT category_ID FROM products WHERE product_name = productName AND size_ID = sizeID LIMIT 1);

            IF EXISTS (SELECT 1 FROM products WHERE product_name = productName AND color_ID = 1) THEN
                UPDATE products SET color_ID = (SELECT color_ID FROM colors WHERE color_name = colorName) WHERE product_name = productName AND color_ID = 1;
            ELSEIF NOT EXISTS (SELECT 1 FROM products WHERE product_name = productName AND size_ID = sizeID AND color_ID = (SELECT color_ID FROM colors WHERE color_name = colorName) AND category_ID = categoryID) THEN
                INSERT INTO products (product_name, product_description, product_price, product_weight, quantity, size_ID, color_ID, category_ID, discount_ID) 
                SELECT product_name, product_description, product_price, product_weight, quantity, size_ID, (SELECT color_ID FROM colors WHERE color_name = colorName), category_ID, discount_ID
                FROM products WHERE product_name = productName AND size_ID = sizeID LIMIT 1;
            END IF;
        END LOOP;

        CLOSE colorCursor;
        SET @done = FALSE;
    END LOOP;

    CLOSE sizeCursor;
END //

DELIMITER //

CREATE PROCEDURE addSizesToProduct(IN productName VARCHAR(255), IN sizeNames TEXT)
BEGIN
    DECLARE sizeName VARCHAR(255);
    DECLARE colorID INT;
    DECLARE categoryID INT;
    DECLARE sizeCursor CURSOR FOR SELECT size_name FROM sizes WHERE FIND_IN_SET(size_name, sizeNames);
    DECLARE colorCursor CURSOR FOR SELECT DISTINCT color_ID FROM products WHERE product_name = productName;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET @done = TRUE;

    OPEN colorCursor;

    color_loop: LOOP
        FETCH colorCursor INTO colorID;
        IF @done THEN
            LEAVE color_loop;
        END IF;

        SET @done = FALSE;
        OPEN sizeCursor;

        size_loop: LOOP
            FETCH sizeCursor INTO sizeName;
            IF @done THEN
                LEAVE size_loop;
            END IF;

            SET categoryID = (SELECT category_ID FROM products WHERE product_name = productName AND color_ID = colorID LIMIT 1);
            IF EXISTS (SELECT 1 FROM products WHERE product_name = productName AND size_ID = 1) THEN
                UPDATE products SET size_ID = (SELECT size_ID FROM sizes WHERE size_name = sizeName) WHERE product_name = productName AND size_ID = 1;
            ELSEIF NOT EXISTS (SELECT 1 FROM products WHERE product_name = productName AND color_ID = colorID AND size_ID = (SELECT size_ID FROM sizes WHERE size_name = sizeName) AND category_ID = categoryID) THEN
                INSERT INTO products (product_name, product_description, product_price, product_weight, quantity, color_ID, size_ID, category_ID, discount_ID) 
                SELECT product_name, product_description, product_price, product_weight, quantity, color_ID, (SELECT size_ID FROM sizes WHERE size_name = sizeName), category_ID, discount_ID
                FROM products WHERE product_name = productName AND color_ID = colorID LIMIT 1;
            END IF;
        END LOOP;

        CLOSE sizeCursor;
        SET @done = FALSE;
    END LOOP;

    CLOSE colorCursor;
END //

DELIMITER ;