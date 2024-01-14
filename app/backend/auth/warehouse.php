<?php
require_once 'app/backend/core/Init.php';

// Redirect if user is not admin
if (!$user->isLoggedIn() || !$user->hasPermission('admin')) {
    Session::flash('danger', 'You do not have permission to access that page.');
    Redirect::to('index.php');
}

// Retrieve distinct product names
$products = Database::getInstance()->query("SELECT DISTINCT product_name FROM products ORDER BY category_ID, product_name")->results();
$chosenProduct = null;

// Retrieve all users
$users = Database::getInstance()->get('users', array('uid', '>', 0))->results();

// Retrieve all orders if any
$ordersCount = Database::getInstance()->query("SELECT COUNT(*) FROM orders")->first()->{'COUNT(*)'};
$orders = $ordersCount > 0 ? Database::getInstance()->query("SELECT * FROM orders ORDER BY order_date DESC")->results() : array();

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
        $chosenProduct = Database::getInstance()->get('products', array('product_name', '=', Input::get('selectedProduct')))->first();
        $category_name = Database::getInstance()->get('categories', array('category_ID', '=', $chosenProduct->category_ID))->first()->category_name;

        $colors = Product::defineColors($chosenProduct->product_name);
        $colorsHTML = '';
        foreach ($colors as $color) {
            $colorsHTML .= $color->color_name . "<br>";
        }

        $sizes = Product::defineSizes($chosenProduct->product_name);
        $sizesHTML = '';
        foreach ($sizes as $size) {
            $sizesHTML .= $size->size_name . "<br>";
        }

        $discount_name = Database::getInstance()->get('discounts', array('discount_ID', '=', $chosenProduct->discount_ID))->first()->discount_name;
    } elseif (isset($_POST['userSubmit'])) {
        // USER SELECTION -----------------------------------------------
        $selectedUser = Database::getInstance()->get('users', array('username', '=', Input::get('selectedUser')))->first();

        $amountOfOrders = Database::getInstance()->query("SELECT COUNT(*) FROM orders WHERE uid = ?", array($selectedUser->uid))->first()->{'COUNT(*)'};

        $groups = Database::getInstance()->get('groups', array('group_ID', '>', 0))->results();
        $groupPerms = array_column($groups, 'permissions');
    } elseif (isset($_POST['monthSubmit'])) {
        // MONTH SELECTION FOR ORDERS ----------------------------------------------
        $selectedMonth = Input::get('selectedMonth');
        $selectedMonthFormatted = date('F Y', strtotime($selectedMonth));
        foreach ($monthsWithOrders[$selectedMonth] as $order) {
            // Retrieve the username from the order
            $order->username = Database::getInstance()->get('users', array('uid', '=', $order->uid))->first()->username;

            // Retrieve the order items for the specific order
            $orderItems = Database::getInstance()->get('order_items', array('order_ID', '=', $order->order_ID))->results();

            // Process and display order items
            $itemsHTML = '';
            foreach ($orderItems as $orderItem) {
                // Retrieve product details for each order item
                $orderedProduct = Database::getInstance()->get('products', array('product_ID', '=', $orderItem->product_ID))->first();

                // Build the HTML for displaying order items
                $itemsHTML .= "x" . $orderItem->quantity . " " . $orderedProduct->product_name . " || " . $orderedProduct->product_price . "dkk" . "<br>";
            }
        }
    }
}
