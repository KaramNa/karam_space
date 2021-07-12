<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com"> 
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Karam Space</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/dropzone.js"></script>
</head>

<body class="bg-light">
    <?php
    include("session.php");
    $current_user = $_SESSION["user_id"];
    ?>
    <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fs-6" href="home.php">KARAM SPACE</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul id="tabs"  class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php"><i class="fa fa-home"></i></a>
                    </li>
                    <li class="dropdown nav-item">
                        <a href="#" class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="friend_request_area">
                            <span id="unseen_friend_request_area"></span>
                            <i class="fa fa-user-plus fa-2" aria-hidden="true"></i>
                            <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu bg-dark" id="friend_request_list" aria-labelledby="dropdownMenuLink" style="position: absolute;width: 190px;">
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-bell"></i></a>
                    </li>
                    <li class="nav-item text-capitalize">
                        <a class="nav-link" href="profile.php?id=<?php echo $_SESSION['user_id'] ?>"><?php echo $firstname ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fa fa-sign-out-alt"></i></a>
                    </li>
                </ul>

                <ul id="search_box" class="navbar-nav">
                    <li class="nav-item d-flex align-items-center position-relative">
                        <br>
                        <div id="search_input" class="dropdown d-none">
                            <input type="text" class="" placeholder="Search" name="search" id="search" data-bs-toggle="dropdown" aria-expanded="false" id="search_results">
                            <div class="dropdown-menu bg-dark" id="search_results" aria-labelledby="dropdownMenuLink" style="position: absolute;width: 190px;"></div>
                        </div>
                        <a id="search_karam_space" class="nav-link"><i class="fa fa-search"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>