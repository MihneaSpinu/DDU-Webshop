<?php

class Cart
{
    public function create($fields = array())
    {
        if (!Database::getInstance()->insert('carts', $fields)) {
            throw new Exception('There was a problem creating an account.');
        }
    }

    public static function getCart($id)
    {
        // Database::getInstance()->query("SELECT * FROM carts WHERE user_ID = $uid");
        $cart = Database::getInstance()->get('carts', array('user_ID', '=', $id));
        if ($cart->count()) {
            return $cart->results();
        }
    }

    public function removeFromCart($id)
    {
        if (!Database::getInstance()->delete('carts', array('product_ID', '=', $id))) {
            throw new Exception('There was a problem deleting the product.');
        }
    }
}