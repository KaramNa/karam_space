<?php
require("header.php");
include("functions.php");
$request_to_id = 0;
$friend_request_id = 0;
$location = $con->query("SELECT profile_picture FROM users WHERE user_id='$current_user'")->fetch_assoc()["profile_picture"];
?>
<!-- user info -->
<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="px-0 w-350 make-space">
            <div class="mt-3 text-center">
                <img class="rounded-circle" src="<?php echo $location ?>" alt="" width="190px" height="190px">
            </div>
            <div class="">
                <ul class="navbar-nav bg-white rounded border mt-3 p-4">
                    <h3>Personal Info</h3>
                    <div class="text-center">
                        <hr class="mt-0">
                    </div>
                    <li class="text-capitalize"><strong>Full Name: </strong><?php echo $firstname . " " . $lastname ?></li>
                    <li><strong>Email: </strong><?php echo $email ?></li>
                    <li class="text-capitalize"><strong>Gender: </strong><?php echo $gender ?></li>
                    <li><strong>Birthday: </strong><?php echo $birthday ?></li>
                </ul>
            </div>
        </div>
        <div class="mt-3 px-0 w-450 posts">
            <div class="border p-3 bg-white rounded">
                <!-- update status -->
                <div>
                    <h1>Update Status</h1>
                    <form id="update_status_form" method="" action="" enctype="multipart/form-data">
                        <textarea id="post_content" placeholder="Say What's in Your Heart?" name="content" class="form-control" rows="3"></textarea>
                        <div class="mt-3 position-relative d-none">
                            <input type="file" id="imgInp" name="upload" hidden>
                            <a role="button" id="clear_imgInp" class="unselect_img">X</a>
                            <img src="" alt="" id="img_preview" width="100px" height="100px" class="p-1">
                        </div>
                        <div class="mt-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-dark" name="fileToUpload" onclick="document.getElementById('imgInp').click();"><i class="fas fa-camera"></i></button>
                            <button type="submit" class="btn btn-dark" name="Submit"><i class="fa fa-rocket" aria-hidden="true"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- show posts -->
            <?php
            show_posts($current_user, $con, "friends_posts");
            ?>
        </div>
    </div>
</div>
<?php require("footer.php");
?>