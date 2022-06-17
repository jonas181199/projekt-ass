<?php
   include_once '../includes/dbh.inc.php';
   session_start();

   if (!isset($_POST['bPrüfen'])) {
      header('Location: Bestellung.php');
      exit;
  }
?>

<!-- Julian Alber -->
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
         //Sämtliche Bestellpositionen durchgehen
         for ($i = 1; $i <= $_SESSION['anzPosition']; $i++){

            $sgname   = "gname" . $i;
            $shname   = "hname" . $i;
            $smenge   = "menge" . $i;
            $sbestand = "sbestand" . $i;
            $smid     = "mid";
            
            $_SESSION[$sgname] = mysqli_real_escape_string($conn, $_POST[$sgname]);
            $_SESSION[$shname] = mysqli_real_escape_string($conn, $_POST[$shname]);
            $_SESSION[$smenge] = mysqli_real_escape_string($conn, $_POST[$smenge]);

            //Prüfen, ob alle Felder befüllt wurden
            if(!isset($_POST[$sgname]) || strlen($_POST[$sgname]) == 0 || 
               !isset($_POST[$shname]) || strlen($_POST[$shname]) == 0 || 
               !isset($_POST[$smenge]) || strlen($_POST[$smenge]) == 0){
               
                  echo "Bestellposition" . $i . ": ";
                  echo "Bitte füllen Sie die erforderlichen Felder aus!";
                  return;
            }
            
            //Stored Function "BestellungPruefen" aufrufen
            //Diese prüft, ob die vorhandene Lagermenge ausreicht und ob die Getränk-Hersteller Kombination existiert
            //Im Fehlerfall wird eine 0 zurückgegeben
            $stmt = $conn->prepare("SELECT BestellungPruefen(?, ?, ?, ?) AS BestellungPruefen");
            $stmt->bind_param("ssis", $_SESSION[$shname], $_SESSION[$sgname], $_SESSION[$smenge], $_SESSION[$smid]); 
            $stmt->execute();
            $result = $stmt->get_result();
            $prErfolgreich = $result->fetch_object();

            if($prErfolgreich->BestellungPruefen == 0){
               echo "Die Prüfung ist fehlgeschlagen!";
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