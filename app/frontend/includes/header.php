<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php appName(); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo FRONTEND_ASSET . 'css/style.css'; ?>">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/jquery.slick/1.3.11/slick.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="#" />
</head>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.3.11/slick.min.js"></script>

    <header class="header">
        <div class="d-sm-block d-none py-3">
            <div class="container">
                <div class="row">
                    <!-- Logo, adjust with screen size  -->
                    <div class="col-sm-8 my-2">
                        <a class="navbar-brand" href="/">
                            <img class="w-75 h-auto" src="<?php echo FRONTEND_ASSET . 'logo.png'; ?>" alt="logo">
                        </a>
                    </div>
                    <!-- Create box for search and cart buttons, aligned vertically exactly center of the col div -->
                    <div class="col-sm-4 d-flex align-items-center justify-content-end">
                        <a class="nav-link" href="/search">
                            <i class="fa fa-search"></i>
                        </a>
                        <a class="nav-link" href="/cart">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-sm-none d-block">
            <!-- collapsed navbar to the left, h1 text center, search and cart icon right -->
            <nav class="navbar sticky-top navbar-expand-sm">
                <div class="container yellow-color">
                    <div class="row mx-auto">
                        <div class="col-2 d-flex align-items">
                            <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#collapsibleHeader">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                        </div>
                        <a class="col mx-auto navbar-brand justify-content-center d-flex" href="/">
                            <img class="w-100" src="<?php echo FRONTEND_ASSET . 'logo.png'; ?>" alt="logo">
                        </a>
                        <div class="col-2 d-flex align-items-center">
                            <a class="nav-link" href="/cart">
                                <i class="fa fa-shopping-cart"></i>
                            </a>
                        </div>
                        <div class="collapse navbar-collapse" id="collapsibleHeader">
                            <ul class="navbar-nav ml-auto">
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
                                <li class="nav-item">
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
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/search">
                                        <i class="fa fa-search"></i> Search
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>