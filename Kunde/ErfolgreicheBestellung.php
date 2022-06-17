<?php
   include_once '../includes/dbh.inc.php';
   include_once '../classes/anmeldungKundeKl.php';
   include_once '../classes/registrierungKundeKl.php';
   include_once '../classes/bestellungAbschließen.php';
   session_start();

   //Nur wenn der Kunde schon angemeldet ist (email ist gesetzt) oder er von Kundenanmeldung oder Kundenregistrierung kommt kann er die Seite öffnen
   if (empty($_SESSION['email']) AND !isset($_POST['loginkunde']) AND !isset($_POST['registrierekunde'])) {
      header('Location: Kundenanmeldung.php');
      exit;
   }


   //Prüfungen, wenn der Benutzer von der Kundenanmeldung-Seite kommt
   if (isset($_POST['loginkunde'])){
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $kunde = new anmeldungKundeKl($email, $_POST['kkennwort'], $conn);

      //Prüfen, ob alle Felder befüllt
      if(!$kunde->alleFelderBelegt()){
         echo "Bitte füllen Sie die erforderlichen Felder aus!";
         $conn->close();
         return;
      } 
      //Prüfen, ob eingegebene E-Mail existiert   
      if(!$kunde->emailPruefen()){
         echo "Die Kunden-email existiert nicht!";
         $conn->close();
         return;
      }     
      //Prüfen, ob eingegebene E-Mail und Passwort übereinstimmen
      if(!$kunde->kennwortPruefen()){
         echo "E-Mail und Kennwort stimmen nicht überein!";
         $conn->close();
         return;
      } 
      unset($_POST['loginkunde']);
   }  
   //Prüfungen, wenn der Benutzer von der Kundenregistrierung-Seite kommt
   elseif(isset($_POST['registrierekunde'])){

      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $kname = mysqli_real_escape_string($conn, $_POST['kname']);
      $plz   = mysqli_real_escape_string($conn, $_POST['plz']);
      $ort   = mysqli_real_escape_string($conn, $_POST['ort']);
      $strasse      = mysqli_real_escape_string($conn, $_POST['strasse']);
      $hausnummer   = mysqli_real_escape_string($conn, $_POST['hausnummer']);
      $kkennwort_un = mysqli_real_escape_string($conn, $_POST['kkennwort']);
      $kkennwort    = password_hash($kkennwort_un, PASSWORD_BCRYPT);
      $kunde = new registrierungKundeKl($email, $kname, $plz, $ort, $strasse, $hausnummer, $kkennwort, $conn);

      //Prüfen, ob alle Felder befüllt wurden
      if(!$kunde->alleFelderBelegt()){
         echo "Bitte füllen Sie die erforderlichen Felder aus!";
         $conn->close();
         return;
      }   
      //Prüfen, ob die eingegebene E-Mail schon registriert ist
      if(!$kunde->emailPruefen()){
         echo "Diese E-Mail ist bereits registriert!";
         $conn->close();
         return;
      }
      //Neue Kunde in die DB einfügen
      if (!$kunde->kundeHinzufuegen()){
         echo "Fehler beim Anlegen ihres Accounts!";
         $conn->close();
         return;
      }
      echo "Ihr Account wurde erfolgreich erstellt!";
      unset($_POST['registrierekunde']);
   }

   //Nach Anmeldung oder Registrierung Kunden-email in Session speichern
   if(!isset($_SESSION['email']) OR isset($_POST['loginkunde']) OR isset($_POST['registrierekunde'])){
      $_SESSION['email'] = mysqli_real_escape_string($conn, $_POST['email']);
   } 
?>

<!-- Julian Alber -->
<!-- Beschreibung:
     Die vom Benutzer eingegeben Daten werden (Abhängig davon, ob er sich registrieren oder anmelden möchte) überprüft 
     und bei Korrekter Eingabe sämtlicher Daten die Bestellung abgeschlossen und in der Datenbank gespeichert.
     Im Falle einer erfolgreichen Registierung wird der neue Kunde noch der Datenbank hinzugefügt.  -->
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
         $bAbschließen = new bestellungAbschließen($_SESSION['mid'], $conn);

         //Bestellung in die Datenbank einfügen
         if(!$bAbschließen->bestellungEinfuegen($_SESSION['email'])){
            echo "Die Bestellung konnte nicht durchgeführt werden!";
            $conn->close();
            return;
         }

         //Sämtliche Bestellpositionen durchgehen und in die DB einfügen
         for($i = 1; $i <= $_SESSION['anzPosition']; $i++){
            $sgname   = "gname" . $i;
            $shname   = "hname" . $i;
            $smenge   = "menge" . $i;

            if (!$bAbschließen->bestellpositionEinfuegen($_SESSION[$sgname], $_SESSION[$shname], $_SESSION[$smenge], $i)){
               echo "Die Bestellung konnte nicht durchgeführt werden!";
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