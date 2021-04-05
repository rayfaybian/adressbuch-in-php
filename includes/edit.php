<?php

include_once "dbhandler.php"; //using database connection file here

$id = $_GET['id']; //get id through query string

$qry = mysqli_query($conn, "select * from adressbuch where id='$id'"); //select query

$data = mysqli_fetch_array($qry); //fetch data

$herr = "";
$frau = "";

if ($data['anrede'] == "Herr") {
    $herr = "selected";
}
if ($data['anrede'] == "Frau") {
    $frau = "selected";
}

if (isset($_POST['update'])) //when click on Update button
{
    $anrede = $_POST['anrede'];
    $vorname = $_POST['vorname'];
    $nachname = $_POST['nachname'];
    $str = $_POST['str'];
    $stadt = $_POST['stadt'];
    $tel = $_POST['tel'];

    $edit = mysqli_query(
        $conn,
        "update adressbuch set anrede='$anrede',
        vorname='$vorname',
        nachname='$nachname',
        str='$str',
        stadt='$stadt',
        telefon='$tel'
        where id='$id'"
    );

    if ($edit) {
        mysqli_close($conn); //close connection
        header("location:../index.php"); //redirects to main page
        exit;
    } else {
        echo mysqli_error($error);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../style.css" />
    <title>Kontakt Update</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
</head>

<body>

    <header>
        <h1>Bearbeiten</h1>
    </header>

    <form class="input-area" method="POST">
        <select name="anrede" selected="<?php echo $data['anrede'] ?>">
            <option name="Anrede" selected disabled hidden>Anrede</option>
            <option name="Herr" value="Herr" <?php echo $herr ?>>Herr</option>
            <option name="Frau" value="Frau" <?php echo $frau ?>>Frau</option>
        </select>
        <input type="text" name="vorname" value="<?php echo $data['vorname'] ?>" placeholder="Vorname" />
        <input type="text" name="nachname" value="<?php echo $data['nachname'] ?>" placeholder="Nachname" />
        <input type="text" name="str" value="<?php echo $data['str'] ?>" placeholder="Straße" />
        <input type="text" name="stadt" value="<?php echo $data['stadt'] ?>" placeholder="Stadt" />
        <input type="text" name="tel" value="<?php echo $data['telefon'] ?>" placeholder="Tel" />

        <button type="submit" name="update" value="Update">Speichern</button>
    </form>

</body>