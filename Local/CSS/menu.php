<?php include('header.php'); ?>
<link href="CSS/menustyle.css" rel="stylesheet">

<div class="page-wrapper">

  <!-- Sidebar -->
  <div class="sidebar">
    <ul>
      <li><a href="#burgers">Burgers</a></li>
      <li><a href="#drinks">Drinks</a></li>
      <li><a href="#main">Main Dishes</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h1>Menu</h1>
    <p>Available all day unless stated otherwise.</p>

    <?php
    if (isset($_SESSION['IsAdmin']) && $_SESSION['IsAdmin'] == 1) {
        echo '<a href="addproducts.php" class="btn btn-primary" style="margin-bottom: 20px;">+ Add Product</a>';
    }

    include('config/connection.php');
    $categories = ['burgers' => 'Burgers', 'drinks' => 'Drinks', 'main' => 'Main Dishes'];

    foreach ($categories as $key => $label) {
        echo "<div id=\"$key\" class=\"menu-section\">";
        echo "<h2>$label</h2>";
        echo '<div class="menu-grid">';

        $sql = "SELECT * FROM menuitems WHERE category = '$key'";
        $result = $connection->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class=\"menu-item\">
                        <img src=\"{$row['image']}\" alt=\"{$row['name']}\">
                        <h3>{$row['name']}</h3>
                        <p>£" . number_format($row['price'], 2) . "</p>
                        <p>{$row['description']}</p>
                        <form method=\"POST\" action=\"add_to_basket.php\">
                            <input type=\"hidden\" name=\"item_id\" value=\"{$row['MenuItemID']}\">
                            <input type=\"hidden\" name=\"name\" value=\"{$row['name']}\">
                            <input type=\"hidden\" name=\"price\" value=\"{$row['price']}\">
                            <button type=\"submit\" class=\"btn btn-warning\">Add to Basket</button>
                        </form>
                      </div>";
            }
        } else {
            echo "<p>No products available in this category yet.</p>";
        }

        echo '</div></div>';
    }
    ?>
  </div>
</div>

<?php include('footer.php'); ?>
