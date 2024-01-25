<?php

class Product
{
    public function create($fields = array())
    {
        if (!Database::getInstance()->insert('products', $fields)) {
            throw new Exception('There was a problem creating an account.');
        }
    }

    public static function getProduct($id)
    {
        $product = Database::getInstance()->get('products', array('product_ID', '=', $id));
        if ($product->count()) {
            return $product->first();
        }
    }

    public function addToCart($fields = array())
    {
        if (!Database::getInstance()->insert('cart_items', $fields)) {
            throw new Exception('There was a problem adding the product to the cart.');
        }
    }

    public static function defineColors($productName)
    {
        $colors = [];

        // Get all the colors for the product as objects
        $query = Database::getInstance()->query("SELECT DISTINCT color_ID FROM products WHERE product_name = ?", array($productName));
        $colorIDs = $query->results();

        foreach ($colorIDs as $colorID) {
            $colorQuery = Database::getInstance()->query("SELECT * FROM colors WHERE color_ID = ?", array($colorID->color_ID));
            $colors[] = $colorQuery->first();
        }

        return $colors;
    }

    public static function defineSizes($productName)
    {
        $sizes = [];

        // Get all the sizes for the product as objects, but only get unique ones
        $query = Database::getInstance()->query("SELECT DISTINCT size_ID FROM products WHERE product_name = ?", array($productName));
        $sizeIDs = $query->results();

        foreach ($sizeIDs as $sizeID) {
            $sizeQuery = Database::getInstance()->query("SELECT * FROM sizes WHERE size_ID = ?", array($sizeID->size_ID));
            $sizes[] = $sizeQuery->first();
        }

        return $sizes;
    }

    public static function defineImagePaths($productName)
    {
        try {
            $imagePaths = [];
            $colors = self::defineColors($productName);

            if (in_array('no color', $colors)) {
                $imageCount = 0;
                foreach (glob(FRONTEND_ASSET . 'productImages/' . $productName) as $filename) {
                    $imageCount++;
                    $imagePaths['no color'] = FRONTEND_ASSET . 'productImages/' . $productName . ' (' . $imageCount . ')' . '.png';
                }
            } else {
                foreach ($colors as $color) {
                    //All images are named like this: productname-colorname (number).png
                    $imageCount = 0;
                    foreach (glob(FRONTEND_ASSET . 'productImages/' . $productName . '-' . $color->color_name . '*') as $filename) {
                        $imageCount++;
                        $imagePaths[$color->color_name] = FRONTEND_ASSET . 'productImages/' . $productName . '-' . $color->color_name . ' (' . $imageCount . ')' . '.png';
                    }
                }
            }
            
            //Check if the image exists
            foreach ($imagePaths as $color->color_name => $imagePath) {
                if (!file_exists($imagePath)) {
                    //If it doesn't, display a placeholder image
                    $imagePaths[$color->color_name] = FRONTEND_ASSET . 'productImages/' . 'placeholder.png';
                }
            }

            return $imagePaths;
        } catch (Exception $e) {
            // Handle the exception (e.g., log it or display an error message)
            throw new Exception('Error defining image paths: ' . $e->getMessage());
        }
    }
}
