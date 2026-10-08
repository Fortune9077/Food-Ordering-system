<?php
session_start();
include('config/connection.php');

// Check if admin is logged in
if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $email = $_POST['email'];           // New email
    $old_email = $_POST['old_email'];   // Current/old email in database
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['Address'];
    $postcode = $_POST['postcode'];
    $password = $_POST['password'];
    $isAdmin = $_POST['IsAdmin'];

    // Update user details (except password and email for now)
    $sql = "UPDATE users SET firstname = ?, lastname = ?, Address = ?, postcode = ?, IsAdmin = ? WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssssis", $firstname, $lastname, $address, $postcode, $isAdmin, $old_email);
    $stmt->execute();

    // If the password is provided, hash it and update it
    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $sqlPassword = "UPDATE users SET password = ? WHERE email = ?";
        $stmtPassword = $connection->prepare($sqlPassword);
        $stmtPassword->bind_param("ss", $passwordHash, $old_email);
        $stmtPassword->execute();
    }

    // If the email has changed, update it separately
    if ($email != $old_email) {
        $sqlEmail = "UPDATE users SET email = ? WHERE email = ?";
        $stmtEmail = $connection->prepare($sqlEmail);
        $stmtEmail->bind_param("ss", $email, $old_email);
        $stmtEmail->execute();
    }

    // Set success message and redirect
    $_SESSION['updateSuccess'] = "User details updated successfully.";
    header("Location: searchusers.php");
    exit();
}
?>
