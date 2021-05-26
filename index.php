<?php include_once 'includes/dbHandler.php';

$conn = dbConnect();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    deleteEntry($conn, $id);
}

function deleteEntry($conn, $id)
{
    $id = (int) $id;
    mysqli_query($conn, "delete from adressbuch where id=$id");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Adressbuch</title>
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="styles/style.css" />
</head>

<body>
    <div class="background">
        <header>
            <h1>Adressbuch</h1>
        </header>

        <button class="addButton" onclick=location.href='form.php'><i class="fas fa-plus fa-sm"></i> Neuer
            Kontakt
        </button>

        <?php
        (isset($_GET['order'])) ? $order = mysqli_real_escape_string($conn, $_GET['order']) : $order = "vorname";
        (isset($_GET['sort'])) ? $sort = mysqli_real_escape_string($conn, $_GET['sort']) : $sort = "ASC";


        $sql = "SELECT * FROM adressbuch LEFT JOIN anrede ON adressbuch.anrede = anrede.anredeID ORDER BY $order $sort";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);

        $sort == "DESC" ? $sort = "ASC" : $sort = "DESC";

        /*Render Table Head*/
        echo "<div class=table id=table>
    <table>
      <thead>
        <tr>
          <th><a href=?order=anredeText&&sort=$sort>Anrede <span class='fas fa-sort'></span></a></th>
          <th><a href=?order=vorname&&sort=$sort>Vorname <span class='fas fa-sort'></span></a></th>
          <th><a href=?order=nachname&&sort=$sort>Nachname <span class='fas fa-sort'></span></a></th>
          <th><a href=?order=adresse&&sort=$sort>Adresse <span class='fas fa-sort'></span></a></th>
          <th><a href=?order=stadt&&sort=$sort>Stadt <span class='fas fa-sort'></span></a></th>
          <th>Telefon</th>
          <th>E-Mail</th>
          <th colspan=2></th>
        </tr>
      </thead>";

        /*Render Table Content if Database is not empty*/
        if ($resultCheck > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo
                "<tr>
        <td>" . $row['anredeText'] . "</td>
        <td>" . $row['vorname'] . "</td>
        <td>" . $row['nachname'] . "</td>
        <td>" . $row['adresse'] . "</td>
        <td>" . $row['stadt'] . "</td>
        <td>" . $row['telefon'] . "</td>
        <td>" . $row['email'] . "</td>
        <td><a href=form.php?id=" . $row['id'] . "><i class='fas fa-pen'></a></i></td>      
        <td><a href=index.php?id=" . $row['id'] . "><i class='fas fa-trash'></a></i></td>
      </tr>";
            }
        }
        ?>
        </table>
    </div>
    </div>
</body>

</html>