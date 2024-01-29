<?php
require_once 'app/backend/core/Init.php';

if (Input::get('name')) {
    $products = Database::getInstance()->query("SELECT * FROM products WHERE product_name = ?", array(Input::get('name')))->results();
    if (count($products) <= 0) {
        Session::flash('danger', 'Product not found.');
        Redirect::to('index.php');
    }
    $product = $products[0];
} else {
    Session::flash('danger', 'Product not found.');
    Redirect::to('index.php');
}
$db = Database::getInstance();

$name = Input::get('name');
$discount = $db::getInstance()->get('discounts', array('discount_ID', '=', $product->discount_ID))->first();
$product->product_org_price = $product->product_price / (1 - ($discount->discount_percentage / 100));
$cart = $db::getInstance()->get('carts', array('uid', '=', $user->data()->uid))->first();

$colors = [];
$colorsHTML = "";
foreach (Product::defineColors($name) as $color) {
    $colors[] = $color;
    $colorsHTML .= "<option value='" . $color->color_ID . "'>" . $color->color_name . "</option>";
}

$sizes = [];
$sizesHTML = "";
foreach (Product::defineSizes($name, $product->color_ID) as $size) {
    $sizes[] = $size;
    $sizesHTML .= "<option value='" . $size->size_ID . "'>" . $size->size_name . "</option>";
}


$imagePaths = Product::defineImagePaths($name);
$imagesHTML = "";
foreach ($imagePaths as $imagePath) {
    foreach ($imagePath as $image) {
        $imagesHTML .= "<div><img class='w-100' class='product-image' src='" . $image . "' alt=''></div>";
    }
}

if (count($colors) > 1) {
    $colorSelect = "block";
} else {
    $colorSelect = "none";
}
if (count($sizes) > 1) {
    $sizeSelect = "block";
} else {
    $sizeSelect = "none";
}

if (Input::exists() && isset($_POST['addToCart'])) {
    if (Token::check(Input::get('csrf_token'))) {
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
            'colorSelect' => array(
                'required' => true,
                'min' => 1
            ),
            'sizeSelect' => array(
                'required' => true,
                'min' => 1
            ),
            'quantitySelect' => array(
                'required' => true,
                'min' => 1,
                'max' => 99
            )
        ));

        if ($validation->passed()) {
            $cartProduct = $db->query("SELECT * FROM products WHERE product_name = ? AND color_ID = ? AND size_ID = ?", $name, Input::get('colorSelect'), Input::get('sizeSelect'))->first();
            try {
                Product::addToCart(array(
                    'cart_ID' => $cart->cart_ID,
                    'product_ID' => $cartProduct->product_ID,
                    'quantity' => Input::get('quantitySelect')
                ));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            $errors = $validation->errors();
        }
    }
}

$color = isset($_POST['selectedColor']) ? $_POST['selectedColor'] : null;
$size = isset($_POST['selectedSize']) ? $_POST['selectedSize'] : null;
$name = isset($_POST['name']) ? $_POST['name'] : null;

if (Input::exists() && $color && $size && $name) {
    $quantity = $db->query("SELECT quantity FROM products WHERE product_name = ? AND color_ID = ? AND size_ID = ?", array($name, $color, $size))->first()->quantity;

    echo json_encode(array('quantity' => $quantity));
}