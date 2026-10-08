<?php
session_start();
include('config/connection.php');

if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}
?>
<?php include('header.php'); ?>
<div class="container mt-5">
    <h2>Add New Product</h2>

    <?php
    if (!empty($_SESSION['productError'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['productError'] . '</div>';
        unset($_SESSION['productError']);
    }
    if (!empty($_SESSION['productSuccess'])) {
        echo '<div class="alert alert-success">' . $_SESSION['productSuccess'] . '</div>';
        unset($_SESSION['productSuccess']);
    }
    ?>

    <form action="addproductsprocess.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Description:</label>
            <textarea name="description" required class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Price (£):</label>
            <input type="number" name="price" step="0.01" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Stock:</label>
            <input type="number" name="stock" min="0" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Category:</label>
            <select name="category" class="form-control" required>
                <option value="burgers">Burgers</option>
                <option value="drinks">Drinks</option>
                <option value="main">Main Dishes</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Product Image:</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <input type="submit" value="Add Product" class="btn btn-primary">
    </form>
</div>
<?php include('footer.php'); ?>
