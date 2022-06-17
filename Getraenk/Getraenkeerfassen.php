<?php
   include_once '../includes/dbh.inc.php';
   session_start();
   if (empty($_SESSION['mid'])) {
      header('Location: ../Anmeldung/Marktanmeldung.php');
      exit;
   }
?>

<!-- Jonas Schirm -->
<!-- Beschreibung:
      Der Marktverantwortliche muss alle Felder befüllen um ein Getränk der Datenbank hinzuzufügen.
      Die Felder Getränkename und Getränkehersteller sind Freitextfelder. Die Kategorie kann durch eine Dropdown-Liste ausgewählt werden.
      Der Preis ist mit 2 Dezimalstellen einzugeben.
      Die Tabelle zeigt an, welche Getränke bereits in der Datenbank vorhanden sind. -->
<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Getränkeverwaltung</title>
   </HEAD>
   <BODY>
      <h1>Getränkeverwaltung</h1>
      <p>
         <a href="../Anmeldung/Markt.php">Zurück zum Markt</a>
      </p>
      <!-- Felder ausfüllen -->
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
                     <option value="Bitte auswählen" selected>Bitte auswählen</option>
                     <option value="Wasser">Wasser</option>
                     <option value="Saft">Saft</option>
                     <option value="Limonade">Limonade</option>
                     <option value="Wein">Wein</option>
                     <option value="Bier">Bier</option>
                     <option value="Sonstiges">Sonstiges</option>
                  </select>
            </p>
            <p>
               <label for="preis">Preis: </label>
               <input type="number" min="0.00" step="any" name="preis" id="preis">
            </p>
            <p>
               <!-- Getränk hinzufügen -->
               <input type="submit" name="ghinzufuegen" value="hinzufügen">
            </p>
         </fieldset>      
      </form>
      <br><br>

      <!-- Erstellung einer Tabelle, die die Getraenke der DB (getraenke) darstellt -->
      <h2>Die Getränke der Datenbank</h2>
      <?php
         $sql = "SELECT * FROM getraenke ORDER BY gname";
         if($result = $conn->query($sql)){
            while($ds = $result->fetch_object()){
               $data[] = $ds;
            }
         }
      ?>

      <div class="row">
         <h4>Anzahl der Getränke: 
            <?php
               if(empty($data)){
                  echo("Es sind noch keine Getränke vorhanden.");
               }
               else{
                  echo count($data); 
               }
            ?></h4>
         <table border="2" cellspacing=2 cellpadding=5>
            <thead>
               <tr>
                  <th scope="col">Getränkename</th>
                  <th scope="col">Getränkehersteller</th>
                  <th scope="col">Kategorie</th>
                  <th scope="col">Preis pro Flasche</th>
               </tr>
            </thead>
            <tbody>
               <?php
                  if(empty($data)){
                        echo("Es kann noch keine Tabelle erzeugt werden.");
                  } else {
                  foreach ($data as $content){
               ?>
               <tr>
                  <td>
                     <?php
                        echo $content->gname;
                     ?>
                  </td>
                  <td>
                     <?php
                        echo $content->ghersteller;
                     ?>
                  </td>
                  <td>
                     <?php
                        echo $content->kategorie;
                     ?>
                  </td>
                  <td>
                     <?php
                        echo $content->preis;
                     ?>
                  </td>
               </tr>
               <?php
               }}
               ?>
            </tbody>
         </table>
      </div>
   </BODY>
</HTML>
