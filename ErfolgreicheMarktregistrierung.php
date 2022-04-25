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

         echo $_POST['mid'];
         echo $_POST['mname'];
         echo $_POST['mpasswort'];
         echo strlen($_POST['mid']);

      //Prüfen, ob alle Felder befüllt
      if(!isset($_POST['mid']) || strlen($_POST['mid']) == 0 || 
         !isset($_POST['mname']) || strlen($_POST['mname']) == 0 || 
         !isset($_POST['mpasswort']) || strlen($_POST['mpasswort']) == 0){
            echo "Bitte füllen Sie die erforderlichen Felder aus!";
      } else {
         /*//Prüfen, ob Markt-ID schon vergeben
         $mid = $db->query("select mid from markt");
         while(($s = $mid->fetch_object()) != false){
            if($s == $_POST['mid']){
               echo "Die Markt-ID ist bereits vergeben."
            }
            else {
               //Prüfen, ob mname schon vergeben
               $mn = $db->query("select mname from markt");
               while(($s = $mn->fetch_object()) != false){
                  if($s == $_POST['mname']){
                     echo "Der mname ist bereits vergeben."
                  }
                  else {*/
                     $db = new mysqli("localhost", "root", "", "getraenkeshop_ass");
                     //$sql = "insert into markt values (, ', '$_POST['mpasswort']')";
                     $sql = "insert into markt values ('" . $_POST['mid']. "', '" . $_POST['mpasswort']. "', '" . $_POST['mname']. "')";
                           if ($db->query($sql) == false){
                              echo "fehler";
                           echo $db->error;
                           }
                           else {
                              echo "worked";
                           }
                           $db->close();
                                 
                  }
     /*          }
            }
         }
      }
           */ 


      ?>



      <p>Ihr Markt wurde erfolgreich erstellt.</p>
   </BODY>
</HTML>