<?php
session_start();
include('config/connection.php');

if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['MenuItemID'])) {
    $_SESSION['stockMessage'] = "No item selected for editing.";
    header("Location: managestock.php");
    exit();
}

$MenuItemID = $_GET['MenuItemID'];

$sql = "SELECT * FROM menuitems WHERE MenuItemID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $MenuItemID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    $_SESSION['stockMessage'] = "Item not found.";
    header("Location: managestock.php");
    exit();
}

$item = $result->fetch_assoc();

include('header.php');
?>

<div class="container mt-5">
    <h2>Edit Product</h2>
    <form action="editproductprocess.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="MenuItemID" value="<?php echo $item['MenuItemID']; ?>">

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($item['name']); ?>">
        </div>

        <div class="mb-3">
            <label>Description:</label>
            <textarea name="description" class="form-control" required><?php echo htmlspecialchars($item['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label>Price (£):</label>
            <input type="number" step="0.01" name="price" class="form-control" required value="<?php echo $item['price']; ?>">
        </div>

        <div class="mb-3">
    <label>Category:</label>
    <select name="category" class="form-control" required>
        <option value="Burgers" <?php if ($item['category'] == 'Burgers') echo 'selected'; ?>>Burgers</option>
        <option value="Drinks" <?php if ($item['category'] == 'Drinks') echo 'selected'; ?>>Drinks</option>
        <option value="Main Dishes" <?php if ($item['category'] == 'Main Dishes') echo 'selected'; ?>>Main Dishes</option>
    </select>
</div>


        <div class="mb-3">
            <label>Stock:</label>
            <input type="number" name="stock" class="form-control" required value="<?php echo $item['stock']; ?>">
        </div>

        <div class="mb-3">
            <label>Current Image:</label><br>
            <img src="<?php echo $item['image']; ?>" alt="Current Image" width="120">
        </div>

        <div class="mb-3">
            <label>Upload New Image (optional):</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="managestock.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include('footer.php'); ?>
