<div class="container mt-5">
  <div class="row justify-content-center">
    <!-- Create 3 cards with these titles. Clothing, Accessories and Homeware -->
    <div class="card-deck">
      <div class="card card-shadow">
        <img src="<?php echo FRONTEND_ASSET ?>productImages/BroomSweep Logo Shirt-Blue (2).png" class="card-img-top">
        <div class="mt-auto">
          <div class="card-body">
            <h5 class="card-title">Clothing</h5>
            <p class="card-text">Explore cool designs here</p>
            <a href="/categories/clothing" class="btn btn-primary">Shop Now</a>
          </div>
        </div>
      </div>
      <div class="card card-shadow">
        <img src="<?php echo FRONTEND_ASSET ?>productImages/Case (1).png" class="card-img-top">
        <div class="mt-auto">
          <div class="card-body">
            <h5 class="card-title">Accessories</h5>
            <p class="card-text">Find the latest accessories here.</p>
            <a href="/categories/accessories" class="btn btn-primary">Shop Now</a>
          </div>
        </div>
      </div>
      <div class="card card-shadow">
        <img src="<?php echo FRONTEND_ASSET ?>productImages/untitled.png" class="card-img-top">
        <div class="mt-auto">
          <div class="card-body">
            <h5 class="card-title">Homewares</h5>
            <p class="card-text">Find great decorations for your home.</p>
            <a href="/categories/homewares" class="btn btn-primary">Shop Now</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Big cart fitting the container width with "Recommended products" and 6 products wide x 2 products tall cards -->
    <div class="card-body mt-4">
      <h5 class="card-title">Recommended Products</h5>
      <div class="row">
        <!-- Show all products from random products with small cards. Write name on the lowest line from all products. Price underneath. Show view button on hover-->
        <div class="card-deck">
          <?php foreach ($randomProducts as $product) : ?>
            <div class="px-2 col-6 col-md-4 col-lg-2 mb-3 d-flex align-items-stretch">
              <div class="mx-0 px-0 card card-product">
                <a href="/product?name=<?php echo $product->product_name ?>" style="text-decoration: none;">
                <img src="<?php echo FRONTEND_ASSET ?>productImages/Hero Shirt-Orange (1).png" class="card-img-top">
                <div class="mt-auto">
                  <div class="card-body d-flex flex-column">
                    <p class="card-title mb-auto font-weight-bold pb-1"><?php echo $product->product_name ?></p>
                    <p class="card-text border-top mt-auto text-muted"><?php echo $product->product_price ?> dkk</p>
                  </div>
                </div>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>