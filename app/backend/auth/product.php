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
$name = $product->product_name;
$discount = Database::getInstance()->get('discounts', array('discount_ID', '=', $product->discount_ID))->first();

$colorNames = [];
foreach (Product::defineColors($name) as $color) {
    $colorNames[] = $color->color_name;
}
$colorsHTML = "";
foreach ($colorNames as $color) {
    $colorsHTML .= "<option value='" . $color . "'>" . $color . "</option>";
}

$sizeNames = [];
foreach (Product::defineSizes($name, $product->color_ID) as $size) {
    $sizeNames[] = $size->size_name;
}
$sizesHTML = "";
foreach ($sizeNames as $size) {
    $sizesHTML .= "<option value='" . $size . "'>" . $size . "</option>";
}

if (count($colorNames) > 1) {
    $colorSelect = "block";
} else {
    $colorSelect = "none";
}
if (count($sizeNames) > 1) {
    $sizeSelect = "block";
} else {
    $sizeSelect = "none";
}


$imagePaths = Product::defineImagePaths($name);
$imagesHTML = "";
foreach ($imagePaths as $imagePath) {
    foreach ($imagePath as $image) {
        $imagesHTML .= "<div><img style='width:100%;' class='product-image' src='" . $image . "' alt=''></div>";
    }
}