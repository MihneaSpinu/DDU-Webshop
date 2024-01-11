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

$colors = Product::defineColors($name);
$imagePaths = Product::defineImagePaths($name);