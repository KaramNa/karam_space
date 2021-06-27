<?php
include("session.php");
include("database-config.php");
$request_to_id = $_GET["id"];
$request_from_id = $_SESSION["user_id"];
$query = $con->query("SELECT * FROM users WHERE user_id = '$request_to_id'");
$user = $query->fetch_assoc();
?>
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

    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">KARAM SPACE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">Home</a>
                    </li>
                    <li class="nav-item text-capitalize">
                        <a class="nav-link" href="profile.php?id=<?php echo $request_from_id ?>"><?php echo $firstname . " " . $lastname ?></a>
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
    <div class="container mt-5">
        <div class="row text-center">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <img class="rounded-circle img-fluid mt-5" src="<?php echo $user['profile_picture'] ?>" alt="">
                <?php
                if ($request_from_id != $request_to_id) {
                    $query_sent = "SELECT * FROM friend_request WHERE
                                request_from_id = '$request_from_id' AND request_to_id = '$request_to_id'";
                    $query_recieve = "SELECT * FROM friend_request WHERE 
                                request_from_id = '$request_to_id' AND request_to_id = '$request_from_id'";
                    $sent_result = $con->query($query_sent);
                    $recieve_result =  $con->query($query_recieve);
                    $friends = false;
                    $friend_request_id = 0;
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
            <div class="col-md-4">
                <p>Full Name: <b class="text-capitalize"><?php echo $user["firstname"] . " " . $user["lastname"] ?></b></p>
                <p>Email Address: <b class="text-capitalize"><?php echo $user["email"] ?></b></p>
                <p>Gender: <b class="text-capitalize"><?php echo $user["gender"] ?></b></p>
                <p>Birthday: <b class="text-capitalize"><?php echo $user["birthday"] ?></b></p>
            </div>
            <div class="col-md-8">
                <?php $request_to_id = $_SESSION["user_id"];
                $query = $con->query("SELECT * FROM users JOIN posts ON users.user_id=$request_to_id AND posts.user_id=$request_to_id ORDER BY post_id DESC");
                while ($row = $query->fetch_assoc()) {
                    $posted_by = $row["firstname"] . " " . $row["lastname"];
                    $post_image = $row["post_image"];
                    $user_image = $row["profile_picture"];
                    $content = $row["post_content"];
                    $post_id = $row["post_id"];
                    $post_date = $row["post_date"];
                ?>
                    <!-- show posts -->
                    <div class="border p-3 mb-3">
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
        </div>
    </div>
    </div>



    <script>
        $(document).ready(function() {
            $("#add_friend").click(function() {
                var request_to_id = <?php echo $request_to_id ?>;
                var action = "add_friend";
                $.ajax({
                    url: "friend_request.php",
                    method: "POST",
                    data: {
                        request_to_id: request_to_id,
                        action: action
                    },
                    success: function(data) {
                        location.reload();
                    }
                });

            });
            $("#cancel_request, #reject_friend,#remove_friend").click(function() {
                var request_id = <?php echo $friend_request_id ?>;
                var action = "cancel_request";

                $.ajax({
                    url: "friend_request.php",
                    method: "POST",
                    data: {
                        request_id: request_id,
                        action: action
                    },
                    success: function(data) {
                        location.reload();
                    }
                });
            });
            $("#accept_friend").click(function() {
                var request_id = <?php echo $friend_request_id ?>;
                var action = "accept_friend";
                $.ajax({
                    url: "friend_request.php",
                    method: "POST",
                    data: {
                        request_id: request_id,
                        action: action
                    },
                    success: function(data) {
                        location.reload();
                    }
                });

            });
        });
    </script>
</body>

</html>