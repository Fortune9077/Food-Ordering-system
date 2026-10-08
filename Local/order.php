<?php
session_start();
include('header.php');
?>
<div class="container text-center mt-5">
    <h2>Let’s get started with your order!</h2>
    <p>Redirecting you to the menu...</p>
</div>
<script>
    setTimeout(() => {
        window.location.href = "menu.php";
    }, 2000);
</script>
<?php include('footer.php'); ?>