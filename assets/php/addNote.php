<?php
session_start();
include('connect.php');

// Define encryption method and secret key
define('ENCRYPTION_METHOD', 'AES-256-CBC');
define('SECRET_KEY', 'your_secret_key_here');  // Replace this with your actual secret key
define('SECRET_IV', 'your_secret_iv_here');    // Replace this with your actual IV (Initialization Vector)

// Function to encrypt data
function encrypt($data) {
    $key = hash('sha256', SECRET_KEY); // Hash the key to get a 256-bit key
    $iv = substr(hash('sha256', SECRET_IV), 0, 16); // Use first 16 bytes of hash for IV
    $encrypted = openssl_encrypt($data, ENCRYPTION_METHOD, $key, 0, $iv);
    return base64_encode($encrypted);  // Base64 encode the result to safely store in DB
}

// Function to decrypt data
function decrypt($data) {
    $key = hash('sha256', SECRET_KEY); // Hash the key to get a 256-bit key
    $iv = substr(hash('sha256', SECRET_IV), 0, 16); // Use first 16 bytes of hash for IV
    $data = base64_decode($data);  // Decode from base64
    $decrypted = openssl_decrypt($data, ENCRYPTION_METHOD, $key, 0, $iv);
    return $decrypted;
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit'])) {
    $note = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    // Encrypt the note content
    $encrypted_note = encrypt($note);

    // Prepared statements to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO `notes` (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $encrypted_note);

    // Sweet alert for success or failure
    if ($stmt->execute()) {
        echo '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "success",
                    title: "Note Added Successfully",
                    confirmButtonText: "OK",
                    confirmButtonColor: "gray",
                    background: "#0e0d0d",
                    color: "white",
                    iconColor: "gray"
                });
            });
        </script>';
    } else {
        echo '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to add note: ' . $stmt->error . '",
                    confirmButtonText: "OK",
                    confirmButtonColor: "gray",
                    background: "#0e0d0d",
                    color: "white",
                    iconColor: "gray"
                });
            });
        </script>';
    }

    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../note.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Add Note | notes-app</title>
</head>
<body class="bg-dark">
    
    <div class="containers">
        
        <div class="header">
            <h1><a href="../../index.php" style="text-decoration:none; color: white;">Back to Notes</a></h1>
            <a class="save" href="logout.php" style="text-decoration: none; color: black;">Logout</a>
        </div>

        <div class="notes-list">

        <form class="note new" method="post" onsubmit="return validateNote()">
            <textarea 
                rows="8" 
                cols="10" 
                placeholder="Type to add a note..." 
                name="content" 
                id="noteContent"
                maxlength="200"></textarea>

            <div class="note-footer">
                <small id="remainingCount">200 Remaining</small>
                <button type="submit" name="submit" class="save">Save</button>
            </div>      
        </form>

        <script>
            const textarea = document.getElementById('noteContent');
            const remainingCount = document.getElementById('remainingCount');

            // Update remaining characters dynamically
            textarea.addEventListener('input', () => {
                const remaining = 200 - textarea.value.length;
                remainingCount.textContent = `${remaining} Remaining`;
            });

            // Prevent blank or whitespace-only submissions
            function validateNote() {
                const content = textarea.value.trim();
                if (content === "") {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Note cannot be blank or whitespace only.",
                        confirmButtonText: "OK",
                        confirmButtonColor: "gray",
                        background: "#0e0d0d",
                        color: "white",
                        iconColor: "gray"
                    });
                    return false;
                }
                return true;
            }
        </script>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>