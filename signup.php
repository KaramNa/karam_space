<?php
    include("database.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $first_name = $_POST["firstname"];
        $last_name = $_POST["lastname"];
        $email = $_POST["email"];
        $newpassword = $_POST["newpassword"];
        $birthdate = $_POST["birthdate"];
        $gender = $_POST["gender"];
    }

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;

    }

    echo "success";
?>