<?php
session_start();
include_once 'includes/dbh.inc.php';

$_SESSION['mid'] = $_POST['mid'];

if(!isset($_SESSION['mid'])){
   echo "Du bist nicht angemeldet.";
} else {
   echo "Du bist angemeldet";
}
?>

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
      <!-- HTML-Körper -->
      <h2>Die Möglichkeiten in Ihrem Markt-Portal</h2>
      <form action="Getraenkeerfassen.php" method="POST">
         <p>
         <input type="submit" value="Getraenke erfassen">
         </p>
      </form>
      <form action="Lagerverwaltung.php" method="POST">
         <p>
         <input type="submit" value="Lagerverwaltung">
         </p>
      </form>
      <form action="Auswertung.php" method="POST">
         <p>
         <input type="submit" value="Auswertung">
         </p>
      </form>     
   </BODY>
</HTML>