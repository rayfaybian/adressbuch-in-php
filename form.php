<?php

include_once "./includes/dbHandler.php";
include_once "./includes/validator.php";

$validator = new Validator();

/*SQL QUERY TO EDIT CONTACT*/
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $qry = mysqli_query(dbConnect(), "select * from adressbuch where id=$id");
    $data = mysqli_fetch_array($qry);
}

/*INSERT & UPDATE DB*/
if (isset($_POST['save'])) {

    $id = 0;

    if (array_key_exists("id", $_GET)) {
        $id = (int)$_GET["id"];
    }

    $conn = dbConnect();
    $data = $newData = getNewData();

    /*VALIDATE REQUIRED FIELDS*/
    if (checkRequiredFields($conn, $newData, $validator, $id)) {

        /*ESCAPE SPECIAL CHARACTERS AFTER VALIDATION*/
        $saveData = escapeString($conn, $newData);

        /*INSERT NEW RECORD WITH ID 0*/
        if ($id == 0) {
            $sql = "INSERT INTO `adressbuch` (id)
            VALUES (?);";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "SQL error";
            } else {
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
            }
            /*SELECT LAST ENTRY*/
            $sql = "SELECT id FROM adressbuch ORDER BY id DESC LIMIT 1;";
            $result = mysqli_query($conn, $sql);
            $id = mysqli_fetch_assoc($result)['id'];
            echo $id;
        }

        $sql = "UPDATE adressbuch SET anrede=?, vorname=?, nachname=?, adresse=?, stadt=?, telefon=?, email=?
                WHERE id=?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "SQL error";
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                "issssssi",
                $saveData['anrede'],
                $saveData['vorname'],
                $saveData['nachname'],
                $saveData['adresse'],
                $saveData['stadt'],
                $saveData['telefon'],
                $saveData['email'],
                $id
            );
            mysqli_stmt_execute($stmt);
        };

        /*RETURNING TO MAIN PAGE*/
        header("Location: index.php");
    }
}

/*CREATING DATA ARRAY WITH NEW/UPDATED VALUES*/
function getNewData()
{
    return array(
        "anrede" => $_POST['anrede'],
        "vorname" => $_POST['vorname'],
        "nachname" => $_POST['nachname'],
        "adresse" => $_POST['adresse'],
        "stadt" => $_POST['stadt'],
        "telefon" => $_POST['telefon'],
        "email" => $_POST['email']
    );
}

function escapeString($conn, $data)
{
    $anrede = mysqli_real_escape_string($conn, $data['anrede']);
    $vorname = mysqli_real_escape_string($conn, $data['vorname']);
    $nachname = mysqli_real_escape_string($conn, $data['nachname']);
    $adresse = mysqli_real_escape_string($conn, $data['adresse']);
    $stadt = mysqli_real_escape_string($conn, $data['stadt']);
    $telefon = mysqli_real_escape_string($conn, $data['telefon']);
    $email = mysqli_real_escape_string($conn, $data['email']);

    return array(
        "anrede" => $anrede,
        "vorname" => $vorname,
        "nachname" => $nachname,
        "adresse" => $adresse,
        "stadt" => $stadt,
        "telefon" => $telefon,
        "email" => $email
    );
}

/*CHECK IF REQUIRED FIELDS CONTAIN DATA*/
function checkRequiredFields($conn, $data, $validator, $id)
{
    if (empty($data['vorname'])) {
        $validator->setErrorVorname("Bitte Vorname angeben!");
        $validator->setErrorClassVorname();
    } else {
        $validator->validateVorname();
    }

    if (empty($data['nachname'])) {
        $validator->setErrorNachname("Bitte Nachname angeben!");
        $validator->setErrorClassNachname();
    } else {
        $validator->validateNachname();
    }

    if (empty($data['email'])) {
        $validator->setErrorEmail("Bitte Email Adresse angeben!");
        $validator->setErrorClassEmail();
    } else if (!validateMail($data['email'])) {
        $validator->setErrorEmail("Ungültige Email Adresse!");
        $validator->setErrorClassEmail();
    } else if (!checkUniqueMail($conn, $data['email'], $id)) {
        $validator->setErrorEmail("Email Adresse wird bereits verwendet!");
        $validator->setErrorClassEmail();
    } else {
        $validator->validateEmail();
    }

    if ($validator->validateInput()) {
        return true;
    }
}

/*CHECK IF EMAIL ADDRESS MATCHES THE REQUIRED REGEX PATTERN*/
function validateMail($email)
{
    $pattern = "/^[a-zA-Z0-9!#$&_*?^{}~-]+(\.?[a-zA-Z0-9!#$&_*?^{}~-]+)*@+([a-z0-9]+([a-z0-9]*)\.)+[a-zA-Z]+$/i";
    return preg_match($pattern, $email);
}

/*CHECK IF MAIL ALREADY EXISTS*/
function checkUniqueMail($conn, $email, $id)
{
    $qry = mysqli_query($conn, "select * from adressbuch where email='$email' AND NOT id= $id");
    $result = mysqli_fetch_array($qry);

    if ($result == 0) {
        return true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="styles/style.css" />
    <title>Kontakt Update</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
</head>

<body>
    <div class="form-background">
        <header>
            <?php
            if (isset($data)) {
                echo "<h1>Kontakt bearbeiten</h1>";
            } else {
                echo "<h1>Neuer Kontakt</h1>";
            } ?>
        </header>

        <form class="input-area" method="POST">
            <select name="anrede">
                <?php
                $sql = "SELECT * FROM anrede";
                $result = mysqli_query(dbConnect(), $sql);
                $resultCheck = mysqli_num_rows($result);
                if ((isset($data['anrede']) &&  ($data['anrede']) == 0) || (!isset($data))) {
                    echo "<option selected hidden>Anrede</option>";
                }

                if ($resultCheck > 0)
                    while ($row = mysqli_fetch_assoc($result)) {

                        if (isset($data['anrede']) && ($data['anrede'] == $row['anredeID'])) {
                            echo "<option value=" . $row['anredeID'] . " selected >" . $row['anredeText'] . "</option>";
                        } else {
                            echo "<option value=" . $row['anredeID'] . ">" . $row['anredeText'] . "</option>";
                        }
                    }
                ?>
            </select>

            <input type="text" class="<?php echo $validator->getErrorClassVorname() ?>" name="vorname" value="<?php if (isset($data)) {
                                                                                                                    echo $data['vorname'];
                                                                                                                } else if (isset($newData)) {
                                                                                                                    echo $newData['vorname'];
                                                                                                                } ?>" placeholder="Vorname" />

            <input type="text" name="nachname" class="<?php echo $validator->getErrorClassNachname() ?>" value="<?php if (isset($data)) {
                                                                                                                    echo $data['nachname'];
                                                                                                                } else if (isset($newData)) {
                                                                                                                    echo $newData['nachname'];
                                                                                                                } ?>" placeholder="Nachname" />
            <input type="text" name="adresse" value="<?php if (isset($data)) {
                                                            echo $data['adresse'];
                                                        } else if (isset($newData)) {
                                                            echo $newData['adresse'];
                                                        } ?>" placeholder="Straße" />
            <input type="text" name="stadt" value="<?php if (isset($data)) {
                                                        echo $data['stadt'];
                                                    } else if (isset($newData)) {
                                                        echo $newData['stadt'];
                                                    } ?>" placeholder="Stadt" />
            <input type="text" name="telefon" value="<?php if (isset($data)) {
                                                            echo $data['telefon'];
                                                        } else if (isset($newData)) {
                                                            echo $newData['telefon'];
                                                        } ?>" placeholder="Telefon" />

            <input type="text" name="email" class="<?php echo $validator->getErrorClassEmail() ?>" value="<?php if (isset($data)) {
                                                                                                                echo $data['email'];
                                                                                                            } else if (isset($newData)) {
                                                                                                                echo $newData['email'];
                                                                                                            } ?>" placeholder="E-Mail" />
            <div class="error-label">
                <label><?php echo $validator->getErrorVorname() ?></label>
                <label><?php echo $validator->getErrorNachname() ?></label>
                <label><?php echo $validator->getErrorEmail() ?></label>
            </div>

            <?php
            echo "<button class=save-button type=submit name=save value=save>Speichern</button>";
            ?>
        </form>
        <button class="abort-button" onclick=location.href='index.php'>Abbrechen</button>
    </div>
</body>