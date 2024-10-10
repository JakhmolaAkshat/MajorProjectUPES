<?php
session_start();
include 'database.php'; // Ensure this is the correct path to your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $repeat_password = $_POST['repeat_password'];

    if ($new_password !== $repeat_password) {
        $error = "Passwords do not match!";
    } else {
        // Retrieve user's current password hash
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        // Verify current password
        if (password_verify($current_password, $user['password'])) {
            // Update with new password
            $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_password_hash, $user_id);
            if ($stmt->execute()) {
                $success = "Password updated successfully!";
            } else {
                $error = "Failed to update password.";
            }
        } else {
            $error = "Current password is incorrect.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="Assets/Logo.png">
    <title>Unifilx | Change Password</title>
    <link rel="stylesheet" href="styless.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e; /* Adjust as needed */
            color: #fff; /* Text color */
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .change-pass-container {
            margin: 20px;
            padding: 20px;
            background-color: rgba(231, 94, 141, 0.8); /* Background color with 80% opacity */
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
        }

        .change-pass-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .change-pass-container .input-box {
            margin-bottom: 15px;
        }

        .change-pass-container .input-box label {
            display: block;
            margin-bottom: 5px;
        }

        .change-pass-container .input-box input[type="password"] {
            width: 95%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #E75E8D; /* Border with accent color */
            background-color: transparent; /* Transparent background */
            color: #fff;
            font-size: 16px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .change-pass-container .input-box input[type="submit"] {
            padding: 12px;
            width: 95%;
            border-radius: 5px;
            border: none;
            background-color: #E75E8D; /* Accent color */
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .change-pass-container .input-box input[type="submit"]:hover {
            background-color: #d64d72; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="home.php" class="nav-logo">UNIFLIX</a>
            <ul class="nav-menu">
                <li><a href="home.php">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="profile.php" class="pro"><i class="fa-regular fa-user" style="color: #ffffff;"></i></a></li>
            </ul>
        </div>  
    </nav>
    <!-- Change Password Section -->
    <div class="change-pass-container">
        <h1>Change Password</h1>
        <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form action="changepass.php" method="post">
            <div class="input-box">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="input-box">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="input-box">
                <label for="repeat_password">Repeat New Password:</label>
                <input type="password" id="repeat_password" name="repeat_password" required>
            </div>
            <div class="input-box">
                <input type="submit" value="Change Password">
            </div>
        </form>
    </div>
</body>
</html>
