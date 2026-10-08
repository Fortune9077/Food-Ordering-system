<?php
session_start();
include('config/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['email'])) {
        $_SESSION['updateError'] = "You must be logged in to change your password.";
        header("Location: login.php");
        exit();
    }

    $email = mysqli_real_escape_string($connection, $_SESSION['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Make sure all fields are filled
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['updateError'] = "Please fill in all fields.";
        header("Location: account.php");
        exit();
    }

    // Step 1: Fetch the current hashed password from the database
    $sql = "SELECT password FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $sql);

    if (!$result || mysqli_num_rows($result) !== 1) {
        $_SESSION['updateError'] = "User not found or database error.";
        header("Location: account.php");
        exit();
    }

    $row = mysqli_fetch_assoc($result);
    $stored_password = $row['password'];

    // Step 2: Check if current password is correct using password_verify
    if (!password_verify($current_password, $stored_password)) {
        $_SESSION['updateError'] = "Current password is incorrect.";
        header("Location: account.php");
        exit();
    }

    // Step 3: Check if new passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['updateError'] = "New passwords do not match.";
        header("Location: account.php");
        exit();
    }

    // Step 4: Hash the new password and update in the database
    $hashedNewPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $update_sql = "UPDATE users SET password = '$hashedNewPassword' WHERE email = '$email'";
    $update_result = mysqli_query($connection, $update_sql);

    if ($update_result) {
        $_SESSION['updateSuccess'] = "Password updated successfully.";
    } else {
        $_SESSION['updateError'] = "Failed to update password.";
    }

    header("Location: account.php");
    exit();
}
?>
