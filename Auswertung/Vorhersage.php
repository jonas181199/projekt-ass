<!-- Jonas Schirm -->
<?php  
    include_once '../includes/dbh.inc.php'
?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Vorhersage</title>
   </HEAD>
   <BODY>
   <h1>Vorhersage des Umsatzes für die nächste Woche</h1>
    <form action="ErfolgteVorhersage.php" method="POST">
        <label for="start">Starttermin</label>
            <input type="date" name="start" id="start"><br>
        <label for="kategorie">Kategorie</label>
	        <select name="kategorie">
                <!-- "%" dient als Platzhalter, wenn keine Vorhersage des Umsatzes je Kategorie erwünscht ist -->
                <option value="%" selected>Beliebig</option>
                <option value ="Wasser">Wasser</option>
                <option value ="Saft">Saft</option>
                <option value ="Limonade">Limonade</option>
                <option value ="Wein">Wein</option>
                <option value ="Bier">Bier</option>
                <option value ="Sonstiges">Sonstiges</option>
	        </select>
            <br><br>
        <input type="submit" name="vorhersage" value="Jetzt prognostizieren">
    </form>

   </BODY>
</HTML>

