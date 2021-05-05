<?php

include_once "dbhandler.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

if (isset($id)) {
    $qry = mysqli_query($conn, "select * from adressbuch where id='$id'");
    $data = mysqli_fetch_array($qry);
}

if (isset($_POST['update'])) /*BESTEHENDEN EINTRAG BEARBEITEN*/ {

    $newData = getData($conn);

    /*Updating data in table using prepared statements*/
    $edit = "UPDATE adressbuch SET anrede=?, vorname=?, nachname=?, adresse=?, stadt=?, telefon=?, email=? WHERE id=?";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $edit)) {
        echo "SQL error";
    } else {
        mysqli_stmt_bind_param($stmt, "issssssi", $newData['anrede'], $newData['vorname'],
            $newData['nachname'], $newData['adresse'], $newData['stadt'], $newData['telefon'], $newData['email'], $id);
        mysqli_stmt_execute($stmt);
    };
    /*Returning to main page*/
    header("Location: ../index.php");
}


if (isset($_POST['insert'])) /*NEUEN EINTRAG SPEICHERN*/ {

    $newData = getData($conn);

    if (validateMail($newData['email'])) {

        /*Inserting new data into table using prepared statements*/
        $sql = "INSERT INTO `adressbuch` (id, anrede, vorname, nachname, adresse, stadt, telefon, email)
            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "SQL error";
        } else {
            mysqli_stmt_bind_param($stmt, "issssss", $newData['anrede'], $newData['vorname'],
                $newData['nachname'], $newData['adresse'], $newData['stadt'], $newData['telefon'], $newData['email']);
            mysqli_stmt_execute($stmt);
        }

        /*Returning to main page*/
        header("Location: ../index.php");
    } else {
        echo "ERROR: EMAIL NOT VALID";
    }

}

function getData($conn)
{
    $anrede = mysqli_real_escape_string($conn, $_POST['anrede']);
    $vorname = mysqli_real_escape_string($conn, $_POST['vorname']);
    $nachname = mysqli_real_escape_string($conn, $_POST['nachname']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $stadt = mysqli_real_escape_string($conn, $_POST['stadt']);
    $telefon = mysqli_real_escape_string($conn, $_POST['telefon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    return array("anrede" => $anrede,
        "vorname" => $vorname,
        "nachname" => $nachname,
        "adresse" => $adresse,
        "stadt" => $stadt,
        "telefon" => $telefon,
        "email" => $email);
}

function validateMail($email)
{
    $pattern = "/^[a-zA-Z0-9!#$&_*?^{}~-]+(\.?[a-zA-Z0-9!#$&_*?^{}~-]+)*@+([a-z0-9]+([a-z0-9]*)\.)+[a-zA-Z]+$/i";
    return preg_match($pattern, $email);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/style.css"/>
    <title>Kontakt Update</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>
</head>

<body>
<div class="form-background">
    <header>
        <?php
        if (isset($data)) {
            echo "<h1>Kontakt bearbeiten</h1>";
        } else {
            echo "<h1>Neuer Kontakt</h1>
    </header>";
        } ?>

        <form class="input-area" method="POST">


            <select name="anrede">
                <?php
                $sql = "SELECT * FROM anrede";
                $result = mysqli_query($conn, $sql);
                $resultCheck = mysqli_num_rows($result);

                if ($resultCheck > 0) {
                    if (!isset($data)) {
                        echo "<option value= '0' name='default' selected hidden>Anrede</option>";
                    }
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['anredeID'] == '0') {
                            echo "<option value=" . $row['anredeID'] . "selected hidden>Anrede</option>";
                        } else if ($data['anrede'] == $row['anredeID']) {
                            echo "<option value=" . $row['anredeID'] . " selected >" . $row['anredeText'] . "</option>";
                        } else {
                            echo "<option value=" . $row['anredeID'] . ">" . $row['anredeText'] . "</option>";
                        }
                    }
                }
                ?>
            </select>


            <input type="text" name="vorname" value="<?php if (isset($data)) {
                echo
                $data['vorname'];
            } ?>" placeholder="Vorname"/>
            <input type="text" name="nachname" value="<?php if (isset($data)) {
                echo $data['nachname'];
            } ?>" placeholder="Nachname"/>
            <input type="text" name="adresse" value="<?php if (isset($data)) {
                echo $data['adresse'];
            } ?>" placeholder="Straße"/>
            <input type="text" name="stadt" value="<?php if (isset($data)) {
                echo $data['stadt'];
            } ?>" placeholder="Stadt"/>
            <input type="text" name="telefon" value="<?php if (isset($data)) {
                echo $data['telefon'];
            } ?>" placeholder="Telefon"/>
            <input type="text" name="email" value="<?php if (isset($data)) {
                echo $data['email'];
            } ?>" placeholder="E-Mail"/>

            <?php
            if (isset($data)) {
                echo "<button class=save-button type=submit name=update value=Update>Speichern</button>";
            } else {
                echo "<button class=save-button type=submit name=insert value=Insert>Speichern</button>";
            }
            ?>
        </form>
</div>
</body>