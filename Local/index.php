<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hero Section</title>
<style>

        body, html {

            height: 100%;

            margin: 0;

            font-family: Arial, sans-serif;

        }
 
        /* The hero image */

        .hero-image {

            /* Dark overlay effect */

            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("Images/cinematic-film-location-decor.jpg");
 
 
            /* Ensure it covers full width */

            width: 100%;

            height: 100vh; /* Use viewport height for better responsiveness */
 
            /* Center the background image */

            background-position: center;

            background-repeat: no-repeat;

            background-size: cover;

            position: relative;

        }
 
        /* Center text inside the hero image */

        .hero-text {

            text-align: center;

            position: absolute;
			text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4), 0 0 25px rgba(0, 0, 0, 0.3), 0 0 5px rgba(0, 0, 0, 0.2);
 

            top: 50%;

            left: 50%;

            transform: translate(-50%, -50%);

            color: white;

        }
		 .hero-text h2 {
			font-size: 30px; /* Adjust the size as needed */
			text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4), 0 0 25px rgba(0, 0, 0, 0.3), 0 0 5px rgba(0, 0, 0, 0.2);
 
			color: yellow; /* Make the text color black */
		}

        /* Style the button */

        .hero-text button {

            background-color: #ff6600;

            color: white;

            border: none;

            padding: 10px 20px;

            font-size: 18px;

            cursor: pointer;

            border-radius: 5px;

        }
 
        .hero-text button:hover {

            background-color: #cc5500;

        }
	footer {
		background-color: #333;}
</style>
</head>
<body>
 
<div class="hero-image">
<div class="hero-text">
<h1> Are you starving?</h1>
<h2>Mr Gomery’s got you!</h2>
<form action="order.php" method="get" style="display:inline;">
  <button type="submit">ORDER NOW</button>
</form>

</div>
</div>
 
</body>
</html>
 <style>
 .footer {
	 background-color:#333;}</style>
<?php include('footer.php'); ?>
 