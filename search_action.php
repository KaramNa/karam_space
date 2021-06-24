<?php
include("session.php");
include("database-config.php");

if (isset($_POST["query"])) {
    $val = $_POST["query"];
    $query = "SELECT * FROM users WHERE firstname LIKE '" . $val . "%'";
    $result = $con->query($query);
    $output = "<div>";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $output .= "<a class='nav-link' href='profile.php?id=" . $row["user_id"] . "'>" . $row["firstname"] . "</a>";
        }
    } else {
        $output .= "No resutl found";
    }
    $output .= "</div>";
    echo $output;
}
