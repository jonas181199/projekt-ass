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

         echo $_POST['marktid'];
         echo $_POST['marktname'];
         echo $_POST['marktpasswort'];
         echo strlen($_POST['marktid']);

      //Prüfen, ob alle Felder befüllt
      if(!isset($_POST['marktid']) || strlen($_POST['marktid']) == 0 || 
         !isset($_POST['marktname']) || strlen($_POST['marktname']) == 0 || 
         !isset($_POST['marktpasswort']) || strlen($_POST['marktpasswort']) == 0){
            echo "Bitte füllen Sie die erforderlichen Felder aus!";
      } else {
         //Prüfen, ob Markt-ID schon vergeben
         $mid = $db->query("select mid from markt");
         while(($s = $mid->fetch_object()) != false){
            if($s == $_POST['marktid']){
               echo "Die Markt-ID ist bereits vergeben."
            }
            else {
               //Prüfen, ob Marktname schon vergeben
               $mn = $db->query("select mname from markt");
               while(($s = $mn->fetch_object()) != false){
                  if($s == $_POST['marktname']){
                     echo "Der Marktname ist bereits vergeben."
                  }
                  else{

                  }
               }
            }  
         }
      } 
      

      $db = new mysqli($host, $user, $password, $db);
               
      

               


      ?>



      <p>Ihr Markt wurde erfolgreich erstellt.</p>
   </BODY>
</HTML>