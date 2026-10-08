<?php
session_start();
include('config/connection.php');

// Only allow admins
if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

// Check if order ID is provided via GET
if (!isset($_GET['id'])) {
    header("Location: adminorders.php");
    exit();
}

$orderID = intval($_GET['id']); // Corrected to 'id' to match ?id= in URL

include('header.php');

// Prepare and fetch customer info (optional)
$customerName = "Unknown";
$customerEmail = "Unknown";
$orderTotal = 0;

$infoStmt = $connection->prepare("SELECT name, email FROM orders WHERE OrderID = ?");
$infoStmt->bind_param("i", $orderID);
$infoStmt->execute();
$infoResult = $infoStmt->get_result();

if ($infoResult->num_rows > 0) {
    $orderInfo = $infoResult->fetch_assoc();
    $customerName = $orderInfo['name'];
    $customerEmail = $orderInfo['email'];
}
$infoStmt->close();
?>

<div class="container mt-5">
    <h2>Order #<?= $orderID ?></h2>
    <p><strong>Customer:</strong> <?= htmlspecialchars($customerName) ?> (<?= htmlspecialchars($customerEmail) ?>)</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Price (£)</th>
                <th>Quantity</th>
                <th>Subtotal (£)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Secure query using prepared statements
            $stmt = $connection->prepare("SELECT name, price, qty, subtotal FROM orderitems WHERE OrderID = ?");
            $stmt->bind_param("i", $orderID);
            $stmt->execute();
            $result = $stmt->get_result();
            $total = 0;

            if ($result->num_rows > 0) {
                while ($item = $result->fetch_assoc()) {
                    $subtotal = $item['subtotal'];
                    $total += $subtotal;

                    echo "<tr>
                            <td>" . htmlspecialchars($item['name']) . "</td>
                            <td>£" . number_format($item['price'], 2) . "</td>
                            <td>" . intval($item['qty']) . "</td>
                            <td>£" . number_format($subtotal, 2) . "</td>
                          </tr>";
                }

                echo "<tr>
                        <td colspan='3' style='text-align:right; font-weight:bold;'>Total:</td>
                        <td>£" . number_format($total, 2) . "</td>
                      </tr>";
            } else {
                echo "<tr><td colspan='4'>No items found for this order.</td></tr>";
            }

            $stmt->close();
            ?>
        </tbody>
    </table>

    <a href="adminorders.php" class="btn btn-secondary">← Back to Orders</a>
</div>

<?php include('footer.php'); ?>
