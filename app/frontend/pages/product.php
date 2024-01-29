<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card slider slider-for">
                <?php echo $imagesHTML; ?>
            </div>
            <div class="card slider slider-nav">
                <?php echo $imagesHTML; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="product-info">
                <h1 class="product-name">
                    <?php echo $product->product_name; ?>
                </h1>
                <!-- . (($discount->discount_ID !== 1 && $discount->active) ?
                            "<span class='text-danger'>
                                <del>" . $product->product_org_price . " dkk </del>
                            </span><br>"
                            . $product->product_price . " dkk <br>" . "
                            <span class='text-success'>Discount: " . $discount->discount_percentage . "%</span>" : $product->product_price . " dkk <br>" . "") . -->
                <div class="product-price text-white">
                    <!-- if product discount  -->

                    
                </div>
                <div class="border-top border-bottom my-2">
                    <form class="card my-2 py-2" id="addToCartForm" method="post">
                        <input type="hidden" name="productID" value="<?php echo $product->product_ID; ?>">
                        <input type="hidden" name="colorID" value="<?php echo $product->color_ID; ?>">
                        <input type="hidden" name="sizeID" value="<?php echo $product->size_ID; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <div class="row">
                            <!-- Only show if product has colors -->
                            <div class="col d-<?php echo $colorSelect ?>">
                                <label class="text-dark" for="colorSelect">Color</label><br>
                                <select class="w-100" id="colorSelect">
                                    <?php echo $colorsHTML; ?>
                                </select>
                            </div>
                            <!-- Only show if product has sizes -->
                            <div class="col d-<?php echo $sizeSelect ?>">
                                <label class="text-dark" for="sizeSelect">Size</label><br>
                                <select class="w-100" id="sizeSelect">
                                    <?php echo $sizesHTML; ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label class="text-dark" for="quantitySelect">Quantity</label><br>
                                <!-- minus -->
                                <!-- quantity -->
                                <!-- plus -->

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="button" onclick="quantity(true)">-</button>
                                    </div>
                                    <input type="text" class="form-control text-center" id="quantity" value="1" min="1" max="99">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="quantity(false)">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-add mt-2">
                            <button class="btn btn-primary btn-block btn-lg" type="button" onclick="addToCart()">Add to cart</button>
                        </div>
                    </form>
                </div>
                <div class="product-description text-white">
                    <?php echo $product->product_description; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to handle color selection and image display. also run when page loads to display first select -->
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

    //quantity increment/decrement
    function quantity(decrement) {
        var quantityField = $("#quantity");
        var currentQuantity = parseInt(quantityField.val());
        if (decrement) {
            if (currentQuantity > 0 ) {
                quantityField.val(currentQuantity - 1);
            }
        } else {
            if (currentQuantity < 99) {
                quantityField.val(currentQuantity + 1);
            }
        }
    }
</script>