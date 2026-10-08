<?php
session_start();
include('config/connection.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Get address from DB
$sql = "SELECT Address, postcode FROM users WHERE email = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && isset($row['Address'], $row['postcode'])) {
    $address = $row['Address'];
    $postcode = $row['postcode'];
} else {
    $address = 'No address on account.';
    $postcode = '';
}
// Calculate total
$total = 0;
if (!empty($_SESSION['basket'])) {
    foreach ($_SESSION['basket'] as $item) {
        $total += $item['price'] * $item['qty'];
    }
}

include('header.php');
?>

<div class="container mt-5" style="max-width: 800px;">
    <h2 class="mb-4">Checkout</h2>
    
    <div class="row">
        <!-- Address Card -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-dark text-white">Delivery Address</div>
                <div class="card-body">
    <p style="margin: 0;">
        <?= htmlspecialchars($address) ?><br>
        <?= htmlspecialchars($postcode) ?>
    </p>
</div>

            </div>
        </div>

        <!-- Basket Overview -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-dark text-white">Items in Your Basket</div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['basket'])): ?>
                        <ul class="list-group">
                            <?php foreach ($_SESSION['basket'] as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <?= htmlspecialchars($item['name']) ?>
                                        (x<?= $item['qty'] ?>)
                                    </span>
                                    <span>£<?= number_format($item['price'] * $item['qty'], 2) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="mt-3 text-end" style="font-weight: bold;">Total: £<?= number_format($total, 2) ?></p>
                    <?php else: ?>
                        <p>Your basket is empty.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Place Order Button -->
    <?php if (!empty($_SESSION['basket'])): ?>
        <form action="placeorder.php" method="POST" class="text-center mt-3">
            <button type="submit" class="btn btn-success btn-lg px-5">
                Place Order
            </button>
        </form>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>
