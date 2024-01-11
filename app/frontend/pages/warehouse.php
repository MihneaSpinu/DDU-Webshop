<!-- Select a product, sorted by product name. Some products have different colors and therefore are more than once in the table, therefore the select should only show the first instance. Add select button to change viewed product-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"></link>
<h1 class="text-center">Products</h1>
<form class="form-group" method="POST">
    <label for="productSelect">Select Product:</label>
    <select name="selectedProduct" id="productSelect">
        <?php foreach ($products as $product) : ?>
            <option value="<?php echo $product->product_name; ?>"><?php echo $product->product_name; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="productSubmit" value="Select">
</form>

<h3><?php echo $product->product_name ?></h3>
<!-- Table of product columns with product details. Sorted by     `product_ID` int(11) NOT NULL AUTO_INCREMENT,
    `product_name` varchar(255) NOT NULL,
    `product_description` varchar(255) NOT NULL,
    `product_price` decimal(10,2) NOT NULL,
    `product_weight` int(11) NOT NULL,
    `quantity` int(11) DEFAULT 0,
    `category_ID` int(11) NOT NULL,
    `color_ID` int(11) NOT NULL DEFAULT 1,
    `discount_ID` int(11) NOT NULL DEFAULT 1, -->
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
            <th>Discount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $chosenProduct->product_ID; ?></td>
            <td><?php echo $chosenProduct->product_name; ?></td>
            <td><?php echo $chosenProduct->product_description; ?></td>
            <td><?php echo $chosenProduct->product_price; ?></td>
            <td><?php echo $chosenProduct->product_weight; ?></td>
            <td><?php echo $chosenProduct->quantity; ?></td>
            <td><?php echo $category_name; ?></td>
            <td>
                <?php
                foreach ($colorNames as $color) {
                    echo $color . "<br>";
                }
                ?>
            </td>
            <td><?php echo $discount_name; ?></td>
        </tr>
    </tbody>
</table>

<!-- Print all pictures of the product. If the path leads to placeholder print missing instead -->
<h2>Product Images</h2>
<?php
//All color ids from the product
$colors = Product::defineColors($chosenProduct->product_name);
$names = [];
foreach ($colors as $color) {
    $names[] = $color->color_name;
}

foreach ($colorNames as $color) {
    $imagePath = "app/frontend/assets/productImages/" . $chosenProduct->product_name . "-" . $color . ".png";

    if (file_exists($imagePath)) {
        echo "<img src='" . $imagePath . "' alt='Product Image'>";
    } else {
        if ($color == "No Color") {
            $color = "Image";
        }
        echo "<p>Missing " . $color . "</p>";
    }
}
?>

<!-- Select a user, sorted by user name. Add select button to change viewed user-->
<h1 class="text-center">Users</h1>
<form class="form-group" method="POST">
    <label for="userSelect">Select User:</label>
    <select name="selectedUser" id="userSelect">
        <?php foreach ($usernames as $user) : ?>
            <option value="<?php echo $user; ?>"><?php echo $user; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="userSubmit" value="Select">
</form>

<!-- List of all orders, select one of 12 months -->
<h1 class="text-center">Orders</h1>
