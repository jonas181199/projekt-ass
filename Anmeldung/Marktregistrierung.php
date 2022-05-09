<!-- Dies ist eine gemeinsame Leistung der Gruppe -->
<?php

include_once '../includes/dbh.inc.php';


?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Getränkemarkt-Registrierung</title>
   </HEAD>
   <BODY>
      <!-- HTML-Körper -->
      <h2>Registrieren Sie Ihren Markt</h2>
      <form method="POST" action="Markt.php">
         <fieldset>
            <legend>Geben Sie Ihre gewünschten Daten ein</legend>
            <p>
               <label for="mid">Markt-ID: </label>
               <input type="text" name="mid" id="mid">
            </p>
            <p>
               <label for="mname">Marktname: </label>
               <input type="text" name="mname" id="mname">
            </p>
            <p>
               <label for="mpasswort">Passwort: </label>
               <input type="password" name="mpasswort" id="mpasswort">
            </p>
            <p>
               <input type="submit" name="registrieremarkt" value="registrieren">
            </p>
         </fieldset>      
        </form>        
   </BODY>
</HTML>
