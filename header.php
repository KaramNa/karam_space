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
    $request_to_id = $_GET["id"];
    $current_user = $_SESSION["user_id"];
    $query = $con->query("SELECT * FROM users WHERE user_id = '$request_to_id'");
    $user = $query->fetch_assoc();
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