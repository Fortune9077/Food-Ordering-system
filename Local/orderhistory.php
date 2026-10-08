<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include('header.php');
include('config/connection.php');

// Get the user's email
$email = $_SESSION['email'];
?>
<div class="main-content">
<div class="container mt-5">
    <h2>Your Order History</h2>

    <?php
    // Query to get orders and their items
$sql = "SELECT o.OrderID, o.OrderDate, o.status, 
               d.name, d.qty, d.price, d.Subtotal
        FROM orders o
        JOIN orderdetails d ON o.OrderID = d.OrderID
        WHERE o.email = '$email'
        ORDER BY o.OrderDate DESC, o.OrderID DESC";



    $result = $connection->query($sql);

    if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['OrderID']) ?></td>
                    
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['qty'] ?></td>
                    <td>£<?= number_format($row['price'], 2) ?></td>
                    <td>£<?= number_format($row['Subtotal'], 2) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= $row['OrderDate'] ?></td>
                    <td>
                        <!-- Button to delete this order -->
                        <form action="deleteorder.php" method="POST">
                            <input type="hidden" name="orderid" value="<?= $row['OrderID'] ?>">
                            <input type="hidden" name="orderdate" value="<?= $row['OrderDate'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no previous orders.</p>
    <?php endif; ?>
</div>
</div>

<?php include('footer.php'); ?>
