<div class="container card">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Cart</h1>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $cartItem) :
                        $product = Database::getInstance()->get('products', array('product_ID', '=', $cartItem->product_ID))->first();
                        $color = Database::getInstance()->get('colors', array('color_ID', '=', $product->color_ID))->first();
                        $size = Database::getInstance()->get('sizes', array('size_ID', '=', $product->size_ID))->first();
                        $discount = Database::getInstance()->get('discounts', array('discount_ID', '=', $product->discount_ID))->first();
                    ?>
                        <tr>
                            <td>
                                <?php echo $product->product_name; ?><br>
                                <?php echo (($color->color_ID !== 1 && $size->size_ID !== 1) ? $color->color_name . " / " . $size->size_name : ""); ?>
                                <form action="" method="post">
                                    <input type="hidden" name="cartItemID" value="<?php echo $cartItem->cart_item_ID; ?>">
                                    <input type="submit" name="removeFromCart" value="Remove" class="btn btn-danger btn-sm">
                                </form>
                            </td>
                            <td>
                                <form action="" method="post">
                                    <div class="input-group">
                                        <input class="btn btn-outline-secondary" type="submit" value="-" name="decrement">
                                        <input type="text" class="form-control text-center" name="quantity" value="<?php echo $cartItem->quantity; ?>" min="1" max="99">
                                        <input class="btn btn-outline-secondary" type="submit" value="+" name="increment">
                                        <input type="hidden" name="cartItemID" value="<?php echo $cartItem->cart_item_ID; ?>">
                                    </div>
                                </form>
                            </td>
                            <td>
                                <?php echo (($discount->discount_ID !== 1 && $discount->active) ? "<span class='text-danger'><del>" . $product->product_org_price . " dkk </del></span><br>" . $product->product_price . " dkk <br>" . "<span class='text-success'>Discount: " . $discount->discount_percentage . "%</span>" : $product->product_price . " dkk <br"); ?>
                            </td>
                            <td id="subtotal_<?php echo $cartItem->cart_item_ID; ?>">
                                <?php echo $cartItem->quantity * $product->product_price . " dkk"; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="font-weight-bold" colspan="3">Total</td>
                        <td><?php echo $cart->total_price; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row mt-2 mb-3">
        <div class="col-md-12">
            <a href="/" class="btn btn-primary">Continue shopping</a>
            <a href="/checkout" class="btn btn-success">Checkout</a>
        </div>
    </div>
</div>