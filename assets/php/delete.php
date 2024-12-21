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

// Checks if a user is logged in 
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
?>