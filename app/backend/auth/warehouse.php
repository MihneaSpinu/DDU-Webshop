<?php
require_once 'app/backend/core/Init.php';

// Redirect if user is not admin
if (!$user->isLoggedIn() || !$user->hasPermission('admin')) {
    Session::flash('danger', 'You do not have permission to access that page.');
    Redirect::to('index.php');
}

$currentID = 0;
$productIteration = 0;

$categories = Database::getInstance()->get('categories', array('category_ID', '>', 0))->results();
$discounts = Database::getInstance()->get('discounts', array('discount_ID', '>', 0))->results();

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
        //Create array of each product with same name and distinct color id
        $chosenProduct = Database::getInstance()->query("SELECT * FROM products WHERE product_name = ? GROUP BY color_ID", array(Input::get('selectedProduct')))->results();
        //all products, sorted by color id and then size id
        $allOfChosenProduct = Database::getInstance()->query("SELECT * FROM products WHERE product_name = ? ORDER BY color_ID, size_ID", array(Input::get('selectedProduct')))->results();

        $category_name = Database::getInstance()->get('categories', array('category_ID', '=', $chosenProduct[0]->category_ID))->first()->category_name;
        $discount_name = Database::getInstance()->get('discounts', array('discount_ID', '=', $chosenProduct[0]->discount_ID))->first()->discount_name;
        $discount_percentage = Database::getInstance()->get('discounts', array('discount_ID', '=', $chosenProduct[0]->discount_ID))->first()->discount_percentage;

        //Get color name for each product. Only show the corresponding color name of each product, since they all have different colors
        $colors = [];
        foreach ($chosenProduct as $product) {
            $color_name = Database::getInstance()->get('colors', array('color_ID', '=', $product->color_ID))->first()->color_name;
            $colors[] = $color_name;
        }
        
        $sizes = Product::defineSizes($chosenProduct[0]->product_name);

        $quantities = [];
        foreach ($chosenProduct as $product) {
            $quantities[] = Database::getInstance()->query("SELECT quantity FROM products WHERE product_name = ? AND color_ID = ? ORDER BY size_ID", array($product->product_name, $product->color_ID))->results();
        }        

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

    $productID = isset($_POST['productID']) ? $_POST['productID'] : null;
    $newQuantity = isset($_POST['newQuantity']) ? $_POST['newQuantity'] : null;

    // Check if $productID is not null before attempting to update the database
    if ($productID !== null) {
        // Update the product quantity in the database
        $updated = Database::getInstance()->update('products', 'product_ID', $productID, array('quantity' => $newQuantity));
    }
}
