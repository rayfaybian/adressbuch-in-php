<?php

include_once 'dbhandler.php';


$anrede = mysqli_real_escape_string($conn, $_POST['anrede']);
$vorname = mysqli_real_escape_string($conn, $_POST['vorname']);
$nachname = mysqli_real_escape_string($conn, $_POST['nachname']);
$adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
$stadt = mysqli_real_escape_string($conn, $_POST['stadt']);
$telefon = mysqli_real_escape_string($conn, $_POST['telefon']);

/*Inserting new data into table using prepared statements*/
$sql = "INSERT INTO `adressbuch` (id, anrede, vorname, nachname, adresse, stadt, telefon) 
      VALUES (NULL, ?, ?, ?, ?, ?, ?);";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "SQL error";
} else {
      mysqli_stmt_bind_param($stmt, "isssss", $anrede, $vorname, $nachname, $adresse, $stadt, $telefon);
      mysqli_stmt_execute($stmt);
}

/*Returning to main page*/
header("Location: ../index.php");
