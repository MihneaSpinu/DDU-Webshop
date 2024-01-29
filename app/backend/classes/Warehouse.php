<?php

class Warehouse
{
    // Function to handle form submissions
    public static function handleFormSubmission($actionType)
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
                            Warehouse::insertProducts($db, $productName, $productDescription, $productPrice, $productWeight, $categorySelect, $discountSelect, $colorSelect, $sizeSelect);
                        } elseif ($actionType === 'editProduct') {
                            $formerProductName = Input::get('currentProductName');
                            Warehouse::updateProducts($db, $productName, $productDescription, $productPrice, $productWeight, $categorySelect, $discountSelect, $formerProductName, $colorSelect, $sizeSelect);
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

    // Function to insert products into the database
    public static function insertProducts($db, $productName, $productDescription, $productPrice, $productWeight, $categorySelect, $discountSelect, $colorSelect, $sizeSelect, $checkIfProductExists = false)
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
    public static function updateProducts($db, $productName, $productDescription, $productPrice, $productWeight, $categorySelect, $discountSelect, $formerProductName, $colorSelect, $sizeSelect)
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

        Warehouse::insertProducts($db, $productName, $productDescription, $productPrice, $productWeight, $categorySelect, $discountSelect, $colorSelect, $sizeSelect, true);
    }
}
