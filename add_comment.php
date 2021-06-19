<?php 
include("database-config.php");
if($_SERVER["REQUEST_METHOD"] == "POST"){
$comment_content = $_POST["comment"];
$post_id = $_POST["post_id"];
$con->query("INSERT INTO comments(post_id, comment_content) VALUES('$post_id','$comment_content')");
}

header("location:home.php");
