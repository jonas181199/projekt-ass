<?php
 
 include_once 'includes/dbh.inc.php';
 session_start();

?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Lagerverwaltung</title>
   </HEAD>
   <BODY>
      <!-- HTML-Körper -->
      <?php
         $mid = mysqli_real_escape_string($conn, $_SESSION['mid']);
         $gname = mysqli_real_escape_string($conn, $_POST['gname']);
         $ghersteller = mysqli_real_escape_string($conn, $_POST['ghersteller']);
         $lagerbest = mysqli_real_escape_string($conn, $_POST['lagerbest']);

         //Prüfen, ob alle Felder befüllt
         if(empty($gname) || empty($ghersteller) || empty($lagerbest)){
            echo "Bitte füllen Sie die erforderlichen Felder aus!";
            return;
        }   

        
        $sql = "replace into lager (mid, gname, ghersteller, bestand) values ('" . $mid. "', '" . $gname. "', '" . $ghersteller. "', '" . $lagerbest. "')";
         if ($conn->query($sql) == false){
                        echo "Fehler <br>";
        }   else {
            echo "Das Getränk wurde erfolgreich der Datenbank hinzugefügt";
        }
         $conn->close();
         ?> 
         
         <form action="Lagerverwaltung.php">
         <p>
            <input type="submit" name="lagerverwaltung" value="Hier können Sie ein weitere Bestände anpassen">
         </p>
      </form>

      <form action="Markt.php">
         <p>
            <input type="submit" name="markt" value="Zurück zu den Funktionen des Markts">
         </p>
      </form>


    </BODY>
</HTML>