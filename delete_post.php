<?php
include("database-config.php");

$post_id = $_GET["id"];
$con->query("DELETE FROM posts WHERE post_id='$post_id'");
header("location:home.php");

?>