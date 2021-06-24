<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item text-capitalize">
                        <a class="nav-link" href="profile.php"><?php echo $firstname . " " . $lastname ?></a>
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
                    <img class="img-fluid rounded-circle user-image" src="<?php echo $location ?>" alt="" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
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
            <div class="col-sm-8 mt-3">
                <div class="border p-3">
                    <!-- update status -->
                    <div>
                        <h1>Update Status</h1>
                        <form method="POST" action="newPost.php" enctype="multipart/form-data">
                            <textarea placeholder="Whats on your mind?" name="content" class="form-control" rows="5"></textarea>
                            <div class="mt-3 d-flex justify-content-between">
                                <input type="file" id="post_image" name="upload" hidden>
                                <button type="button" class="btn btn-secondary" name="fileToUpload" onclick="document.getElementById('post_image').click();">Upload photo</button>
                                <button type="submit" class="btn btn-secondary" name="Submit">Share</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                $user_id = $_SESSION["user_id"];
                $query = $con->query("SELECT * FROM users JOIN posts ON users.user_id=$user_id AND posts.user_id=$user_id ORDER BY post_id DESC");
                while ($row = $query->fetch_assoc()) {
                    $posted_by = $row["firstname"] . " " . $row["lastname"];
                    $post_image = $row["post_image"];
                    $user_image = $row["profile_picture"];
                    $content = $row["post_content"];
                    $post_id = $row["post_id"];
                    $post_date = $row["post_date"];
                ?>
                    <!-- show posts -->
                    <div class="border p-3 my-3">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class=""><img class="img-fluid rounded-circle" src="<?php echo $user_image ?>" alt=""></div>
                            </div>
                            <div class="col-sm-9 d-flex flex-column justify-content-center">
                                <div class="text-capitalize"><?php echo $posted_by ?></div>
                                <span><?php echo $post_date ?></span>
                            </div>
                            <div class="col-sm-1 ps-2"><a href="delete_post.php<?php echo '?id=' . $post_id ?>" class="nav-link text-secondary">X</a></div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="row my-4 ps-4"><?php echo $content ?></div>
                            <div class="row"><img class="img-fluid" src="<?php echo $post_image ?>" alt=""></div>
                        </div>
                        <div class="row mt-3">
                            <?php
                            $comment = $con->query("SELECT * FROM comments WHERE post_id = '$post_id' ORDER BY post_id DESC");
                            while ($row = $comment->fetch_assoc()) {
                                $comment_id = $row["comment_id"];
                                $comment_content = $row["comment_content"];
                                $comment_date = $row["comment_date"];
                            ?>
                                <!-- show comments -->
                                <div class="row mt-1">
                                    <div class="col-lg-1 col-md-2"><img class="img-fluid img-comment" src="<?php echo $user_image ?>" alt=""></div>
                                    <div class="col-lg-11 col-md-10">
                                        <div class="bg-light rounded p-1">
                                            <p class="mb-0 text-capitalize"><strong><?php echo $posted_by ?></strong></p>
                                            <div id="new_comment" class="d-none" data-id="<?php echo "div" . $comment_id ?>">
                                                <textarea class="form-control" id="new_comment_content" rows="2" placeholder="Enter a comment" data-id="<?php echo "textarea" . $comment_id ?>"></textarea>
                                                <button class="link-button small" onclick="edit_done()">Done</button>
                                                <button class="link-button small" onclick="edit_cancel()">Cancel</button>
                                            </div>
                                            <p id="old_comment" class="" data-id="<?php echo "p" . $comment_id ?>"><?php echo $comment_content ?></p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="small"><button class="link-button" id="edit_comment" onclick="edit_comment(this.value)" value="<?php echo $comment_id ?>" data-id="<?php echo "edit_btn" . $comment_id ?>">Edit</button></span>
                                                <span class="small"><a id="delete_comment" href="delete_comment.php<?php echo '?id=' . $comment_id ?>" class="link" data-id="<?php echo "delete_btn" . $comment_id ?>">Delete</a></span>
                                            </div>
                                            <span class="small" data-id="<?php echo "date" . $comment_id ?>"><?php echo $comment_date ?></span>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                            <!-- add a comment -->
                            <form action="add_comment.php" method="POST">
                                <div class="row border justify-content-center py-3 my-3 mx-1">
                                    <div class="col-lg-10  col-md-8  pe-1">
                                        <textarea class="form-control" name="comment" rows="2" placeholder="Enter a comment"></textarea>
                                        <input type="text" name="post_id" value="<?php echo $post_id ?>" hidden>
                                    </div>
                                    <div class="col-lg-2 col-md-4 mt-2">
                                        <button type="submit" class="btn btn-secondary">Comment</button>
                                    </div>
                            </form>
                        </div>
                    </div>
            </div>
        <?php } ?>
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
                console.log("dksaf");
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

            })
            

        }); //Document ready
    </script>
</body>

</html>