
<?php include('header.php'); ?>


<html lang="en">
<head>
	
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Password Reset page</title>
    <link href = "CSS/bootstrap.min.css" rel="stylesheet">
    <link href="CSS/formstyle.css" rel="stylesheet">
	 <style>
	 body {
    background-color:rgb(245, 245, 220) ;
}
		  </style>
</head>
<body>
	<div class="main-content">

<!--forgot password form-->
    <div class="container">
        <h2 class="text-center">Forgot Password</h2>
        <form action="./passwordreset.php" method="post" id="forgotPassword">
            <div class="mb-4">
                <label for="email" class="form-label">Email address</label>
                <input type="text" class="form-control" id="username" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-clock mb-4">Submit</button>
            <p class="text-danger mt-2" id="errorMessage"></p>
        </form>
		<div class="row">
			<div class = "text-center mt-3">
			
				<p>Haven't got an account?<a href="./register.php">  Register here!</a></p>
				 <p>Back to <a href="./login.php">Login</a></p>
			</div>
		
		</div>
    </div>
		</div>

    <script>
           document.getElementById("forgotPassword").addEventListener("submit", function(event) {
            event.preventDefault();
            
            const username = document.getElementById("username").value;
            const errorMessage = document.getElementById("errorMessage");

            // Simple validation and feedback
            if (username === "") {
                errorMessage.textContent = "Please enter your email address.";
            } else {
                alert("Password reset link sent to your email!");
                window.location.href = "./login.php"; // Redirect to login
            }
        });
    </script>

    <script src="CSS/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- end login form-->


<?php include('footer.php'); ?>

