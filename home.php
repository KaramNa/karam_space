<?php
require("header.php");
include("functions.php");
$request_to_id = 0;
$friend_request_id = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["upload_user_image"])) {
        $target_dir = "images/profile_pictures/";
        $target_file = $target_dir . basename($_FILES["upload_user_image"]["name"]);
        if (move_uploaded_file($_FILES["upload_user_image"]["tmp_name"], $target_file)) {
            $location = "images/profile_pictures/" . $_FILES["upload_user_image"]["name"];
            $con->query("UPDATE users SET profile_picture = '$location' WHERE user_id = '$user_id'");
        }
    }
}
?>
<!-- user info -->
<?php
$location = $con->query("SELECT profile_picture FROM users WHERE user_id='$current_user'")->fetch_assoc()["profile_picture"];
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-4">
            <div class="col-12 mt-3 text-center">
                <img class="rounded-circle user-image" src="<?php echo $location ?>" alt="" data-bs-toggle="modal" data-bs-target="#staticBackdrop" width="200px" height="200px">
            </div>
            <div class="col-12">
                <ul class="navbar-nav border mt-3 p-4">
                    <h3>Personal Info</h3>
                    <li class="text-capitalize"><strong>First Name: </strong><?php echo $firstname ?></li>
                    <li class="text-capitalize"><strong>Last Name: </strong><?php echo $lastname ?></li>
                    <li><strong>Email: </strong><?php echo $email ?></li>
                    <li class="text-capitalize"><strong>Gender: </strong><?php echo $gender ?></li>
                    <li><strong>Birthday: </strong><?php echo $birthday ?></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-8 mt-3 posts">
            <div class="border p-3">
                <!-- update status -->
                <div>
                    <h1>Update Status</h1>
                    <form method="POST" action="newPost.php" enctype="multipart/form-data">
                        <textarea placeholder="Whats on your mind?" name="content" class="form-control" rows="3"></textarea>
                        <div class="mt-3 d-flex justify-content-between">
                            <input type="file" id="post_image" name="upload" hidden>
                            <button type="button" class="btn btn-secondary" name="fileToUpload" onclick="document.getElementById('post_image').click();">Upload photo</button>
                            <button type="submit" class="btn btn-secondary" name="Submit">Share</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- show posts -->
            <?php
            show_post($current_user, $con, "friends_posts");
            ?>
        </div>
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
                    <input type="file" id="user_image_input" name="upload_user_image" hidden>
                    <span>Please choose a photo </span>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('user_image_input').click();">Choose</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
</div>
<?php require("footer.php");
?>