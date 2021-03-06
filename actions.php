<?php
include("session.php");
include("database-config.php");
include("functions.php");

if (isset($_POST["action"])) {
    $action = $_POST["action"];
    $current_user = $_SESSION["user_id"];

    // Search actions
    if ($action == "search_people") {
        $val = $con->real_escape_string($_POST["query"]);
        $query = "SELECT * FROM users WHERE firstname LIKE '" . $val . "%' OR lastname LIKE '" . $val . "%'";
        $result = $con->query($query);
        $output = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $output .= "
                <div class='p-2 search-result' onclick='window.location.href = " . '"profile.php?id=' . $row["user_id"] . '"' . "'><img class='img-size rounded-circle' src='" . $row["profile_picture"]  . "' alt=''>  
                <a id='profile_link' class='text-capitalize link text-light' href='profile.php?id=" . $row["user_id"] . "'>" . $row["firstname"] . " " . $row["lastname"] . "</a>
                </div>
                ";
            }
        } else {
            $output .= "<p class='text-light m-0 ms-1'>No resutl found</p>";
        }
        $output .= "";
        echo $output;
    }
    // Friendship actions
    if ($action == "add_friend") {
        $request_to_id = $_POST["request_to_id"];
        $con->query("INSERT INTO friend_request(request_from_id, request_to_id, request_status)
             VALUES ('$current_user','$request_to_id','pending')");
    }

    if ($action == "cancel_request") {
        $request_id = $_POST["request_id"];
        $con->query("DELETE FROM friend_request WHERE request_id = '$request_id'");
    }
    if ($action == "accept_friend") {
        $request_id = $_POST["request_id"];
        $con->query("UPDATE friend_request SET request_status = 'confirm' WHERE request_id = '$request_id'");
        $query = "SELECT request_from_id, request_to_id FROM friend_request WHERE request_id = '$request_id'";
        $result = $con->query($query)->fetch_assoc();
        $recipient_id = $result["request_from_id"];
        $sender_id = $result["request_to_id"];
        $con->query("INSERT INTO notifications (recipient_id, sender_id, activity_type, object_url) VALUES ('$recipient_id','$sender_id','accept_friend_request','profile.php?id=$current_user')");
    }
    // friend request list
    if ($action == "count_un_seen_friend_request") {
        $query = "SELECT COUNT(request_id) AS total FROM friend_request
        WHERE request_to_id = '$current_user'
        AND request_status = 'pending'
        AND request_notify_status = 'no'";
        $result = $con->query($query)->fetch_assoc();
        echo $result['total'];
    }
    function get_user_name($user_id, $con)
    {
        $query = "SELECT * FROM users WHERE user_id = '$user_id'";
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

                $user_row = $user_data->fetch_assoc();
                $user_name = $user_row["firstname"] . " " . $user_row["lastname"];
                $user_image = $user_row["profile_picture"];
                $user_id = $user_row["user_id"];



                $output .= "
                <div class='p-2 search-result' onclick='window.location.href = " . '"profile.php?id=' . $user_id . '"' . "'><img class='img-size rounded-circle' src='" . $user_image  . "' alt=''>  
                <a id='profile_link' class='text-capitalize link text-light' href='profile.php?id=" . $user_id . "'>" . $user_name . "</a>
                </div>
                ";
            }
        } else {
            $output = "<p class='ms-2 mb-0 text-light'>No friend request</p>";
        }
        echo $output;
    }

    if ($action == "remove_friend_request_count") {
        $con->query("UPDATE friend_request SET request_notify_status = 'yes' 
        WHERE request_to_id = '$current_user'
        AND request_notify_status = 'no'");
    }

    // notifications list
    if ($action == "count_un_seen_notificatons") {
        $query = "SELECT COUNT(notification_id) AS total FROM notifications
    WHERE recipient_id = '$current_user'
    AND is_read = 'no'";
        $result = $con->query($query)->fetch_assoc();
        echo $result['total'];
    }

    if ($action == "load_notifications") {
        $query = "
                SELECT * FROM notifications 
                WHERE recipient_id = '$current_user' 
                ORDER BY notification_date DESC";

        $result = $con->query($query);

        $output = '';
        if ($result->num_rows > 0) {
            foreach ($result as $row) {
                $user_data = get_user_name($row["sender_id"], $con);
                $user_row = $user_data->fetch_assoc();
                $user_name = $user_row["firstname"] . " " . $user_row["lastname"];
                $user_image = $user_row["profile_picture"];
                $activity_type = $row["activity_type"];
                $object_url = $row["object_url"];
                if ($activity_type == "accept_friend_request") {
                    $output .= "
                    <div class='p-2 search-result small' onclick='window.location.href = " . '"' . $object_url . '"' ."'>
                        <img class='img-size-small rounded-circle' src='" . $user_image  . "' alt=''>  
                        <span class='text-capitalize small  link text-light'>$user_name</span>
                        <span class='text-light'>accepted your friend request</span>
                    </div>
                    ";
                } else if ($activity_type == "comment") {
                    $output .= "
                    <div class='p-2 search-result small' onclick='window.location.href = " . '"' . $object_url . '"' ."'>
                        <img class='img-size-small rounded-circle' src='" . $user_image  . "' alt=''>  
                        <span class='text-capitalize small  link text-light'>$user_name</span>
                        <span class='text-light'>commented on your post</span>
                    </div>
                    ";
                } else if ($activity_type == "like") {
                    $output .= "
                    <div class='p-2 search-result small' onclick='window.location.href = " . '"' . $object_url . '"' ."'>
                        <img class='img-size-small rounded-circle' src='" . $user_image  . "' alt=''>  
                        <span class='text-capitalize  small link text-light'>$user_name</span>
                        <span class='text-light'>liked your post</span>
                    </div>
                    ";
                }
            }
        } else {
            $output = "<p class='ms-2 mb-0 text-light'>No notifications</p>";
        }
        echo $output;
    }

    if ($action == "remove_notifications_count") {
        $con->query("UPDATE notifications SET is_read = 'yes' 
    WHERE recipient_id = '$current_user'
    AND is_read = 'no'");
    }


    if ($action == "load_friend_list") {
        $condition = '';
        $user_id = $_POST["user_id"];
        if (!empty($_POST["query"])) {
            $condition = "AND (users.firstname LIKE '" . $con->real_escape_string($_POST["query"]) . "%' OR users.lastname LIKE '" . $con->real_escape_string($_POST["query"]) . "%' ) ";
        }
        $query = "
                    SELECT *
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
                $output .= "<div class='p-2 search-result' onclick='window.location.href = " . '"profile.php?id=' . $row["user_id"] . '"' . "'><img class='img-size rounded-circle me-2' src='" . $row["profile_picture"]  . "' alt=''>  
                <a id='profile_link' class='text-capitalize link text-dark fw-bold' href='profile.php?id=" . $row["user_id"] . "'>" . $row["firstname"] . " " . $row["lastname"] . "</a>
                </div>";
            }
        } else {
            $output = "You don't have any friends yet";
        }
        echo $output;
    }
    // Post actions
    if ($action == "add_post") {
        $post_content = $con->real_escape_string($_POST["content"]);
        insert_post($con, $current_user, $post_content);
        $post_content = $_POST["content"];
        $post_id = $con->insert_id;
        $posted_by = $firstname . " " . $lastname;
        $post_date = date("Y-m-d H:i:s");
        $post_image = "images/uploads/" . $_FILES["upload"]["name"];
        show_one_post($con, $current_user_pic, $posted_by, $post_date, $current_user, $post_id, $post_content, $post_image, "add", false, 0);
    }

    if ($action == "edit_post") {
        $post_id = $_POST["post_id"];
        $post_content = $con->real_escape_string($_POST["content"]);
        $post_img = $_POST["post_img"];
        if ($post_img == "" && $_FILES['upload']['size'] == 0) {
            $query = $con->query("UPDATE posts SET post_content = '$post_content', post_image = '$post_img' WHERE post_id = '$post_id'");
        } else if ($post_img != "" && $_FILES['upload']['size'] == 0) {
            $query = $con->query("UPDATE posts SET post_content = '$post_content' WHERE post_id = '$post_id'");
        } else if ($_FILES['upload']['size'] != 0) {
            check_image();
            $post_img = "images/uploads/" . $_FILES["upload"]["name"];
            $query = $con->query("UPDATE posts SET post_content = '$post_content', post_image = '$post_img' WHERE post_id = '$post_id'");
        }
        $output = array(test_output($post_content), $post_img);
        echo json_encode($output);
    }

    if ($action == "delete_post") {
        $post_id = $_POST["post_id"];
        $query = $con->query("SELECT user_id FROM posts WHERE post_id = $post_id");
        $user_id = $query->fetch_assoc()['user_id'];
        if ($current_user == $user_id) {
            $con->query("DELETE FROM posts WHERE post_id='$post_id'");
        }
        echo $user_id;
    }
    // Comment actions
    if ($action == "add_comment") {
        $output = "";
        $comment_content = $con->real_escape_string($_POST["comment_content"]);
        $result = $con->query("SELECT profile_picture FROM users WHERE user_id = '$current_user'");
        $user_image = $result->fetch_assoc()["profile_picture"];
        $comment_date = date("Y-m-d H:i:s");
        $post_id = $_POST["post_id"];
        $commented_by = $firstname . " " . $lastname;
        if ($con->query("INSERT INTO comments(post_id,posted_by, comment_content) VALUES('$post_id', '$current_user', '$comment_content')")) {
            $comment_id = $con->insert_id;
            $comment_content = $_POST["comment_content"];
            $user = $con->query("SELECT user_id FROM posts WHERE post_id = '$post_id'");
            $recipient_id = $user->fetch_assoc()["user_id"];
            if ($recipient_id != $current_user) {
                $con->query("INSERT INTO notifications (recipient_id, sender_id, activity_type, comment_id, post_id, object_url) VALUES ('$recipient_id','$current_user','comment','$comment_id','$post_id', 'view_notif_object.php?post_id=$post_id')");
            }
            show_comment($user_image, $commented_by, $comment_date, $comment_id, $comment_content, $current_user, $current_user);
        }
    }



    if ($action == "edit_comment") {
        $comment_id = $_POST["comment_id"];
        $comment_content = $con->real_escape_string($_POST["new_comment_content"]);
        $time = date("Y-m-d H:i:s");
        $query = $con->query("SELECT posted_by FROM comments WHERE comment_id = $comment_id");
        $comented_by = $query->fetch_assoc()['posted_by'];
        if ($current_user == $comented_by) {
            $con->query("UPDATE comments SET comment_content = '$comment_content', comment_date = '$time' WHERE comment_id='$comment_id'");
        }
        echo $comment_content;
    }
    if ($action == "delete_comment") {
        $comment_id = $_POST["comment_id"];
        $query = $con->query("SELECT posted_by FROM comments WHERE comment_id = $comment_id");
        $comented_by = $query->fetch_assoc()['posted_by'];
        if ($current_user == $comented_by) {
            if ($con->query("DELETE FROM comments  WHERE comment_id = '$comment_id'")) {
                $con->query("DELETE  FROM notifications WHERE comment_id = '$comment_id'");
            }
        } else {
            die();
        }
    }
    // Like actions
    if ($action == "like_post") {
        $post_id = $_POST["post_id"];
        $user_id = $current_user;
        $query = $con->query("SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'");
        if ($query->num_rows > 0) {
            if ($con->query("DELETE FROM likes WHERE user_id = '$user_id' AND post_id = '$post_id'")) {
                $con->query("DELETE FROM notifications WHERE post_id = '$post_id'");
            }
            echo "unlike";
        } else {
            if ($con->query("INSERT INTO likes VALUES('$post_id','$user_id')")) {
                $user = $con->query("SELECT user_id FROM posts WHERE post_id = '$post_id'");
                $recipient_id = $user->fetch_assoc()["user_id"];
                if ($recipient_id != $current_user) {
                    $con->query("INSERT INTO notifications (recipient_id, sender_id, activity_type, post_id, object_url) VALUES ('$recipient_id','$current_user','like',$post_id, 'view_notif_object.php?post_id=$post_id')");
                }
            }
            echo "like";
        }
    }

    if ($action == "count_post_likes") {
        $post_id = $_POST["post_id"];
        if ($post_id > 0) {
            $query = "SELECT COUNT(post_id) AS total FROM likes
        WHERE post_id = '$post_id'";
            $result = $con->query($query)->fetch_assoc();
            echo $result["total"];
        }
    }

    // Edit personal info
    if ($action == "edit_presonal_info") {
        $firstname = $con->real_escape_string($_POST["firstname"]);
        $lastname = $con->real_escape_string($_POST["lastname"]);
        $email = $con->real_escape_string($_POST["email"]);
        $gender = $_POST["gender"];
        $birthday = $_POST["day"] . "/" . $_POST["month"] . "/" . $_POST["year"];
        $update_info_query = "UPDATE users SET firstname = '$firstname', lastname = '$lastname', gender = '$gender', email = '$email', birthday = '$birthday' WHERE user_id = '$current_user'";
        $con->query($update_info_query);
        $user = $con->query("SELECT * FROM users WHERE user_id = '$current_user'");
        $user = $user->fetch_assoc();
        load_personal_info($user);
    }
}
