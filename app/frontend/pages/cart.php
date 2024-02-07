<!-- Show cart with total price -->
<div class="container card mt-5">
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
    <div class="row">
        <div class="col-md-12">
            <form action="checkout.php" method="post">
                <input type="submit" value="Checkout" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<script>
    function incrementQuantity(cartItemID) {
        var quantityField = $("#quantity_" + cartItemID);
        var currentQuantity = parseInt(quantityField.val());
        if (currentQuantity <= 99) {
            quantityField.val(currentQuantity + 1);
            updateCartItemQuantity(cartItemID, currentQuantity + 1);
        }
    }

    function decrementQuantity(cartItemID) {
        var quantityField = $("#quantity_" + cartItemID);
        var currentQuantity = parseInt(quantityField.val());
        if (currentQuantity > 1) {
            quantityField.val(currentQuantity - 1);
            updateCartItemQuantity(cartItemID, currentQuantity - 1);
        }
    }

    // Add event listener for input changes
    $(document).on('input', 'input[id^="quantity_"]', function() {
        //keeo input value between 1 and 99
        if ($(this).val() < 1) {
            $(this).val(1);
        } else if ($(this).val() > 99) {
            $(this).val(99);
        }
        var cartItemID = $(this).attr('id').replace('quantity_', '');
        var newQuantity = $(this).val();
        updateCartItemQuantity(cartItemID, newQuantity);
    });

    function updateCartItemQuantity(cartItemID, newQuantity) {
        $.ajax({
            url: "cart.php",
            type: "POST",
            data: {
                cartItemID: cartItemID,
                newQuantity: newQuantity
            },

            success: function(response) {
                window.location.reload();
            }
        });
    }
</script>