<?php
require_once 'start.php';
require_once FRONTEND_INCLUDE . 'header.php';
require_once FRONTEND_INCLUDE . 'navbar.php';
require_once FRONTEND_INCLUDE . 'messages.php';
require_once BACKEND_AUTH . 'categories.php';

// Get the category from the URL path
$url = $_SERVER['REQUEST_URI'];
$parts = explode('/', $url);
$category = end($parts);

// Display products based on category
// Include frontend display logic for products
echo "Displaying products for category: $category";

require_once FRONTEND_INCLUDE . 'footer.php';
?>
