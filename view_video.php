<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'database.php';

// Get video ID from URL
$video_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch video details from the database
$query = "SELECT title, description, video_url, username FROM videos WHERE id = $video_id LIMIT 1";
$result = mysqli_query($conn, $query);

// Check if video exists
if ($result && mysqli_num_rows($result) > 0) {
    $video = mysqli_fetch_assoc($result);
} else {
    // Redirect to home page if video not found
    header('Location: home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="shortcut icon" type="image/x-icon" href="Assets/Logo.png">
    <title><?php echo htmlspecialchars($video['title']); ?> | Unifilx</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styless.css">
    <style>
        .content-container {
    width: 50%;
    margin: 5rem auto;
    background-color: #EC6090;
    padding: 20px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    font-size: 24px;
    margin-bottom: 10px;
}

p {
    font-size: 16px;
    color: #333;
}

video {
    display: block;
    width: 100%;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
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
                <li><a href="profile.php" class="pro"><img src="Assets/1.png"></a></li>   
            </ul>
        </div>
    </nav>

    <!-- Video Content -->
    <div class="content-container">
        <p>Uploaded by: <span style="color: white;"><?php echo htmlspecialchars($video['username']); ?></span></p>
        <!-- Video Player -->
        <video controls width="50%">
            <source src="<?php echo htmlspecialchars($video['video_url']); ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        
        <h1><?php echo htmlspecialchars($video['title']); ?></h1>
        <p><?php echo htmlspecialchars($video['description']); ?></p>
    </div>
</body>
</html>
