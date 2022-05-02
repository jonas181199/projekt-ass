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
      <?php
         $mid = mysqli_real_escape_string($conn, $_POST['mid']);
         $mpasswort_un = mysqli_real_escape_string($conn, $_POST['mpasswort']);
         $mpasswort = password_hash($mpasswort_un, PASSWORD_BCRYPT);

         //Prüfen, ob alle Felder befüllt
         if(!isset($_POST['mid']) || strlen($_POST['mid']) == 0 || 
            !isset($_POST['mpasswort']) || strlen($_POST['mpasswort']) == 0){
               echo "Bitte füllen Sie die erforderlichen Felder aus!";
               return;
         } 
   
         //Prüfen, ob Markt-ID existiert
         function check_id(): bool {
            $db = new mysqli ("localhost", "root", "", "getraenkeshop_ass");
            $mids = $db->query("select mid from markt");
            while(($s = $mids->fetch_object()) != false){
               if($s->mid == $_POST['mid']){
                  echo "Die Markt-ID existiert!";
                  return true;
               }
            }
            return false;
         }
         
         if (check_id() == false){
            return;
         }

         //Prüfen, ob Markt-ID und Passwort übereinstimmen
         $markt = $conn->query("select mpasswort from markt where mid = '$mid'");
         $s = $markt->fetch_object();
         echo $s->mpasswort;
         echo "\n";
         echo $mpasswort;
         if($s->mpasswort != $mpasswort){
            echo "ID und Passwort stimmen nicht überein!";
            return;
         } 
      ?>

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
      <a href="logout.php">Logout</a>     
   </BODY>
</HTML>