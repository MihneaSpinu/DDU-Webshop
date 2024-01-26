<?php
require_once 'app/backend/core/Init.php';

// Redirect if user is not admin
if (!$user->isLoggedIn() || !$user->hasPermission('admin')) {
    Session::flash('danger', 'You do not have permission to access that page.');
    Redirect::to('index.php');
}

$db = Database::getInstance();

$categories = $db->get('categories', array('category_ID', '>', 0))->results();
$discounts = $db->get('discounts', array('discount_ID', '>', 0))->results();
$products = $db->query("SELECT DISTINCT product_name FROM products")->results();
$users = $db->get('users', array('uid', '>', 0))->results();

// Retrieve all orders if any
$ordersCount = $db->query("SELECT COUNT(*) FROM orders")->first()->{'COUNT(*)'};
$orders = $ordersCount > 0 ? $db->query("SELECT * FROM orders ORDER BY order_date DESC")->results() : array();

// Retrieve All months with orders
$monthsWithOrders = array();
foreach ($orders as $order) {
    $yearMonth = date('Y-m', strtotime($order->order_date));
    $monthsWithOrders[$yearMonth][] = $order;
}

// Process form submissions
if (Input::exists()) {
    if (isset($_POST['productSubmit'])) {
        // PRODUCT SELECTION --------------------------------------------        
        //3d array called $allProducts. $allProducts[][][]. First is for each color, second is for each size, third is for each product. Sort by color id, then size id.
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
        //Count every element in $allProducts and store it in $allProductsCount
        $allProductsCount = array();
        foreach ($allProducts as $color => $sizes) {
            foreach ($sizes as $size => $products) {
                $allProductsCount[$color][$size] = count($products);
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

    $productID = isset($_POST['productID']) ? $_POST['productID'] : null;
    $newQuantity = isset($_POST['newQuantity']) ? $_POST['newQuantity'] : null;
    $deleteProduct = isset($_POST['deleteProduct']) ? $_POST['deleteProduct'] : null;

    // Check if $productID is not null before attempting to update the database
    if ($productID !== null) {
        // Update the product quantity in the database
        $updated = $db->update('products', 'product_ID', $productID, array('quantity' => $newQuantity));
    }
    // Check if $deleteProduct is not null before attempting to delete the product
    if ($productID !== null && $deleteProduct !== null) {
        // Delete each product with the same color as the selected product
        $product = $db->get('products', array('product_ID', '=', $productID))->first();
        echo $product->product_name . " " . $product->color_ID;
        $db->delete('products', array('product_name', '=', $product->product_name, 'AND', 'color_ID', '=', $product->color_ID));
    }
}
