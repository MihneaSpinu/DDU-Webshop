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
<?php if (isset($chosenProduct)) : ?>
    <h3><?php echo $chosenProduct->product_name ?></h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Description</th>
                <th>Product Price</th>
                <th>Product Weight</th>
                <th>Quantity</th>
                <th>Category</th>
                <th>Color</th>
                <th>Size</th>
                <th>Discount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $chosenProduct->product_ID; ?></td>
                <td><?php echo $chosenProduct->product_name; ?></td>
                <td><?php echo $chosenProduct->product_description; ?></td>
                <td><?php echo $chosenProduct->product_price . "dkk"; ?></td>
                <td><?php echo $chosenProduct->product_weight . "g"; ?></td>
                <td><?php echo $chosenProduct->quantity; ?></td>
                <td><?php echo $category_name; ?></td>
                <td><?php echo $colorsHTML; ?></td>
                <td><?php echo $sizesHTML; ?></td>
                <td><?php echo $discount_name; ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Print all pictures of the product. If the path leads to placeholder print missing instead -->
    <h2>Product Images</h2>
    <?php
    foreach ($colors as $color) {
        $imagePath = "app/frontend/assets/productImages/" . $chosenProduct->product_name . "-" . $color->color_name . ".png";

        if (file_exists($imagePath)) {
            echo "<img src='" . $imagePath . "' alt='Product Image'>";
        } else {
            if ($color->color_name == "No Color") {
                $color->color_name = "Image";
            }
            echo "<p>Missing " . $color->color_name . "</p>";
        }
    }
    ?>
<?php else : ?>
    <p>Please select a product.</p>
<?php endif; ?>