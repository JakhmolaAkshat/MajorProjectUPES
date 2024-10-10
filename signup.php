<?php
session_start();
include 'database.php'; // Ensure this is the correct path to your database connection file

function is_alphanumeric($string) {
    return preg_match('/^[a-zA-Z0-9]+$/', $string);
}

if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['uid'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Check if the username is alphanumeric
    if (!is_alphanumeric($username)) {
        die("Username must be alphanumeric!");
    }

    // Check if passwords match
    if ($password !== $repeat_password) {
        die("Passwords do not match!");
    }

    // Check if the username is unique
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the SQL statement: " . $conn->error);
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Username already taken!");
    }

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profilePicture = $_FILES['profile_picture'];
        $uploadDir = 'uploads/';
        $profilePictureName = uniqid() . '-' . basename($profilePicture['name']);
        $uploadFilePath = $uploadDir . $profilePictureName;

        // Ensure the uploads directory exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($profilePicture['tmp_name'], $uploadFilePath)) {
            $profilePicturePath = $uploadFilePath;
        } else {
            die("Error uploading profile picture.");
        }
    } else {
        $profilePicturePath = 'Assets/A.png'; // Default profile picture
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $sql = "INSERT INTO users (fullname, username, email, password, profile_picture) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the INSERT statement: " . $conn->error);
    }

    $stmt->bind_param('sssss', $fullname, $username, $email, $hashed_password, $profilePicturePath);

    if ($stmt->execute()) {
        header('Location: login.php');
        exit();
    } else {
        die("Error executing the SQL statement: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="Assets/Logo.png">
    <title>Unifilx | Signup</title>
    <link rel="stylesheet" href="styless.css">
    <style>
        .log-box {
            width: 50%;
            height: 90%;
            border-radius: 10px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #fff;
            padding: 10px; /* Increased padding */
            background-color: #2a2a2a; 
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5); /* Added box shadow for depth */
            backdrop-filter: blur(10px); /* Blur effect for background */
        }
        .log-box .input-box input {
            width: 80%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #E75E8D; /* Border with accent color */
            background-color: transparent; /* Transparent background */
            color: #fff;
            font-size: 16px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-container">
            <a href="index.php" class="nav-logo">UNIFLIX</a>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
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
        <!-- Overlay Box with Login Form -->
        <div class="log-box">
            <div class="wrapper">
                <h2>Signup</h2>
                    <form action="signup.php" method="post">
                        <div class="input-box">
                            <input type="text" class="form-control" name="fullname" placeholder="Full Name:" required>
                        </div>
                        <div class="input-box">
                            <input type="text" class="form-control" name="uid" placeholder="Username:" required>
                        </div>
                        <div class="input-box">
                            <input type="email" class="form-control" name="email" placeholder="Email:" required>
                        </div>
                        <div class="input-box">
                            <input type="password" class="form-control" name="password" placeholder="Password:" required>
                        </div>
                        <div class="input-box">
                            <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:" required>
                        </div>
                        <div class="input-box">
                            <label for="profile_picture">Profile Picture:</label>
                            <input type="file" name="profile_picture" accept="image/*">
                        </div>
                        <div class="input-box button">
                            <input type="submit" class="btn btn-primary" value="Register" name="submit">
                        </div>
                    </form>
                        <h3>Already Member? <a href="Login.php">Login Here</a></h3>
            </div>
        </div>
    </div>
</body>
</html>
