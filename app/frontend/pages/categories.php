<?php if ($design) : ?>
    <div class="container">
            <div class="mr-auto">
                <h1>Designs</h1>
            </div>
        <div class="row">
            <div class="card-deck">
                <?php foreach ($allDesigns as $design) : ?>
                    <div class="col">
                        <div class="card mb-4">
                            <img class="card-img-top" src="<?php echo FRONTEND_ASSET . $design['image_path']; ?>" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title mt-3"><?php echo $design['design_name']; ?></h5>
                                <p class="card-text"><?php echo $design['design_description']; ?></p>
                                <a href="/designs?design=<?php echo $design['design_name']; ?>" class="btn btn-primary">See all products</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?php echo $chosenCategory ?><h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>See all products for the category</p>
            </div>
        </div>
    </div>
<?php endif; ?>