<?php
session_start();
include('assets/php/connect.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: assets/php/login.php");
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
                text: "Welcome to your note page!",
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

// Define encryption method and secret key
define('ENCRYPTION_METHOD', 'AES-256-CBC');
define('SECRET_KEY', 'your_secret_key_here');  // Replace this with your actual secret key
define('SECRET_IV', 'your_secret_iv_here');    // Replace this with your actual IV (Initialization Vector)

// Function to decrypt data
function decrypt($data) {
    $key = hash('sha256', SECRET_KEY); // Hash the key to get a 256-bit key
    $iv = substr(hash('sha256', SECRET_IV), 0, 16); // Use first 16 bytes of hash for IV
    $data = base64_decode($data);  // Decode from base64
    $decrypted = openssl_decrypt($data, ENCRYPTION_METHOD, $key, 0, $iv);
    return $decrypted;
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
    <title>Home | notes-app</title>
</head>
<body class="bg-dark">

    <div class="containers">
        <div class="header">
            <h1><?php 
            $username = $_SESSION['username'];
            echo $username . "'s Notes"; ?></h1>
            <a class="save" href="assets/php/addNote.php" style="text-decoration: none; color: black;">Add Note</a>
            <a class="save" href="assets/php/logout.php" style="text-decoration: none; color: black;">Logout</a>
        </div>

        <div class="search">
             <input type="text" placeholder="Type to search..." id="search-input">
        </div>

        <div class="notes-list">

        <?php 
            $confirm_del = 'Are you sure you want to delete this note?';

            $sql = "SELECT * FROM `notes` WHERE user_id = ? ORDER BY date DESC";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $id = $row['notes_id'];
                $content = decrypt($row['content']); // Decrypt the note content before displaying it
                $date = $row['date'];

                echo '
                <div class="note">
                    <span>' . htmlspecialchars($content) . '</span>
                    <div class="note-footer">
                        <small>' . htmlspecialchars($date) . '</small>
                        <div class="delete-icon"><a href="assets/php/delete.php?deleteid='.$id.'" style="text-decoration: none; color: black;" onclick="return confirm(\''.$confirm_del.'\')"><i class="las la-trash-alt" style="font-size: 1.3em;"></i></a></div>
                    </div>
                </div>';
            }

            $stmt->close();
        ?>

        </div>
    </div>

    <script>
        document.getElementById('search-input').addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();
            const notes = document.querySelectorAll('.notes-list .note');

            notes.forEach(note => {
                const content = note.querySelector('span').textContent.toLowerCase();
                if (content.includes(searchValue)) {
                    note.style.display = '';
                } else {
                    note.style.display = 'none';
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
