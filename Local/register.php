
<?php 
session_start();
include('header.php'); ?>


<html lang="en">
<head>
	
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="CSS/bootstrap.min.css" rel="stylesheet">
   <link href="CSS/formstyle.css" rel="stylesheet">
	 <style>
	 body {
    background-color:rgb(245, 245, 220) ;
}
		 .nav-link {
      color: white;
      font-size: 14px;
      text-transform: uppercase;
      text-decoration: none;
    }
		  </style>
</head>
<body>
	
	
	
	    <div class="main-content">


   <!-- Registration Form -->
    <div class="container" >
		<p><span style= "color: red; text-align: center" ><?php 
			if(!empty($_SESSION ["registerError"])){
			echo $_SESSION['registerError']; }?></span></p>
		
        <h2 class="text-center">Register</h2>
        <form action="./signup.php" method="post" id="registerForm">
            <div class="mb-4">
                <label for="Firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" required>
            </div>
            <div class="mb-4">
                <label for="Lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="LastnameInput" name="lastname" required>
            </div>
			<div class="mb-4">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-4">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary btn-clock mb-4">Register</button>
            <p class="text-danger mt-2" id="errorMessage"></p>

        </form>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="./login.php">Login here!</a></p>
        </div>
    </div>
</div>

  

<script src="CSS/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- end login form-->





























<?php include('footer.php'); ?>

</body>
