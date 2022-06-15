<?php
   include_once '../includes/dbh.inc.php';
   session_start();

   if (empty($_SESSION['email']) AND !isset($_POST['loginkunde']) AND !isset($_POST['registrierekunde'])) {
      header('Location: Kundenanmeldung.php');
      exit;
   }

   // ganze Prüfungen hier einfügen (methoden Aufrufe)

   if(!isset($_SESSION['email'])){
      $_SESSION['email'] = $_POST['email'];
   } 
?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Die Bestellung war erfolgreich</title>
   </HEAD>
   <BODY>
      <?php
         if (isset($_POST['loginkunde'])){
            $email = mysqli_real_escape_string($conn, $_POST['email']);

            //Prüfen, ob alle Felder befüllt
            if(!isset($_POST['email']) || strlen($_POST['email']) == 0 || 
               !isset($_POST['kkennwort']) || strlen($_POST['kkennwort']) == 0){
                  echo "Bitte füllen Sie die erforderlichen Felder aus!";
                  return;
            } 
      
            //Prüfen, ob Kunden E-Mail existiert
            function check_id() {
               $db = new mysqli ("localhost", "root", "", "getraenkeshop_ass");
               $emails = $db->query("select email from kunde");
               while(($s = $emails->fetch_object()) != false){
                  if($s->email == $_POST['email']){
                     return true;
                  }
               }
               return false;
            }
            
            if (!check_id()){
               echo "Die Kunden-email existiert nicht!";
               return;
            }
            
            //Prüfen, ob Kunden E-Mail und Passwort übereinstimmen
            $kunde = $conn->query("select kkennwort from kunde where email = '$email'");
            $s = $kunde->fetch_object();
            if(password_verify($_POST['kkennwort'], $s->kkennwort) == false){
               echo "E-Mail und Kennwort stimmen nicht überein!";
               return;
            } 
         }  
         elseif(isset($_POST['registrierekunde'])){

            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $kname = mysqli_real_escape_string($conn, $_POST['kname']);
            $plz   = mysqli_real_escape_string($conn, $_POST['plz']);
            $ort   = mysqli_real_escape_string($conn, $_POST['ort']);
            $strasse      = mysqli_real_escape_string($conn, $_POST['strasse']);
            $hausnummer   = mysqli_real_escape_string($conn, $_POST['hausnummer']);
            $kkennwort_un = mysqli_real_escape_string($conn, $_POST['kkennwort']);
            $kkennwort    = password_hash($kkennwort_un, PASSWORD_DEFAULT);

            //Prüfen, ob alle Felder befüllt
            if(!isset($_POST['email'])      || strlen($_POST['email']) == 0 || 
               !isset($_POST['kname'])      || strlen($_POST['kname']) == 0 || 
               !isset($_POST['plz'])        || strlen($_POST['plz'])   == 0 || 
               !isset($_POST['ort'])        || strlen($_POST['ort'])   == 0 || 
               !isset($_POST['strasse'])    || strlen($_POST['strasse'])    == 0 || 
               !isset($_POST['hausnummer']) || strlen($_POST['hausnummer']) == 0 || 
               !isset($_POST['kkennwort'])  || strlen($_POST['kkennwort'])  == 0){
                  echo "Bitte füllen Sie die erforderlichen Felder aus!";
                  return;
            } 
         
            //Prüfen, ob E-Mail schon registriert ist
            $emails = $conn->query("select email from kunde");
            while(($s = $emails->fetch_object()) != false){
               if($s->email == $_POST['email']){
                  echo "Diese E-Mail ist bereits registriert!";
                  return;
               }
            }

            //Kunde in die DB einfügen
            $sql = "insert into kunde values ('" . $email. "', '" . $kkennwort . "', '" . $kname. "', '" . $plz. "', '" . $ort. "', '" . $strasse. "', '" . $hausnummer. "')";
            if ($conn->query($sql) == false){
               echo "Fehler beim Anlegen ihres Accounts!";
               echo $conn->error;
               $conn->close();
               return;
            }
            else {
               echo "Ihr Account wurde erfolgreich erstellt!";
            }
         }


         //Bestellung in die Datenbank einfügen
         $bestellnr  = $conn->query("select count(*) as bestnr from bestellung");
         $abestellnr = $bestellnr->fetch_object();
         $nbestellnr = $abestellnr->bestnr + 1; 
         $bestdatum  = date('Y-m-d');

         $sql = "insert into bestellung values ('" . $nbestellnr. "', '" . $bestdatum. "', '" . $email. "', '" . $_SESSION['mid']. "')";
         if ($conn->query($sql) == false){
            echo "Die Bestellung konnte nicht durchgeführt werden!";
            echo $conn->error;
            $conn->close();
            return;
         }

         //Sämtliche Bestellpositionen durchgehen
         for($i = 1; $i <= $_SESSION['anzPosition']; $i++){
          
            $sgname   = "gname" . $i;
            $shname   = "hname" . $i;
            $smenge   = "menge" . $i;
            $sbestand = "sbestand" . $i;
            $smid     = "smid";

            //Prüfen, ob Lagerbestand noch ausreicht (z.B.: Falls parallele Zugriffe, im Normalfall kein Problem, da schon geprüft)
            $bestand = $conn->query("select bestand from lager where gname = '$_SESSION[$sgname]' AND ghersteller = '$_SESSION[$shname]' AND mid = '$_SESSION[$smid]'");
            while($s = $bestand->fetch_object()){
               $_SESSION[$sbestand] = $s->bestand - $_SESSION[$smenge];
            }
            if($_SESSION[$sbestand] < 0){
               echo "Fehler! Die Bestellposition" .$i.  "konnte nicht Bestellt werden!";
               break;
            }

            //Bestellposition in die Datenbank einfügen
            $sql = "insert into bestellpos values ('" . $_SESSION[$smenge]. "', '" . $i. "', '" . $nbestellnr. "', '" . $_SESSION[$shname]. "', '" . $_SESSION[$sgname]. "')";
            if ($conn->query($sql) == false){
               echo "Die Bestellung konnte nicht durchgeführt werden!";
               echo $conn->error;
               $conn->close();
               return;
            }
         }         
         echo "Die Bestellung war erfolgreich!";         
         $conn->close();             
      ?>

      <form action="Kundenregistrierung.php">
         <fieldset>
            <p>
               <a href="../index.php">Zurück zum Hauptmenü</a> 
            </p>
         </fieldset>
      </form>
   </BODY>
</HTML>