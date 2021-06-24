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
            $output .= "<a href='search_result.php'>" . $row["firstname"] . "</a><br>";
        }
    } else {
        $output .= "No resutl found";
    }
    $output .= "</div>";
    echo $output;
}
