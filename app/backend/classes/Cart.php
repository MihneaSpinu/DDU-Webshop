<?php

class Cart
{
    public function create($fields = array())
    {
        if (!Database::getInstance()->insert('carts', $fields)) {
            throw new Exception('There was a problem creating an account.');
        }
    }

    public function removeFromCart($id)
    {
        if (!Database::getInstance()->delete('carts', array('product_ID', '=', $id))) {
            throw new Exception('There was a problem deleting the product.');
        }
    }
}