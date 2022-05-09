<!-- Dies ist eine gemeinsame Leistung der Gruppe -->
<?php

   include_once '../includes/dbh.inc.php';
   session_start();

   if(!isset($_SESSION['mid'])){
      $_SESSION['mid'] = $_POST['mid'];
   } else {
      echo "Sie sind angemeldet!";
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
      if (isset($_POST['loginmarkt'])){
         $mid = mysqli_real_escape_string($conn, $_POST['mid']);

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
                  // echo "Die Markt-ID existiert!";
                  return true;
               }
            }
            return false;
         }
         
         if (check_id() == false){
            echo "Die Markt-ID existiert nicht!";
            return;
         }
         

         //Prüfen, ob Markt-ID und Passwort übereinstimmen
         $markt = $conn->query("select mpasswort from markt where mid = '$mid'");
         $s = $markt->fetch_object();
         if(password_verify($_POST['mpasswort'], $s->mpasswort) == false){
            echo "ID und Passwort stimmen nicht überein!";
            return;
         } 
      }
      elseif(isset($_POST['registrieremarkt'])){
         $mid = mysqli_real_escape_string($conn, $_POST['mid']);
         $mname = mysqli_real_escape_string($conn, $_POST['mname']);
         $mpasswort_un = mysqli_real_escape_string($conn, $_POST['mpasswort']);
         $mpasswort = password_hash($mpasswort_un, PASSWORD_BCRYPT);

         //Prüfen, ob alle Felder befüllt
         if(!isset($_POST['mid']) || strlen($_POST['mid']) == 0 || 
            !isset($_POST['mname']) || strlen($_POST['mname']) == 0 || 
            !isset($_POST['mpasswort']) || strlen($_POST['mpasswort']) == 0){
               echo "Bitte füllen Sie die erforderlichen Felder aus!";
               return;
         } 
      
         //Prüfen, ob Markt-ID schon vergeben
         $mids = $conn->query("select mid from markt");
         while(($s = $mids->fetch_object()) != false){
            if($s->mid == $_POST['mid']){
               echo "Die Markt-ID ist bereits vergeben!";
               return;
            }
         }

         //Prüfen, ob mname schon vergeben
         $mn = $conn->query("select mname from markt");
         while(($s = $mn->fetch_object()) != false){
            if($s->mname == $_POST['mname']){
               echo "Der Marktname ist bereits vergeben!";
               return;
            }
         }

         $sql = "insert into markt values ('" . $mid. "', '" . $mpasswort. "', '" . $mname. "')";
         if ($conn->query($sql) == false){
            echo "fehler";
            echo $conn->error;
         }
         else {
            echo "Ihr Markt wurde erfolgreich erstellt.";
         }
         $conn->close();
      }

      ?>

      <h2>Die Möglichkeiten in Ihrem Markt-Portal</h2>
      <form action="Getränk/Getraenkeerfassen.php" method="POST">
         <p>
         <input type="submit" value="Getraenke erfassen">
         </p>
      </form>
      <form action="Lager/Lagerverwaltung.php" method="POST">
         <p>
         <input type="submit" value="Lagerverwaltung">
         </p>
      </form>
      <form action="Auswertung/Auswertung.php" method="POST">
         <p>
         <input type="submit" value="Auswertung">
         </p>
      </form>
      <a href="logout.php">Logout</a>     
   </BODY>
</HTML>