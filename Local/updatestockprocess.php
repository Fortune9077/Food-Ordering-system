<?php
session_start();
include('config/connection.php');

// Only admin can access
if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

// Validate input
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['MenuItemID']) && isset($_POST['newStock'])) {
    $MenuItemID = intval($_POST['MenuItemID']);
    $newStock = intval($_POST['newStock']);

    if ($newStock < 0) {
        $_SESSION['stockMessage'] = "Stock cannot be negative.";
        header("Location: managestock.php");
        exit();
    }

    $sql = "UPDATE menuitems SET stock = $newStock WHERE MenuItemID = $MenuItemID";

    if ($connection->query($sql) === TRUE) {
        $_SESSION['stockMessage'] = "Stock updated successfully.";
    } else {
        $_SESSION['stockMessage'] = "Failed to update stock: " . $connection->error;
    }

    header("Location: managestock.php");
    exit();
} else {
    $_SESSION['stockMessage'] = "Invalid request.";
    header("Location: managestock.php");
    exit();
}
?>
