<?php
   include_once '../includes/dbh.inc.php';
   session_start();
   if (!isset($_POST['registrieren'])) {
      header('Location: Kundenanmeldung.php');
      exit;
  }
  unset($_POST['registrieren']);
?>

<!-- Julian Alber -->
<!-- Beschreibung:
     Der Nutzer kann hier sämtliche Daten eingeben, die für seine Registrierung benötigt werden und anschließend die Bestellung tätigen-->
<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Kundenregistrierung</title>
   </HEAD>
   <BODY>
      <h2>Legen Sie einen Account an</h2>
      <form method="POST" action="ErfolgreicheBestellung.php">
         <fieldset>
            <legend>Geben Sie Ihre Daten ein</legend>
            <p>
               <label for="email">E-Mail: </label>
               <input type="text" name="email" id="email">
            </p>
            <p>
               <label for="kname">Name: </label>
               <input type="text" name="kname" id="kname">
            </p>
            <p>
               <label for="kkennwort">Passwort: </label>
               <input type="password" name="kkennwort" id="kkennwort">
            </p>
            <p>
               <label for="plz">Plz: </label>
               <input type="text" name="plz" id="plz">
            </p>
            <p>
               <label for="ort">Ort: </label>
               <input type="text" name="ort" id="ort">
            </p>
            <p>
               <label for="strasse">Straße: </label>
               <input type="text" name="strasse" id="strasse">
            </p>
            <p>
               <label for="hausnummer">Hausnummer: </label>
               <input type="text" name="hausnummer" id="hausnummer">
            </p>
            <p>
               <input type="submit" name="registrierekunde" value="registrieren">
            </p>
         </fieldset>      
        </form>        
   </BODY>
</HTML>
