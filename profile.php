<?php
$request_to_id = $_GET["id"];
require("header.php");
$query = $con->query("SELECT * FROM users WHERE user_id = '$request_to_id'");
$user = $query->fetch_assoc();
include("functions.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["upload_user_image"])) {
        $target_dir = "images/profile_pictures/";
        $target_file = $target_dir . basename($_FILES["upload_user_image"]["name"]);
        if (move_uploaded_file($_FILES["upload_user_image"]["tmp_name"], $target_file)) {
            $location = "images/profile_pictures/" . $_FILES["upload_user_image"]["name"];
            $con->query("UPDATE users SET profile_picture = '$location' WHERE user_id = '$current_user'");
        }
    }
}else{
    $location = $user["profile_picture"];
}

?>

<div class="container mt-5">
    <div class="row text-center">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <img class="rounded-circle mt-5 user-image" src="<?php echo $location ?>" alt=""  data-bs-toggle="modal" data-bs-target="#staticBackdrop" width="200px" height="200px">
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

    <!-- User Photo Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Update your profile picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <span>Please choose a photo </span>
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('user_image_input').click();">Choose</button>
                    </div>
                    <div class="mt-3 position-relative d-none">
                            <input type="file" id="user_image_input" name="upload_user_image" hidden>
                            <a href="#" role="button" id="profile_clear_imgInp" class="unselect_img">X</a>
                            <img src="" alt="" id="profile_img_preview" width="100px" height="100px" class="p-1">
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    require("footer.php");
    ?>