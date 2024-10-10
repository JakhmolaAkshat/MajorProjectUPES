<?php

include 'database.php';
// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $username = $conn->real_escape_string($_POST['username']);
    
    $target_dir = "uploads/videos";
    $target_file = $target_dir . basename($_FILES["video"]["name"]);
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a video
    $allowedTypes = array("mp4", "avi", "mov", "3gp", "mpeg");
    if (!in_array($videoFileType, $allowedTypes)) {
        echo "Sorry, only MP4, AVI, MOV, 3GP & MPEG files are allowed.";
        $uploadOk = 0;
    }

    // Check file size (limit to 100MB)
    if ($_FILES["video"]["size"] > 100000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO videos (title, description, video_url, username) VALUES ('$title', '$description', '$target_file', '$username')";

            if ($conn->query($sql) === TRUE) {
                echo "The video has been uploaded.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch videos from the database
$sql = "SELECT * FROM videos ORDER BY uploaded_on DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="Assets/Logo.png">
    <title>GoLive - Upload & Watch Videos</title>
    <link rel="stylesheet" href="styless.css"> <!-- Add your CSS file if needed -->
    <style>
        
        body {
            background-color: #1e1e1e;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        
        .upload-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        
        .upload-box {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
        }
        
        .video-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            padding: 20px;
        }
        
        .video-item {
            background-color: #444;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
        }
        
        video {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
        <nav>
            <div class="nav-container">
                <a href="home.php" class="nav-logo">UNIFLIX</a>
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
        </nav>
        
    <div class="upload-container">
        <div class="upload-box">
            <h1>Upload a Video</h1>
            <form action="golive.php" method="post" enctype="multipart/form-data">
                <label for="title">Title:</label><br>
                <input type="text" name="title" id="title" required><br><br>
                <label for="description">Description:</label><br>
                <textarea name="description" id="description" required></textarea><br><br>
                <label for="username">Username:</label><br>
                <input type="text" name="username" id="username" required><br><br>
                <label for="video">Select video to upload:</label><br>
                <input type="file" name="video" id="video" required><br><br>
                <input type="submit" value="Upload Video">
            </form>
        </div>
    </div>

            <h2>Watch Videos</h2>
            <div class="video-gallery">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="video-item">
                            <h3><?php echo $row['title']; ?></h3>
                            <video width="320" height="240" controls>
                                <source src="<?php echo $row['video_url']; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <p><?php echo $row['description']; ?></p>
                            <small>Uploaded on: <?php echo $row['uploaded_on']; ?></small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No videos found.</p>
                <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?> 