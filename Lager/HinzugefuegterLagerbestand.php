<!-- Noah Schöne -->
<?php

   include_once '../includes/dbh.inc.php';
   include_once '../classes/lager.php';
   session_start();

   if (empty($_SESSION['mid'])) {
      header('Location: Marktanmeldung.php');
      exit;
   }

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
         // Objekt erzeugen
         $lager = new Lager($_SESSION['mid'], $_POST['gname'], $_POST['ghersteller'], $_POST['lagerbest'], $conn);
         $data[] = null;
         $data = $lager->replaceLagerbestand();
      ?> 
         
      <!-- Weiterleitung -->
      <form action="Lagerverwaltung.php">
         <p>
            <input type="submit" name="lagerverwaltung" value="Hier können Sie weitere Bestände anpassen">
         </p>
      </form>

      <form action="../Anmeldung/Markt.php">
         <p>
            <input type="submit" name="markt" value="Zurück zu den Funktionen des Markts">
         </p>
      </form>
    </BODY>
</HTML>