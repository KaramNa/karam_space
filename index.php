<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <title>Welcome to Karam Space</title>
</head>

<body>
  <section class="bg-image container-fluid">
    <div class="container">
      <div class="row align-items-center text-center title-row">
        <div class="col-md-7">
          <h1 class="display-1 text-light">Karam Space</h1>
        </div>
        <div class="col-md-5">
          <form action="" class="signin-form">
            <input class="form-control mt-4" type="email" name="email" placeholder="Enter your email address">
            <input class="form-control mt-4" type="password" name="password" placeholder="Enter your password">
            <button class="btn btn-primary  form-control mt-4" type="submit">Sign in</button>
            <a href="#" class="nav-link text-info">Forget your password?</a>
            <hr>
            <button type="button" class="btn btn-warning form-control" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Sign up</button>
          </form>
        </div>
      </div>
      <footer class="text-center text-light">&copy; 2021 Copyright: <strong>Karam Nassar</strong></footer>
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
              <form action="signup.php" method="POST">
                <div class="row">
                  <div class="col-sm-6 px-1"><input class="form-control mt-2" type="text" name="firstname" placeholder="First name"></div>
                  <div class="col-sm-6 px-1"><input class="form-control mt-2" type="text" name="lastname" placeholder="Last name"></div>
                </div>
                <div class="row">
                  <div class="px-1"><input class="form-control mt-2" type="email" name="email" placeholder="Email"></div>
                </div>
                <div class="row">
                  <div class="px-1"><input class="form-control mt-2" type="password" name="newpassword" placeholder="New password">
                  </div>
                </div>
                <div class="row">
                  <p class="mb-0 mt-2 px-1">Birthday</p>
                  <div class="col-sm-4 px-1 mt-2">
                    <div class="dropdown">
                      <button class="dropdown-toggle form-control" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="col-sm-4 px-1 mt-2">
                    <div class="dropdown">
                      <button class="dropdown-toggle form-control" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Somethin</a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="col-sm-4 px-1 mt-2">
                    <div class="dropdown">
                      <button class="dropdown-toggle form-control" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <p class="mb-0 mt-2 px-1">Gender</p>
                  <div class="col-sm-4 px-1 mt-2">
                    <div class="form-control d-flex justify-content-between align-items-center">
                      <label for="female">Female</label>
                      <input type="radio" id="female" name="gender" value="female">
                    </div>
                  </div>
                  <div class="col-sm-4 px-1 mt-2">
                    <div class="form-control d-flex justify-content-between align-items-center">
                      <label for="male">Male</label>
                      <input type="radio" id="male" name="gender" value="male">
                    </div>
                  </div>
                  <div class="col-sm-4 px-1 mt-2">
                    <div class="form-control d-flex justify-content-between align-items-center">
                      <label for="custom">Custom</label>
                      <input type="radio" id="custom" name="gender" value="custom">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <p class="term mt-2 px-1">By clicking Sign Up, you agree to <a href="" class="policy-link">Terms, Data
                      Policy</a> and <a href="" class="policy-link">Cookies Policy.</a> You may receive SMS
                    Notifications from us and can opt out any time.</p>
                </div>
                <div class="row justify-content-center mt-3">
                  <button type="submit" class="btn btn-warning form-control w-50">Sign up</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
</body>

</html>