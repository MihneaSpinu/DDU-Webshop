<!-- Show cart with total price -->
<div class="container">
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
                    <?php echo $productsHTML; ?>
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
</div>