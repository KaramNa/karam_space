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

    if ($action == "load_friend_list") {
        $condition = '';
        $user_id = $_POST["user_id"];
        if (!empty($_POST["query"])) {
            $condition = "AND users.firstname LIKE '" . $_POST["query"] . "%'";
        }
        $query = "
                    SELECT users.user_id ,users.firstname, users.lastname, friend_request.request_from_id, friend_request.request_to_id
                    FROM users INNER JOIN friend_request ON
                    friend_request.request_from_id = users.user_id OR
                    friend_request.request_to_id = users.user_id 
                    WHERE (friend_request.request_to_id = '$user_id' OR friend_request.request_from_id = '$user_id')
                    AND users.user_id != '$user_id'
                    AND friend_request.request_status = 'confirm'"
            . $condition . "
                    ";
        $result = $con->query($query);
        $output = "";
        if ($result->num_rows > 0) {
            foreach ($result as $row) {
                $output .= "<li class='list-unstyled'><a class='text-capitalize link' href='profile.php?id=" . $row["user_id"] . "'>" . $row["firstname"] . " " . $row["lastname"] . "</a></li>";
            }
        }
        echo $output;
    }

    if ($action == "add_comment") {
        $output = "";
        $comment_content = $_POST["comment_content"];
        $result = $con->query("SELECT profile_picture FROM users WHERE user_id = '$request_from_id'");
        $user_image = $result->fetch_assoc()["profile_picture"];
        $time = date("Y-m-d H:i:s");
        $post_id = $_POST["post_id"];
        $posted_by = $firstname . " " . $lastname;
        if ($con->query("INSERT INTO comments(post_id,posted_by, comment_content) VALUES('$post_id', '$request_from_id', '$comment_content')")) {
            $comment_id = $con->insert_id;

            $output .= '
            <div class="row mt-1">
            <div class="col-lg-1 col-md-2"><img class="img-fluid img-comment" src="' . $user_image . '" alt=""></div>
            <div class="col-lg-11 col-md-10">
                <div class="bg-light rounded p-1">
                    <p class="mb-0 text-capitalize"><strong>' . $posted_by . '</strong></p>
                    <div id="new_comment" class="d-none" data-id="div' . $comment_id . '">
                        <textarea class="form-control" id="new_comment_content" rows="2" placeholder="Enter a comment" data-id="textarea' . $comment_id . '"></textarea>
                        <button class="link-button small done_comment_edit" value="' . $comment_id . '">Done</button>
                        <button class="link-button small" onclick="edit_cancel()">Cancel</button>
                    </div>
                    <p id="old_comment" class="old_comment text-break" data-id="p' . $comment_id . '">' . $comment_content . '</p>
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="small"><button class="link-button" id="edit_comment" onclick="edit_comment(this.value)" value="' . $comment_id . '" data-id="edit_btn' . $comment_id . '">Edit</button></span>
                        <span class="small"><button class="link-button small delete_comment" value="' .$comment_id . '" data-id="delete_btn' . $comment_id . '">Delete</button></span>
                    </div>
                    <span class="small" data-id="date' . $comment_id . '">' . $time . '</span>
                </div>
            </div>
        </div>
        ';
            echo $output;
        }
    }

    if ($action == "edit_comment") {
        $comment_id = $_POST["comment_id"];
        $comment_content = $_POST["new_comment_content"];
        $time = date("Y-m-d H:i:s");
        $con->query("UPDATE comments SET comment_content = '$comment_content', comment_date = '$time' WHERE comment_id='$comment_id'");
        echo $comment_content;
    }
    if ($action == "delete_comment") {
        $comment_id = $_POST["comment_id"];
        $con->query("DELETE FROM comments  WHERE comment_id = '$comment_id'");
    }
}
