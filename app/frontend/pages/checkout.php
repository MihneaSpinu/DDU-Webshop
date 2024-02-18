<!-- ceckout page -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Checkout</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h2 class="
            mt-5">Shipping Address</h2>
            <form action="" method="post">
                <div class="form-group mt-3">
                    <label for="name">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group mt-3">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="form-group mt-3">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>
                <div class="form-group mt-3">
                    <label for="zip">Zip Code</label>
                    <input type="text" class="form-control" id="zip" name="postalCode" required>
                </div>
                <div class="form-group mt-3">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" id="country" name="country" required>
                </div>
                <div class="form-group mt-3">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group mt-3">
                    <label for="notes">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
                <div class="form-group mt-3">
                    <input type="hidden" name="csrf_token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" class="btn btn-primary" name="checkout" value="Pay now">
                </div>
            </form>
        </div>
        <div class="col-md-6 sticky-top" style="align-self: flex-start; top: 100px;">
            <div class="card px-2 py-2">
                <!-- Show image with amount overlayed on top right -->
                <!-- $cartHTML = "<span class='badge badge-warning' id='lblCartCount'>" . count($cartItems) . "</span>"; -->
                <!-- To the right of image show Product name. Underneath show color and size -->
                <!-- To the right of name show price -->
                <?php $i = 0;
                foreach ($products as $product) :
                    $color = Database::getInstance()->get('colors', array('color_ID', '=', $product->color_ID))->first();
                    $size = Database::getInstance()->get('sizes', array('size_ID', '=', $product->size_ID))->first();
                ?>
                    <div class="row mb-2">
                        <div class="col-2 my-auto">
                            <div class="card mr-2">
                                <img class="w-100" src="<?php echo $images[$i][$color->color_name][0]; ?>" alt="">
                            </div>
                            <div class="position-absolute" style="top: 0; right: 0;">
                                <p class="mb-0"><?php echo $cartItems[$i]->quantity; ?></p>
                            </div>
                        </div>
                        <div class="col-6 my-auto">
                            <p class="mb-0"><?php echo $product->product_name; ?></p>
                            <p class="mb-0"><?php if ($color->color_ID > 1) {
                                                echo $color->color_name;
                                            }
                                            if ($color->color_ID > 1 && $size->size_ID > 1) {
                                                echo " / ";
                                            }
                                            if ($size->size_ID > 1) {
                                                echo $size->size_name;
                                            } ?></p>
                        </div>
                        <div class="col my-auto">
                            <p class="mb-0"><?php echo $product->product_price * $cartItems[$i]->quantity; ?> dkk</p>
                        </div>
                    </div>
                <?php $i++;
                endforeach; ?>
                <!-- Total -->
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="
                    mt-5">Total</h2>
                        <p class="
                    mt-3"><?php echo $cart->total_price; ?> dkk</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>