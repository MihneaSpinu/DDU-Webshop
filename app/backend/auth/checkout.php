<?php
if (!$user->isLoggedIn()) {
    Redirect::to('/');
}

$cart = Database::getInstance()->get('carts', array('uid', '=', $user->data()->uid))->first();
$cartItems = Database::getInstance()->get('cart_items', array('cart_ID', '=', $cart->cart_ID))->results();

//If no items, redirect to home
if (count($cartItems) <= 0) {
    Redirect::to('/');
}
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer;
if (Input::exists()) {
    if (Token::check(Input::get('csrf_token'))) {
        $validate = new Validation();

        $validationMailAndDelivery = $validate->check($_POST, array(
            'email' => array(
                'required' => true
            ),
            'country' => array(
                'required' => true
            ),
            'name' => array(
                'required' => true
            ),
            'address' => array(
                'required' => true
            ),
            'postalCode' => array(
                'required' => true
            ),
            'city' => array(
                'required' => true
            ),
            'phone' => array(
                'required' => false
            )
        ));
        $totalPrice = $cart->total_price;

        if ($validate->passed()) {
            //Try to define delivery
            try {
                $delivery = array(
                    'uid' => $user->data()->uid,
                    'email' => Input::get('email'),
                    'country' => Input::get('country'),
                    'first_name' => Input::get('firstName'),
                    'last_name' => Input::get('lastName'),
                    'address' => Input::get('address'),
                    'postal_code' => Input::get('postalCode'),
                    'city' => Input::get('city'),
                    'phone' => Input::get('phone') ? Input::get('phone') : null
                );
                error_log(print_r($delivery, true));
            } catch (Exception $e) {
                die($e->getMessage());
            }

            try {
                $order = Database::getInstance()->insert('orders', array(
                    'uid' => $user->data()->uid,
                    'total' => $totalPrice,
                    'order_date' => date('Y-m-d H:i:s')
                ));
            } catch (Exception $e) {
                die($e->getMessage());
            }
            $order = Database::getInstance()->get('orders', array('uid', '=', $user->data()->uid))->last();
            //Create order items in database
            foreach ($cartItems as $cartItem) {
                try {
                    $orderItem = Database::getInstance()->insert('order_items', array(
                        'order_ID' => $order->order_ID,
                        'product_ID' => $cartItem->product_ID,
                        'quantity' => $cartItem->quantity
                    ));
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            }

            $mail->isSMTP();
            $mail->Host = 'websmtp.simply.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'BroomSweep@mihneaSpinu.dk';
            $mail->Password = 'kode123';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('BroomSweep@mihneaspinu.dk'); //Your email
            $mail->addAddress(Input::get('email')); //User email

            $mail->isHTML(true);
            $mail->Subject = 'Order confirmation';
            $mail->Body = '<h1>Thank you for your order</h1>
                <p>Order date: ' . $order->order_date . '</p>
                <p>Delivery information: ' . $delivery['first_name'] . ' ' . $delivery['last_name'] . '<br>'
                . $delivery['address'] . '<br>' . $delivery['postal_code'] . ' ' . $delivery['city'] . '<br>' . $delivery['country'] . '<br>' . $delivery['phone'] . '</p>
                <p>Items:</p>';
            foreach ($cartItems as $cartItem) {
                $product = Database::getInstance()->get('products', array('product_ID', '=', $cartItem->product_ID))->first();
                $mail->Body .= '<p>' . $product->product_name . ' ' . $cartItem->quantity . 'x ' . $product->product_price . ' DKK</p>';
            }
            $mail->Body .= '<p>Total: ' . $totalPrice . ' DKK</p>';
            $mail->AltBody = 'Thank you for your order. Order date: ' . $order->order_date . '. Delivery information: ' . $delivery['first_name'] . ' ' . $delivery['last_name'] . ' ' . $delivery['address'] . ' ' . $delivery['postal_code'] . ' ' . $delivery['city'] . ' ' . $delivery['country'] . ' ' . $delivery['phone'] . '. Items: ';

            $mail->send();
        } else {
            //Print validation errors in error_log
            foreach ($validate->errors() as $error) {
                error_log(cleaner($error));
            }
        }
    }
}

//Get all cart items
$cartItems = Database::getInstance()->get('cart_items', array('cart_ID', '=', $cart->cart_ID))->results();
$products = array();
foreach ($cartItems as $cartItem) {
    $product = Database::getInstance()->get('products', array('product_ID', '=', $cartItem->product_ID))->first();
    $products[] = $product;
}

$images = array();
foreach ($cartItems as $cartItem) {
    $product = Database::getInstance()->get('products', array('product_ID', '=', $cartItem->product_ID))->first();
    $images[] = Product::defineImagePaths($product->product_name);
}
