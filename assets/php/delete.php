<?php 
// inlcudes connection to database
    include('connect.php');

// Logic Respoinsible for deleting content
    if(isset($_GET['deleteid'])) {
        $id = $_GET['deleteid'];

        $sql = "DELETE FROM `notes` WHERE notes_id = $id";
        $result = mysqli_query($con, $sql);

        if(!$result) {
            die(mysqli_error($con));
        } else {
            header('location: ../../index.php');
            exit();
        }
    }

// Logic responsible for deleting users and their notes
if (isset($_GET['delete_userid'])) {
    $user_id = $_GET['delete_userid'];

    // Delete notes associated with the user
    $sql_notes = "DELETE FROM `notes` WHERE user_id = $user_id";
    $result_notes = mysqli_query($con, $sql_notes);

    if (!$result_notes) {
        die("Error deleting notes: " . mysqli_error($con));
    }

    // Delete the user
    $sql_user = "DELETE FROM `users` WHERE user_id = $user_id";
    $result_user = mysqli_query($con, $sql_user);

    if (!$result_user) {
        die("Error deleting user: " . mysqli_error($con));
    } else {
        header('location: ../../admin.php');
        exit();
    }
}

// Checks if a user is logged in 
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
?>