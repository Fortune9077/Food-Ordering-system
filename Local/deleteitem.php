<?php
session_start();
include 'config/connection.php'; // DB connection

// Restrict access to admins only
if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

// If delete request received via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteID = intval($_POST['delete_id']);

    // Attempt to delete image file from server
    $imgQuery = "SELECT image FROM menuitems WHERE MenuItemID = $deleteID";
    $imgResult = $connection->query($imgQuery);
    if ($imgResult && $imgResult->num_rows > 0) {
        $imgRow = $imgResult->fetch_assoc();
        $imagePath = $imgRow['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Remove image
        }
    }

    // Delete product from database
    $sql = "DELETE FROM menuitems WHERE MenuItemID = $deleteID";
    if ($connection->query($sql)) {
        $_SESSION['productSuccess'] = "Product deleted successfully.";
    } else {
        $_SESSION['productError'] = "Failed to delete product: " . $connection->error;
    }

    header("Location: deleteitem.php");
    exit();
}
?>

<?php include('header.php'); ?>
<style> body { background-color: rgb(245, 245, 220); } </style>

<div class="container mt-5">
    <h2>Delete Menu Items</h2>

    <!-- Show success or error messages -->
    <?php
    if (!empty($_SESSION['productError'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['productError'] . '</div>';
        unset($_SESSION['productError']);
    }
    if (!empty($_SESSION['productSuccess'])) {
        echo '<div class="alert alert-success">' . $_SESSION['productSuccess'] . '</div>';
        unset($_SESSION['productSuccess']);
    }

    // Get all menu items from DB
    $result = $connection->query("SELECT * FROM menuitems");
    if ($result->num_rows > 0):
    ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price (£)</th>
                    <th>Category</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($row['image']) ?>" width="80" alt="Product Image"></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td>
                            <!-- Form to delete item -->
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                <input type="hidden" name="delete_id" value="<?= $row['MenuItemID'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>
