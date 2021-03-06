<?php
// include("session.php");
function show_posts($current_profile_user, $con, $show_post_customize)
{
    $no_posts_to_show = true;
    $friends = $con->query("
                        SELECT * FROM friend_request WHERE request_from_id = '$current_profile_user' OR request_to_id = '$current_profile_user' 
                        AND request_status = 'confirm'");
    if ($friends->num_rows == 0) {
    }
    $included_current_user = false;
    do {
        if (!$included_current_user) {
            $user_id = $current_profile_user;
            $included_current_user = true;
            global $friend;
        } elseif ($friend["request_from_id"] == $current_profile_user) {
            $user_id = $friend["request_to_id"];
        } elseif ($friend["request_to_id"] == $current_profile_user) {
            $user_id = $friend["request_from_id"];
        }
        $query = $con->query("SELECT * FROM users JOIN posts ON users.user_id=$user_id AND posts.user_id=$user_id ORDER BY post_id DESC");

        while ($row = $query->fetch_assoc()) {
            $no_posts_to_show = false;
            $posted_by = $row["firstname"] . " " . $row["lastname"];
            $post_image = $row["post_image"];
            $user_image = $row["profile_picture"];
            $content = $row["post_content"];
            $post_id = $row["post_id"];
            $post_date = $row["post_date"];
            $posted_by_id = $row["user_id"];
            $current_user = $GLOBALS["current_user"];
            $post_likes_num = count_likes($post_id, $con);
            $liked_by_me = $con->query("SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = '$current_user'");
            if ($liked_by_me->num_rows > 0) {
                $liked_by_me_flag = true;
            } else {
                $liked_by_me_flag = false;
            }

            show_one_post($con, $user_image, $posted_by, $post_date, $posted_by_id, $post_id, $content, $post_image, "show", $liked_by_me_flag, $post_likes_num);
        }
        if ($show_post_customize == "my_posts") {
            break;
        }
    } while ($friend = $friends->fetch_assoc());
    if ($no_posts_to_show) {
        echo '<div class="border container-fluid p-0 mt-3 post bg-white text-center rounded"><h1 id="no_posts" class="text-secondary">Nothing to show<h1></div>';
    }
}

function show_comment($user_image, $commented_by, $comment_date, $comment_id, $comment_content, $commented_by_id)
{
?>
    <div name="comment_field" class="row mt-1">
        <div class="col-2"><img class="img-size ms-0 rounded-circle img-comment" src="<?php echo $user_image ?>" alt=""></div>
        <div class="col-10 px-1">
            <div class="bg-light rounded px-1">
                <p class="mb-0 text-capitalize"><strong><?php echo $commented_by ?></strong></p>
                <div name="new_comment_panel" class="d-none">
                    <textarea name="new_comment_content" class="form-control" rows="1" placeholder="Enter a comment"></textarea>
                    <button name="edit_comment_done" class="link-button small" value="<?php echo $comment_id ?>">Done</button>
                    <button name="edit_comment_cancel" class="link-button small">Cancel</button>
                </div>
                <p name="old_comment" class="text-break mb-0 preserve_newline"><?php echo test_output($comment_content) ?></p>
            </div>
            <div class="d-flex justify-content-between">
                <div name="comment_edit_delete">
                    <?php
                    $current_user = $GLOBALS["current_user"];
                    if ($current_user == $commented_by_id) {
                    ?>
                        <span class="small"><button class="link-button ps-0" name="edit_comment">Edit</button></span>
                        <span class="small"><button name="delete_comment" class="link-button small " value="<?php echo $comment_id ?>">Delete</button></span>
                    <?php } ?>
                </div>
                <span name="date" class="small"><?php echo $comment_date ?></span>
            </div>
        </div>
    </div>
<?php
}
function count_likes($post_id, $con)
{
    if ($post_id > 0) {
        $query = "SELECT COUNT(post_id) AS total FROM likes
                WHERE post_id = '$post_id'";
        $result = $con->query($query)->fetch_assoc();
        return $result["total"];
    }
}

function insert_post($con, $user_id, $post_content)
{
    if ($_FILES['upload']['size'] == 0) {
        $location = "";
    } else {
        if (check_image()) {
            $location = "images/uploads/" . $_FILES["upload"]["name"];
        }
    }

    $con->query("INSERT INTO posts(user_id,post_image,post_content) VALUES('$user_id','$location','$post_content')");
}


function check_image()
{
    $uploadOk = true;
    $target_dir = "images/uploads/";
    $target_file = $target_dir . basename($_FILES["upload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["upload"]["tmp_name"]);
        if ($check == false) {
            $uploadOk = false;
        }
    }

    // Check file size
    if ($_FILES["upload"]["size"] > 50000000) {
        $uploadOk = false;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $uploadOk = false;
    }
    if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
        return $uploadOk;
    }
}

function show_one_post($con, $user_image, $posted_by, $post_date, $posted_by_id, $post_id, $content, $post_image, $add_show, $liked_by_me_flag, $post_likes_num)
{
?>
    <div class="border container-fluid p-0 mt-3 post bg-white rounded">
        <div class="row mt-3">
            <div class="col-2 pe-0">
                <img class="img-size rounded-circle" src="<?php echo $user_image ?>" alt="">
            </div>
            <div class="col-9 ps-1 d-flex flex-column justify-content-center">
                <div class="text-capitalize fw-bold"><a class="link text-dark" href="<?php echo "profile.php?id=" . $posted_by_id ?>"><?php echo $posted_by ?></a></div>
                <span class="small"><?php echo $post_date ?></span>
            </div>
            <div class="col-1 d-flex justify-content-center">
                <?php
                $current_user = $GLOBALS["current_user"];
                if ($current_user == $posted_by_id) {
                ?>
                    <div class="dropdown nav-item">
                        <a href="#" class="fa fa-angle-double-down text-secondary nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="friend_request_area">
                        </a>
                        <div class="dropdown-menu small bg-dark" id="friend_request_list" aria-labelledby="dropdownMenuLink" style="position: absolute;min-width: 30px;">
                            <div class="search-result">
                                <button id="edit_post" type="button" class="link-button form-control small text-light edit_post" value="<?php echo $post_id ?>" data-bs-toggle="modal" data-bs-target="#update_post">Edit</button>
                            </div>
                            <div class="search-result ">
                                <button id="delete_post" class="link-button small text-light delete_post form-control" value="<?php echo $post_id ?>">Delete</button>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="row my-4 px-3 post_content text-break preserve_newline" name="post_content" data-id="<?php echo 'div' .  $post_id ?>"><?php echo test_output($content) ?></div>
        <div class=""><img id="<?php echo 'postImg' .  $post_id ?>" class="img-fluid post_img" src="<?php echo $post_image ?>" alt="" data-id="<?php echo 'postImg' .  $post_id ?>"></div>
        <div class="row ms-2 count_post_likes">
            <?php
            if ($add_show == "add") {
                echo "Be the first to like this";
            } else if ($add_show == "show") {
                like_status($liked_by_me_flag, $post_likes_num);
            }
            ?>
        </div>
        <div class="row justify-content-center align-itmes-center">
            <?php
            if ($add_show == "add") {
                $likes_count = 0;
            } else {
                $current_user = $GLOBALS["current_user"];
                $likes_query = $con->query("SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = '$current_user'");
                $likes_count = $likes_query->num_rows;
            }
            if ($likes_count > 0) { ?>
                <div class="col-6 btn btn-outline-light p-0">
                    <button type="button" class="like_post btn text-secondary form-control" value="<?php echo $post_id ?>">
                        <p class="text-primary m-0"><i class="fa fa-thumbs-up"></i></p>
                    </button>
                </div>
            <?php
            } else { ?>
                <div class="col-6 btn btn-outline-light p-0">
                    <button type="button" class="like_post btn text-secondary form-control" value="<?php echo $post_id ?>"><span><i class="fa fa-thumbs-up"></i></span></button>
                </div>
            <?php

            }

            ?>

            <div class="col-6 btn btn-outline-light p-0">
                <button type="button" class="make_a_comment btn text-secondary form-control" value="<?php echo "c" . $post_id ?>">
                    <p class="text-secondary m-0"><i class="fa fa-comment"></i></p>
                </button>
            </div>
        </div>
        <!-- add a comment -->
        <div id="comments" class="row">
            <div class="comment_section p-0">
                <div class="border d-flex align-items-center px-1 py-3 my-3 add_comment_form d-none">
                    <div class="col-9">
                        <textarea data-postid="<?php echo $post_id ?>" class="form-control comment_content" name="comment" style="height: 16px;" placeholder="Enter a comment"></textarea>
                    </div>
                    <div class="col-3 text-center">
                        <button type="button" class="btn btn-dark btn-sm add_comment" value="<?php echo $post_id ?>">Comment</button>
                    </div>
                </div>
                <?php
                if ($add_show = "show") {
                    $comment = $con->query("SELECT * FROM comments LEFT JOIN users ON comments.posted_by = users.user_id WHERE post_id = '$post_id' ORDER BY comments.comment_id");
                    while ($row = $comment->fetch_assoc()) {
                        $comment_id = $row["comment_id"];
                        $comment_content = $row["comment_content"];
                        $comment_date = $row["comment_date"];
                        $user_image = $row["profile_picture"];
                        $commented_by = $row["firstname"] . " " . $row["lastname"];
                        $commented_by_id = $row["posted_by"];

                        show_comment($user_image, $commented_by, $comment_date, $comment_id, $comment_content, $commented_by_id);
                    }
                } ?>

            </div>
        </div>
    </div>
<?php
}
function like_status($liked_by_me_flag, $post_likes_num)
{
    if ($liked_by_me_flag && $post_likes_num == 1) {
        echo "You liked this";
    } else if ($liked_by_me_flag && $post_likes_num > 1) {
        echo "You, and " . ($post_likes_num - 1) . " people liked this";
    } else if (!$liked_by_me_flag && $post_likes_num > 0) {
        echo $post_likes_num . " people liked this";
    } else if (!$liked_by_me_flag && $post_likes_num == 0) {
        echo "Be the first to like this";
    }
}

function load_personal_info($user)
{
?>
    <p><b>First Name: </b><span id="firstname" class="text-capitalize"><?php echo $user["firstname"] ?></span></p>
    <p><b>Last Name: </b><span id="lastname" class="text-capitalize"><?php echo $user["lastname"] ?></span></p>
    <p><b>Email Address: </b><span id="email"><?php echo $user["email"] ?></span></p>
    <p><b>Gender: </b><span id="gender" class="text-capitalize"><?php echo $user["gender"] ?></span></p>
    <p><b>Birthday: </b><span id="birthday" class="text-capitalize"><?php echo $user["birthday"] ?></span></p>
<?php


}

function test_output($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>