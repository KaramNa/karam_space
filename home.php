<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Karam Space</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>

    <?php
    include("session.php");
    include("database-config.php");
    ?>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">KARAM SPACE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="dropdown nav-item">
                        <a href="#" class="dropdown-toggle nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="friend_request_area">
                            <span id="unseen_friend_request_area"></span>
                            <i class="fa fa-user-plus fa-2" aria-hidden="true"></i>
                            <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu bg-dark" id="friend_request_list" aria-labelledby="dropdownMenuLink">

                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">Home</a>
                    </li>
                    <li class="nav-item text-capitalize">
                        <a class="nav-link" href="profile.php?id=<?php echo $_SESSION['user_id'] ?>"><?php echo $firstname . " " . $lastname ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Photos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Log out</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <form method="POST">
                            <input type="text" placeholder="Search" name="search" id="search">
                            <div id="search_result" class="text-light bg-dark" style="position: absolute;width: 185px;z-index: 1001;"></div>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_FILES["upload_user_image"])) {
            $target_dir = "images/profile_pictures/";
            $target_file = $target_dir . basename($_FILES["upload_user_image"]["name"]);
            if (move_uploaded_file($_FILES["upload_user_image"]["tmp_name"], $target_file)) {
                $location = "images/profile_pictures/" . $_FILES["upload_user_image"]["name"];
                $user_id = $_SESSION["user_id"];
                $con->query("UPDATE users SET profile_picture = '$location' WHERE user_id = '$user_id'");
            }
        }
    }
    ?>
    <!-- user info -->
    <?php
    $user_id = $_SESSION["user_id"];
    $location = $con->query("SELECT profile_picture FROM users WHERE user_id='$user_id'")->fetch_assoc()["profile_picture"];
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
                <?php
                $friends = $con->query("
                        SELECT * FROM friend_request WHERE request_from_id = '$user_id' OR request_to_id = '$user_id' 
                        AND request_status = 'confirm'");
                $flag = 0;
                do {
                    if ($flag == 0) {
                        $friend_id = $user_id;
                        $flag = 1;
                    } elseif ($frined["request_from_id"] == $user_id) {
                        $friend_id = $frined["request_to_id"];
                    } elseif ($frined["request_to_id"] == $user_id) {
                        $friend_id = $frined["request_from_id"];
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
                ?>
                        <!-- show posts -->
                        <div class="border container-fluid p-0 mt-3">
                            <div class="row mt-3">
                                <div class="col-xl-1 col-lg-3 col-md-3 col-2">
                                    <img class="img-size rounded-circle ms-3" src="<?php echo $user_image ?>" alt="">
                                </div>
                                <div class="col-xl-9 col-lg-7 col-md-7 col-8 ps-1 d-flex flex-column justify-content-center">
                                    <div class="text-capitalize"><?php echo $posted_by ?></div>
                                    <span><?php echo $post_date ?></span>
                                </div>

                                <div class="col-2 d-flex justify-content-end">
                                    <?php
                                    if ($user_id == $posted_by_id) {
                                    ?>
                                        <a href="delete_post.php<?php echo '?id=' . $post_id ?>" class="nav-link text-secondary">X</a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row my-4 px-4"><?php echo $content ?></div>
                            <div class=""><img class="img-fluid" src="<?php echo $post_image ?>" alt=""></div>
                            <!-- add a comment -->
                            <div id="comments" class="row mt-3">
                                <div class="add_comment_form">
                                    <div class="row border align-items-center py-3 my-3 mx-1">
                                        <div class="col-lg-10 col-md-8 col-9 pe-0">
                                            <textarea data-postid="<?php echo $post_id ?>" class="form-control comment_content" name="comment" style="height: 16px;"  placeholder="Enter a comment"></textarea>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-3 ps-0">
                                            <button type="button" class="btn btn-secondary btn-sm add_comment ms-2" value="<?php echo $post_id ?>">Comment</button>
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
                                    ?>
                                        <!-- show comments -->
                                        <div class="row mt-1 comment_to_remove">
                                            <div class="col-xl-1 col-lg-2 col-md-2 col-2"><img class="img-size rounded-circle ms-3 img-comment" src="<?php echo $user_image ?>" alt=""></div>
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

                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                <?php }
                } while ($frined = $friends->fetch_assoc()); ?>
                <!-- End while loop -->
            </div>
        </div>
    </div>
    <footer class="text-center text-light bg-dark fixed-bottom">&copy; 2021 Copyright: <strong>Karam Nassar</strong></footer>


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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <script>
        $(document).ready(function() {
            $("#search").keyup(function() {
                var query = $(this).val();
                if (query != "") {
                    $.ajax({
                        url: "search_action.php",
                        method: "POST",
                        data: {
                            query: query
                        },
                        success: function(data) {
                            $("#search_result").html(data);
                        }
                    });
                } else {
                    $("#search_result").html("");
                }

            });
            $(".add_comment").click(function() {
                var action = "add_comment";
                var thisbtn = $(this).parents(".add_comment_form");
                comment_content = $(this).parent().siblings().find(".comment_content");
                $.ajax({
                    url: "friend_request.php",
                    method: "POST",
                    data: {
                        action: action,
                        post_id: $(this).val(),
                        comment_content: comment_content.val()

                    },
                    success: function(data) {
                        thisbtn.append(data);
                        comment_content.val("");
                    }
                });

            });
            $(".done_comment_edit").click(function() {
                var action = "edit_comment";
                var comment_id = $(this).val();
                var new_comment_content = $(this).siblings("textarea");
                var edited_paragraph = $(this).parent().parent().find(".old_comment");
                $.ajax({
                    url: "friend_request.php",
                    method: "POSt",
                    data: {
                        action: action,
                        comment_id: comment_id,
                        new_comment_content: new_comment_content.val()
                    },
                    success: function(data) {
                        edited_paragraph.html(data);
                        edit_done();
                    }
                });
            });
            $(".delete_comment").click(function() {
                console.log("dddddddd");
                var action = "delete_comment";
                var comment_id = $(this).val();
                var remove_comment = $(this).parents(".comment_to_remove");
                $.ajax({
                    url: "friend_request.php",
                    method: "POSt",
                    data: {
                        action: action,
                        comment_id: comment_id,
                    },
                    success: function(data) {
                        remove_comment.html("");
                    }
                });
            });
            var friend_request_seen = false;
            $("#friend_request_area").click(function() {
                if (!friend_request_seen) {
                    load_friends_request_list();
                    friend_request_seen = true;
                }
            });

            function count_un_seen_friend_request() {
                var action = 'count_un_seen_friend_request';

                $.ajax({
                    url: "friend_request.php",
                    method: "POST",
                    data: {
                        action: action
                    },
                    success: function(data) {
                        if (data > 0) {
                            $('#unseen_friend_request_area').html('<span class="label text-danger">' + data + '</span>');
                            friend_request_seen = false;
                        }
                    }
                })
            }
            count_un_seen_friend_request();
            setInterval(function() {
                count_un_seen_friend_request();
            }, 5000);

            function load_friends_request_list() {
                var action = "load_friend_request_list";
                $.ajax({
                    url: "friend_request.php",
                    method: "POST",
                    data: {
                        action: action
                    },
                    beforeSend: function() {
                        $('#friend_request_list').html('<li align="center"><i class="fa fa-circle-o-notch fa-spin"></i></li>');

                    },
                    success: function(data) {
                        console.log(data);
                        $("#friend_request_list").html(data);
                        remove_friend_request_count();
                    }

                });
            }


            function remove_friend_request_count() {
                var action = "remove_friend_request_count";
                $.ajax({
                    url: "friend_request.php",
                    method: "POST",
                    data: {
                        action: action
                    },
                    success: function(data) {
                        $("#unseen_friend_request_area").html("");

                    }
                });
            }
        }); //Document ready
    </script>
</body>

</html>