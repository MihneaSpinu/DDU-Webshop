<!-- Product Name -->
<h1><?php echo $product->product_name; ?></h1>
<!-- Product description -->
<p><?php echo $product->product_description; ?></p>
<!-- Product Price -->
<h2><?php echo $product->product_price; ?> dkk</h2>
<!-- Add to cart button -->
<form action="app/backend/auth/addToCart.php" method="post">
    <input type="hidden" name="product_ID" value="<?php echo $product->product_ID; ?>">
    <input type="hidden" name="quantity" value="1">
    <input type="submit" value="Add to cart" class="btn btn-primary">
</form>

<!-- Choose amount as a number with minus to the left and plus to the right -->
<div class="mt-5">
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
</div>

<!-- Choose sizes -->
<div class="mt-5" id="sizeSelector" style="display: <?php echo $sizeSelect; ?>">
    <label for="sizeSelect">Select Size:</label>
    <select name="selectedSize" id="sizeSelect">
        <?php echo $sizesHTML; ?>
    </select>
</div>

<!-- Color Selector. If $displaySelect is false display nothing -->
<div class="mt-5" id="colorSelector" style="display: <?php echo $colorSelect; ?>;">
    <label for="colorSelect">Select Color:</label>
    <select name="selectedColor" id="colorSelect" onchange="findColor(options[selectedIndex].value)">
        <?php 
        echo $colorsHTML;
        ?>
    </select>
</div>

<div id="displayedImage"></div>
<!-- JavaScript to handle color selection and image display. also run when page loads to display first select -->
<script>
    // Get the image paths from PHP
    var imagePaths = <?php echo json_encode($imagePaths); ?>;
    var select = document.getElementById('colorSelect');
    var displayedImage = document.getElementById('displayedImage');

    displayedImage.innerHTML = '<img style="width:500px;" src="' + imagePaths[select.value] + '" alt="Product Image">';

    // Function to display the image for the selected color
    function findColor(color) {
        displayedImage.innerHTML = '<img style="width:500px;" src="' + imagePaths[color] + '" alt="Product Image">';
    }
</script>