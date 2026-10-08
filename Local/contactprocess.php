<?php include("header.php"); ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = htmlspecialchars(trim($_POST['name']));
  $email = htmlspecialchars(trim($_POST['email']));
  $message = htmlspecialchars(trim($_POST['message']));

  // You can log it, store in DB, or send via email.
  // For now, just show a simple success message.
  echo "
  <div style='padding:20px; font-family:sans-serif;'>
          <h3>Thank you, $name!</h3>
          <p>We’ve received your message and will get back to you at <strong>$email</strong>.</p>
          <a href='index.php'>Return to Home</a>
        </div>";
} else {
  header("Location: contact.php");
  exit;
}
 ?>
<?php include("footer.php"); ?>
