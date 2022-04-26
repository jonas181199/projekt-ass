<?php

include_once 'includes/dbh.inc.php'

?>


<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Erfolgreiche Getränkemarkt-Registrierung</title>
   </HEAD>
   <BODY>
      <!-- HTML-Körper -->
      <?php
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
            echo "worked";
         }
         $conn->close();
      ?>
      <p>Ihr Markt wurde erfolgreich erstellt.</p>
   </BODY>
</HTML>