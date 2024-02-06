<div class="container mt-5">
  <div class="row justify-content-center">
    <div>
      <div class="container">
        <!-- Create 3 cards with these titles. Clothing, Accessories and Homeware -->
        <div class="card-deck">
          <div class="card card-shadow">
            <img src="<?php echo FRONTEND_ASSET ?>productImages/BroomSweep Logo Shirt-Blue (2).png" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title">Clothing</h5>
              <p class="card-text text-dark">Explore cool designs here</p>
              <a href="products.php?category=clothing" class="btn btn-primary">Shop Now</a>
            </div>
          </div>
          <div class="card card-shadow">
            <img src="<?php echo FRONTEND_ASSET ?>productImages/Case (1).png" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title">Accessories</h5>
              <p class="card-text text-dark">Find the latest accessories here.</p>
              <a href="products.php?category=accessories" class="btn btn-primary">Shop Now</a>
            </div>
          </div>
          <div class="card card-shadow">
            <img src="https://via.placeholder.com/150" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title">Homeware</h5>
              <p class="card-text text-dark">Find great decorations for your home.</p>
              <a href="products.php?category=homeware" class="btn btn-primary">Shop Now</a>
            </div>
          </div>
        </div>
        <!-- Big cart fitting the container width with "Recommended products" and 6 products wide x 2 products tall cards -->
        <div class="card mt-5">
          <div class="card-body">
            <h5 class="card-title">Recommended Products</h5>
            <div class="row">
              <!-- Show all products from random products with small cards. Write name on the lowest line from all products. Price underneath. Show view button on hover-->
              <?php foreach ($randomProducts as $product) : ?>
                <div class="col-6 col-md-4 col-lg-2 mb-3">
                  <div class="card">
                    <img src="<?php echo FRONTEND_ASSET ?>productImages/<?php echo $product->product_image ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                      <h5 class="card-title
                      text-truncate"><?php echo $product->product_name ?></h5>
                      <p class="card-text text-dark"><?php echo $product->product_price ?> dkk</p>
                      <a href="/Webshop-DDU/product?name=<?php echo $product->product_name ?>" class="btn btn-primary">View</a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>