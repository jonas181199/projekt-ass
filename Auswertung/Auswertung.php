<?php

?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Auswertung</title>
   </HEAD>
   <BODY>
      <!-- HTML-Körper -->
      <h1>Auswertung</h1>
      <form action="ErfolgteAuswertung.php" method="POST" >
         <fieldset>
            <legend>Bitte geben Sie den Starttermin für die Auswertung an</legend>
            <p>
               <label for="start">Starttermin </label>
               <input type="date" name="start" id="start">
            </p>
            <p>
               <input type="submit" name="auswerten" value="Auswertung starten">
            </p>
         </fieldset>
      </form>
      

      
               <p>
                  <a href="KategorieAuswertung.php">Zur Auswertung nach Kategorie</a> 
               </p>




   </BODY>
</HTML>