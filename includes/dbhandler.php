<?php

include_once './config/dbConfig.php';

$dbServername = DBHOST;
$dbUsername = DBUSER;
$dbPassword = DBPWD;
$dbName = DBNAME;

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

/* check connection */
if (mysqli_connect_errno()) {
    echo ("Connect failed:" . mysqli_connect_error());
    exit();
}

/* change character set to utf8mb4 */
if (!mysqli_set_charset($conn, "utf8mb4")) {
    echo ("Error loading character set utf8mb4:" . mysqli_error($conn));
    exit();
}
//echo mysqli_character_set_name($conn);


//github test