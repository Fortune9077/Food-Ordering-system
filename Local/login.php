<?php 
session_start();
if (!empty($_SESSION["email"])) {
    header("Location: ./account.php");
    exit();
}
include('header.php'); 
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="CSS/bootstrap.min.css" rel="stylesheet">
    <link href="CSS/formstyle.css" rel="stylesheet">
</head>
<body>

<style> .nav-link {
      color: white;
      font-size: 14px;
      text-transform: uppercase;
      text-decoration: none;
    }
</style>
<div class="main-content">
    <div class="login-container">
        <h2 class="text-center">Login</h2>
        
        <?php 
        if (isset($_SESSION['signinError'])) {
            echo '<p style="color: red; text-align: center;">' . $_SESSION['signinError'] . '</p>';
            unset($_SESSION['signinError']);
        }
        ?>

        <form action="./signin.php" method="post" id="signin">
            <div class="mb-4">
                <label for="email" class="form-label">Email address</label>
                <input type="text" name="email" class="form-control" id="email" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <button type="submit" class="btn btn-brown w-100">Login</button>
        </form>

        <div class="row">
            <div class="text-center">
                <a href="./forgotpassword.php">Forgot password?</a>
                <p>Haven't got an account? <a href="./register.php">Register here!</a></p>
            </div>
        </div>
    </div>
</div>

<script src="CSS/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('footer.php'); ?>
