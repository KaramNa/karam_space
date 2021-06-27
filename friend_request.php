<?php
include("session.php");
include("database-config.php");

if (isset($_POST["action"])) {
    $action = $_POST["action"];
    $request_from_id = $_SESSION["user_id"];
    if ($action == "add_friend") {
        $request_to_id = $_POST["request_to_id"];
        $con->query("INSERT INTO friend_request(request_from_id, request_to_id, request_status)
             VALUES ('$request_from_id','$request_to_id','pending')");
    }

    if ($action == "cancel_request") {
        $request_id = $_POST["request_id"];
        $con->query("DELETE FROM friend_request WHERE request_id = '$request_id'");
    }
    if ($action == "accept_friend") {
        $request_id = $_POST["request_id"];
        $con->query("UPDATE friend_request SET request_status = 'confirm' WHERE request_id = '$request_id'");
    }

    if ($action == "count_un_seen_friend_request") {
        $query = "SELECT COUNT(request_id) AS total FROM friend_request
        WHERE request_to_id = '$request_from_id'
        AND request_status = 'pending'
        AND request_notify_status = 'no'";
        $result = $con->query($query);
        foreach ($result as $row) {
            echo $row['total'];
        }
    }
    function get_user_name($user_id, $con)
    {
        $query = "SELECT firstname, lastname FROM users WHERE user_id = '$user_id'";
        return $con->query($query);
    }

    if ($action == "load_friend_request_list") {
        $query = "
		SELECT * FROM friend_request 
		WHERE request_to_id = '" . $_SESSION["user_id"] . "' 
	    AND request_status = 'pending' 
	    ORDER BY request_id DESC
		";

        $result = $con->query($query);

        $output = '';
        if ($result->num_rows > 0) {
            foreach ($result as $row) {
                $user_data = get_user_name($row["request_from_id"], $con);

                $user_name = '';

                foreach ($user_data as $user_row) {
                    $user_name = $user_row["firstname"] . " " . $user_row["lastname"];
                }

                $output .= "<a class='dropdown-item nav-link' href='profile.php?id=" . $row["request_from_id"] . "'>" . $user_name . "</a>";
            }
        } else {
            $output = "<p class='ms-2 mb-0 text-light'>No friend request</p>";
        }
        echo $output;
    }

    if ($action == "remove_friend_request_count") {
        $con->query("UPDATE friend_request SET request_notify_status = 'yes' 
        WHERE request_to_id = '$request_from_id'
        AND request_notify_status = 'no'");
    }
}
