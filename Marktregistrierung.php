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
      <form method="POST" action="ErfolgreicheMarktregistrierung.php">
         <fieldset>
            <legend>Geben Sie Ihre gewünschten Daten ein</legend>
            <p>
               <label for="mid">Markt-ID: </label>
               <input type="text" name="marktid" id="mid">
            </p>
            <p>
               <label for="mname">Marktname: </label>
               <input type="text" name="marktname" id="mname">
            </p>
            <p>
               <label for="mpasswort">Passwort: </label>
               <input type="password" name="marktpasswort" id="mpasswort">
            </p>
            <p>
               <input type="submit" name="registrieremarkt" value="registrieren">
            </p>
         </fieldset>      
        </form>        
   </BODY>
</HTML>
