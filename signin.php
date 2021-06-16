<?php
    include('database.php');
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST["email"];
        $password = $_POST["password"];
        $sql = $con->query("SELECT * FROM users WHERE email = '$email' AND password = '$password'");
        $row = $sql->fetch_assoc();
        echo "<h1>" . $row['id'] . "</h1>";
        if($sql !== false and $sql->num_rows > 0){
            session_start();
            $_SESSION["id"] = $row["id"];
            header("location:home.php");
        }
        else{
            echo "<script>alert('Please check your username and password!'); window.location='index.php'</script>";

        }
    }