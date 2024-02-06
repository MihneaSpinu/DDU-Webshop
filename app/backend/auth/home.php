<?php
require_once 'app/backend/core/Init.php';

//Products
$products = Database::getInstance()->get('products', array('product_ID', '>', 0))->results();


//Get 12 unique named random products
$randomProducts = Database::getInstance()->query("SELECT * FROM products GROUP BY product_name ORDER BY RAND() LIMIT 12")->results();

//Define product images for ea