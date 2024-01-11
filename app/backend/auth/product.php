<?php
require_once 'app/backend/core/Init.php';

if (Input::get('id')) {
    $product = Product::getProduct(Input::get('id'));
    if (!$product) {
        Session::flash('danger', 'Product not found.');
        Redirect::to('index.php');
    }
} else {
    Session::flash('danger', 'Product not found.');
    Redirect::to('index.php');
}

$name = $product->product_name;

$colorNames = [];
foreach (Product::defineColors($name) as $color) {
    $colorNames[] = $color->color_name;
}
$imagePaths = Product::defineImagePaths($name);

if (count($colorNames) > 1) {
    $displaySelect = "block";
} else {
    $displaySelect = "none";
}