<?php
session_start();
include('config/connection.php');

// Restrict access to admins only
if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

// Handle order deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_id'])) {
    $clearID = $connection->real_escape_string($_POST['clear_id']);
    $connection->query("DELETE FROM orderitems WHERE OrderID = '$clearID'");
    $connection->query("DELETE FROM orders WHERE OrderID = '$clearID'");
    header("Location: adminorders.php");
    exit();
}

include('header.php');
?>

<div class="main-content">
<div class="container mt-5">
    <h2>All Orders</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Email</th>
                <th>Date</th>
                <th>Status</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
           $sql = "
    SELECT o.OrderID, o.email, o.OrderDate, o.status,
           d.name AS item_name, d.qty, d.Subtotal
    FROM orders o
    JOIN orderdetails d ON o.OrderID = d.OrderID
    ORDER BY o.OrderDate DESC, o.OrderID DESC
";

            $result = $connection->query($sql);
            $lastOrderId = null;

            while ($row = $result->fetch_assoc()):
                $isNewOrder = $row['OrderID'] !== $lastOrderId;
                $lastOrderId = $row['OrderID'];
            ?>
                <tr>
                    <td><?= $isNewOrder ? htmlspecialchars($row['OrderID']) : '' ?></td>
                    <td><?= $isNewOrder ? htmlspecialchars($row['email']) : '' ?></td>
                    <td><?= $isNewOrder ? htmlspecialchars($row['OrderDate']) : '' ?></td>
                    <td>
                        <?php if ($isNewOrder): ?>
                        <form method="POST" action="updatestatus.php" class="d-flex">
                            <input type="hidden" name="OrderID" value="<?= $row['OrderID'] ?>">
                            <select name="status" class="form-select form-select-sm me-2">
                                <option value="Preparing" <?= $row['status'] === 'Preparing' ? 'selected' : '' ?>>Preparing</option>
                                <option value="Out for Delivery" <?= $row['status'] === 'Out for Delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                    <td><?= $row['qty'] ?></td>
                    <td>£<?= number_format($row['Subtotal'], 2) ?></td>
                    <td>
                        <?php if ($isNewOrder): ?>
                        <form method="POST" onsubmit="return confirm('Delete this order?');">
                            <input type="hidden" name="clear_id" value="<?= $row['OrderID'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Clear</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>

<?php include('footer.php'); ?>
