<?php
include("database-config.php");
include("session.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "images/uploads/";
    $target_file = $target_dir . basename($_FILES["upload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["upload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    // if (file_exists($target_file)) {
    //     echo "Sorry, file already exists.";
    //     $uploadOk = 0;
    // }

    // Check file size
    if ($_FILES["upload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["upload"]["name"])) . " has been uploaded.";
            $location = "images/uploads/" . $_FILES["upload"]["name"];
            $user_id = $_SESSION["user_id"];
            $post_content = $_POST["content"];
            $likes = 0;

            $con->query("INSERT INTO posts(user_id,post_image,post_content,post_likes) VALUES('$user_id','$location','$post_content','$likes')");
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
header("location:home.php");
