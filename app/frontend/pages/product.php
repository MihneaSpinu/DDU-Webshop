<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card slider slider-for">
                <?php echo $imagesHTML; ?>
            </div>
            <div class="card slider slider-nav mb-3 mx-4">
                <?php echo $imagesHTML; ?>
            </div>
        </div>
        <div class="col-md-6 card">
            <h1 class="product-name">
                <?php echo $product->product_name; ?>
            </h1>
            <div class="product-price text-white">
                <?php if ($discount->discount_percentage > 0 && $discount->active) : ?>
                    <h4><?php echo $discount->discount_name; ?> Sale</h4>
                    <p><span class='text-success'><?php echo $discount->discount_percentage; ?>% Discount!</span></p>
                    <p>
                        <a>Original price:</a>
                        <span class='text-danger'>
                            <del><?php echo $product->product_org_price; ?> dkk </del>
                        </span><br>
                    <h3><?php echo $product->product_price; ?> dkk </h3>
                    </p>
                <?php else : ?>
                    <?php echo $product->product_price; ?> dkk <br>
                <?php endif; ?>
            </div>
            <div class="border-top border-bottom my-2">
                <form class="my-2 py-2" action="" method="post">
                    <div class="row">
                        <!-- Only show if product has colors -->
                        <div class="col d-<?php echo $colorSelect ?>">
                            <label class="">Color</label><br>
                            <select class="w-100" name="colorSelect" id="colorSelect">
                                <?php echo $colorsHTML; ?>
                            </select>
                        </div>
                        <!-- Only show if product has sizes -->
                        <div class="col d-<?php echo $sizeSelect ?>">
                            <label class="">Size</label><br>
                            <select class="w-100" name="sizeSelect" id="sizeSelect">
                                <?php echo $sizesHTML; ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="">Quantity</label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <input class='btn btn-outline-secondary' type='button' value='-' onclick='decrementQuantity()'>
                                </div>
                                <input type="text" class="form-control text-center" name="quantitySelect" id="quantity" value="1" min="1" max="99">
                                <div class="input-group-append">
                                    <input class='btn btn-outline-secondary' type='button' value='+' onclick='incrementQuantity()'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cart">
                        <?php if ($loggedIn) : ?>
                            <input type="hidden" name="csrf_token" value="<?php echo Token::generate(); ?>">
                            <input type="submit" name="addToCart" value="Add to cart" class="btn btn-primary mt-2">
                        <?php else : ?>
                            <a href="/login" class="btn btn-primary mt-2">Login to add to cart</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="product-description text-white">
                <?php echo $product->product_description; ?>
            </div>
        </div>
    </div>
</div>


<script>
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        centerMode: false,
        focusOnSelect: true
    });

    function incrementQuantity() {
        var quantityField = $("#quantity");
        var currentQuantity = parseInt(quantityField.val());
        if (currentQuantity <= 99) {
            quantityField.val(currentQuantity + 1);
        }
    }

    function decrementQuantity() {
        var quantityField = $("#quantity");
        var currentQuantity = parseInt(quantityField.val());
        if (currentQuantity > 1) {
            quantityField.val(currentQuantity - 1);
        }
    }

    //event listener for colorSelect and sizeSelect
    $(document).on('change', '#colorSelect, #sizeSelect', function() {
        handleProductQuantity();
    });

    // Event listener for page load or refresh
    $(document).ready(handleProductQuantity);

    function handleProductQuantity() {
        var color = $('#colorSelect').val();
        var size = $('#sizeSelect').val();

        $.ajax({
            url: "product.php",
            type: "POST",
            data: {
                selectedColor: color,
                selectedSize: size,
                name: "<?php echo $product->product_name; ?>"
            },
            success: function(response) {
                response = response.substring(response.indexOf("{"), response.indexOf("}") + 1);
                var product = JSON.parse(response);
                if (product.quantity < 1) {
                    $('input[name="addToCart"]').prop('disabled', true);
                    $('input[name="addToCart"]').val('Sold out');
                } else {
                    $('input[name="addToCart"]').prop('disabled', false);
                    $('input[name="addToCart"]').val('Add to cart');
                }
            }
        });
    }
</script>