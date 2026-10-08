<?php 
session_start();
include('header.php'); 
?>
<link href="CSS/menustyle.css" rel="stylesheet">

<!-- ✅ JavaScript: Handles "Add to Basket" action using AJAX -->
<script>
function addToBasket(form) {
    const formData = new FormData(form);
    fetch('add_to_basket.php', {
        method: 'POST',
        body: formData
    }).then(response => response.text())
    .then(() => {
        //  update basket count in header
        fetch('basket_count.php')
            .then(res => res.text())
            .then(count => {
                const basketCount = document.querySelector('#basket-count');
                if (basketCount) basketCount.innerText = count;
            });
    });
    return false; // Prevent default form submission
}
</script>

<div class="page-wrapper">
  <!-- Sidebar navigation to categories -->
  <div class="sidebar">
    <ul>
      <li><a href="#burgers">Burgers</a></li>
      <li><a href="#drinks">Drinks</a></li>
      <li><a href="#main">Main Dishes</a></li>
    </ul>
  </div>

  <div class="content">
    <h1>Menu</h1>
    <p>Available all day unless stated otherwise.</p>

    <!-- Show "Add Product" button to admin users only -->
    <?php
    if (isset($_SESSION['IsAdmin']) && $_SESSION['IsAdmin'] == 1) {
    echo '<div class="mb-3">';
    echo '<a href="addproducts.php" class="btn btn-primary me-2">+ Add Product</a>';
    echo '<a href="deleteitem.php" class="btn btn-danger"> Delete Items</a>';
    echo '</div>';
}

    include('config/connection.php');
    
    // Categories and their display labels
    $categories = ['burgers' => 'Burgers', 'drinks' => 'Drinks', 'main' => 'Main Dishes'];

    foreach ($categories as $key => $label) {
        echo "<div id=\"$key\" class=\"menu-section\">";
        echo "<h2>$label</h2>";
        echo '<div class="menu-grid">';

        // Fetch all menu items by category
        $sql = "SELECT * FROM menuitems WHERE category = '$key'";
        $result = $connection->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class=\"menu-item\">
                        <img src=\"{$row['image']}\" alt=\"{$row['name']}\">
                        <h3>" . htmlspecialchars($row['name']) . "</h3>
                        <p>£" . number_format($row['price'], 2) . "</p>
                        <p>" . htmlspecialchars($row['description']) . "</p>";

                // Show add to basket button if in stock
                if ($row['stock'] > 0) {
                    echo "<form onsubmit=\"return addToBasket(this)\" method=\"POST\">
                            <input type=\"hidden\" name=\"MenuItemID\" value=\"{$row['MenuItemID']}\">
                            <input type=\"hidden\" name=\"name\" value=\"" . htmlspecialchars($row['name']) . "\">
                            <input type=\"hidden\" name=\"price\" value=\"{$row['price']}\">
                            <button type=\"submit\" class=\"btn btn-warning\">Add to Basket</button>
                          </form>";
                } else {
                    echo "<button class=\"btn btn-secondary\" disabled>Out of Stock</button>";
                }

                //  Show stock level at the bottom of each box
                echo "<p class=\"stock-info\" style=\"margin-top:10px; font-weight: bold;\">Stock: {$row['stock']}</p>";

                echo "</div>"; // close .menu-item
            }
        } else {
            echo "<p>No products available in this category yet.</p>";
        }

        echo '</div></div>'; // close .menu-grid and category section
    }
	  
    ?>
  </div>
</div>

<?php include('footer.php'); ?>


