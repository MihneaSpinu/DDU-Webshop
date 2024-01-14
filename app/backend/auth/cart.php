<?php
require_once 'app/backend/core/Init.php';

$sessionUser = $user->data();
$uid = $sessionUser->uid;

//Find cart for this user.
$cart = Database::getInstance()->get('carts', array('uid', '=', $uid))->first();
//Find all cart items for this cart.
$cartItems = Database::getInstance()->get('cart_items', array('cart_ID', '=', $cart->cart_ID))->results();
$productsHTML = "";
foreach ($cartItems as $cartItem) {
    $product = Database::getInstance()->get('products', array('product_ID', '=', $cartItem->product_ID))->first();
    $productsHTML .= "<tr>
                        <td>" . $product->product_name . "</td>
                        <td>" . $cartItem->quantity . "</td>
                        <td>" . $product->product_price . " dkk" . "</td>
                        <td>" . $cartItem->quantity * $product->product_price . " dkk" . "</td>
                    </tr>";
}
$cart->total_price = $cart->total_price . " dkk";