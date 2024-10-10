<?php
session_start();
include 'database.php'; // Ensure this is the correct path to your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check database connection
if ($conn === false) {
    die("ERROR: Could not connect to the database. " . mysqli_connect_error());
}

// Prepare and execute the SQL statement
$user_id = $_SESSION['user_id'];
$sql = "SELECT fullname, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

// Check if the statement preparation was successful
if ($stmt === false) {
    die("ERROR: Could not prepare the SQL statement. " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any results were returned
if ($result->num_rows === 0) {
    die("ERROR: User not found.");
}

$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="Assets/Logo.png">
    <title>Unifilx | Profile</title>
    <link rel="stylesheet" href="styless.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: url(Assets/3.mp4); /* Dark background color */
            color: #fff; /* Text color */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .pro img {
            border-radius: 50%;
            width: 15px;
            height: 15px;
            background-color: #EC6090;
        }
        .profile-container {
            margin-left: 290px;
            background-color: #2a2a2a; /* Background color with opacity */
            border-radius: 8px;
            padding: 20px;
            width: 80%;
            max-width: 600px;
            text-align: center;
        }

        .profile-container img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .profile-container h1 {
            margin: 10px 0;
            font-size: 24px;
        }

        .profile-options {
            margin: 20px 0;
        }

        .profile-options a {
            display: block;
            margin: 10px 0;
            padding: 15px;
            background-color: #E75E8D; /* Accent color */
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .profile-options a:hover {
            background-color: #E75E8D; /* Darker shade on hover */
        }
        .profile-options a i {
            margin-right: 8px;
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
            </ul>
        </div>  
    </nav>
        <div class="video-container">
        <div class="video-overlay"></div>
        <video autoplay muted loop>
            <source src="Assets/3.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <!-- Overlay Box with Text and Buttons -->
        <div class="vid">
            <div class="video-text-box">
                <div class="container">
                        <div class="profile-container">
                        <!-- Display Profile Picture -->
                        <?php
                        $profile_picture = !empty($user['profile_picture']) ? $user['profile_picture'] : 'Assets/A.png';
                        ?>
                        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
                        <!-- Display Full Name -->
                        <h1><?php echo htmlspecialchars($user['fullname']); ?></h1>
                        
                        <!-- Profile Options -->
                        <div class="profile-options">
                            <a href="account_settings.php"><i class="fa fa-cog"></i> Account Settings</a>
                            <a href="golive.php"><i class="fa fa-video"></i> Go Live</a>
                            <a href="livestudio.php"><i class="fa fa-tv"></i> Live Studio</a>
                            <a href="help.php"><i class="fa fa-question-circle"></i> Help and Feedback</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
