<?php
   include_once '../includes/dbh.inc.php';
?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Kundenanmeldung</title>
   </HEAD>
   <BODY>
      <form action="ErfolgreicheBestellung.php" method="POST" >
         <fieldset>
            <legend>Bitte die Anmeldedaten für Ihren Account eingeben</legend>
            <p>
               <label for="email">E-Mail: </label>
               <input type="text" name="email" id="email">
            </p>
            <p>
               <label for="kkennwort">Kennwort: </label>
               <input type="password" name="kkennwort" id="kkennwort">
            </p>
            <p>
               <input type="submit" name="loginkunde" value="Anmelden">
            </p>
         </fieldset>
      </form>
      <br>
      <form action="Kundenregistrierung.php">
         <fieldset>
            <legend>Wenn Sie neu auf dem Portal sind, können Sie sich hier registrieren</legend>
            <p>
               <a href="Kundenregistrierung.php">Registrieren</a> 
            </p>
         </fieldset>
      </form>
   </BODY>
</HTML>