<?php
require_once 'app/backend/core/Init.php';

// Redirect if the user is not admin
if (!$user->isLoggedIn() || !$user->hasPermission('admin')) {
    Session::flash('danger', 'You do not have permission to access that page.');
    Redirect::to('index.php');
}

$db = Database::getInstance();
$selectedProduct = Input::get('selectedProduct');

Warehouse::handleFormSubmission('createProduct');
Warehouse::handleFormSubmission('editProduct');

// Retrieve all products, categories, discounts, colors and sizes
$products = $db->query("SELECT DISTINCT product_name FROM products ORDER BY product_name")->results();
$categories = $db->get('categories', array('category_ID', '>', 0))->results();
$discounts = $db->get('discounts', array('discount_ID', '>', 0))->results();
$colors = $db->get('colors', array('color_ID', '>', 0))->results();
$sizes = $db->get('sizes', array('size_ID', '>', 0))->results();

// Retrieve all users
$users = $db->get('users', array('uid', '>', 0))->results();

// Retrieve all orders if any
$ordersCount = $db->query("SELECT COUNT(*) FROM orders")->first()->{'COUNT(*)'};
$orders = $ordersCount > 0 ? $db->query("SELECT * FROM orders ORDER BY order_date DESC")->results() : array();

// Retrieve all months with orders
$monthsWithOrders = array();
foreach ($orders as $order) {
    $yearMonth = date('Y-m', strtotime($order->order_date));
    $monthsWithOrders[$yearMonth][] = $order;
}

// Process form submissions
if (Input::exists()) {
    if (isset($_POST['productSubmit'])) {
        // PRODUCT SELECTION --------------------------------------------        
        $distinctColors = Product::defineColors(Input::get('selectedProduct'));
        $allProducts = [];
        foreach ($distinctColors as $color) {
            $allProducts[$color->color_name] = [];
            $distinctSizes = Product::defineSizes(Input::get('selectedProduct'), $color->color_ID);
            foreach ($distinctSizes as $size) {
                $allProducts[$color->color_name][$size->size_name] = [];
                foreach ($db->query("SELECT * FROM products WHERE product_name = ? AND color_ID = ? AND size_ID = ?", array(Input::get('selectedProduct'), $color->color_ID, $size->size_ID))->results() as $product) {
                    $allProducts[$color->color_name][$size->size_name][] = $product;
                }
            }
        }

        //count everything in $allProducts
        $allProductsCount = [];
        foreach ($allProducts as $color) {
            foreach ($color as $size) {
                foreach ($size as $product) {
                    $allProductsCount[] = $product;
                }
            }
        }

        $chosenProduct = $db->query("SELECT * FROM products WHERE product_name = ?", array(Input::get('selectedProduct')))->first();
        $category_name = $db->get('categories', array('category_ID', '=', $chosenProduct->category_ID))->first()->category_name;
        $discount_name = $db->get('discounts', array('discount_ID', '=', $chosenProduct->discount_ID))->first()->discount_name;
        $discount_percentage = $db->get('discounts', array('discount_ID', '=', $chosenProduct->discount_ID))->first()->discount_percentage;
    } elseif (isset($_POST['userSubmit'])) {
        // USER SELECTION -----------------------------------------------
        $selectedUser = $db->get('users', array('username', '=', Input::get('selectedUser')))->first();

        $amountOfOrders = $db->query("SELECT COUNT(*) FROM orders WHERE uid = ?", array($selectedUser->uid))->first()->{'COUNT(*)'};

        $groups = $db->get('groups', array('group_ID', '>', 0))->results();
        $groupPerms = array_column($groups, 'permissions');
    } elseif (isset($_POST['monthSubmit'])) {
        // MONTH SELECTION FOR ORDERS ----------------------------------------------
        $selectedMonth = Input::get('selectedMonth');
        $selectedMonthFormatted = date('F Y', strtotime($selectedMonth));
        foreach ($monthsWithOrders[$selectedMonth] as $order) {
            // Retrieve the username from the order
            $order->username = $db->get('users', array('uid', '=', $order->uid))->first()->username;

            // Retrieve the order items for the specific order
            $orderItems = $db->get('order_items', array('order_ID', '=', $order->order_ID))->results();

            // Process and display order items
            $itemsHTML = '';
            foreach ($orderItems as $orderItem) {
                // Retrieve product details for each order item
                $orderedProduct = $db->get('products', array('product_ID', '=', $orderItem->product_ID))->first();

                // Build the HTML for displaying order items
                $itemsHTML .= "x" . $orderItem->quantity . " " . $orderedProduct->product_name . " || " . $orderedProduct->product_price . "dkk" . "<br>";
            }
        }
    }

    // Handle update quantity and delete color form product
    $productID = isset($_POST['productID']) ? $_POST['productID'] : null;
    $newQuantity = isset($_POST['newQuantity']) ? $_POST['newQuantity'] : null;
    $colorToDelete = isset($_POST['deleteColor']) ? $_POST['deleteColor'] : null;
    $productName = isset($_POST['productName']) ? $_POST['productName'] : null;

    // Check if $productID is not null before attempting to update the database
    if ($productID) {
        // Update the product quantity in the database
        $db->update('products', 'product_ID', $productID, array('quantity' => $newQuantity));
    }
    // Check if $deleteProduct is not null before attempting to delete the product
    if ($colorToDelete && $productName) {
        $colorID = $db->query("SELECT color_ID FROM colors WHERE color_name = ?", array($colorToDelete))->first()->color_ID;
        $db->query("DELETE FROM products WHERE product_name = ? AND color_ID = ?", array($productName, $colorID));       
    }
}
