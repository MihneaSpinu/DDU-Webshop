<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<h2 class="text-center mt-5 border-top border-dark">Product Information</h2>
<!-- Select a product -->
<form class="form-group" method="POST">
    <label for="productSelect">Select Product:</label>
    <select name="selectedProduct" id="productSelect">
        <?php foreach ($products as $product) : ?>
            <option value="<?php echo $product->product_name; ?>"><?php echo $product->product_name; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="productSubmit" value="Select">
</form>

<!-- Print the product information -->
<?php
if (isset($chosenProduct)) :
    $i = 0;
    $allProducts = 0; ?>
    <h3><?php echo $chosenProduct[0]->product_name . " (" . count($chosenProduct) . ")"; ?></h3>
    <!-- Edit product here. Create a table with Name, description, price, weight, category select, add/remove color button, add/remove size button, discount select. If something changes, update database for all products with the same name -->
    <form action="warehouse.php" method="post">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Product Price</th>
                    <th>Product Weight</th>
                    <th>Category</th>
                    <th>Discount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="productName" value="<?php echo $chosenProduct[0]->product_name; ?>"></td>
                    <td><input type="text" name="productDescription" value="<?php echo $chosenProduct[0]->product_description; ?>"></td>
                    <td><input type="text" name="productPrice" value="<?php echo $chosenProduct[0]->product_price; ?>"></td>
                    <td><input type="text" name="productWeight" value="<?php echo $chosenProduct[0]->product_weight; ?>"></td>
                    <td>
                        <select name="categorySelect">
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo $category->category_ID; ?>" <?php echo ($category->category_ID == $chosenProduct[0]->category_ID) ? "selected" : ""; ?>><?php echo $category->category_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="discountSelect">
                            <?php foreach ($discounts as $discount) : ?>
                                <option value="<?php echo $discount->discount_ID; ?>" <?php echo ($discount->discount_ID == $chosenProduct[0]->discount_ID) ? "selected" : ""; ?>><?php echo $discount->discount_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="productID" value="<?php echo $chosenProduct[0]->product_ID; ?>">
        <input type="submit" name="editProduct" value="Edit Product">
    </form>

    <?php
    foreach ($chosenProduct as $product) : ?>
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
                        <!-- Delete product button -->
                        <form action="warehouse.php" method="post">
                            <input type="hidden" name="productID" value="<?php echo $product->product_ID; ?>">
                            <input type="submit" name="deleteProduct" value="Delete Product(s)">
                        </form>
                        <?php $productIteration++;
                        for ($k = 0 + $currentID; $k < count($quantities[$i]) * $productIteration; $k++) : ?>
                            <?php
                            echo $allOfChosenProduct[$currentID]->product_ID . "<br>";
                            $currentID++;
                            ?>
                        <?php endfor; ?>
                    </td>
                    <td><?php echo $product->product_name; ?></td>
                    <td><?php echo $product->product_description; ?></td>
                    <td><?php
                        echo $product->product_price . " kr <br>";
                        echo "Original price: " . $product->product_price / (1 - ($discount_percentage / 100)) . " kr";
                        ?></td>
                    <td><?php echo $product->product_weight . " g"; ?></td>
                    <td><?php echo $category_name; ?></td>
                    <td><?php echo $colors[$i]; ?></td>
                    <td><?php $j = 0;
                        foreach ($quantities[$i] as $quantity) : ?>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <button class='btn btn-outline-secondary' type='button' onclick='decrementQuantity(<?php echo $allOfChosenProduct[$allProducts]->product_ID; ?>)'>-</button>
                                </div>
                                <input type='text' class='form-control text-center' id='quantity_<?php echo $allOfChosenProduct[$allProducts]->product_ID; ?>' value='<?php echo $quantity->quantity; ?>'>
                                <input class='btn btn-outline-secondary' type='submit' value='+' onclick='incrementQuantity(<?php echo $allOfChosenProduct[$allProducts]->product_ID; ?>)'>
                                <div class='input-group-append' style='min-width: 50px;'>
                                    <span class='input-group-text'>
                                        <?php echo $sizes[$j]->size_name;
                                        $j++;
                                        $allProducts++; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </td>
                    <td><?php
                        echo $discount_name;
                        echo "<br>Discount percentage: " . $discount_percentage . "%"; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php $i++;
        $allProducts++; ?>
    <?php endforeach; ?>

<?php else : ?>
    <p>Please select a product.</p>
<?php endif; ?>

<script>
    function incrementQuantity(ProductID) {
        var quantityField = $("#quantity_" + ProductID);
        var currentQuantity = parseInt(quantityField.val());
        if (currentQuantity <= 99) {
            quantityField.val(currentQuantity + 1);
            updateProductQuantity(ProductID, currentQuantity + 1);
        }
    }

    function decrementQuantity(ProductID) {
        var quantityField = $("#quantity_" + ProductID);
        var currentQuantity = parseInt(quantityField.val());
        if (currentQuantity > 1) {
            quantityField.val(currentQuantity - 1);
            updateProductQuantity(ProductID, currentQuantity - 1);
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
        updateProductQuantity(ProductID, newQuantity);
    });

    function updateProductQuantity(productID, newQuantity) {
        $.ajax({
            url: "warehouse.php",
            type: "POST",
            data: {
                productID: productID,
                newQuantity: newQuantity
            },
        });
    }
</script>