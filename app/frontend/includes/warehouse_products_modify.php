<div class="container">
    <h2>Create Product</h2>
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
                    <td><input type="text" id="productName" name="productName"></td>
                    <td><input type="text" id="productDescription" name="productDescription"></td>
                    <td><input type="text" id="productPrice" name="productPrice"></td>
                    <td><input type="text" id="productWeight" name="productWeight"></td>
                    <td>
                        <select name="categorySelect" id="categorySelect">
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo $category->category_ID; ?>"><?php echo $category->category_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="discountSelect" id="discountSelect">
                            <?php foreach ($discounts as $discount) : ?>
                                <option value="<?php echo $discount->discount_ID; ?>"><?php echo $discount->discount_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <!-- Multiple select for colors and sizes -->
                    <td>
                        <select name="colorSelect[]" id="colorSelect" multiple>
                            <?php foreach ($colors as $color) : ?>
                                <option value="<?php echo $color->color_ID; ?>"><?php echo $color->color_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="sizeSelect[]" id="sizeSelect" multiple>
                            <?php foreach ($sizes as $size) : ?>
                                <option value="<?php echo $size->size_ID; ?>"><?php echo $size->size_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="csrf_token" value="<?php echo Token::generate(); ?>">
        <input type="submit" name="createProduct" value="Create Product">
    </form>
</div>

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