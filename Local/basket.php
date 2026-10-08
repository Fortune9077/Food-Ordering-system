<?php
session_start();

// Handle quantity adjustments when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['MenuItemID'], $_POST['action'])) {
    $MenuItemID = $_POST['MenuItemID'];
    
    // Make sure the item exists in the basket before modifying
    if (isset($_SESSION['basket'][$MenuItemID])) {
        if ($_POST['action'] === 'increase') {
            // Increase quantity
            $_SESSION['basket'][$MenuItemID]['qty']++;
        } elseif ($_POST['action'] === 'decrease') {
            // Decrease quantity, but remove item if it reaches 0
            if ($_SESSION['basket'][$MenuItemID]['qty'] > 1) {
                $_SESSION['basket'][$MenuItemID]['qty']--;
            } else {
                unset($_SESSION['basket'][$MenuItemID]);
            }
        } elseif ($_POST['action'] === 'remove') {
            // Remove item from basket
            unset($_SESSION['basket'][$MenuItemID]);
        }
    }
}

// Ensure $basket is always defined (avoid undefined variable warning)
$basket = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];

include('header.php');
?>

<h2 style="padding: 20px 0;">Your Basket</h2>

<style>
.basket-container {
    max-width: 700px;
    margin: 0 auto;
}
.basket-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.qty-btn {
    background-color: #333;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}
.qty-btn:hover {
    background-color: #555;
}
.remove-btn {
    background-color: crimson;
    margin-left: 10px;
}
.total {
    font-weight: bold;
    font-size: 18px;
    text-align: right;
    margin-top: 10px;
}
.checkout-link {
    display: block;
    text-align: center;
    background-color: #228B22;
    color: white;
    padding: 12px;
    border-radius: 5px;
    font-weight: bold;
    text-decoration: none;
    margin-top: 20px;
}
</style>

<div class="basket-container">
<?php if (empty($basket)): ?>
    <!-- Show message if basket is empty -->
    <p>No items yet.</p>
<?php else: ?>
    <?php $total = 0; ?>
    <?php foreach ($basket as $MenuItemID => $item): ?>
        <!-- Display each item in basket -->
        <div class="basket-item">
            <div>
                <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                <small>£<?= number_format($item['price'], 2) ?> x <?= $item['qty'] ?> = £<?= number_format($item['price'] * $item['qty'], 2) ?></small>
            </div>
            <form method="POST" action="basket.php" style="display: flex; align-items: center; gap: 5px;">
                <input type="hidden" name="MenuItemID" value="<?= $MenuItemID ?>">
                <button type="submit" name="action" value="decrease" class="qty-btn">−</button>
                <span><?= $item['qty'] ?></span>
                <button type="submit" name="action" value="increase" class="qty-btn">+</button>
                <button type="submit" name="action" value="remove" class="qty-btn remove-btn">Remove</button>
            </form>
        </div>
        <?php $total += $item['price'] * $item['qty']; ?>
    <?php endforeach; ?>

    <!-- Display total amount -->
    <p class="total">Total: £<?= number_format($total, 2) ?></p>

    <!-- Checkout button -->
    <a href="checkout.php" class="checkout-link">Checkout</a>
<?php endif; ?>
</div>

<?php include('footer.php'); ?>
