<?php

include_once "dbhandler.php";

$id = $_GET['id'];

if (isset($id)) {
    $qry = mysqli_query($conn, "select * from adressbuch where id='$id'");
    $data = mysqli_fetch_array($qry);
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
<div class="background">
    <header>
        <h1>Bearbeiten</h1>
    </header>

    <form class="input-area" method="POST">


        <select name="anrede">
            <?php
            $sql = "SELECT * FROM anrede";
            $result = mysqli_query($conn, $sql);
            $resultCheck = mysqli_num_rows($result);

            if ($resultCheck > 0) {
                if (!isset($data)) {
                    echo "<option name= 'default' selected disabled hidden>Anrede</option>";
                }
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($data['anrede'] == $row['anredeID']) {
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


        <button type="submit"
                <?if(isset($data))
                {echo "type=submit name=update value=Update>Update</button>";}
                else {
                    echo "type=submit name=insert value=Update>Update</button>";
                }?>
    </form>
</div>
</body>