<?php
session_start();
include('config/connection.php');
include('header.php');

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

?>
<div class="main-content">
<div class="container mt-5">
    <h2>Search Results for "<?= htmlspecialchars($query) ?>"</h2>

    <?php
    if ($query !== '') {
        $safeQuery = $connection->real_escape_string($query);
        $sql = "SELECT * FROM menuitems 
                WHERE name LIKE '%$safeQuery%' OR 
                      description LIKE '%$safeQuery%' 
                ORDER BY category";
        $result = $connection->query($sql);

        if ($result && $result->num_rows > 0) {
            echo '<div class="menu-grid">';
            while ($row = $result->fetch_assoc()) {
                echo "<div class=\"menu-item\">
                        <img src=\"{$row['image']}\" alt=\"{$row['name']}\">
                        <h3>{$row['name']}</h3>
                        <p>£" . number_format($row['price'], 2) . "</p>
                        <p>{$row['description']}</p>";

                if ($row['stock'] > 0) {
                    echo "<form action=\"add_to_basket.php\" method=\"POST\">
                            <input type=\"hidden\" name=\"MenuItemID\" value=\"{$row['MenuItemID']}\">
                            <input type=\"hidden\" name=\"name\" value=\"{$row['name']}\">
                            <input type=\"hidden\" name=\"price\" value=\"{$row['price']}\">
                            <button type=\"submit\" class=\"btn btn-warning\">Add to Basket</button>
                          </form>";
                } else {
                    echo "<button class=\"btn btn-secondary\" disabled>Out of Stock</button>";
                }

                echo "</div>";
            }
            echo '</div>';
        } else {
            echo "<p>No results found for \"$query\".</p>";
        }
    } else {
        echo "<p>Please enter a search term.</p>";
    }
    ?>
</div>
</div>
<?php include('footer.php'); ?>
