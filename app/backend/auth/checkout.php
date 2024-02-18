<?php
if (!$user->isLoggedIn()) {
    Redirect::to('/');
}

$cart = Database::getInstance()->get('carts', array('uid', '=', $user->data()->uid))->first();
$cartItems = Database::getInstance()->get('cart_items', array('cart_ID', '=', $cart->cart_ID))->results();
echo "<pre>";
print_r($cartItems);
echo "</pre>";

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
            'firstName' => array(
                'required' => true
            ),
            'lastName' => array(
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

            try {
                $mail->isSMTP();
                $mail->Host = 'websmtp.simply.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'BroomSweepMerch@hamsterbil.dk';
                $mail->Password = 'kode123';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('BroomSweep@mihneaspinu.dk'); //Your email
                $mail->addAddress(Input::get('email')); //User email

                $mail->isHTML(true);
                $mail->Subject = 'Order confirmation';
                //Mail body. Should write Thank you for your order in bold. Then Write order date. Underneath

                $mail->send();
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            //Print validation errors in error_log
            foreach ($validate->errors() as $error) {
                error_log(cleaner($error));
            }
        }
    }
}
