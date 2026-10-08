<?php 
session_start();
include 'config/connection.php';
$errors = array();

// Check if email and password fields are empty
if(empty($_POST['email'])){
    $errors[] = "The email field must not be empty";
}
if(empty($_POST['password'])){
    $errors[] = "The password field must not be empty";
}

// If there are errors, redirect to the login page
if(!empty($errors)) {
    $_SESSION['signinError'] = "Error on the sign-in page";
    header("Location: ./login.php");
    exit();
} else {
    // Sanitize inputs
    $email = $connection->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $sql = "SELECT email, password, FirstName, Lastname, IsAdmin FROM users WHERE email = '$email'";
    $result = $connection->query($sql);
    
    // Check if user exists
    if($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['firstname'] = $row['FirstName'];
            $_SESSION['lastname'] = $row['Lastname'];
            $_SESSION['IsAdmin'] = $row['IsAdmin'];

            header("Location: ./account.php");
            exit();
        } else {
            $_SESSION['signinError'] = "You have entered incorrect user details.";
            header("Location: ./login.php");
            exit();
        }
    } else {
        // No such user
        $_SESSION['signinError'] = "You have entered incorrect user details.";
        header("Location: ./login.php");
        exit();
    }
}
?>
