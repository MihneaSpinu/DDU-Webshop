<?php
require_once 'app/backend/core/Init.php';

//Products
$products = Database::getInstance()->get('products', array('product_ID', '>', 0))->results();


//Get 12 unique named random products
$randomProducts = Database::getInstance()->query("SELECT * FROM products GROUP BY product_name ORDER BY RAND() LIMIT 12")->results();

//Define product images for $randomProducts
$images = array();
foreach ($randomProducts as $product) {
    $images[] = Product::defineImagePaths($product->product_name);
}

$firstImage = array();
foreach ($images as $image) {
    $firstImage[] = array_values($image[array_rand($image)])[0];
}