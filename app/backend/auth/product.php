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
$colorsHTML = "";
foreach ($colorNames as $color) {
    $colorsHTML .= "<option value='" . $color . "'>" . $color . "</option>";
}

$sizeNames = [];
foreach (Product::defineSizes($name) as $size) {
    $sizeNames[] = $size->size_name;
}
var_dump($sizeNames);
$sizesHTML = "";
foreach ($sizeNames as $size) {
    $sizesHTML .= "<option value='" . $size . "'>" . $size . "</option>";
}

$imagePaths = Product::defineImagePaths($name);

if (count($colorNames) > 1) {
    $displaySelect = "block";
} else {
    $displaySelect = "none";
}