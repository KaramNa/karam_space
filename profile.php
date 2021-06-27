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
            <p>Full Name: <b class="text-capitalize"><?php echo $user["firstname"] . " " . $user["lastname"] ?></b></p>
            <p>Email Address: <b class="text-capitalize"><?php echo $user["email"] ?></b></p>
            <p>Gender: <b class="text-capitalize"><?php echo $user["gender"] ?></b></p>
            <p>Birthday: <b class="text-capitalize"><?php echo $user["birthday"] ?></b></p>
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