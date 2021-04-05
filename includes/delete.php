<?php

include_once "dbhandler.php"; //using database connection file here

$id = $_GET['id']; //get id through query string

$del = mysqli_query($conn, "delete from adressbuch where id = '$id'"); //delete query

if ($del) {
    mysqli_close($conn); //close connection
    header("location:../index.php"); //redirects to main page
    exit;
} else {
    echo "Error deleting record"; //display error message if not delete
}
