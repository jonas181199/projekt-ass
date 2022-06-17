<?php
   include_once '../includes/dbh.inc.php';
   include_once '../classes/anmeldungMarktKl.php';
   include_once '../classes/registrierungMarktKl.php';
   session_start();

   //Nur wenn der Benutzer schon angemeldet ist (mid ist gesetzt) oder er von Marktanmeldung oder Marktregistrierung kommt kann er die Seite öffnen
   if (empty($_SESSION['mid']) AND !isset($_POST['loginmarkt']) AND !isset($_POST['registrieremarkt'])) {
      header('Location: Marktanmeldung.php');
      exit;
   }
   

   //Prüfungen, wenn der Benutzer von der Marktanmeldung-Seite kommt
   if (isset($_POST['loginmarkt'])){ 
      $mid = mysqli_real_escape_string($conn, $_POST['mid']);
      $markt = new anmeldungMarktKl($mid, $_POST['mpasswort'], $conn);
   
      //Prüfen, ob alle Felder befüllt
      if (!$markt->alleFelderBelegt()){
         echo "Bitte füllen Sie die erforderlichen Felder aus!";
         $conn->close();
         return;
      }  
      //Prüfen, ob eingegebene Markt-ID existiert 
      if (!$markt->midPruefen()){
         echo "Die Markt-ID existiert nicht!";
         $conn->close();
         return;
      }  
      //Prüfen, ob eingegebene Markt-ID und Passwort übereinstimmen
      if (!$markt->passwortPruefen()){
         echo "ID und Passwort stimmen nicht überein!";
         $conn->close();
         return;
      }  
   }
   //Prüfungen, wenn der Benutzer von der Marktregistrierung-Seite kommt
   elseif(isset($_POST['registrieremarkt'])){
      $mid          = mysqli_real_escape_string($conn, $_POST['mid']);
      $mname        = mysqli_real_escape_string($conn, $_POST['mname']);
      $mpasswort_un = mysqli_real_escape_string($conn, $_POST['mpasswort']);
      $mpasswort    = password_hash($mpasswort_un, PASSWORD_BCRYPT);
      $markt = new registrierungMarktKl($mid, $mname, $_POST['mpasswort'], $conn);
      
      //Prüfen, ob alle Felder befüllt wurden
      if (!$markt->alleFelderBelegt()){
         echo "Bitte füllen Sie die erforderlichen Felder aus!";
         $conn->close();
         return;
      }  
      //Prüfen, ob die eingegebene Markt-ID schon vergeben ist
      if (!$markt->midPruefen()){
         echo "Die Markt-ID ist bereits vergeben!";
         $conn->close();
         return;
      }  
      //Prüfen, ob der eingegebene Marktname schon vergeben ist
      if (!$markt->mnamePruefen()){
         echo "Der Marktname ist bereits vergeben!";
         $conn->close();
         return;
      }  
      //Neue Markt in die DB einfügen
      if (!$markt->marktHinzufuegen()){
         echo "Fehler beim Erstelen ihres Marktes";
         $conn->close();
         return;
      } 
      echo "Ihr Markt wurde erfolgreich erstellt.";
   }

   //Nach Anmeldung oder Registrierung Markt-ID in Session speichern (-> bleibt angemeldet)   
   if(!isset($_SESSION['mid']) OR isset($_POST['loginmarkt']) OR isset($_POST['registrieremarkt'])){
      $_SESSION['mid'] = $_POST['mid'];
   } 
?>

<!-- Dies ist eine gemeinsame Leistung der Gruppe -->
<!-- Beschreibung:
     Die vom Benutzer eingegeben Daten werden (Abhängig davon, ob er sich registrieren oder anmelden möchte) überprüft 
     und bei Korrekter Eingabe sämtlicher Daten wird der Zugang zu dieser Seite gewährt. 
     Er kann anschließend zur Lagerverwaltung, zur Getränkeerfassung oder zur Auswertung wechseln.
     Im Falle einer erfolgreichen Registierung wird der neue Kunde noch der Datenbank hinzugefügt.  -->
<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Die Möglichkeiten im Portal</title>
   </HEAD>
   <BODY>
      <h2>Die Möglichkeiten in Ihrem Markt-Portal</h2>
      <form action="../Getraenk/Getraenkeerfassen.php" method="POST">
         <p>
         <input type="submit" value="Getraenke erfassen">
         </p>
      </form>
      <form action="../Lager/Lagerverwaltung.php" method="POST">
         <p>
         <input type="submit" value="Lagerverwaltung">
         </p>
      </form>
      <form action="../Auswertung/Auswertung.php" method="POST">
         <p>
         <input type="submit" value="Auswertung">
         </p>
      </form>
      <a href="logout.php">Logout</a>     
   </BODY>
</HTML>