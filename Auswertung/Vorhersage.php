<!-- Jonas Schirm -->
<?php  
   include_once '../includes/dbh.inc.php';
   session_start();
   if ((empty($_SESSION['mid']))) {
      header('Location: ../Anmeldung/Marktanmeldung.php');
      exit;
   }
?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Vorhersage</title>
   </HEAD>
   <BODY>
   <h1>Vorhersage des Umsatzes für die nächste Woche</h1>
    <form action="ErfolgteVorhersage.php" method="POST">
		<label for="kategorie">Kategorie</label><br>
	     <select name="kategorie">
	        <option value="%" selected>Alle</option>
	        <option value ="Wasser">Wasser</option>
	        <option value ="Saft">Saft</option>
	        <option value ="Limonade">Limonade</option>
	        <option value ="Wein">Wein</option>
	        <option value ="Bier">Bier</option>
	        <option value ="Sonstiges">Sonstiges</option>
	    </select><br><br>
		<input type="submit" name="vorhersage" value="Jetzt vorhersagen!">
    </form>

   </BODY>
</HTML>

