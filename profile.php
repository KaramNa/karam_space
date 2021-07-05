<?php
$request_to_id = $_GET["id"];
require("header.php");
$query = $con->query("SELECT * FROM users WHERE user_id = '$request_to_id'");
$user = $query->fetch_assoc();
include("functions.php");

?>

<div class="container mt-5">
    <div class="row text-center">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <img class="rounded-circle mt-5" src="<?php echo $user['profile_picture'] ?>" alt="" width="200px" height="200px">
            <?php
            $friend_request_id = 0;
            if ($current_user != $request_to_id) {
                $query_sent = "SELECT * FROM friend_request WHERE
                                request_from_id = '$current_user' AND request_to_id = '$request_to_id'";
                $query_recieve = "SELECT * FROM friend_request WHERE 
                                request_from_id = '$request_to_id' AND request_to_id = '$current_user'";
                $sent_result = $con->query($query_sent);
                $recieve_result =  $con->query($query_recieve);
                $friends = false;
                if ($sent_result->num_rows > 0) {
                    $result = $sent_result->fetch_assoc();
                    $request_status = $result["request_status"];
                    $friend_request_id = $result["request_id"];
                    if ($request_status == "pending") {
            ?>
                        <div class="d-flex justify-content-center">
                            <div class="mt-2 ms-2"><button id="cancel_request" class="btn btn-primary">Cancel request</button></div>
                        </div>
                    <?php
                    } elseif ($request_status == "confirm") {
                        $friends = true;
                    }
                } elseif ($recieve_result->num_rows > 0) {
                    $result = $recieve_result->fetch_assoc();
                    $request_status = $result["request_status"];
                    $friend_request_id = $result["request_id"];
                    if ($request_status == "pending") {
                    ?>
                        <div class="d-flex justify-content-center">
                            <div class="mt-2"><button id="accept_friend" class="btn btn-primary">Accept</button></div>
                            <div class="mt-2 ms-2"><button id="reject_friend" class="btn btn-primary">Reject</button></div>
                        </div>
                    <?php
                    } elseif ($request_status == "confirm") {
                        $friends = true;
                    }
                } elseif ($recieve_result->num_rows === 0 and $sent_result->num_rows === 0) {
                    ?>
                    <div class="mt-2"><button id="add_friend" class="btn btn-primary">Add friend</button></div>

                <?php
                }
                if ($friends) {
                ?>
                    <div class="d-flex justify-content-center">
                        <div class="mt-2"><button id="remove_friend" class="btn btn-primary">Remove friend</button></div>
                    </div>
            <?php
                }
            }
            ?>
            <div class="col-sm-4"></div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-4 mt-3">
            <div class="row border">
                <p>Full Name: <b class="text-capitalize"><?php echo $user["firstname"] . " " . $user["lastname"] ?></b></p>
                <p>Email Address: <b class="text-capitalize"><?php echo $user["email"] ?></b></p>
                <p>Gender: <b class="text-capitalize"><?php echo $user["gender"] ?></b></p>
                <p>Birthday: <b class="text-capitalize"><?php echo $user["birthday"] ?></b></p>
            </div>
            <div class="row border  mt-3 p-1">
                <div class="row p-0 m-0  align-items-center">
                    <div class="col-md-4">
                        <h5 class="m-0">Friends</h5>
                    </div>
                    <div class="col-md-8 m-0 p-0">
                        <input class="form-control" type="text" id="search_friend_list" placeholder="Search list">
                    </div>
                </div>
                <div class="row m-0 mt-2 border">
                    <ul id="friend_list">
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8 posts">
            <?php show_post($request_to_id, $con, "my_posts"); ?>
        </div>
    </div>
    <?php
    require("footer.php");
    ?>