<?php
 
 include_once 'includes/dbh.inc.php';
 session_start();

?>

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
         <a href="Markt.php">Zurück zum Markt</a>
      </p>

      <form method="POST" action="HinzugefuegterLagerbestand.php">
         <fieldset>
            <legend> Lagerbestand anpassen </legend>
            <p>
               <label for="gname">Getraenkename: </label>
               <input type="text" name="gname" id="gname">
            </p>
            <p>
               <label for="ghersteller">Getraenkehersteller: </label>
               <input type="text" name="ghersteller" id="ghersteller">
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


      <?php

         $mid = mysqli_real_escape_string($conn, $_SESSION['mid']);

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
               <th scope="col">Getränkename</th>
               <th scope="col">Getränkehersteller</th>
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
