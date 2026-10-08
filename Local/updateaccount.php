<?php 
session_start();
include 'config/connection.php';

$errors = array();

// Retrieve and sanitize form data
$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$address = trim($_POST['Address']);
$postcode = strtoupper(trim($_POST['postcode']));
$email = $_SESSION['email'];

// Required field validation
if (empty($firstname)) {
    $errors[] = "First name is required.";
}
if (empty($lastname)) {
    $errors[] = "Last name is required.";
}
if (empty($address)) {
    $errors[] = "Address is required.";
}
if (empty($postcode)) {
    $errors[] = "Postcode is required.";
}

// Format validation
if (!empty($postcode) && !preg_match("/^[A-Z0-9 ]{5,8}$/", $postcode)) {
    $errors[] = "Postcode format is invalid.";
}

// If errors, redirect back to account page with session message
if (!empty($errors)) {
    $_SESSION['updateError'] = implode("<br>", $errors); // Combine errors for display
    header("Location: ./account.php");
    exit();
} else {
    // Perform SQL update
    $sql = "UPDATE users SET 
                firstname = '$firstname', 
                lastname = '$lastname', 
                Address = '$address', 
                postcode = '$postcode'
            WHERE email = '$email'";
    $connection->query($sql);

    // Handle profile image upload
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $filename = $email . ".png";
        $location = "Images/" . $filename;
        move_uploaded_file($_FILES['profileImage']['tmp_name'], $location);
    }

    // Set success message and redirect
    $_SESSION['updateSuccess'] = "Your profile has been successfully updated.";
    header("Location: ./account.php");
    exit();
}
?>
