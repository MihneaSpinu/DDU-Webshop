<?php
if (!$user->isLoggedIn()) {
    Session::flash('register-error', 'You must be logged in to view your cart.');
    Redirect::to('/');
}

$sessionUser = $user->data();
$uid = $sessionUser->uid;

// Find cart for this user.
$cart = Database::getInstance()->get('carts', array('uid', '=', $uid))->first();
// Find all cart items for this cart.
$cartItems = Database::getInstance()->get('cart_items', array('cart_ID', '=', $cart->cart_ID))->results();
//If cart is empty, Only show "Your cart is empty <br> <a href='/'>Go shopping</a>"
if (count($cartItems) <= 0) {
    $cartItems = [];
    echo "<div class='text-center'><h1>Your cart is empty</h1><br><a href='/'>Go shopping</a></div>";
    require_once FRONTEND_INCLUDE . 'footer.php';
    die();
}
foreach ($cartItems as $cartItem) {
    $product = Database::getInstance()->get('products', array('product_ID', '=', $cartItem->product_ID))->first();
    $productItems[] = $product;
}

$productsHTML = "";
foreach ($cartItems as $cartItem) {
    $product = Database::getInstance()->get('products', array('product_ID', '=', $cartItem->product_ID))->first();
    $color = Database::getInstance()->get('colors', array('color_ID', '=', $product->color_ID))->first();
    $size = Database::getInstance()->get('sizes', array('size_ID', '=', $product->size_ID))->first();
    $discount = Database::getInstance()->get('discounts', array('discount_ID', '=', $product->discount_ID))->first();

    //calculate original price
    $product->product_org_price = $product->product_price / (1 - ($discount->discount_percentage / 100));
}

$cart->total_price = $cart->total_price . " dkk";

$cartItemID = isset($_POST['cartItemID']) ? $_POST['cartItemID'] : null;
$newQuantity = isset($_POST['newQuantity']) ? $_POST['newQuantity'] : null;

// Check if $cartItemID is not null before attempting to update the database
if ($cartItemID !== null) {
    // Update the cart item quantity in the database
    $updated = Database::getInstance()->update('cart_items', 'cart_item_ID', $cartItemID, array('quantity' => $newQuantity));
}

if (Input::get('removeFromCart')) {
    $cartItemID = Input::get('cartItemID');
    $deleted = Database::getInstance()->delete('cart_items', array('cart_item_ID', '=', $cartItemID));
    if ($deleted) {
        Redirect::to('cart');
    }
}

if (Input::get('increment') || Input::get('decrement')) {
    $cartItem = Database::getInstance()->get('cart_items', array('cart_item_ID', '=', Input::get('cartItemID')))->first();
    $quantity = Input::get('quantity');
    if (Input::get('increment')) {
        $quantity++;
    } else {
        $quantity--;
    }
    if ($quantity < 1) {
        $quantity = 1;
    } elseif ($quantity > 99) {
        $quantity = 99;
    }
    $updated = Database::getInstance()->update('cart_items', 'cart_item_ID', $cartItem->cart_item_ID, array('quantity' => $quantity));

    Redirect::to('cart');
}