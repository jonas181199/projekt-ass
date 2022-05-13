<?php
   include_once '../includes/dbh.inc.php';
   session_start();
?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Startseite</title>
   </HEAD>
   <BODY>
      <?php      
         for ($i = 1; $i <= $_SESSION['anzPosition']; $i++){

            $sgname   = "gname" . $i;
            $shname   = "hname" . $i;
            $smenge   = "menge" . $i;
            $sbestand = "sbestand" . $i;
            $smid     = "mid";
            
            $_SESSION[$sgname]       = mysqli_real_escape_string($conn, $_POST[$sgname]);
            $_SESSION[$shname]       = mysqli_real_escape_string($conn, $_POST[$shname]);
            $_SESSION[$smenge]       = mysqli_real_escape_string($conn, $_POST[$smenge]);

            //Prüfen, ob alle Felder befüllt
            if(!isset($_POST[$sgname]) || strlen($_POST[$sgname]) == 0 || 
               !isset($_POST[$shname]) || strlen($_POST[$shname]) == 0 || 
               !isset($_POST[$smenge]) || strlen($_POST[$smenge]) == 0){
               
                  echo "Bestellposition" . $i . ": ";
                  echo "Bitte füllen Sie die erforderlichen Felder aus!";
                  return;
            }

            //Prüfen ob Getränk-Hersteller Kombination existiert
            $getraenk = $conn->query("select * from getraenke where gname = '$_SESSION[$sgname]' AND ghersteller = '$_SESSION[$shname]'");
            while($s = $getraenk->fetch_object()){
               $data[] = $s;
            }
            if(empty($data)){
               echo "Bestellposition" . $i . ": ";
               echo "Dieser Hersteller bietet das ausgewählte Getränk nicht an!";
               return;
            } 
            
            //Bestand prüfen
            if ($smenge <= 0){
               echo "Bestellposition " . $i . ": ";
               echo "Die Bestellmenge darf nicht Null sein!";
               return;
            }

            $bestand = $conn->query("select bestand from lager where gname = '$_SESSION[$sgname]' AND ghersteller = '$_SESSION[$shname]' AND mid = '$_SESSION[$smid]'");
            while($s = $bestand->fetch_object()){
               $b[] = $s;
               if ($s->bestand <  $_SESSION[$smenge]){
                  echo "Bestellposition" . $i . ": ";
                  echo "Die eingegebene Menge liegt über dem Lagerbestand!";
                  echo "Aktueller Lagerbestand: " . $s->bestand;
                  return;
               }
               $_SESSION[$sbestand] = $s->bestand - $_SESSION[$smenge];
            }
            if(empty($b)){
               echo "Bestellposition" . $i . ": ";
               echo "Die eingegebene Menge liegt über dem Lagerbestand!";
               echo "Aktueller Lagerbestand: 0";
               return;
            } 
         }  
      ?>

      <h2>Die Prüfung war erfolgreich!</h2>
      <form action="Kundenanmeldung.php" method="POST" >
         <fieldset>
            <legend>Bestellung abschließen</legend>
            <p>
               <input type="submit" name="babschließen" value="Bestellung Abschließen">
            </p>
         </fieldset>
      </form>
      <p>
         <a href="Bestellung.php">Zurück zur Bestellung</a>
      </p>
   </BODY>
</HTML>