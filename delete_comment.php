<?php 
include("database-config.php");
$comment_id = $_GET["id"];
$con->query("DELETE FROM comments WHERE comment_id='$comment_id'");

header("location:home.php");
