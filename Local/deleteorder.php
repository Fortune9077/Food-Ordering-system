<?php
session_start();
include('config/connection.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderid'], $_POST['orderdate'])) {
    $orderId = $connection->real_escape_string($_POST['orderid']); // don't cast to int
    $orderDate = $connection->real_escape_string($_POST['orderdate']);

    $isAdmin = isset($_SESSION['IsAdmin']) && $_SESSION['IsAdmin'] == 1;
    $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : '';

    // Check: user owns the order
    if (!$isAdmin) {
        $checkSql = "SELECT * FROM orders 
                     WHERE OrderID = '$orderId' 
                       AND OrderDate = '$orderDate' 
                       AND email = '$userEmail'";
        $checkResult = $connection->query($checkSql);
        if (!$checkResult || $checkResult->num_rows === 0) {
            $_SESSION['updateError'] = "You are not authorized to delete this order.";
            header("Location: orderhistory.php");
            exit();
        }
    }

    // Delete associated order details
    $deleteDetailsSql = "DELETE FROM orderdetails WHERE OrderID = '$orderId'";
    $connection->query($deleteDetailsSql);

    // Delete the order
    $deleteOrderSql = "DELETE FROM orders WHERE OrderID = '$orderId' AND OrderDate = '$orderDate'";
    if ($connection->query($deleteOrderSql)) {
        $_SESSION['updateSuccess'] = "Order #$orderId deleted successfully.";
    } else {
        $_SESSION['updateError'] = "Failed to delete order.";
    }

    header("Location: " . ($isAdmin ? 'adminorders.php' : 'orderhistory.php'));
    exit();
} else {
    $_SESSION['updateError'] = "Invalid request.";
    header("Location: " . ((isset($_SESSION['IsAdmin']) && $_SESSION['IsAdmin'] == 1) ? 'adminorders.php' : 'orderhistory.php'));
    exit();
}
?>
