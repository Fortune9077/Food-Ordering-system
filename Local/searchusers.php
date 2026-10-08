<?php
session_start();
include('config/connection.php');
include('header.php');

// Check if admin is logged in
if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    header("Location: login.php");
    exit();
}

// Initialize variables for user search
$user = null;

if (isset($_POST['search'])) {
    $email = $_POST['email'];

    // Query to fetch the user details based on the email entered by the admin
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();  // Store user data to be edited
    } else {
        $_SESSION['userNotFound'] = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search and Edit User</title>

    <!-- Bootstrap CSS for styling -->
    <link href="CSS/bootstrap.min.css" rel="stylesheet">
    <style>
       body {
		   background-color: rgb(245, 245, 220);}
        .container {
            margin-top: 30px;
            max-width: 800px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .form-control {
            border-radius: 5px;
        }
        .form-group label {
            font-weight: bold;
        }
        h2, h3 {
            color: #343a40;
        }
        .alert {
            margin-top: 20px;
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

<div class="container">
    <h2>Search for User</h2>

    <!-- Search Form -->
    <form action="searchusers.php" method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" name="email" class="form-control" placeholder="Enter user email" required>
            <div class="input-group-append">
                <button type="submit" name="search" class="btn btn-custom">Search</button>
            </div>
        </div>
    </form>

    <?php if (isset($user)): ?>
        <h3>Edit User Details</h3>

        <!-- Edit User Form -->
        <form action="edituserprocess.php" method="POST">
            <input type="hidden" name="old_email" value="<?php echo $user['email']; ?>">

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
            </div>

            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="Address" id="address" class="form-control" value="<?php echo htmlspecialchars($user['Address']); ?>" required>
            </div>

            <div class="form-group">
                <label for="postcode">Postcode:</label>
                <input type="text" name="postcode" id="postcode" class="form-control" value="<?php echo htmlspecialchars($user['postcode']); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password (leave empty to keep current)">
            </div>

            <div class="form-group">
                <label for="IsAdmin">Is Admin:</label>
                <select name="IsAdmin" id="isadmin" class="form-control">
                    <option value="1" <?php echo ($user['IsAdmin'] == 1) ? 'selected' : ''; ?>>Yes</option>
                    <option value="0" <?php echo ($user['IsAdmin'] == 0) ? 'selected' : ''; ?>>No</option>
                </select>
            </div>

            <button type="submit" class="btn btn-custom">Save Changes</button>
        </form>
    <?php endif; ?>

    <?php
    // Display session message if the user was not found
    if (isset($_SESSION['userNotFound'])) {
        echo "<div class='alert alert-danger'>" . $_SESSION['userNotFound'] . "</div>";
        unset($_SESSION['userNotFound']);
    }
    ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="CSS/jquery-3.7.1.min.js"></script>
<script src="CSS/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include('footer.php'); ?>
