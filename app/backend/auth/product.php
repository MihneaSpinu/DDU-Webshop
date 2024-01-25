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

$colorNames = [];
foreach (Product::defineColors($name) as $color) {
    $colorNames[] = $color->color_name;
}
$colorsHTML = "";
foreach ($colorNames as $color) {
    $colorsHTML .= "<option value='" . $color . "'>" . $color . "</option>";
}

$sizeNames = [];
foreach (Product::defineSizes($name) as $size) {
    $sizeNames[] = $size->size_name;
}
$sizesHTML = "";
foreach ($sizeNames as $size) {
    $sizesHTML .= "<option value='" . $size . "'>" . $size . "</option>";
}

$imagePaths = Product::defineImagePaths($name);

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