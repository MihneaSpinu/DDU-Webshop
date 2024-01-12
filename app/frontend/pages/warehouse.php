<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
</link>
<div class="mx-5">
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
        <h3><?php echo $product->product_name ?></h3>
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
                        <?php foreach ($colors as $color) : ?>
                            <?php echo $color->color_name . "<br>"; ?>
                        <?php endforeach; ?>
                    </td>
                    <td><?php echo $discount_name; ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Print all pictures of the product. If the path leads to placeholder print missing instead -->
        <h2>Product Images</h2>
        <?php
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
    <?php else : ?>
        <p>Please select a product.</p>
    <?php endif; ?>

    <!-- Edit product -->
    <form class="form-group mt-3" method="POST">
        <label for="editProduct">Edit Product:</label>
        <input type="text" name="editProductName" placeholder="Enter new product name">
        <input type="text" name="editProductDescription" placeholder="Enter new product description">
        <input type="text" name="editProductPrice" placeholder="Enter new product price">
        <input type="text" name="editProductWeight" placeholder="Enter new product weight">
        <input type="text" name="editProductQuantity" placeholder="Enter new product quantity">
        <input type="text" name="editProductCategory" placeholder="Enter new product category">
        <input type="text" name="editProductDiscount" placeholder="Enter new product discount">
        <button type="submit" name="editProductSubmit" class="btn btn-primary" value="select">Edit Product</button>
    </form>

    <!-- Delete product -->
    <form class="form-group mt-3" method="POST">
        <button type="submit" name="deleteProductSubmit" class="btn btn-danger">Delete Product</button>
    </form>


    <!-- Select a user -->
    <h2 class="text-center mt-5 border-top border-dark">User Information</h2>
    <form class="form-group" method="POST">
        <label for="userSelect">Select User:</label>
        <select name="selectedUser" id="userSelect">
            <?php foreach ($users as $user) : ?>
                <option value="<?php echo $user->username; ?>"><?php echo $user->username; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="userSubmit" value="Select">
    </form>

    <!-- Print the user information -->
    <?php if (isset($selectedUser)) : ?>
        <h3><?php echo $selectedUser->username; ?></h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Amount of Orders</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Name</th>
                    <th>Is Guest</th>
                    <th>Joined</th>
                    <th>Email</th>
                    <th>Group</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $selectedUser->uid; ?></td>
                    <td><?php echo $amountOfOrders; ?></td>
                    <td><?php echo $selectedUser->username; ?></td>
                    <td><?php echo $selectedUser->password; ?></td>
                    <td><?php echo $selectedUser->name; ?></td>
                    <td><?php echo $selectedUser->is_guest; ?></td>
                    <td><?php echo $selectedUser->joined; ?></td>
                    <td><?php echo $selectedUser->email; ?></td>
                    <!-- echo user group permissions from id -->
                    <td><?php echo $groupPerms[$selectedUser->group_ID]; ?></td>
                </tr>
        </table>
    <?php else : ?>
        <p>Please select a user.</p>
    <?php endif; ?>

    <!-- Edit User -->
    <form class="form-group mt-3" method="POST">
        <label for="editUser">Edit User:</label>
        <input type="text" name="editUserUsername" placeholder="Enter new username">
        <input type="text" name="editUserPassword" placeholder="Enter new password">
        <input type="text" name="editUserName" placeholder="Enter new name">
        <input type="text" name="editUserIsGuest" placeholder="Enter new is_guest">
        <input type="text" name="editUserJoined" placeholder="Enter new joined">
        <input type="text" name="editUserEmail" placeholder="Enter new email">
        <input type="text" name="editUserGroup" placeholder="Enter new group">
        <button type="submit" name="editUserSubmit" class="btn btn-primary" value="select">Edit User</button>
    </form>
    <!-- Delete User -->
    <form class="form-group mt-3" method="POST">
        <button type="submit" name="deleteUserSubmit" class="btn btn-danger">Delete User</button>
    </form>

    <!-- Select an order -->
    <h2 class="text-center mt-5 border-top border-dark">Order Information</h2>
    <form class="form-group" method="POST">
        <label for="monthSelect">Select Orders By Month:</label>
        <select name="selectedMonth" id="monthSelect">
            <?php
            foreach ($monthsWithOrders as $monthAndYear => $orders) :
                $date = DateTime::createFromFormat('Y-m', $monthAndYear);
            ?>
                <option value="<?php echo $monthAndYear; ?>"><?php echo $date->format('M Y'); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="monthSubmit" value="Select">
    </form>

    <!-- Print the order information -->
    <?php if (isset($selectedMonth)) : ?>
        <h3><?php echo $selectedMonthFormatted; ?></h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <!-- <th>Items Ordered</th> -->
                    <th>Order Total</th>
                    <!-- <th>Order Shipping</th> -->
                    <!-- <th>Order Discount</th> -->
                    <!-- <th>Order Subtotal</th>
                <th>Order Payment</th>
                <th>Order Shipping Address</th>
                <th>Order Billing Address</th> -->
                    <th>Ordered By</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monthsWithOrders[$selectedMonth] as $order) : ?>
                    <tr>
                        <td><?php echo $order->order_ID; ?></td>
                        <td><?php echo $order->order_date; ?></td>
                        <td><?php echo $order->total; ?></td>
                        <td><?php echo "Uid: " . $order->uid . " || Username: " . $order->username; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Please select a valid order date.</p>
    <?php endif; ?>
</div>