<?php

function show_post($user_id, $con, $show_post_customize)
{
    if ($show_post_customize == "friends_posts") {
        $friends = $con->query("
                        SELECT * FROM friend_request WHERE request_from_id = '$user_id' OR request_to_id = '$user_id' 
                        AND request_status = 'confirm'");
    }
    $flag = 0;
    function count_likes($post_id, $con)
    {
        if ($post_id > 0) {
            $query = "SELECT COUNT(post_id) AS total FROM likes
                    WHERE post_id = '$post_id'";
            $result = $con->query($query)->fetch_assoc();
            return $result["total"];
        }
    }
    do {
        if ($flag == 0) {
            $friend_id = $user_id;
            $flag = 1;
            global $friend;
        } elseif ($friend["request_from_id"] == $user_id) {
            $friend_id = $friend["request_to_id"];
        } elseif ($friend["request_to_id"] == $user_id) {
            $friend_id = $friend["request_from_id"];
        }
        $query = $con->query("SELECT * FROM users JOIN posts ON users.user_id=$friend_id AND posts.user_id=$friend_id ORDER BY post_id DESC");
        while ($row = $query->fetch_assoc()) {
            $posted_by = $row["firstname"] . " " . $row["lastname"];
            $post_image = $row["post_image"];
            $user_image = $row["profile_picture"];
            $content = $row["post_content"];
            $post_id = $row["post_id"];
            $post_date = $row["post_date"];
            $posted_by_id = $row["user_id"];
            $post_likes_num = count_likes($post_id, $con);
            $liked_by_me = $con->query("SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'");
            if ($liked_by_me->num_rows > 0) {
                $liked_by_me_flag = true;
            } else {
                $liked_by_me_flag = false;
            }
?>
            <div class="border container-fluid p-0 mt-3 post bg-white rounded">
                <div class="row mt-3">
                    <div class="col-xl-1 col-lg-3 col-md-3 col-2">
                        <img class="img-size rounded-circle" src="<?php echo $user_image ?>" alt="">
                    </div>
                    <div class="col-xl-9 col-lg-7 col-md-7 col-8 ps-1 d-flex flex-column justify-content-center">
                        <div class="text-capitalize fw-bold"><?php echo $posted_by ?></div>
                        <span><?php echo $post_date ?></span>
                    </div>

                    <div class="col-2 d-flex justify-content-end">
                        <?php
                        if ($user_id == $posted_by_id) {
                        ?>
                            <div class="dropdown nav-item">
                                <a href="#" class="fa fa-angle-double-down text-secondary nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="friend_request_area">
                                    <!-- <span class="caret"></span> -->
                                </a>
                                <div class="dropdown-menu bg-dark" id="friend_request_list" aria-labelledby="dropdownMenuLink" style="position: absolute;min-width: 55px;">
                                    <div class="search-result">
                                        <button type="button" class="link-button small text-light edit_post" value="<?php echo $post_id ?>" data-bs-toggle="modal" data-bs-target="#update_post">Edit</button>
                                    </div>
                                    <div class="search-result">
                                        <button class="link-button small text-light delete_post" value="<?php echo $post_id ?>">Delete</button>
                                    </div>
                                </div>
                            </div>

                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row my-4 px-3"><?php echo $content ?></div>
                <div class=""><img class="img-fluid" src="<?php echo $post_image ?>" alt=""></div>
                <div class="row ms-2 count_post_likes">
                    <?php
                    if ($liked_by_me_flag && $post_likes_num == 1) {
                        echo "You liked this";
                    } else if ($liked_by_me_flag && $post_likes_num > 1) {
                        echo "You, and " . ($post_likes_num - 1) . " people liked this";
                    } else if (!$liked_by_me_flag && $post_likes_num > 0) {
                        echo $post_likes_num . " people liked this";
                    } else if (!$liked_by_me_flag && $post_likes_num == 0) {
                        echo "Be the first to like this";
                    }
                    ?>
                </div>
                <div class="row justify-content-center align-itmes-center">
                    <?php
                    $likes_query = $con->query("SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'");
                    if ($likes_query->num_rows > 0) { ?>
                        <div class="col-6 btn btn-outline-light bg-warning p-0">
                            <button type="button" class="like_post btn text-secondary form-control" value="<?php echo $post_id ?>">
                                <p class="text-secondary m-0">Unlike</p>
                            </button>
                        </div>
                    <?php
                    } else { ?>
                        <div class="col-6 btn btn-outline-light p-0">
                            <button type="button" class="like_post btn text-secondary form-control" value="<?php echo $post_id ?>"><span class="fa fa-thumbs-up" style="font-size:22px;"> Like</span></button>
                        </div>
                    <?php

                    }

                    ?>

                    <div class="col-6 btn btn-outline-light p-0">
                        <button type="button" class="make_a_comment btn text-secondary form-control" value="<?php echo "c" . $post_id ?>">
                            <p class="text-secondary m-0">Comment</p>
                        </button>
                    </div>
                </div>
                <!-- add a comment -->
                <div id="comments" class="row">
                    <div class="comment_section p-0">
                        <div class="row border justify-content-between align-items-center py-3 my-3 add_comment_form d-none">
                            <div class="col-lg-10 col-md-8 col-8">
                                <textarea data-postid="<?php echo $post_id ?>" class="form-control comment_content" name="comment" style="height: 16px;" placeholder="Enter a comment"></textarea>
                            </div>
                            <div class="col-lg-2 col-md-4 col-4 text-center">
                                <button type="button" class="btn btn-secondary btn-sm add_comment" value="<?php echo $post_id ?>">Comment</button>
                            </div>
                        </div>
                        <?php
                        $comment = $con->query("SELECT * FROM comments LEFT JOIN users ON comments.posted_by = users.user_id WHERE post_id = '$post_id' ORDER BY comments.comment_id");
                        while ($row = $comment->fetch_assoc()) {
                            $comment_id = $row["comment_id"];
                            $comment_content = $row["comment_content"];
                            $comment_date = $row["comment_date"];
                            $user_image = $row["profile_picture"];
                            $commented_by = $row["firstname"] . " " . $row["lastname"];
                            $commented_by_id = $row["posted_by"];

                            show_comment($user_image, $commented_by, $comment_date, $comment_id, $comment_content, $user_id, $commented_by_id);
                        } ?>

                    </div>
                </div>
            </div>
    <?php }
        if ($show_post_customize == "my_posts") {
            break;
        }
    } while ($friend = $friends->fetch_assoc());
}

function show_comment($user_image, $commented_by, $comment_date, $comment_id, $comment_content, $user_id, $commented_by_id)
{
    ?>
    <div class="row mt-1 comment_to_remove">
        <div class="col-xl-1 col-lg-2 col-md-2 col-2"><img class="img-size ms-0 rounded-circle img-comment" src="<?php echo $user_image ?>" alt=""></div>
        <div class="col-xl-11 col-lg-10 col-md-10 col-10 make-space ps-1">
            <div class="bg-light rounded me-2 p-1">
                <p class="mb-0 text-capitalize"><strong><?php echo $commented_by ?></strong></p>
                <div id="new_comment" class="d-none" data-id="<?php echo "div" . $comment_id ?>">
                    <textarea class="form-control" id="new_comment_content" rows="1" placeholder="Enter a comment" data-id="<?php echo "textarea" . $comment_id ?>"></textarea>
                    <button class="link-button small done_comment_edit" value="<?php echo $comment_id ?>">Done</button>
                    <button class="link-button small" onclick="edit_cancel()">Cancel</button>
                </div>
                <p id="old_comment" class="old_comment text-break mb-0" data-id="<?php echo "p" . $comment_id ?>"><?php echo $comment_content ?></p>
            </div>
            <div class="d-flex me-2 justify-content-between">
                <div>
                    <?php
                    if ($user_id == $commented_by_id) {
                    ?>
                        <span class="small"><button class="link-button ps-0" id="edit_comment" onclick="edit_comment(this.value)" value="<?php echo $comment_id ?>" data-id="<?php echo "edit_btn" . $comment_id ?>">Edit</button></span>
                        <span class="small"><button class="link-button small delete_comment" value="<?php echo $comment_id ?>" data-id="<?php echo "delete_btn" . $comment_id ?>">Delete</button></span>
                    <?php } ?>
                </div>
                <span class="small" data-id="<?php echo "date" . $comment_id ?>"><?php echo $comment_date ?></span>
            </div>
        </div>
    </div>
<?php
}
?>