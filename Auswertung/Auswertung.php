<?php
   session_start();
   if (empty($_SESSION['mid'])) {
      header('Location: ../Anmeldung/Marktanmeldung.php');
      exit;
   }
?>

<!-- Noah Schöne -->
<!-- Beschreibung:
	   Durch Eingabe eines Datums und einer Kategorie ist es möglich statistische Werte (Gesamtumsatz, Größte Bestellung, Standardabweichung und Median aller Bestellungen)
	   je Kalenderwoche zu berechnen. Wenn keine Kategorie spezifisch ausgewählt wird, findet die Auswertung für alle Kategorien statt. -->
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
               <label for="start">Starttermin</label>
               <input type="date" name="start" id="start">
            </p>
            <p>
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
            </p>
            <p>
               <input type="submit" name="auswerten" value="Auswertung starten">
            </p>
         </fieldset>
      </form>
      <br>
      <form action="Vorhersage.php" method="POST">
         <button>Zur Umsatzvorhersage für die nächste Woche</button>
      </form>
   </BODY>
</HTML>
