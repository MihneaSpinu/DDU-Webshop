<?php
require_once 'app/backend/core/Init.php';

if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

$sessionUser = $user->data();
$uid = $sessionUser->uid;

// Find cart for this user.
$cart = Database::getInstance()->get('carts', array('uid', '=', $uid))->first();
// Find all cart items for this cart.
$cartItems = Database::getInstance()->get('cart_items', array('cart_ID', '=', $cart->cart_ID))->results();
$productsHTML = "";
foreach ($cartItems as $cartItem) {
    $product = Database::getInstance()->get('products', array('product_ID', '=', $cartItem->product_ID))->first();
    //Find product discount percent from discount table (find discount_percentage)
    $discount = Database::getInstance()->get('discounts', array('discount_ID', '=', $product->discount_ID))->first();

    //calculate original price
    $product->product_org_price = $product->product_price / (1 - ($discount->discount_percentage / 100));

    // quantity can be incremented by 1 or decremented by 1 from buttons
    $productsHTML .= "<tr>
                        <td>" . $product->product_name . "</td>
                        <td>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <button class='btn btn-outline-secondary' type='button' onclick='decrementQuantity(" . $cartItem->cart_item_ID . ")'>-</button>
                                </div>
                                <input type='text' class='form-control text-center' id='quantity_" . $cartItem->cart_item_ID . "' value='" . $cartItem->quantity . "'>
                                    <input class='btn btn-outline-secondary' type='button' value='+' onclick='incrementQuantity(" . $cartItem->cart_item_ID . ")'>
                            </div>
                        </td>
                        <td>"
                            . (($discount->discount_ID !== 1 && $discount->active) ?
                            "<span class='text-danger'>
                                <del>" . $product->product_org_price . " dkk </del>
                            </span><br>"
                            . $product->product_price . " dkk <br>" . "
                            <span class='text-success'>Discount: " . $discount->discount_percentage . "%</span>" : $product->product_price . " dkk <br>" . "") .
                        "</td>


                        <td id='subtotal_" . $cartItem->cart_item_ID . "'>" 
                            . $cartItem->quantity * $product->product_price . " dkk" .
                        "</td>
                    </tr>";
}

$cart->total_price = $cart->total_price . " dkk";

$cartItemID = isset($_POST['cartItemID']) ? $_POST['cartItemID'] : null;
$newQuantity = isset($_POST['newQuantity']) ? $_POST['newQuantity'] : null;

// Check if $cartItemID is not null before attempting to update the database
if ($cartItemID !== null) {
    // Update the cart item quantity in the database
    $updated = Database::getInstance()->update('cart_items', 'cart_item_ID', $cartItemID, array('quantity' => $newQuantity));
}
