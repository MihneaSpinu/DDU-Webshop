<?php
require_once 'app/backend/core/Init.php';

// Redirect if the user is not admin
if (!$user->isLoggedIn() || !$user->hasPermission('admin')) {
    Session::flash('danger', 'You do not have permission to access that page.');
    Redirect::to('index.php');
}

$db = Database::getInstance();

// Function to handle form submissions
function handleFormSubmission($actionType)
{
    global $db;
    if (Input::exists() && isset($_POST[$actionType])) {
        if (Token::check(Input::get('csrf_token'))) {
            $validate = new Validation();

            $validationRules = [
                'productName' => ['required' => true, 'min' => 2, 'max' => 255],
                'productDescription' => ['required' => true, 'min' => 2, 'max' => 500],
                'productPrice' => ['required' => true, 'numeric' => true],
                'productWeight' => ['required' => true, 'numeric' => true],
                'categorySelect' => ['required' => true],
                'discountSelect' => ['required' => true],
                'colorSelect' => ['required' => true, 'isArray' => true],
                'sizeSelect' => ['required' => true, 'isArray' => true],
            ];

            $validation = $validate->check($_POST, $validationRules);

            if ($validation->passed()) {
                try {
                    $productName = Input::get('productName');
                    $productDescription = Input::get('productDescription');
                    $productPrice = Input::get('productPrice');
                    $productWeight = Input::get('productWeight');
                    $categorySelect = Input::get('categorySelect');
                    $discountSelect = Input::get('discountSelect');
                    $colorSelect = Input::get('colorSelect');
                    $sizeSelect = Input::get('sizeSelect');

                    // Insert or update products based on action type
                    if ($actionType === 'createProduct') {
                        insertProducts($db, $productName, $productDescription, $productPrice, $productWeight, $categorySelect, $discountSelect, $colorSelect, $sizeSelect);
                    } elseif ($actionType === 'editProduct') {
                        $formerProductName = Input::get('currentProductName');
                        updateProducts($db, $productName, $productDescription, $productPrice, $productWeight, $categorySelect, $discountSelect, $formerProductName, $colorSelect, $sizeSelect);
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                echo '<div class="alert alert-danger"><strong></strong>' . cleaner($validation->error()) . '</div>';
            }
        }
    }
}

handleFormSubmission('createProduct');
handleFormSubmission('editProduct');

// Function to insert products into the database
function insertProducts($db, $productName, $productDescription, $productPrice, $productWeight, $categorySelect, $discountSelect, $colorSelect, $sizeSelect, $checkIfProductExists = false)
{
    // For each color and size, insert a new product
    foreach ($colorSelect as $color) {
        foreach ($sizeSelect as $size) {
            // Check if the product already exists
            if ($checkIfProductExists) {
                $productExists = $db->query("SELECT * FROM products WHERE product_name = ? AND color_ID = ? AND size_ID = ?", [$productName, $color, $size])->count();
                if ($productExists) {
                    break;
                }
            }
            $sql = "INSERT INTO products (product_name, product_description, product_price, product_weight, category_ID, color_ID, size_ID, discount_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                $productName,
                $productDescription,
                $productPrice,
                $productWeight,
                $categorySelect,
                $color,
                $size,
                $discountSelect
            ];
            $db->query($sql, $params);
        }
    }
}

// Function to update products in the database
function updateProducts($db, $productName, $productDescription, $productPrice, $productWeight, $categorySelect, $discountSelect, $formerProductName, $colorSelect, $sizeSelect)
{
    $sql = "UPDATE products SET 
                product_name = ?,
                product_description = ?, 
                product_price = ?, 
                product_weight = ?, 
                category_ID = ?,
                discount_ID = ? 
                WHERE product_name = ?";

    $params = [
        $productName,
        $productDescription,
        $productPrice,
        $productWeight,
        $categorySelect,
        $discountSelect,
        $formerProductName
    ];

    $db->query($sql, $params);

    insertProducts($db, $productName, $productDescription, $productPrice, $productWeight, $categorySelect, $discountSelect, $colorSelect, $sizeSelect, true);
}

$categories = $db->get('categories', array('category_ID', '>', 0))->results();
$discounts = $db->get('discounts', array('discount_ID', '>', 0))->results();
$colors = $db->get('colors', array('color_ID', '>', 0))->results();
$sizes = $db->get('sizes', array('size_ID', '>', 0))->results();
$products = $db->query("SELECT DISTINCT product_name FROM products ORDER BY product_name")->results();
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
    $colorToDelete = isset($_POST['colorToDelete']) ? $_POST['colorToDelete'] : null;
    // Check if $productID is not null before attempting to update the database
    if ($productID) {
        // Update the product quantity in the database
        $updated = $db->update('products', 'product_ID', $productID, array('quantity' => $newQuantity));
    }

    // Check if $deleteProduct is not null before attempting to delete the product
    if ($colorToDelete) {
        // Delete every product with color id $colorToDelete
        //Get color id from name
        $colorID = $db->get('colors', array('color_name', '=', $colorToDelete))->first()->color_ID;
        //Delete all products with same name and color id
        $db->query("DELETE FROM products WHERE product_name = ? AND color_ID = ?", array(Input::get('selectedProduct'), $colorID));
    }
}