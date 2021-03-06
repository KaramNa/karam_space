<?php
session_start();


if (isset($_SESSION['user_id'])) {
  header('location:home.php');
} else {
  $error_email = "";
  $error_password = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('database-config.php');
    $singinOk = true;
    // Signin
    if (array_key_exists('signin', $_POST)) {
      $email = $con->real_escape_string($_POST["email"]);
      $password = $con->real_escape_string($_POST["password"]);

      if ($email == "") {
        $error_email = "<label class='error'>* Required field</label>";
        $singinOk = false;
      }
      if ($password == "") {
        $error_password = "<label class='error'>* Required field</label>";
        $singinOk = false;
      }
      if ($singinOk) {
        $sql = $con->query("SELECT * FROM users WHERE email = '$email' AND password = '$password'");
        $row = $sql->fetch_assoc();
        if ($sql !== false and $sql->num_rows > 0) {
          $_SESSION["user_id"] = $row["user_id"];
          header('location:home.php');
        } else {
          $error_email = "<label class='error'>Incorrect email or password</label>";
        }
      }
    }
  }
}

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <title>Welcome to Karam Space</title>
</head>

<body>
  <section class="bg-image container-fluid">
    <div class="container">
      <div class="row align-items-center title-row">
        <div class="col-md-7">
          <h1 class="display-1 text-light">Karam Space</h1>
        </div>
        <div class="col-md-5">
          <form action="" method="POST" class="signin-form">
            <div class="mt-4">
              <?php echo $error_email ?>
              <input class="form-control" type="email" name="email" placeholder="Enter your email address">
            </div>
            <div class="mt-4">
              <?php echo $error_password ?>
              <input class="form-control" type="password" name="password" placeholder="Enter your password">
            </div>
            <button class="btn btn-primary  form-control mt-4" name="signin" type="submit">Sign in</button>
            <a id="forget" href="#" class="nav-link text-primary  text-center">Forget your password?</a>
            <hr>
            <button type="button" class="btn btn-warning form-control" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Sign up</button>
          </form>
        </div>
      </div>
      <footer class="text-center text-light fixed-bottom">&copy; 2021 Copyright: <strong>Karam Nassar</strong></footer>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Sign Up</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <form id="signup_form" action="" method="POST">
                <div class="row">
                  <div class="col-sm-6 px-1 mt-2">
                    <label id="error_firstname" class='error p-0'></label>
                    <input class="form-control" type="text" name="firstname" placeholder="First name">
                  </div>
                  <div class="col-sm-6 px-1  mt-2">
                    <label id="error_lastname" class='error p-0'></label>
                    <input class="form-control" type="text" name="lastname" placeholder="Last name">
                  </div>
                </div>
                <div class="row mt-2">
                  <label id="error_new_email" class='error p-1'></label>
                  <div class="px-1">
                    <input class="form-control" type="email" name="email" placeholder="Email">
                  </div>
                </div>
                <div class="row  mt-2">
                  <label id="error_new_password" class='error p-1'></label>
                  <div class="px-1"><input class="form-control" type="password" name="newpassword" placeholder="New password">
                  </div>
                </div>
                <div class="row">
                  <p class="mb-0 mt-2 px-1">Birthday</p>
                  <div class="col-sm-4 px-1 mt-2">
                    <select name="day" class="form-select">
                      <?php
                      $day = 1;
                      while ($day <= 31) {
                        echo "<option> $day </option>";
                        $day++;
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-sm-4 px-1 mt-2">
                    <select name="month" class="form-select">
                      <option>January</option>
                      <option>Febuary</option>
                      <option>March</option>
                      <option>April</option>
                      <option>May</option>
                      <option>June</option>
                      <option>July</option>
                      <option>August</option>
                      <option>September</option>
                      <option>October</option>
                      <option>November</option>
                      <option>December</option>
                    </select>
                  </div>
                  <div class="col-sm-4 px-1 mt-2">
                    <select name="year" class="form-select">
                      <?php
                      $year = 2021;
                      while ($year >= 1900) {
                        echo "<option> $year </option>";
                        $year--;
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <p class="mb-0 mt-2 px-1">Gender</p>
                  <label id="error_gender" class='error'></label>
                  <div class="col-sm-4 px-1 mt-2">
                    <div class="form-control d-flex justify-content-between align-items-center">
                      <label for="female">Female</label>
                      <input type="radio" id="female" name="gender" value="female" checked>
                    </div>
                  </div>
                  <div class="col-sm-4 px-1 mt-2">
                    <div class="form-control d-flex justify-content-between align-items-center">
                      <label for="male">Male</label>
                      <input type="radio" id="male" name="gender" value="male">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <p class="term mt-2 px-1">By clicking Sign Up, you agree to <a href="" class="policy-link">Terms, Data
                      Policy</a> and <a href="" class="policy-link">Cookies Policy.</a> You may receive SMS
                    Notifications from us and can opt out any time.</p>
                </div>
                <div class="row justify-content-center mt-3">
                  <button id="signup" type="submit" name="signup" class="btn btn-warning form-control w-50">Sign up</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function() {
      $("#signup_form").on("submit", function(e) {
        e.preventDefault();
        var formdata = new FormData(this);
        $.ajax({
          url: "signup.php",
          method: "POST",
          data: formdata,
          processData: false,
          cache: false,
          contentType: false,
          success: function(data) {
            var result = jQuery.parseJSON(data);
            $("#error_firstname").html(result[0]);
            $("#error_lastname").html(result[1]);
            $("#error_new_email").html(result[2]);
            $("#error_new_password").html(result[3]);
            $("#error_gender").html(result[4]);
            console.log(result);
            if (result[5]) {
              window.location.href = "home.php";
            } 
          }
        });
      });
    });
  </script>
</body>

</html>