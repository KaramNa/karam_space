<?php
session_start();
include("database-config.php");
include("functions.php");
// Sign up
    $error_firstname = "";
    $error_lastname = "";
    $error_new_email = "";
    $error_new_password = "";
    $error_gender = "";
    $firstname = $con->real_escape_string($_POST["firstname"]);
    $lastname = $con->real_escape_string($_POST["lastname"]);
    $email = $con->real_escape_string($_POST["email"]);
    $newpassword = $con->real_escape_string($_POST["newpassword"]);
    $birthday = $_POST["day"] . "/" . $_POST["month"] . "/" . $_POST["year"];
    $gender = $_POST["gender"];
    if ($gender == "female") {
        $profile_picture = "images/profile_pictures/female.png";
    } else {
        $profile_picture = "images/profile_pictures/male.jpg";
    }
    $singupOk = true;

    if ($firstname == "") {
        $error_firstname = "<label class='error'>* Required field</label>";
        $singupOk = false;
    }
    if ($lastname == "") {
        $error_lastname = "<label class='error'>* Required field</label>";
        $singupOk = false;
    }
    if ($email == "") {
        $error_new_email = "<label class='error'>* Required field</label>";
        $singupOk = false;
    }
    $sql = $con->query("SELECT * FROM users WHERE email='$email'");
    if ($sql->num_rows > 0) {
        $error_new_email = "<label class='error'>Email address already exsits</label>";
        $singupOk = false;
    }
    if ($newpassword == "") {
        $error_new_password = "<label class='error'>* Required field</label>";
        $singupOk = false;
    }
    if ($gender == "") {
        $error_gender = "<label class='error'>* Required field</label>";
        $singupOk = false;
    }
    if ($singupOk) {
        $con->query("INSERT INTO users(firstname,lastname,email,password,gender,birthday ,profile_picture) VALUES('$firstname','$lastname','$email','$newpassword','$gender','$birthday','$profile_picture')");
        $sql = $con->query("SELECT * FROM users WHERE email = '$email'");
        $row = $sql->fetch_assoc();
        if ($sql !== false and $sql->num_rows > 0) {
            $_SESSION["user_id"] = $row["user_id"];
        }
    }
    $output = array($error_firstname, $error_lastname, $error_new_email, $error_new_password, $error_gender, $singupOk);
    echo json_encode($output);
