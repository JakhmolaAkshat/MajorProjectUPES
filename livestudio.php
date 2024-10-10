<?php
include 'database.php';

// Handle Edit and Delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $title = $conn->real_escape_string($_POST['title']);
        $description = $conn->real_escape_string($_POST['description']);

        $sql = "UPDATE videos SET title='$title', description='$description' WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "The video has been updated.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id'];

        // Get the video URL
        $sql = "SELECT video_url FROM videos WHERE id='$id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $video_url = $row['video_url'];

            // Delete the file from the server
            if (file_exists($video_url)) {
                unlink($video_url);
            }

            // Delete the record from the database
            $sql = "DELETE FROM videos WHERE id='$id'";

            if ($conn->query($sql) === TRUE) {
                echo "The video has been deleted.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo " ";
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
    <title>Unifilx | Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styless.css">
    <style>
        body {
            background-color: #1e1e1e;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
        }

        .nav-container {
            background-color: transparent;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            font-size: 1.5rem;
            color: #fff;
            text-decoration: none;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
        }

        .video-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 40px 0;
            padding: 0 20px;
        }

        .video-item {
            background-color: #EC6090;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        video {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .vido {
            margin-top: 5rem;
        }

        form {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        input[type="text"], textarea {
            width: 90%;
            padding: 10px;
            border-radius: 8px;
            border: none;
        }

        input[type="submit"] {
            background-color: #ffffff;
            color: #1e1e1e;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #f5f5f5;
        }

        .footer {
            background-color: #1e1e1e;
            padding: 20px;
            text-align: center;
            color: #fff;
        }

        .footer h2 a {
            color: #EC6090;
            text-decoration: none;
        }
        .no-videos {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh; /* Adjust height as needed */
            text-align: center;
            color: #EC6090;
            font-size: 24px;
            font-weight: bold;
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

    <!-- Video Gallery -->
    <div class="vido">
        <div class="video-gallery">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="video-item">
                        <h3><?php echo $row['title']; ?></h3>
                        <video controls>
                            <source src="<?php echo $row['video_url']; ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <p><?php echo $row['description']; ?></p>
                        <small>Uploaded on: <?php echo $row['uploaded_on']; ?></small>

                        <!-- Edit Form -->
                        <form action="livestudio.php" method="post">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <label for="title">Change Title:</label><br>
                            <input type="text" name="title" value="<?php echo $row['title']; ?>" required><br><br>
                            <label for="description">Change Description:</label><br>
                            <textarea name="description" required><?php echo $row['description']; ?></textarea><br><br>
                            <input type="submit" value="Update Video">
                        </form>

                        <!-- Delete Form -->
                        <form action="livestudio.php" method="post">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="submit" value="Delete Video" onclick="return confirm('Are you sure you want to delete this video?');">
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-videos">
                    <p>No videos found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <section class="footer">
        <div>
            <h2><a href="">UNIFLIX</a></h2>
            <p>Welcome to Unifilx, your ultimate destination for seamless and high-quality live streaming experiences.</p>
            <p>Follow us on social media for special promotions and important news.</p>
            <div class="social-icons">
                <a href="https://x.com/yourprofile" target="_blank"><i class="fab fa-x-twitter"></i> X(Twitter)</a>
                <a href="https://www.instagram.com/yourprofile" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
                <a href="https://www.facebook.com/yourprofile" target="_blank"><i class="fab fa-facebook"></i> Facebook</a>
                <a href="https://www.youtube.com/yourchannel" target="_blank"><i class="fab fa-youtube"></i> YouTube</a>
            </div>
        </div>
    </section>
</body>
</html>

<?php
$conn->close();
?>
