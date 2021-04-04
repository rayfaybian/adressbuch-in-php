<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="reset.css">
  <link rel="stylesheet" href="style.css" />
  <title>Adressbuch</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
</head>

<body>
  <div class="background">
    <header>
      <h1>Adressbuch</h1>
    </header>

    <form class="input-area" action="includes/insert.php" method="POST">
      <select name="anrede">
        <option value="" selected disabled hidden>Anrede</option>
        <option value="Herr">Herr</option>
        <option value="Frau">Frau</option>
      </select>
      <input type="text" name="vorname" placeholder="Vorname" />
      <input type="text" name="nachname" placeholder="Nachname" />
      <input type="text" name="str" placeholder="Straße" />
      <input type="text" name="stadt" placeholder="Stadt" />
      <input type="text" name="tel" placeholder="Tel" />

      <button type="submit">Speichern</button>
    </form>

    <div class="table" id="table">
      <table>
        <col />
        <col />
        <col />
        <col />
        <col />
        <colgroup span=""></colgroup>
        <thead>
          <tr>
            <th>Anrede</th>
            <th>Vorname</th>
            <th>Nachname</th>
            <th>Straße</th>
            <th>Stadt</th>
            <th>Telefon</th>
            <th colspan="2"></th>
          </tr>
        </thead>

        <?php
        include_once 'includes/dbhandler.php';
        $sql = "SELECT * FROM adressbuch; ";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);


        if ($resultCheck > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo
            "<tr>
        <td>" . $row['anrede'] . "</td>
        <td>" . $row['vorname'] . "</td>
        <td>" . $row['nachname'] . "</td>
        <td>" . $row['str'] . "</td>
        <td>" . $row['stadt'] . "</td>
        <td>" . $row['telefon'] . "</td>
        <td><a href=includes/edit.php?id=" . $row['id'] . "><i class=\"fas fa-pen\"></a></i></td>
        <td><a href=includes/delete.php?id=" . $row['id'] . "><i class=\"fas fa-trash\"></a></i></td>
      </tr>";
          }
        } ?>

      </table>
    </div>
  </div>

</body>

</html>