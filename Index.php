<?php
   include_once 'includes/dbh.inc.php';
?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Startseite</title>
   </HEAD>
   <BODY>
      <form action="Kunde/Bestellung.php" method="POST" >
         <fieldset>
            <legend>Bitte die Daten für Ihre Bestellung eingeben</legend>
            <p>
               <label for="mid">Marktname: </label>
               <input type="text" name="mid" id="mid">
            </p>
            <p>
               <label for="anzPosition">Anzahl an Positionen: </label>
               <input type="text" name="anzPosition" id="anzPosition">
            </p>
            <p>
               <input type="submit" name="Bestätigen" value="Bestätigen">
            </p>
         </fieldset>
      </form>
      <br>
      <form action="../projekt-ass/Marktanmeldung.php">
         <fieldset>
            <legend>Hier können Sie sich an ihrem Markt anmelden</legend>
            <p>
               <a href="Marktanmeldung.php">Als Markt anmelden</a> 
            </p>
         </fieldset>
      </form>
   </BODY>
</HTML>