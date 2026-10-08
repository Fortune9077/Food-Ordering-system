<?php
session_start();
include('config/connection.php');

// Redirect if not admin
if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

include('header.php');
?>

<div class="main-content">
<div class="container mt-5">
    <h2>Manage Menu</h2>

    <?php
    // Display a stock update message if set
    if (isset($_SESSION['stockMessage'])) {
        echo '<div class="alert alert-info">' . $_SESSION['stockMessage'] . '</div>';
        unset($_SESSION['stockMessage']);
    }
    ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Current Stock</th>
                <th>Update Stock</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Get all menu items ordered by name
        $sql = "SELECT * FROM menuitems ORDER BY name ASC";
        $result = $connection->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($item = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$item['name']}</td>
                    <td>{$item['stock']}</td>
                    <td>
                        <form action='updatestockprocess.php' method='POST' class='d-flex'>
                            <input type='hidden' name='MenuItemID' value='{$item['MenuItemID']}'>
                            <input type='number' name='newStock' min='0' class='form-control me-2' style='width:100px;' required>
                            <button type='submit' class='btn btn-success btn-sm'>Update</button>
                        </form>
                    </td>
                    <td>
                        <a href='editproduct.php?MenuItemID={$item['MenuItemID']}' class='btn btn-warning btn-sm'>Edit</a>
                    </td>
                    <td>
                        <form action='deleteproduct.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this product?');\">
                            <input type='hidden' name='MenuItemID' value='{$item['MenuItemID']}'>
                            <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                        </form>
                    </td>
                  </tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>No products found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</div>

<?php include('footer.php'); ?>
