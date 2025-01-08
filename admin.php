<?php
session_start();
include('assets/php/connect.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: assets/php/admin-login.php");
    exit();
}

// Sweet alert if login is successful
if (isset($_SESSION['login_success'])) {
    echo '
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "success",
                title: "Login Successful",
                text: "Welcome to your admin page!",
                confirmButtonText: "OK",
                confirmButtonColor: "gray",
                background: "#0e0d0d",
                color: "white",
                iconColor: "gray"
            });
        });
    </script>';
    unset($_SESSION['login_success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/note.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Admin Page | Edit Password</title>
</head>
<body class="bg-dark">
    <div class="containers">
        <div class="header">
            <h1>Admin Page</h1>
            <a class="save" href="assets/php/logout.php" style="text-decoration: none; color: black;">Logout</a>
        </div>

        <h3 style="color: white;">Lists of Accounts:</h3>
        <div class="search">
             <input type="text" placeholder="Type to search..." id="search-input">
        </div>
        

        <?php
        $confirm_del = 'Are you sure you want to delete this account?';

        $sql = "SELECT * FROM users";
        $result = mysqli_query($con, $sql);

        if ($result) {
            echo '<table class="table table-bordered table-hover">';
            echo '<thead>';
            echo '<tr><th>#</th><th>Username</th><th>Password</th><th>Edit Password</th><th>Delete</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            $counter = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $counter++;
                $id = $row["user_id"];
                $username = $row["username"];

                echo "
                    <tr>
                        <th>$counter</th>
                        <td>$username</td>
                        <td>********</td>
                        <td>
                            <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editModal$id'>Edit</button>
                        </td>
                        <td>
                            <form action='assets/php/delete.php?delete_userid=$id' method='POST'>
                                <button type='submit' class='btn btn-danger' onclick='return confirm(\"$confirm_del\")'>Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Password Modal -->
                    <div class='modal fade' id='editModal$id' tabindex='-1' aria-labelledby='editModalLabel$id' aria-hidden='true'>
                        <div class='modal-dialog'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='editModalLabel$id'>Edit Password for $username</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <form action='assets/php/update-password.php' method='POST'>
                                    <div class='modal-body'>
                                        <input type='hidden' name='id' value='$id'>
                                        <div class='mb-3'>
                                            <label for='newPassword$id' class='form-label'>New Password</label>
                                            <input type='password' class='form-control' name='new_password' id='newPassword$id' required>
                                        </div>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                        <button type='submit' class='btn btn-primary'>Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                ";
            }

            echo '</tbody>';
            echo '</table>';

            echo '</tbody>';
            echo '</table>';

          }
        ?>
    </div>

    <script>
    document.getElementById('search-input').addEventListener('input', function () {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');  // Target the table rows directly

        rows.forEach(row => {
            const usernameCell = row.querySelector('td:nth-child(2)');  // Get the username cell (2nd column)
            if (usernameCell) {
                const username = usernameCell.textContent.toLowerCase();
                if (username.includes(searchValue)) {
                    row.style.display = '';  // Show row
                } else {
                    row.style.display = 'none';  // Hide row
                }
            }
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
