<?php
include("database-config.php");
session_start();
if (isset($_SESSION['user_id'])) {
    $post_id = $_GET["id"];
    $con->query("DELETE FROM posts WHERE post_id='$post_id'");
}
// header("location:home.php");
