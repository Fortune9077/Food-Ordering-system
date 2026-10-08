<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menuItemId = $_POST['MenuItemID']; // Use correct spelling
    $name       = trim($_POST['name']);
    $price      = floatval($_POST['price']);
    $qty        = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }

    // Ensure ID is a string key (in case it's numeric)
    $key = (string)$menuItemId;

    // Add or update quantity
    if (isset($_SESSION['basket'][$key])) {
        $_SESSION['basket'][$key]['qty'] += $qty;
    } else {
        $_SESSION['basket'][$key] = [
            'MenuItemID' => $menuItemId,
            'name' => $name,
            'price' => $price,
            'qty' => $qty
        ];
    }

    header("Location: basket.php");
    exit();
}
?>
