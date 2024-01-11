<?php

class Product
{
    public function create($fields = array())
    {
        if (!Database::getInstance()->insert('products', $fields)) {
            throw new Exception('There was a problem creating an account.');
        }
    }

    public function delete($id)
    {
        if (!Database::getInstance()->delete('products', array('id', '=', $id))) {
            throw new Exception('There was a problem deleting the product.');
        }
    }

    public static function getProduct($id)
    {
        $product = Database::getInstance()->get('products', array('product_ID', '=', $id));
        if ($product->count()) {
            return $product->first();
        }
    }

    public static function defineColors($productName)
    {
        $colors = [];

        // Get all the colors for the product
        //SELECT color_ID FROM products WHERE product_name = :productName";
        $query = Database::getInstance()->query("SELECT color_ID FROM products WHERE product_name = ?", array($productName));
        $colorIDs = $query->results();

        foreach ($colorIDs as $colorID) {
            $colorQuery = Database::getInstance()->query("SELECT color_name FROM colors WHERE color_ID = ?", array($colorID->color_ID));
            $colorResult = $colorQuery->first();

            if ($colorResult) {
                $colors[] = $colorResult->color_name;
            }
        }

        return $colors;
    }

    public static function defineImagePaths($productName)
    {
        try {
            $imagePaths = [];
            $colors = self::defineColors($productName);

            if (in_array('no color', $colors)) {
                $imagePaths['no color'] = 'app/frontend/assets/productImages/' . $productName . '.png';
            } else {
                foreach ($colors as $color) {
                    $imagePaths[$color] = 'app/frontend/assets/productImages/' . $productName . '-' . $color . '.png';
                }
            }

            return $imagePaths;
        } catch (Exception $e) {
            // Handle the exception (e.g., log it or display an error message)
            throw new Exception('Error defining image paths: ' . $e->getMessage());
        }
    }


    public static function displayImage($imagePath)
    {
        // Check if the image exists
        if (file_exists($imagePath)) {
            // If it does, display it
            echo '<img src="' . $imagePath . '" alt="Product Image">';
        } else {
            // If it doesn't, display a placeholder image
            echo '<img src="app/frontend/assets/productImages/placeholder.png" alt="Placeholder Image">';
        }
    }
}
