<?php
session_start();
include 'config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['OrderID'], $_POST['status'])) {
    $orderID = $_POST['OrderID']; // ✅ Don't cast to int
    $status = $_POST['status'];

    $stmt = $connection->prepare("UPDATE orders SET status = ? WHERE OrderID = ?");
    if (!$stmt) {
        echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
        exit();
    }

    $stmt->bind_param("ss", $status, $orderID); // ✅ Use "ss" — both are strings
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    $stmt->close();
}

header("Location: adminorders.php");
exit();
