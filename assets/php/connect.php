<?php 

     //Connection to database
     
    $con = new mysqli('localhost', 'root', '', 'notes_app');

    if(!$con) {
    die(mysqli_error($con));
    }

?>