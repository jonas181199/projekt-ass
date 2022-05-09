<?php
   include_once '../includes/dbh.inc.php';
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
         for ($i = 0; $i < $_POST['anzPosition']; $i++){

            $sgname = "gname" . $i;
            $shname = "hname" . $i;
            $smenge = "menge" . $i;
            
            $gname = mysqli_real_escape_string($conn, $_POST[$sgname]);
            $hname = mysqli_real_escape_string($conn, $_POST[$shname]);
            $menge = mysqli_real_escape_string($conn, $_POST[$smenge]);
            $mid   = mysqli_real_escape_string($conn, $_POST['mid']);

            //Prüfen, ob alle Felder befüllt
            if(!isset($_POST[$sgname]) || strlen($_POST[$sgname]) == 0 || 
               !isset($_POST[$shname]) || strlen($_POST[$shname]) == 0 || 
               !isset($_POST[$smenge]) || strlen($_POST[$smenge]) == 0){
               
                  echo "Bestellposition" . $i . ": ";
                  echo "Bitte füllen Sie die erforderlichen Felder aus!";
                  return;
            }

            //Prüfen ob Getränk-Hersteller Kombination existiert
            $getraenk = $conn->query("select * from getraenke where gname = '$gname' AND ghersteller = '$hname'");
            while($s = $getraenk->fetch_object()){
               $data[] = $s;
            }
            if(empty($data)){
               echo "Bestellposition" . $i . ": ";
               echo "Dieser Hersteller bietet das ausgewählte Getränk nicht an!";
               return;
            } 
            
            //Bestand prüfen
            $bestand = $conn->query("select bestand from lager where gname = '$gname' AND ghersteller = '$hname' AND mid = '$mid'");
            while($s = $bestand->fetch_object()){
               $b[] = $s;
               if ($s->bestand < $menge){
                  echo "Bestellposition" . $i . ": ";
                  echo "Die eingegebene Menge liegt über dem Lagerbestand!";
                  echo "Aktueller Lagerbestand: " . $s->bestand;
                  return;
               }
            }
            if(empty($b)){
               echo "Bestellposition" . $i . ": ";
               echo "Die eingegebene Menge liegt über dem Lagerbestand!";
               echo "Aktueller Lagerbestand: 0";
               return;
            } 
         }  
      ?>

      <form action="Kundenanmeldung.php" method="POST" >
         <fieldset>
            <legend>Bestellung abschließen</legend>
            <p>              
               <input type="hidden" name="anzPosition" id="anzPosition" value="<?php echo $_POST['anzPosition']; ?>">
               <input type="hidden" name="mid" id="mid" value="<?php echo $_POST['mid']; ?>">
            </p>
            <p>
               <input type="submit" name="babschließen" value="Bestellung Abschließen">
            </p>
         </fieldset>
      </form>

      <form action="Bestellung.php">
         <p>
            <input type="submit" name="bestellung" value="Zurück zur Bestellung">
         </p>
      </form>
   </BODY>
</HTML>