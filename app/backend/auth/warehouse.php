<?php
require_once 'app/backend/core/Init.php';

//If user is not admin redirect to index.php
if (!$user->isLoggedIn() || !$user->hasPermission('admin')) {
    Session::flash('danger', 'You do not have permission to access that page.');
    Redirect::to('index.php');
}

$products = Database::getInstance()->query("SELECT DISTINCT product_name FROM products ORDER BY category_ID, product_name")->results();

//Select first from products table in database
$chosenProduct = Database::getInstance()->query("SELECT * FROM products WHERE product_id = 1")->first();
if (Input::exists()) {
    if (isset($_POST['productSubmit'])) {
        // PRODUCT SELECTION --------------------------------------------
        $chosenProduct = Database::getInstance()->query("SELECT * FROM products WHERE product_name = ?", array(Input::get('selectedProduct')))->first();

        $category_name = Database::getInstance()->query("SELECT category_name FROM categories WHERE category_ID = ?", array($chosenProduct->category_ID))->first()->category_name;

        $colors = Product::defineColors($chosenProduct->product_name);
        foreach ($colors as $color) {
            $colorNames[] = $color->color_name;
        }

        $discount_name = Database::getInstance()->query("SELECT discount_name FROM discounts WHERE discount_ID = ?", array($chosenProduct->discount_ID))->first()->discount_name;

    } elseif (isset($_POST['userSubmit'])) {
        // USER SELECTION -----------------------------------------------
        $selectedUser = Input::get('selectedUser');
        // Rest of the user-related code...

    } elseif (isset($_POST['orderSubmit'])) {
        // ORDER SELECTION ----------------------------------------------
        $selectedOrder = Input::get('selectedOrder');
        // Rest of the order-related code...
    }
}

//list of all users
$users = Database::getInstance()->query("SELECT * FROM users")->results();
foreach ($users as $user) {
    $usernames[] = $user->username;
}

//list of all orders, if there are any
if (Database::getInstance()->query("SELECT * FROM `order`")->count() > 0) {
    $orders = Database::getInstance()->query("SELECT * FROM `order` ORDER BY order_date, total")->results();
} else {
    $orders = [];
}

$months = array(
    "January"   => "01",
    "February"  => "02",
    "March"     => "03",
    "April"     => "04",
    "May"       => "05",
    "June"      => "06",
    "July"      => "07",
    "August"    => "08",
    "September" => "09",
    "October"   => "10",
    "November"  => "11",
    "December"  => "12"
);

