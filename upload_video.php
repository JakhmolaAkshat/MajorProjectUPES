<?php
if (isset($_POST['upload'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $target_dir = "uploads/videos/";
    $target_file = $target_dir . basename($_FILES["video"]["name"]);
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check file size (limit to 50MB for example)
    if ($_FILES["video"]["size"] > 50000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($videoFileType != "mp4" && $videoFileType != "avi" && $videoFileType != "mov" && $videoFileType != "mpeg") {
        echo "Sorry, only MP4, AVI, MOV, & MPEG files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file)) {
            // Insert video info into the database
            $conn = new mysqli("localhost", "username", "password", "database");
            $stmt = $conn->prepare("INSERT INTO videos (title, description, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $description, $target_file);
            $stmt->execute();
            echo "The file ". htmlspecialchars(basename($_FILES["video"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
