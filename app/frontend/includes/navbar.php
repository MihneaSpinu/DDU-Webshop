<nav class="navbar d-none d-sm-block navbar-expand-lg bg-dark navbar-dark sticky-top">
  <div class="container">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/">Designs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/">Clothing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/">Accessories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/">Homewares</a>
        </li>
      </ul>
    </div>

    <!-- Add the search and cart buttons here -->
    <div class="nav-icons d-none">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="/search">
            <i class="fa fa-search"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/cart">
            <i class="fa fa-shopping-cart"></i>
          </a>
        </li>
      </ul>
    </div>
    
    <div class="profile">
      <?php if ($user->isLoggedIn()) : ?>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="/profile">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/logout">Logout</a>
          </li>
        </ul>
      <?php else : ?>
        <a class="nav-link" href="/login">Profile</a>
      <?php endif; ?>
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
        $(".navbar-brand").addClass("d-none");
        $(".nav-icons").removeClass("d-none");
        profileLink.addClass("d-none");
      } else {
        $(".navbar-brand").removeClass("d-none");
        $(".nav-icons").addClass("d-none");
        profileLink.removeClass("d-none");
      }
    });
  });
</script>