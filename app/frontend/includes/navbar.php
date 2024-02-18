<nav class="navbar d-none d-sm-block navbar-expand-lg sticky-top mb-5">
  <div class="container">
    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse order-last" id="collapsibleNavbar">
      <ul class="navbar-nav yellow-color">
        <li class="nav-item">
          <a class="nav-link" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/designs">Designs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/clothing">Clothing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/accessories">Accessories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/homewares">Homewares</a>
        </li>
      </ul>
    </div>

    <div class="nav-icons d-none yellow-color order-lg-last">
      <div class="d-flex">
        <a class="nav-link" href="/">
          <i class="fa fa-search"></i>
        </a>
        <a class="nav-link" href="/cart">
          <i class="fa fa-shopping-cart"></i>
        </a>
      </div>
    </div>

    <div class="profile yellow-color order-lg-last">
      <div class="d-flex">
        <?php if ($user->isLoggedIn()) : ?>
          <a class="nav-link" href="/profile">Profile</a>
          <a class="nav-link" href="/logout">Logout</a>
        <?php else : ?>
          <a class="nav-link" href="/login">Profile</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<script>
  // Add JavaScript to handle navbar scrolling and button replacement
  $(document).ready(function() {
    var header = $(".header");
    var profileLink = $(".profile");

    $(window).scroll(function() {
      if ($(this).scrollTop() > header.height()) {
        $(".nav-icons").removeClass("d-none");
        profileLink.addClass("d-none");
      } else {
        $(".nav-icons").addClass("d-none");
        profileLink.removeClass("d-none");
      }
    });
  });
</script>