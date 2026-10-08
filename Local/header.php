<?php
require("./config/connection.php");

$basketCount = 0;
if (isset($_SESSION['basket'])) {
  foreach ($_SESSION['basket'] as $item) {
    $basketCount += $item['qty'];
  }
}
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mr Gomery's</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Fopefoluwa Akanni">
  <meta name="keywords" content="Burger, Fastfood, Tasty">
  
  <!-- Stylesheets -->
  <link href="CSS/bootstrap.min.css" rel="stylesheet">
  <link href="CSS/bootstrap-icons.min.css" rel="stylesheet">
  <link href="CSS/styles.css" rel="stylesheet">

  <style>
    body {
      background-color: rgb(245, 245, 220); /* Cream */
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    .navbar {
      background-color: #333; /* Dark background */
      color: white;
      padding: 10px 20px;
    }

    .navbar-brand img {
      max-height: 50px;
    }

    .navbar-nav {
      display: flex;
      gap: 15px;
      margin-left: 20px;
    }

    .nav-link {
      color: white;
      font-size: 14px;
      text-transform: uppercase;
      text-decoration: none;
    }

    .nav-link:hover {
      color: #ccc;
    }

    .order-now-btn {
      background-color: white;
      color: black;
      border: none;
      padding: 5px 15px;
      border-radius: 5px;
      font-size: 14px;
      margin-left: auto;
      margin-right: 15px;
    }

    .order-now-btn:hover {
      background-color: #ccc;
    }

    .navbar-icons i {
      color: white;
      font-size: 20px;
      margin-left: 15px;
    }

    .navbar-icons i:hover {
      color: #ccc;
    }

    .account-link {
      text-decoration: none;
    }
  </style>
</head>

<body>
  <nav class="navbar d-flex align-items-center">
    <a class="navbar-brand" href="index.php">
      <img src="Images/logo-removebg-preview.png" alt="Mr. Gomery's Logo">
    </a>

    <ul class="navbar-nav flex-row align-items-center">
      <li class="nav-item">
        <a class="nav-link" href="menu.php">Menu</a>
      </li>
    </ul>

    <!-- Search form aligned to the right -->
    <form action="search.php" method="GET" class="d-flex" role="search" style="margin-left:auto; margin-top:15px;">
      <input class="form-control me-2" type="search" name="query" placeholder="Search menu..." aria-label="Search" required>
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>

    <a href="order.php" class="order-now-btn">Order Now</a>

    <div class="navbar-icons d-flex align-items-center">
      <a href="basket.php" style="color: white; text-decoration: none;">
        <i class="bi bi-bag"></i>
        (<span id="basket-count"><?= $basketCount ?></span>)
      </a>
      <a href="login.php" class="account-link">
        <i class="bi bi-person-circle"></i>
      </a>
    </div>
  </nav>

  <script>
    // Update basket count dynamically
    fetch('basket_count.php')
      .then(res => res.text())
      .then(count => {
        const basketCount = document.getElementById('basket-count');
        if (basketCount) {
          basketCount.innerText = count;
        }
      });
  </script>
