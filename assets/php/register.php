<?php
session_start();
include('connect.php');

// Check if user is logged in
if (isset($_SESSION['username'])) {
  header("Location: ../../index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the inputs
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password for storage
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $stmt = $con->prepare("INSERT INTO users (username, user_password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            // Redirect to login page with success message
            header("Location: login.php?success=Account created successfully. Please log in.");
            exit(); // Ensure no further code is executed after the redirect
        } else {
            $error = "Error creating account. Please try again.";
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../note.png" type="image/png">
  <!-- Bootstrap cdn css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- STYLE.CSS LINK -->
  <link rel="stylesheet" href="/assets/css/style.css">
  <title>Register | notes-app</title>
</head>

<body>

<section class="vh-100" style="background-color: rgb(31, 29, 29)">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body p-5">
              <h2 class="text-uppercase text-center mb-5">Register</h2>

              <form method="POST" action="">

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                <?php if (isset($success)) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success; ?>
                    </div>
                <?php } ?>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="text" name="username" id="form3Example1cg" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example1cg">Enter Username</label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="password" name="password" id="form3Example4cg" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example4cg">Enter Password</label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="password" name="confirm_password" id="form3Example4cdg" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example4cdg">Repeat your password</label>
                </div>

                <div class="d-flex justify-content-center">
                  <button type="submit" class="btn btn-dark btn-block btn-lg">Create Account</button>
                </div>

                <p class="text-center text-muted mt-5 mb-0">Already have an account? <a href="login.php"
                    class="fw-bold text-body"><u>Login here</u></a></p>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  <!-- Bootstrap cdn js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>
