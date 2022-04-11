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
      <form method=“POST“ action=“Markt.php”>
         <fieldset>
            <legend>Bitte die Anmeldendaten für Ihren Markt eingeben</legend>
            <p>
               <label for="mid">Markt-ID: </label>
               <input type=”text” name="marktid" id="mid">
            </p>
            <p>
               <label for="mpasswort">Passwort: </label>
               <input type="password" name="marktpassword" id="mpasswort">
            </p>
            <p>
               <input type="submit" name="loginmarkt" value="Anmelden">
            </p>
         </fieldset>
      </form>
<br>
      <form action="Marktregistrierung.php">
         <fieldset>
            <legend>Wenn Sie neu auf dem Portal sind, können Sie sich hier registrieren</legend>
            <p>
               <a href="Marktregistrierung.php"> Registrieren</a> 
            </p>
         </fieldset>
      </form>
   </BODY>
</HTML>