<!-- Product Name -->
<h1><?php echo $product->product_name; ?></h1>

<!-- Color Selector -->
<label for="colorSelect">Select Color:</label>
<select name="selectedColor" id="colorSelect">
    <?php foreach ($colors as $color) : ?>
        <option value="<?php echo $color; ?>"><?php echo $color; ?></option>
    <?php endforeach; ?>
</select>

<!-- Image -->
<div id="displayedImage"></div>

<!-- JavaScript to handle color selection and image display -->
<script>
    document.getElementById('colorSelect').addEventListener('change', function() {
        var selectedColor = this.value;
        var imagePaths = <?php echo json_encode($imagePaths); ?>;

        // Find the image path that matches the selected color
        var imagePath = imagePaths[selectedColor];

        // Display the selected image using PHP
        document.getElementById('displayedImage').innerHTML = '<?php Product::displayImage("' + imagePath + '"); ?>';
    });
</script>
