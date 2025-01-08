<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $new_password = $_POST['new_password'];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $sql = "UPDATE users SET user_password = ? WHERE user_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('si', $hashed_password, $id);

    if ($stmt->execute()) {
        echo '
        <script>
            alert("Password updated successfully!");
            window.location.href = "../../admin.php";
        </script>';
    } else {
        echo '
        <script>
            alert("Failed to update password!");
            window.location.href = "../../admin.php";
        </script>';
    }

    $stmt->close();
    $con->close();
}
?>
