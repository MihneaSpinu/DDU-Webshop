<!-- Product Name -->
<h1><?php echo $product->product_name;?></h1>

<!-- Color Selector. If $displaySelect is false display nothing -->
<div id="colorSelector" style="display: <?php echo $displaySelect; ?>;">
    <label for="colorSelect">Select Color:</label>
    <select name="selectedColor" id="colorSelect" onchange="findColor(this.options[this.selectedIndex].value)"> 
        <?php foreach ($colorNames as $color) : ?>
            <option value="<?php echo $color; ?>"><?php echo $color; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Image -->
<div id="displayedImage"></div>

<!-- JavaScript to handle color selection and image display. also run when page loads to display first select -->
<script>    
    // Get the image paths from PHP
    var imagePaths = <?php echo json_encode($imagePaths); ?>;        
    var select = document.getElementById('colorSelect');
    var displayedImage = document.getElementById('displayedImage');

    displayedImage.innerHTML = '<img src="' + imagePaths[select.value] + '" alt="Product Image">';
    
    // Function to display the image for the selected color
    function findColor(color) {
        displayedImage.innerHTML = '<img src="' + imagePaths[color] + '" alt="Product Image">';
    }
    
</script>
