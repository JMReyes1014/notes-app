<?php
include('connect.php'); // Include database connection
session_start();

// Define encryption method and secret key
define('ENCRYPTION_METHOD', 'AES-256-CBC');
define('SECRET_KEY', 'I_Love_Pizza_Secret_Key');
define('SECRET_IV', 'I_Also_Like_Chocolate_Secret_IV');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $note = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    // Encrypt the note content
    $key = hash('sha256', SECRET_KEY); // Hash the key to get a 256-bit key
    $iv = substr(hash('sha256', SECRET_IV), 0, 16); // Use first 16 bytes of hash for IV
    $encrypted_note = openssl_encrypt($note, ENCRYPTION_METHOD, $key, 0, $iv);
    $encrypted_note = base64_encode($encrypted_note); // Encode to base64 for storage

    // Insert into the database
    $stmt = $con->prepare("INSERT INTO `notes` (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $encrypted_note);

    if ($stmt->execute()) {
        $_SESSION['note_added'] = true;
    } else {
        $_SESSION['note_error'] = $stmt->error;
    }

    $stmt->close();

    // Redirect back to avoid resubmission
    header("Location: ../../index.php");
    exit();
}