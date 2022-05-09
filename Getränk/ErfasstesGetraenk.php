<!-- Jonas Schirm -->
<?php

include_once 'includes/dbh.inc.php'

?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Hinzufügen eines Getränks</title>
   </HEAD>
   <BODY>
      <!-- HTML-Körper -->
      <?php
         $gname = mysqli_real_escape_string($conn, $_POST['gname']);
         $ghersteller = mysqli_real_escape_string($conn, $_POST['ghersteller']);
         $kategorie = mysqli_real_escape_string($conn, $_POST['kategorie']);
         $preis = mysqli_real_escape_string($conn, $_POST['preis']);

         //Prüfen, ob alle Felder befüllt
         if(empty($gname) || empty($ghersteller) || empty($kategorie) || empty($preis)){
               echo "Bitte füllen Sie die erforderlichen Felder aus!";
               return;
         }

         $sql = "SELECT insertgetraenke('$ghersteller', '$gname', '$kategorie', $preis)";

         // /* $sql = "insert into getraenke (gname, ghersteller, kategorie, preis) values ('" . $gname. "', '" . $ghersteller. "', '" . $kategorie. "', '" . $preis. "')";   */
         // if ($conn->query($sql) == false){
         //    echo "Fehler <br>";
         //    if(mysqli_errno($conn) == 1062){ //Catch Duplicate Key
         //       echo "Das Getränk ist bereits vorhanden";
         //    } else {
         //       echo $conn->error;
         //    }
         // }
         // else {
         //    echo "Das Getränk wurde erfolgreich der Datenbank hinzugefügt";
         // }
         // $conn->close(); */
         ?>

      <form action="Getraenkeerfassen.php">
         <p>
            <input type="submit" name="gerfassen" value="Hier können Sie ein weiteres Getränk erfassen">
         </p>
      </form>

      <form action="Markt.php">
         <p>
            <input type="submit" name="markt" value="Zurück zu den Funktionen des Markts">
         </p>
      </form>


   </BODY>
</HTML>