
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
                <h2>Feedback Form</h2>
                <form action="submit_feedback.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name:</label><br>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label><br>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating:</label><br>
                        <select id="rating" name="rating" required>
                            <option value="1">1 - Poor</option>
                            <option value="2">2 - Fair</option>
                            <option value="3">3 - Good</option>
                            <option value="4">4 - Very Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="comments">Comments:</label><br>
                        <textarea id="comments" name="comments" rows="4" required></textarea>
                    </div>
                    <div class="input-box button">
                        <input style="background-color: aliceblue; color: #E75E8D;" type="submit" value="Update" name="Update" class="btn btn-primary">
                    </div>
                    </form>
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