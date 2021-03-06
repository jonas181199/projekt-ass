<?php
   include_once '../includes/dbh.inc.php';
   include_once '../classes/lager.php';
   session_start();

   if (empty($_SESSION['mid'])) {
      header('Location: ../Anmeldung/Marktanmeldung.php');
      exit;
   }
?>

<!-- Noah Schöne -->
<!-- Beschreibung:
     Dem Marktverantwortlichen werden Drop-Down Felder für die Getränkehersteller und die jeweiligen Getränke angezeigt, sowie ein Eingabefeld für den Lagerbestand.
     Darunter kann der Nutzer den aktuellen Lagerbestand in Form einer Tabelle einsehen. -->
<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Lagerverwaltung</title>
   </HEAD>
   <BODY>
      <!-- HTML-Körper -->
      <h1> Lagerverwaltung </h1>

      <p>
         <a href="../Anmeldung/Markt.php">Zurück zum Markt</a>
      </p>

      <!-- Felder befüllen -->
      <form method="POST" action="HinzugefuegterLagerbestand.php">
         <fieldset>
            <legend> Lagerbestand anpassen </legend>
            <p>
            <label for="ghersteller">Hersteller: </label>
               <select name="ghersteller">
                   <?php
                     $hersteller  = $conn->query("select distinct ghersteller from getraenke");
                        while(($s = $hersteller->fetch_object()) != false){
                   ?>  
                     <option><?php echo $s->ghersteller; ?></option>
                   <?php      
                       }
                   ?>
               </select>

               <label for="gname">Getränkename: </label>
               <select name="gname">
                  <?php
                        $getraenke  = $conn->query("select distinct gname from getraenke");
                        while(($s = $getraenke->fetch_object()) != false){
                    ?>  
                        <option><?php echo $s->gname; ?></option>
                    <?php      
                        }
                    ?>
               </select> 
            </p>
            <p>
               <label for="lagerbest">Lagerbestand: </label>
               <input type="text" name="lagerbest" id="lagerbest">
            </p>
            <p>
               <input type="submit" name="bestanderf" value="Lagerbestand erfassen"> 
            </p>
         </fieldset>
      </form>


      <!-- Lagertabelle -->
      <?php

         $mid = mysqli_real_escape_string($conn, $_SESSION['mid']);

         //Einträge aus der Datenbank holen
         $sql = "SELECT * FROM lager WHERE mid = $mid ORDER BY gname";
         if($result = $conn->query($sql)){
            while($ds = $result->fetch_object()){
               $data[] = $ds;
            }
         }
      ?>
      <div class=row> 

      
      <table border="2" cellspacing=2 cellpadding=5>
         <thead>
            <tr>
               <th scope="col">Getränkehersteller</th>
               <th scope="col">Getränkename</th>
               <th scope="col">Lagerbestand</th>
            </tr>
         </thead>
         <tbody>
         <?php
            if(empty($data)){
                  echo("Es sind noch keine Getränke im Lager vorhanden.");
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
                     echo $content->bestand;
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
