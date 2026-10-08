<?php
session_start();
include('config/connection.php');

if (empty($_SESSION['just_ordered'])) {
    header("Location: menu.php");
    exit();
}
$orderID = $_SESSION['just_ordered'];
unset($_SESSION['just_ordered']);

include('header.php');
?>
<div class="container mt-5" style="max-width:600px; text-align:center;">
  <h2>Thank You!</h2>
  <p>Your order <strong><?= htmlspecialchars($orderID) ?></strong> has been placed successfully.</p>
  <p>A confirmation has been sent to <strong><?= htmlspecialchars($_SESSION['email']) ?></strong>.</p>
  <a href="menu.php" class="btn btn-primary mt-3">Back to Menu</a>
</div>
<?php include('footer.php'); ?>
