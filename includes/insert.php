<?php

include_once 'dbhandler.php';

$anrede = $_POST['anrede'];
$vorname = $_POST['vorname'];
$nachname = $_POST['nachname'];
$str = $_POST['str'];
$stadt = $_POST['stadt'];
$tel = $_POST['tel'];


$sql = "INSERT INTO `adressbuch` (`id`, `anrede`, `vorname`, `nachname`, `str`, `stadt`, `telefon`) 
      VALUES (NULL, '$anrede', '$vorname', '$nachname', '$str', '$stadt', '$tel');";
mysqli_query($conn, $sql);

header("Location: ../index.php");
