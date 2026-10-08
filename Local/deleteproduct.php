<?php
session_start();
include('config/connection.php');

if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['MenuItemID'])) {
    $MenuItemID = intval($_POST['MenuItemID']);

    $sql = "DELETE FROM menuitems WHERE MenuItemID = $MenuItemID";

    if ($connection->query($sql) === TRUE) {
        $_SESSION['stockMessage'] = "Product deleted successfully!";
    } else {
        $_SESSION['stockMessage'] = "Error deleting product: " . $connection->error;
    }

    header("Location: managestock.php");
    exit();
} else {
    header("Location: managestock.php");
    exit();
}
?>
