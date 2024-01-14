<!-- Select a month -->
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
                <th>Items Ordered</th>
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
                    <td><?php echo $itemsHTML; ?></td>
                    <td><?php echo $order->total . "dkk"; ?></td>
                    <td><?php echo "Uid: " . $order->uid . " || Username: " . $order->username; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Please select a valid order date.</p>
<?php endif; ?>