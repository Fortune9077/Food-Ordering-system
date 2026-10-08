<?php
session_start();
date_default_timezone_set('Europe/London');
include('config/connection.php');

if (empty($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
if (empty($_SESSION['basket'])) {
    header("Location: basket.php");
    exit();
}

$error = '';
$cardNumber = '';
$expiry = '';
$cvv = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardNumber = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
    $expiry     = isset($_POST['expiry']) ? trim($_POST['expiry']) : '';
    $cvv        = isset($_POST['cvv']) ? trim($_POST['cvv']) : '';
    $errors = array();

    // Card number validation
    if (!preg_match('/^\d{16}$/', $cardNumber)) {
        $errors[] = "Card number must be 16 digits.";
    }

    // Expiry date validation
    if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
        $errors[] = "Expiry must be in MM/YY format.";
    } else {
        list($expMonth, $expYear) = explode('/', $expiry);
        $expYearFull = 2000 + (int)$expYear;
        $expDate = DateTime::createFromFormat('Y-m', "$expYearFull-$expMonth");
        $expDate->modify('last day of this month');
        $today = new DateTime('now');
        if ($expDate <= $today) {
            $errors[] = "Expiry date must be in the future.";
        }
    }

    // CVV validation
    if (!preg_match('/^\d{3}$/', $cvv)) {
        $errors[] = "CVV must be 3 digits.";
    }

    if (!empty($errors)) {
        $error = implode("<br>", $errors);
    } else {
        $orderID   = uniqid('ORD');
        $email     = $_SESSION['email'];
        $orderDate = date("Y-m-d H:i:s");
        $status    = 'Preparing';

        // Get user's full name
        $userQuery = $connection->prepare("SELECT FirstName, LastName FROM users WHERE email = ?");
        $userQuery->bind_param("s", $email);
        $userQuery->execute();
        $userResult = $userQuery->get_result();
        $name = '';
        if ($userResult->num_rows === 1) {
            $userRow = $userResult->fetch_assoc();
            $name = $userRow['FirstName'] . ' ' . $userRow['LastName'];
        }

        // Calculate total
        $totalAmount = 0;
        // Debug output (comment out in production)
        // echo "<pre>"; print_r($_SESSION['basket']); echo "</pre>";

        foreach ($_SESSION['basket'] as $item) {
            if (isset($item['price'], $item['qty'])) {
                $totalAmount += $item['price'] * $item['qty'];
            }
        }

        // Insert into orders
        $stmt = $connection->prepare("INSERT INTO orders (OrderID, name, email, OrderDate, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $orderID, $name, $email, $orderDate, $status);
        if (!$stmt->execute()) {
            die("Order insert failed: " . $stmt->error);
        }

        // Insert into orderdetails
        foreach ($_SESSION['basket'] as $item) {
            if (!isset($item['MenuItemID'], $item['qty'], $item['price'])) {
                error_log("Skipping item with missing MenuItemID/qty/price: " . print_r($item, true));
                continue;
            }

            $detailID   = uniqid('DET');
            $menuItemID = $item['MenuItemID'];
            $qty        = $item['qty'];
            $price      = $item['price'];
            $subtotal   = $price * $qty;

            // Get the actual item name from the menuitems table
            $itemName = '';
            $itemStmt = $connection->prepare("SELECT name FROM menuitems WHERE MenuItemID = ?");
            $itemStmt->bind_param("s", $menuItemID);
            $itemStmt->execute();
            $itemResult = $itemStmt->get_result();
            if ($itemResult->num_rows === 1) {
                $itemRow = $itemResult->fetch_assoc();
                $itemName = $itemRow['name'];
            }

            $stmt2 = $connection->prepare("INSERT INTO orderdetails (OrderDetailID, OrderID, MenuItemID, name, qty, price, Subtotal) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt2) {
                error_log("Prepare failed for orderdetails: " . $connection->error);
                continue;
            }

            $stmt2->bind_param("ssissdd", $detailID, $orderID, $menuItemID, $itemName, $qty, $price, $subtotal);
            if (!$stmt2->execute()) {
                error_log("Order detail insert failed: " . $stmt2->error);
                continue;
            }
        }

        unset($_SESSION['basket']);
        $_SESSION['just_ordered'] = $orderID;
        header("Location: order_confirmation.php");
        exit();
    }
}

include('header.php');
?>

<div class="container mt-5" style="max-width:600px;">
  <h2>Payment Details</h2>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <label for="card_number" class="form-label">Card Number</label>
      <input type="text" id="card_number" name="card_number" class="form-control"
             value="<?= htmlspecialchars($cardNumber) ?>" maxlength="16" required>
    </div>
    <div class="mb-3">
      <label for="expiry" class="form-label">Expiry (MM/YY)</label>
      <input type="text" id="expiry" name="expiry" class="form-control"
             value="<?= htmlspecialchars($expiry) ?>" maxlength="5" required>
    </div>
    <div class="mb-3">
      <label for="cvv" class="form-label">CVV</label>
      <input type="text" id="cvv" name="cvv" class="form-control"
             value="<?= htmlspecialchars($cvv) ?>" maxlength="3" required>
    </div>
    <button type="submit" class="btn btn-success btn-lg">Confirm Payment</button>
  </form>
</div>

<?php include('footer.php'); ?>
