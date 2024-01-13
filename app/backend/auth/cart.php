<?php
require_once 'app/backend/core/Init.php';

$sessionUser = $user->data();
$uid = $sessionUser->uid;

//print table of products in cart
$cart = Cart::getCart($uid);
$cartItems = Database::getInstance()->query("SELECT * FROM carts WHERE user_ID = $uid");
?>