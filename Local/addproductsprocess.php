<?php
session_start();
include 'config/connection.php';

if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

$name = trim($_POST['name']);
$price = floatval($_POST['price']);
$stock = intval($_POST['stock']);
$category = trim($_POST['category']);
$description = trim($_POST['description']);

$errors = [];

if (empty($name)) $errors[] = "Product name is required.";
if ($price <= 0) $errors[] = "Price must be a positive number.";
if ($stock < 0) $errors[] = "Stock must be 0 or more.";
if (empty($category)) $errors[] = "Category is required.";
if (empty($description)) $errors[] = "Description is required.";

if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
    $errors[] = "Product image is required.";
}

if (!empty($errors)) {
    $_SESSION['productError'] = implode("<br>", $errors);
    header("Location: addproducts.php");
    exit();
}

$image_name = uniqid() . "_" . basename($_FILES['image']['name']);
$image_path = "Images/" . $image_name;
move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

$sql = "INSERT INTO menuitems (name, price, stock, category, description, image) 
        VALUES ('$name', '$price', '$stock', '$category', '$description', '$image_path')";

if ($connection->query($sql) === TRUE) {
    $_SESSION['productSuccess'] = "Product added successfully!";
    header("Location: menu.php");
} else {
    $_SESSION['productError'] = "Failed to add product: " . $connection->error;
    header("Location: addproducts.php");
}
exit();
