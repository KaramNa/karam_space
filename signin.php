<?php
    include('database-config.php');
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST["email"];
        $password = $_POST["password"];
        $sql = $con->query("SELECT * FROM users WHERE email = '$email' AND password = '$password'");
        $row = $sql->fetch_assoc();
        if($sql !== false and $sql->num_rows > 0){
            session_start();
            $_SESSION["user_id"] = $row["user_id"];
            header("location:home.php");
        }
        else{
            echo "<script>alert('Please check your username and password!'); window.location='index.php'</script>";

        }
    }