<?php
require_once 'app/backend/core/Init.php';

// Redirect if user is not admin
if (!$user->isLoggedIn() || !$user->hasPermission('admin')) {
    Session::flash('danger', 'You do not have permission to access that page.');
    Redirect::to('index.php');
}

// Retrieve distinct product names
$products = Database::getInstance()->query("SELECT DISTINCT product_name FROM products ORDER BY category_ID, product_name")->results();

// Retrieve all users
$users = Database::getInstance()->query("SELECT * FROM users")->results();

// Retrieve all orders if any
$ordersCount = Database::getInstance()->query("SELECT COUNT(*) FROM orders")->first()->{'COUNT(*)'};
$orders = $ordersCount > 0 ? Database::getInstance()->query("SELECT * FROM orders")->results() : array();

// Retrieve All months with orders
$monthsWithOrders = array();
foreach ($orders as $order) {
    // Extract year and month from the date
    $yearMonth = date('Y-m', strtotime($order->order_date));

    // Create an array for each unique year-month combination
    if (!isset($monthsWithOrders[$yearMonth])) {
        $monthsWithOrders[$yearMonth] = array();
    }

    // Add the order to the corresponding year-month array
    $monthsWithOrders[$yearMonth][] = $order;
}

// Process form submissions
if (Input::exists()) {
    if (isset($_POST['productSubmit'])) {
        // PRODUCT SELECTION --------------------------------------------
        $chosenProduct = Database::getInstance()->query("SELECT * FROM products WHERE product_name = ?", array(Input::get('selectedProduct')))->first();
        $category_name = Database::getInstance()->query("SELECT category_name FROM categories WHERE category_ID = ?", array($chosenProduct->category_ID))->first()->category_name;

        $colors = Product::defineColors($chosenProduct->product_name);
        $colorNames = array_column($colors, 'color_name');

        $discount_name = Database::getInstance()->query("SELECT discount_name FROM discounts WHERE discount_ID = ?", array($chosenProduct->discount_ID))->first()->discount_name;
    } elseif (isset($_POST['userSubmit'])) {
        // USER SELECTION -----------------------------------------------
        $selectedUser = Database::getInstance()->query("SELECT * FROM users WHERE username = ?", array(Input::get('selectedUser')))->first();
        $amountOfOrders = Database::getInstance()->query("SELECT COUNT(*) FROM orders WHERE uid = ?", array($selectedUser->uid))->first()->{'COUNT(*)'};
        $usernames = array_column($users, 'username');

        $groups = Database::getInstance()->query("SELECT * FROM groups")->results();
        $groupPerms = array_column($groups, 'permissions');
    } elseif (isset($_POST['monthSubmit'])) {
        // MONTH SELECTION FOR ORDERS ----------------------------------------------
        $selectedMonth = Input::get('selectedMonth');
        $selectedMonthFormatted = date('F Y', strtotime($selectedMonth));
        foreach ($orders as $order) {
            // Retrieve the username from the order
            $order->username = Database::getInstance()->query("SELECT username FROM users WHERE uid = ?", array($order->uid))->first()->username;
        }
    }

    if (isset($_POST['editProductSubmit'])) {
    } elseif (isset($_POST['deleteProductSubmit'])) {
    } elseif (isset($_POST['editUserSubmit'])) {
    } elseif (isset($_POST['deleteUserSubmit'])) {
    }
}
