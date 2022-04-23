<?php
session_start();
include_once 'includes/dbh.inc.php'

?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Getränke erfassen</title>
   </HEAD>
   <BODY>
      <!-- HTML-Körper -->
      <form method="POST" action="ErfasstesGetraenk.php">
         <fieldset>
            <legend>Fügen Sie ein neues Getränk hinzu</legend>
            <p>
               <label for="gname">Getraenkename: </label>
               <input type="text" name="gname" id="gname">
            </p>
            <p>
               <label for="ghersteller">Getraenkehersteller: </label>
               <input type="text" name="ghersteller" id="ghersteller">
            </p>
            <p>
               <label for="kategorie">Kategorie: </label>
                  <select name="kategorie" id="kategorie">
                     <option value="Wasser">Wasser</option>
                     <option value="saft">Saft</option>
                     <option value="limonade">Limonade</option>
                     <option value="wein">Wein</option>
                     <option value="bier">Bier</option>
                     <option value="sonstiges">Sonstiges</option>
                  </select>
            </p>
            <p>
               <label for="preis">Preis: </label>
               <input type="number" min="1" step="any" name="preis" id="preis">
            </p>
            <p>
               <input type="submit" name="ghinzufuegen" value="hinzufügen">
            </p>
         </fieldset>      
        </form>


      <table>
         <tr>
            <th>Company</th>
            <th>Contact</th>
            <th>Country</th>
         </tr>
         <tr>
            <td>Alfreds Futterkiste</td>
            <td>Maria Anders</td>
            <td>Germany</td>
         </tr>
         <tr>
            <td>Centro comercial Moctezuma</td>
            <td>Francisco Chang</td>
            <td>Mexico</td>
         </tr>
         </table>
   </BODY>
</HTML>
