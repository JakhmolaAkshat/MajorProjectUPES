<?php
session_start();
require 'database.php'; // Include database connection

// Initialize variables to store user data
$username = '';
$email = '';
$profile_picture = '';

if(isset($_SESSION['user_id'])) {
    // Fetch user data from database
    $user_id = $_SESSION['user_id'];
    $query = "SELECT username, email, profile_picture FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if($result) {
        $user = mysqli_fetch_assoc($result);
        $username = $user['username'];
        $email = $user['email'];
        $profile_picture = $user['profile_picture'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission for updating password and profile picture
    // You can add validation and other logic as necessary
    // Updating password
    if (!empty($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $update_password_query = "UPDATE users SET password='$new_password' WHERE id=$user_id";
        mysqli_query($conn, $update_password_query);
    }

    // Updating profile picture
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/imag";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

        $update_picture_query = "UPDATE users SET profile_picture='$target_file' WHERE id=$user_id";
        mysqli_query($conn, $update_picture_query);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="Assets/Logo.png">
    <title>Unifilx | Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styless.css">
    <style>
        .pro img {
            padding: 10px;
            padding-bottom: 5px;
            border-radius: 5px;
            width: 15px;
            height: 15px;
            background-color: #EC6090; /* Ensure the image itself has no background */
        }   
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-container">
            <a href="home.php" class="nav-logo">UNIFLIX</a>
            <ul class="nav-menu">
                <li><a href="home.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Video Section -->
    <div class="video-container">
        <div class="video-overlay"></div>
        <video autoplay muted loop>
            <source src="Assets/3.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <!-- Overlay Box with Text and Buttons -->
         
        <div class="log-box">
            <div class="wrapper">
        <h2>Account Settings</h2>
        <?php if ($profile_picture): ?>
            <div>
                <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" width="100" style="border-radius: 100px;">
            </div>
        <?php endif; ?>
        <form action="account_settings.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="username">Username:</label><br>
                <input class="form-control" type="text" id="username" name="username" value="<?php echo $username; ?>" disabled>
            </div>
            <div>
                <label for="email">Email:</label><br>
                <input class="form-control" type="email" id="email" name="email" value="<?php echo $email; ?>" disabled>
            </div>
            <div>
                <label for="new_password">New Password:</label><br>
                <input class="form-control" type="password" id="new_password" name="new_password">
            </div>
            <div>
                <label for="profile_picture">Profile Picture:</label>
                <input class="form-control" type="file" id="profile_picture" name="profile_picture">
            </div><br>
                <div class="input-box button">
                    <input style="background-color: aliceblue; color: #E75E8D;" type="submit" value="Update" name="Update" class="btn btn-primary">
                </div>
        </form>
    </div>
                </div>
            </div>
        </div>
    </div>

        <section class="foot">
            <div class="footer">
                <div class="contact-us">
                    <h2><a href="">UNIFLIX</a></h2>
                    <p class="sp">Welcome to Unifilx, your ultimate destination for seamless and high-quality live streaming experiences.<br>Join our community to start your streaming journey, watch your favorite streams from anywhere, and enjoy content like never before. With Unifilx, superior streaming is just a click away!</p>
                    <br>
                    <h3>Stay-up to date with UNIFLIX</h3>
                    <p>Follow us on social media for<br>special promotions and important news.</p>
                    <div class="social-icons">
                        <a href="https://x.com/yourprofile" target="_blank"><i class="fab fa-x-twitter"></i> X(Twitter)</a>
                        <a href="https://www.instagram.com/yourprofile" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
                        <a href="https://www.facebook.com/yourprofile" target="_blank"><i class="fab fa-facebook"></i> Facebook</a>
                        <a href="https://www.youtube.com/yourchannel" target="_blank"><i class="fab fa-youtube"></i> YouTube</a>
                    </div>
                </div>                
            </div>
        </section>
    </div>
</body>
</html>
