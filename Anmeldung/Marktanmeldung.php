<!-- Dies ist eine gemeinsame Leistung der Gruppe -->
<!-- Beschreibung:
     Zur Nutzung des Marktportales muss sich der Nutzer erst anmelden.
     Dafür wird ein Passwort und seine Markt-ID benötigt.
     Sollte er noch nicht registriert sein, kann er auch zur Registrierungsoberfläche wechseln-->
<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Getränkemarkt-Anmeldung</title>
   </HEAD>
   <BODY>
      <!-- HTML-Körper -->
      <h1>Getränkemarkt-Portal</h1>
      <form action="Markt.php" method="POST" >
         <fieldset>
            <legend>Bitte die Anmeldedaten für Ihren Markt eingeben</legend>
            <p>
               <label for="mid">Markt-ID: </label>
               <input type="text" name="mid" id="mid">
            </p>
            <p>
               <label for="mpasswort">Passwort: </label>
               <input type="password" name="mpasswort" id="mpasswort">
            </p>
            <p>
               <input type="submit" name="loginmarkt" value="Anmelden">
            </p>
         </fieldset>
      </form>
      <br>
      <form action="../projekt-ass/Marktregistrierung.php">
         <fieldset>
            <legend>Wenn Sie neu auf dem Portal sind, können Sie sich hier registrieren</legend>
            <p>
               <a href="Marktregistrierung.php"> Registrieren</a> 
            </p>
         </fieldset>
      </form>
   </BODY>
</HTML>