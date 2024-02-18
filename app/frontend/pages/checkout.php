<form action="checkout" method="post">
    <h2>Contact</h2>
    <!-- Email -->
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <h2>Delivery</h2>
    <!-- Country, first name, last name, address, postal code, city, phone (optional) -->
    <div class="form-group">
        <label for="country">Country</label>
        <input type="text" class="form-control" id="country" name="country" required>
    </div>
    <div class="form-group">
        <label for="firstName">First name</label>
        <input type="text" class="form-control" id="firstName" name="firstName" required>
    </div>
    <div class="form-group">
        <label for="lastName">Last name</label>
        <input type="text" class="form-control" id="lastName" name="lastName" required>
    </div>
    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" class="form-control" id="address" name="address" required>
    </div>
    <div class="form-group">
        <label for="postalCode">Postal code</label>
        <input type="text" class="form-control" id="postalCode" name="postalCode" required>
    </div>
    <div class="form-group">
        <label for="city">City</label>
        <input type="text" class="form-control" id="city" name="city" required>
    </div>
    <div class="form-group">
        <label for="phone">Phone (Optional)</label>
        <input type="text" class="form-control" id="phone" name="phone">
    </div>

    <h2>Payment</h2>
    <!-- Card number, expiry date, CVV, name on card -->
    <div class="form-group">
        <label for="cardNumber">Card number</label>
        <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="Does nothing">
    </div>
    <div class="form-group">
        <label for="expiryDate">Expiry date</label>
        <input type="text" class="form-control" id="expiryDate" name="expiryDate" placeholder="Does nothing">
    </div>
    <div class="form-group">
        <label for="cvv">CVV</label>
        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="Does nothing">
    </div>
    <div class="form-group">
        <label for="nameOnCard">Name on card</label>
        <input type="text" class="form-control" id="nameOnCard" name="nameOnCard" placeholder="Does nothing">
    </div>

    <!-- Pay now button -->
    <div class="text-center">
        <input type="submit" name="payNow" value="Pay now" class="btn btn-primary">
        <input type="hidden" name="csrf_token" value="<?php echo Token::generate(); ?>">
        <input type="hidden" name="cartID" value="<?php echo $cart->cart_ID; ?>">
    </div>
</form>