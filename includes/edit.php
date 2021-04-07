<?php

include_once "dbhandler.php";

$id = $_GET['id'];

$qry = mysqli_query($conn, "select * from adressbuch where id='$id'");

$data = mysqli_fetch_array($qry);

/*determine if selected option is Herr/Frau*/
$herr = "";
$frau = "";

if ($data['anrede'] == "Herr") {
    $herr = "selected";
}
if ($data['anrede'] == "Frau") {
    $frau = "selected";
}

if (isset($_POST['update'])) //when click on update button
{
    $anrede = mysqli_real_escape_string($conn, $_POST['anrede']);
    $vorname = mysqli_real_escape_string($conn, $_POST['vorname']);
    $nachname = mysqli_real_escape_string($conn, $_POST['nachname']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $stadt = mysqli_real_escape_string($conn, $_POST['stadt']);
    $telefon = mysqli_real_escape_string($conn, $_POST['telefon']);

    /*Updating data in table using prepared statements*/
    $edit = "UPDATE adressbuch SET anrede=?, vorname=?, nachname=?, adresse=?, stadt=?, telefon=? WHERE id=?";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $edit)) {
        echo "SQL error";
    } else {
        mysqli_stmt_bind_param($stmt, "ssssssi", $anrede, $vorname, $nachname, $adresse, $stadt, $telefon, $id);
        mysqli_stmt_execute($stmt);
    };
    /*Returning to main page*/
    header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/style.css" />
    <title>Kontakt Update</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
</head>

<body>
    <div class="background">
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
            <input type="text" name="adresse" value="<?php echo $data['adresse'] ?>" placeholder="Straße" />
            <input type="text" name="stadt" value="<?php echo $data['stadt'] ?>" placeholder="Stadt" />
            <input type="text" name="telefon" value="<?php echo $data['telefon'] ?>" placeholder="Telefon" />

            <button type="submit" name="update" value="Update">Speichern</button>
        </form>
    </div>
</body>