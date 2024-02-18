<?php
//Get cart
$cart = Database::getInstance()->get('carts', array('uid', '=', $user->data()->uid))->first();

//Get all cart items
$cartItems = Database::getInstance()->get('cart_items', array('cart_ID', '=', $cart->cart_ID))->results();
$products = array();    
foreach ($cartItems as $cartItem) {
    $product = Database::getInstance()->get('products', array('product_ID', '=', $cartItem->product_ID))->first();
    $products[] = $product;
}

$images = array();
foreach ($cartItems as $cartItem) {
    $product = Database::getInstance()->get('products', array('product_ID', '=', $cartItem->product_ID))->first();
    $images[] = Product::defineImagePaths($product->product_name);
}

foreach ($images as $image) {
    $firstImage[] = array_values($image[array_rand($image)])[0];
}