<?php 
include("database-config.php");
$comment_content = $_GET["comment"];
$comment_id = $_GET["id"];
$time = date("Y-m-d H:i:s"); ;
$con->query("UPDATE comments SET comment_content = '$comment_content', comment_date = '$time' WHERE comment_id='$comment_id'");

header("location:home.php");
