<?php
include("database-config.php");
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:index.php');
} 

$current_user = $_SESSION['user_id'];

$query = $con->query("SELECT * FROM users WHERE user_id ='$current_user'");
$row = mysqli_fetch_array($query);
$firstname = $row["firstname"];
$lastname = $row["lastname"];
$email = $row["email"];
$gender = $row["gender"];
$birthday = $row["birthday"];
$current_user_pic = $row["profile_picture"];
