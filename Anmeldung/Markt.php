<?php
   include_once '../includes/dbh.inc.php';
   include_once '../classes/anmeldungMarktKl.php';
   include_once '../classes/registrierungMarktKl.php';
   session_start();

   if (empty($_SESSION['mid']) AND !isset($_POST['loginmarkt']) AND !isset($_POST['registrieremarkt'])) {
      header('Location: Marktanmeldung.php');
      exit;
   }
   

   if (isset($_POST['loginmarkt'])){ 
      $mid = mysqli_real_escape_string($conn, $_POST['mid']);
      $markt = new anmeldungMarktKl($mid, $_POST['mpasswort'], $conn);
   
      if (!$markt->alleFelderBelegt()){
         echo "Bitte füllen Sie die erforderlichen Felder aus!";
         return;
      }  
      if (!$markt->midPruefen()){
         echo "Die Markt-ID existiert nicht!";
         return;
      }  
      if (!$markt->passwortPruefen()){
         echo "ID und Passwort stimmen nicht überein!";
         return;
      }  
   }
   elseif(isset($_POST['registrieremarkt'])){
      $mid          = mysqli_real_escape_string($conn, $_POST['mid']);
      $mname        = mysqli_real_escape_string($conn, $_POST['mname']);
      $mpasswort_un = mysqli_real_escape_string($conn, $_POST['mpasswort']);
      $mpasswort    = password_hash($mpasswort_un, PASSWORD_BCRYPT);

      $markt = new registrierungMarktKl($mid, $mname, $_POST['mpasswort'], $conn);
      
      if (!$markt->alleFelderBelegt()){
         echo "Bitte füllen Sie die erforderlichen Felder aus!";
         return;
      }  
      if (!$markt->midPruefen()){
         echo "Die Markt-ID ist bereits vergeben!";
         return;
      }  
      if (!$markt->mnamePruefen()){
         echo "Der Marktname ist bereits vergeben!";
         return;
      }  
      if (!$markt->marktHinzufuegen()){
         echo "Fehler beim Erstelen ihres Marktes";
         return;
      } 
      echo "Ihr Markt wurde erfolgreich erstellt.";
   }

   
   if(!isset($_SESSION['mid']) OR isset($_POST['loginmarkt']) OR isset($_POST['registrieremarkt'])){
      $_SESSION['mid'] = $_POST['mid'];
   } 
?>

<!-- Dies ist eine gemeinsame Leistung der Gruppe -->
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