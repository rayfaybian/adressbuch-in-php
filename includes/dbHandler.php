<?php

include_once './config/dbConfig.php';

function dbConnect()
{
    $dbServername = DBHOST;
    $dbUsername = DBUSER;
    $dbPassword = DBPWD;
    $dbName = DBNAME;

    $conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

    /*CHECK CONNECTION*/
    if (mysqli_connect_errno()) {
        echo("Connection failed: " . mysqli_connect_error());
        exit();
    } else {
        return $conn;
    }
}


