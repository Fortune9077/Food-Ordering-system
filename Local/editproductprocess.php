<?php
session_start();
include('config/connection.php');

if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['MenuItemID'])) {
    $id = $_POST['MenuItemID'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];

    // Default to current image
    $sql = "SELECT image FROM menuitems WHERE MenuItemID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentImage = $result->fetch_assoc()['image'];

    $imagePath = $currentImage;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . time() . "_" . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imagePath = $targetFile;
            } else {
                $_SESSION['stockMessage'] = "Failed to upload image.";
                header("Location: managestock.php");
                exit();
            }
        } else {
            $_SESSION['stockMessage'] = "Only JPG, JPEG, PNG & GIF files are allowed.";
            header("Location: managestock.php");
            exit();
        }
    }

    $updateSql = "UPDATE menuitems SET name=?, description=?, price=?, category=?, stock=?, image=? WHERE MenuItemID=?";
    $stmt = $connection->prepare($updateSql);
    $stmt->bind_param("ssdsisi", $name, $description, $price, $category, $stock, $imagePath, $id);

    if ($stmt->execute()) {
        $_SESSION['stockMessage'] = "Product updated successfully.";
    } else {
        $_SESSION['stockMessage'] = "Error updating product.";
    }
} else {
    $_SESSION['stockMessage'] = "Invalid form submission.";
}

header("Location: managestock.php");
exit();
