<?php
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = test_input($_POST["firstname"]);
    $last_name = test_input($_POST["lastname"]);
    $email = test_input($_POST["email"]);
    $newpassword = test_input($_POST["newpassword"]);
    $birthday = test_input($_POST["day"] . "/" . $_POST["month"] . "/" . $_POST["year"]);
    $gender = test_input($_POST["gender"]);
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$sql = $con->query("SELECT * FROM users WHERE email='$email'");
if ($sql->num_rows > 0) {
    echo "<script>alert('E-mail already taken!'); window.location='signup.php'</script>";
}else{
    $con->query("INSERT INTO users(firstname,lastname,email,password,gender,birthday) VALUES('$first_name','$last_name','$email','$newpassword','$gender','$birthday')");

}

?>