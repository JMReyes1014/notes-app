<?php
session_start();

// Display SweetAlert if error is present in the URL
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '$error_message',
                confirmButtonText: 'OK',
                confirmButtonColor: 'gray',
                background: '#0e0d0d',
                color: 'white',
                iconColor: 'gray'
            });
        });
    </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../note.png" type="image/png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Login | notes-app </title>
</head>
<body>
<section class="vh-100" style="background-color: rgb(31, 29, 29)">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body p-5">
              <h2 class="text-uppercase text-center mb-5">Login</h2>
              <form method="post" action="login-logic.php">
                <div class="form-outline mb-4">
                  <input type="text" id="form3Example1cg" class="form-control form-control-lg" name="username"/>
                  <label class="form-label" for="form3Example1cg">Enter Username</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="password" id="form3Example4cg" class="form-control form-control-lg" name="password" />
                  <label class="form-label" for="form3Example4cg">Enter Password</label>
                </div>

                <div class="d-flex justify-content-center">
                  <input type="submit" class="btn btn-dark btn-block btn-lg" name="login" value="Login">
                </div>

                <p class="text-center text-muted mt-5 mb-0">Don't have an account? <a href="register.php" class="fw-bold text-body"><u>Register here</u></a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

 <!-- Add the success alert if it exists in the URL -->
 <?php
    if (isset($_GET['success'])) {
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: '" . htmlspecialchars($_GET['success']) . "',
                confirmButtonText: 'OK',
                confirmButtonColor: 'gray',
                background: '#0e0d0d',
                color: 'white'
            });
        </script>
        ";
    }
  ?>

</html>