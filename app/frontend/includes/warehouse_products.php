<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<h2 class="text-center mt-5 border-top border-dark font-weight-bold">Product Information</h2>

<?php require_once 'warehouse_products_modify.php'; ?>

<!-- Select a product -->
<form class="form-group" method="POST">
    <label for="productSelect">Select Product:</label>
    <select name="selectedProduct" id="selectedProduct">
        <?php foreach ($products as $product) : ?>
            <option value="<?php echo $product->product_name; ?>"><?php echo $product->product_name; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="productSubmit" value="Select">
</form>

<!-- Print the product information -->
<?php
if (isset($chosenProduct)) : ?>
    <h3><?php echo $chosenProduct->product_name . " (" . count($allProductsCount) . ")"; ?></h3>
    <div class="container">
        <h2>Edit Product</h2>
        <form action="" method="post">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Product Description</th>
                        <th>Product Price</th>
                        <th>Product Weight</th>
                        <th>Category</th>
                        <th>Discount</th>
                        <th>Colors</th>
                        <th>Sizes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" id="productName" name="productName" value="<?php echo $chosenProduct->product_name; ?>"></td>
                        <td><input type="text" id="productDescription" name="productDescription" value="<?php echo $chosenProduct->product_description; ?>"></td>
                        <td><input type="text" id="productPrice" name="productPrice" value="<?php echo $chosenProduct->product_price; ?>"></td>
                        <td><input type="text" id="productWeight" name="productWeight" value="<?php echo $chosenProduct->product_weight; ?>"></td>
                        <td>
                            <select name="categorySelect" id="categorySelect">
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?php echo $category->category_ID; ?>" <?php echo ($category->category_ID == $chosenProduct->category_ID) ? "selected" : ""; ?>><?php echo $category->category_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="discountSelect" id="discountSelect">
                                <?php foreach ($discounts as $discount) : ?>
                                    <option value="<?php echo $discount->discount_ID; ?>" <?php echo ($discount->discount_ID == $chosenProduct->discount_ID) ? "selected" : ""; ?>><?php echo $discount->discount_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <!-- Multiple select for colors and sizes -->
                        <td>
                            <select name="colorSelect[]" id="colorSelect" multiple>
                                <!-- Colors already in distinctColors are automatically selected, but not shown in the select -->
                                <?php foreach ($colors as $color) : ?>
                                    <?php if (in_array($color, $distinctColors)) : ?>
                                        <option class="d-none" value="<?php echo $color->color_ID; ?>" selected><?php echo $color->color_name; ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $color->color_ID; ?>"><?php echo $color->color_name; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="sizeSelect[]" id="sizeSelect" multiple>
                                <!-- Sizes already in distinctSizes are automatically selected -->
                                <?php foreach ($sizes as $size) : ?>
                                    <?php if (in_array($size, $distinctSizes)) : ?>
                                        <option class="d-none" value="<?php echo $size->size_ID; ?>" selected><?php echo $size->size_name; ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $size->size_ID; ?>"><?php echo $size->size_name; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="currentProductName" value="<?php echo $chosenProduct->product_name; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo Token::generate(); ?>">
            <input type="submit" name="editProduct" value="Edit Product">
        </form>
    </div>
    <!-- get count of the first arrays in allproducts -->
    <?php for ($i = 0; $i < count($allProducts); $i++) : $currentColor = $distinctColors[$i]->color_name ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Product Price</th>
                    <th>Product Weight</th>
                    <th>Category</th>
                    <th>Color</th>
                    <th>Quantity & Size</th>
                    <th>Discount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <!-- Delete product button. UpdateProuct(productID, quantity, true) -->
                        <input type="submit" class="btn btn-danger" value="Delete" onclick='deleteColor("<?php echo $currentColor ?> ", "<?php echo $chosenProduct->product_name ?>")'>

                        <!-- Print all ids of products with same color id as product->color_ID and unique size id -->
                        <?php
                        for ($j = 0; $j < count($allProducts[$currentColor]); $j++) {
                            if (array_key_exists($distinctSizes[$j]->size_name, $allProducts[$currentColor])) {
                                echo "<br>" . $allProducts[$currentColor][$distinctSizes[$j]->size_name][0]->product_ID;
                            }
                        }
                        ?>
                    </td>
                    <td><?php echo $chosenProduct->product_name; ?></td>
                    <td><?php echo $chosenProduct->product_description; ?></td>
                    <td><?php
                        echo $chosenProduct->product_price . " kr";
                        echo "<br>Original price: " . $chosenProduct->product_price / (1 - ($discount_percentage / 100)) . " kr";
                        ?></td>
                    <td><?php echo $chosenProduct->product_weight . " g"; ?></td>
                    <td><?php echo $category_name; ?></td>
                    <td><?php echo $currentColor; ?></td>
                    <td><?php
                        for ($j = 0; $j < count($allProducts[$currentColor]); $j++) :
                            if (array_key_exists($distinctSizes[$j]->size_name, $allProducts[$currentColor])) {
                                $currentSize = $distinctSizes[$j]->size_name;
                                $productID = $allProducts[$currentColor][$currentSize][0]->product_ID;
                                $quantity = $allProducts[$currentColor][$currentSize][0]->quantity;
                        ?>
                                <div class='input-group'>
                                    <input class='btn btn-outline-secondary' type='submit' value='-' onclick='decrementQuantity(<?php echo $productID; ?>)'>
                                    <input type='text' class='form-control text-center' id='quantity_<?php echo $productID; ?>' value='<?php echo $quantity; ?>'>
                                    <input class='btn btn-outline-secondary' type='submit' value='+' onclick='incrementQuantity(<?php echo $productID; ?>)'>
                                    <div class='input-group-append' style='min-width: 50px;'>
                                        <span class='input-group-text'>
                                            <?php echo $currentSize; ?>
                                        </span>
                                    </div>
                                </div>
                        <?php }
                        endfor; ?>
                    </td>
                    <td><?php
                        echo $discount_name;
                        echo "<br>Discount percentage: " . $discount_percentage . "%"; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php endfor; ?>

<?php else : ?>
    <p>Please select a product.</p>
<?php endif; ?>

<script>
    function incrementQuantity(ProductID) {
        var quantityField = $("#quantity_" + ProductID);
        var currentQuantity = parseInt(quantityField.val());
        if (currentQuantity <= 99) {
            quantityField.val(currentQuantity + 1);
            updateProduct(ProductID, currentQuantity + 1);
        }
    }

    function decrementQuantity(ProductID) {
        var quantityField = $("#quantity_" + ProductID);
        var currentQuantity = parseInt(quantityField.val());
        if (currentQuantity > 1) {
            quantityField.val(currentQuantity - 1);
            updateProduct(ProductID, currentQuantity - 1);
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
        var ProductID = $(this).attr('id').replace('quantity_', '');
        var newQuantity = $(this).val();
        updateProduct(ProductID, newQuantity);
    });

    function updateProduct(productID, newQuantity) {
        $.ajax({
            url: "warehouse.php",
            type: "POST",
            data: {
                productID: productID,
                newQuantity: newQuantity
            },
        });
    }

    function deleteColor(color, productName) {
        $.ajax({
            url: "warehouse.php",
            type: "POST",
            data: {
                deleteColor: color,
                productName: productName
            },
        });
    }
</script>