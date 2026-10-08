<?php
session_start();
if (empty($_SESSION)) {
    header("Location: ./login.php");
    exit();
}

include('header.php');
include('config/connection.php');

// Fetch user data
$sql = "SELECT email, firstname, lastname, IsAdmin, Address, postcode FROM users WHERE email ='" . $_SESSION['email'] . "'";
$result = $connection->query($sql);

if ($result->num_rows !== 1) {
    header("Location: ./login.php");
    exit();
} else {
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $IsAdmin = $row['IsAdmin'];
    $Address = $row['Address'];
    $postcode = $row['postcode'];
}
?>

<main class="container">

    <!-- Ã¢Å“â€¦ Success and Error Messages -->
    <?php
    if (isset($_SESSION['updateError'])) {
        echo '<div class="alert alert-danger" style="color: red; padding: 10px; margin-bottom: 15px; background-color: #fdd;">' . $_SESSION['updateError'] . '</div>';
        unset($_SESSION['updateError']);
    }
    if (isset($_SESSION['updateSuccess'])) {
        echo '<div class="alert alert-success" style="color: green; padding: 10px; margin-bottom: 15px; background-color: #dfd;">' . $_SESSION['updateSuccess'] . '</div>';
        unset($_SESSION['updateSuccess']);
    }
    ?>

    <div class="mb-4" style="background-color: #efefef;">
        <div class="p-4">
            <link href="CSS/formstyle.css" rel="stylesheet">
            <link href="CSS/profile.css" rel="stylesheet">

            <div class="container rounded bg-white mb-5">
				   <!-- Ã°Å¸â€Â Logout Button -->
                <div style="text-align: right; padding: 10px;">
                    <form action="logout.php" method="post">
                        <button type="submit" style="
                            background-color: #333;
                            color: white;
                            border: none;
                            padding: 8px 16px;
                            border-radius: 5px;
                            cursor: pointer;
                        ">Logout</button>
                    </form>
                </div>
			<?php if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1): ?>
    <a href="orderhistory.php" class="btn btn-outline-primary" style="margin-left: 10px;">
        🧾View Order History
    </a>
<?php endif; ?>


                <!-- Ã°Å¸â€Â§ Update Account Info -->
                <form action="./updateaccount.php" method="post" id="accountForm" enctype="multipart/form-data" class="profile-form">
                    <div class="profile-container">
                        <div class="profile-left">
                            <?php if (file_exists("Images/" . $email . ".png")) { ?>
                                <img src="Images/<?php echo $email ?>.png?<?php echo time(); ?>" alt="Profile Image">
                            <?php } else { ?>
                                <img src="Images/base pfp.jpg" alt="Default Profile">
                            <?php } ?>

                            <label for="profileImage" class="upload-label">Choose File</label>
                            <input type="file" name="profileImage" id="profileImage" class="file-input">
                            <p class="email"><?php echo htmlspecialchars($email); ?></p>
                        </div>

                        <div class="profile-right">
                            <h2>Profile Settings</h2>

                            <label for="firstname">First Name</label>
                            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($firstname); ?>">

                            <label for="lastname">Last Name</label>
                            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($lastname); ?>">

                            <label for="Address">Address</label>
                            <input type="text" name="Address" id="Address" value="<?php echo htmlspecialchars($Address); ?>">

                            <label for="postcode">Postcode</label>
                            <input type="text" name="postcode" id="postcode" value="<?php echo htmlspecialchars($postcode); ?>">

                            <input type="submit" value="Save Profile" class="save-btn">
                        </div>
                    </div>
                </form>

                <!-- Ã°Å¸â€â€™ Change Password -->
                <hr style="margin: 30px 0;">
                <form action="./change_password.php" method="POST" class="profile-form">
                    <div class="profile-container">
                        <div class="profile-right">
                            <h2>Change Password</h2>

                            <label for="current_password">Current Password</label>

                            <input type="password" name="current_password" required>

                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" required>

                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" name="confirm_password" required>

                            <input type="submit" value="Update Password" class="save-btn">
                        </div>
                    </div>
                </form>
<?php if ($_SESSION['IsAdmin'] == 1): ?>
  <div style="text-align: right; margin-top: 20px;">
    <a href="adminorders.php" class="btn btn-warning" style="padding: 8px 16px; border-radius: 5px; margin-left: 10px;">📋 View Orders</a>
    <a href="addproducts.php" class="btn btn-primary" style="padding: 8px 16px; border-radius: 5px; margin-left: 10px;">+ Add Product</a>
    <a href="managestock.php" class="btn btn-outline-secondary" style="padding: 8px 16px; border-radius: 5px; margin-left: 10px;">📦 Manage Menu</a>
    
    <!-- Button to Search Users -->
    <a href="searchusers.php" class="btn btn-info" style="padding: 8px 16px; border-radius: 5px; margin-left: 10px;">🔍 Search Users</a>
  </div>
<?php endif; ?>


  </div>
</main>

<?php include('footer.php'); ?>
