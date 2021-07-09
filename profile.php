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
} else {
    $location = $user["profile_picture"];
}

?>

<div class="container mt-5">
    <div class="row text-center">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <img class="rounded-circle mt-5 user-image" src="<?php echo $location ?>" alt="" data-bs-toggle="modal" data-bs-target="#staticBackdrop" width="200px" height="200px">
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
    <div id="personal_info" class="row mt-5">
        <div class="col-md-4 mt-3">
            <div class="row border bg-white rounded ">
                <div class="d-flex justify-content-between align-items-center my-2">
                    <p class="fw-bold m-0">Personal Information</p>
                    <button id="edit_personal_info" class="btn btn-dark">Edit</button>
                </div>
                <div class="text-center">
                    <hr class="mt-0">
                </div>
                <div id="show_personal_info" class="">
                    <?php load_personal_info($user) ?>
                </div>
                <form id="edit_personal_info_form" method="POST" action="" class="d-none mb-3">
                    <p><b>First Name: </b><input id="input_firstname" name="firstname" class="form-control" type="text" class="">
                    <p><b>Last Name: </b><input id="input_lastname" name="lastname" class="form-control" type="text" class="">
                    <p><b>Email Address: </b><input id="input_email" name="email" class="form-control" type="text" class="">
                    <p class="mb-0"><b>Gender: </b></p>
                    <div class="row mb-3" id="input_gender">
                        <div class="col-sm-4 px-1 mt-2">
                            <div class="form-control d-flex justify-content-between align-items-center">
                                <label for="female">Female</label>
                                <input type="radio" id="female" name="gender" value="female">
                            </div>
                        </div>
                        <div class="col-sm-4 px-1 mt-2">
                            <div class="form-control d-flex justify-content-between align-items-center">
                                <label for="male">Male</label>
                                <input type="radio" id="male" name="gender" value="male">
                            </div>
                        </div>
                        <div class="col-sm-4 px-1 mt-2">
                            <div class="form-control d-flex justify-content-between align-items-center">
                                <label for="custom">Custom</label>
                                <input type="radio" id="custom" name="gender" value="custom">
                            </div>
                        </div>
                    </div>

                    <p class="mb-0"><b>Birthday: </b></p>
                    <div class="row mb-3" id="input_birthday">
                        <div class="col-sm-4 px-1 mt-2">
                            <select name="day" class="form-select">
                                <?php
                                $day = 1;
                                while ($day <= 31) {
                                    echo "<option> $day </option>";
                                    $day++;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-4 px-1 mt-2">
                            <select name="month" class="form-select">
                                <option>January</option>
                                <option>Febuary</option>
                                <option>March</option>
                                <option>April</option>
                                <option>May</option>
                                <option>June</option>
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                                <option>October</option>
                                <option>November</option>
                                <option>December</option>
                            </select>
                        </div>
                        <div class="col-sm-4 px-1 mt-2">
                            <select name="year" class="form-select">
                                <?php
                                $year = 2021;
                                while ($year >= 1900) {
                                    echo "<option> $year </option>";
                                    $year--;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" id="save_edited_personal_info" class="btn btn-dark d-none">Save</button>
                </form>
                <div class="text-center">
                    <hr class="mt-0">
                </div>
                <div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <p class="fw-bold m-0">Update Profile Picture</p>
                            <button type="button" class="btn btn-dark" onclick="document.getElementById('user_image_input').click();">Choose</button>
                        </div>
                        <div class="mt-3 position-relative mb-2 d-none">
                            <input type="file" id="user_image_input" name="upload_user_image" hidden>
                            <a href="#" role="button" id="profile_clear_imgInp" class="unselect_img">X</a>
                            <img src="" alt="" id="profile_img_preview" width="100px" height="100px" class="p-1">
                            <button type="submit" class="btn btn-dark position-absolute end-0 bottom-0">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row border mt-3 p-1 bg-white rounded">
                <div class="row p-0 m-0  align-items-center">
                    <div class="col-md-4">
                        <h5 class="m-0">Friends</h5>
                    </div>
                    <div class="col-md-8 m-0 p-0">
                        <input class="form-control" type="text" id="search_friend_list" placeholder="Search list">
                    </div>
                </div>
                <div class="row m-0 mt-2 border px-0">
                    <ul id="friend_list">
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8 posts">
            <?php show_posts($request_to_id, $con, "my_posts"); ?>
        </div>
    </div>
    <?php
    require("footer.php");
    ?>