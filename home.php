<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Karam Space</title>
</head>

<body>
    <?php include("session.php") ?>
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
                        <a class="nav-link" href="#"><?php echo $firstname . " " . $lastname ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Photos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Log out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-4">
                <div class="col-12 mt-3 text-center">
                    <img class="img-fluid rounded-circle" src="images/images.jpg" alt="">
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
                    <div>
                        <h1>Update Status</h1>
                        <form method="POST" action="newPost.php" enctype="multipart/form-data">
                            <textarea placeholder="Whats on your mind?" name="content" class="form-control" rows="5"></textarea>
                            <div class="mt-3 d-flex justify-content-between">
                                <input type="file" id="fileInput" name="upload" hidden>
                                <button type="button" class="btn btn-secondary" name="fileToUpload" onclick="document.getElementById('fileInput').click();">Upload photo</button>
                                <button type="submit" class="btn btn-secondary" name="Submit">Share</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                include("database-config.php");
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
                    </div>
                <?php } ?>
                <!-- End while loop -->
            </div>
        </div>
    </div>
    <footer class="text-center text-light bg-dark fixed-bottom">&copy; 2021 Copyright: <strong>Karam Nassar</strong></footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>