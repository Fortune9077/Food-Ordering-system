<?php  
session_start();
include 'config/connection.php';
$errors = array();

// Check if fields are empty
if(empty($_POST['firstname'])){
    $errors[] = "The firstname field must not be empty";
}
if(empty($_POST['lastname'])){
    $errors[] = "The lastname field must not be empty";
}
if(empty($_POST['email'])){
    $errors[] = "The email field must not be empty";
}
if(empty($_POST['password'])){
    $errors[] = "The password field must not be empty";
}
if(empty($_POST['confirmPassword'])){
    $errors[] = "The confirm password field must not be empty";
}

// If errors exist, redirect back with error message
if(!empty($errors)) {
    $_SESSION['registerError'] = implode("<br>", $errors);
    header("Location: ./register.php");
    ;
}

// Check if the email already exists
$email = $_POST['email'];
$checkEmailQuery = "SELECT * FROM USERS WHERE email = '$email'";
$result = $connection->query($checkEmailQuery);

if ($result->num_rows > 0) {
    $_SESSION['registerError'] = "This email is already registered!";
    header("Location: ./register.php");
    ;
}

// Hash the password before storing
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Prepare SQL query to insert a new user
$sql = "INSERT INTO USERS (email, password, firstname, lastname, IsAdmin) VALUES 
('" . $_POST['email'] . "' ,
'" . $hashedPassword . "' ,
'" . $_POST['firstname'] . "',
'" .$_POST['lastname'] . "',
0 )";

// Execute the query
if ($connection->query($sql) === TRUE) {
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['firstname'] = $_POST['firstname'];
    $_SESSION['lastname'] = $_POST['lastname'];
    $_SESSION['IsAdmin'] = 0;
    header("Location: ./account.php");
    ;
} else {
    echo "Error: " . $sql . "<br>" . $connection->error;
}
?>
