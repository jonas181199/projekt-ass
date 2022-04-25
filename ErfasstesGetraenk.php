<?php

include_once 'includes/dbh.inc.php'

?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Hinzufügen eines Getränks</title>
   </HEAD>
   <BODY>
      <!-- HTML-Körper -->
      <?php

         echo $_POST['gname'];
         echo $_POST['ghersteller'];
         echo $_POST['kategorie'];
         echo $_POST['preis'];

/*          $gname = ($conn, $_POST['gname']);
         $ghersteller = ($conn, $_POST['ghersteller']);
         $kategorie = ($conn, $_POST['kategorie']);
         $preis = ($conn, $_POST['preis']); */

         if(isset($_POST['hinzufügen'])){

            if(empty($_POST['gname'])){
               echo "Bitte geben Sie einen Namen für das Getraenk an";
            }
            if(empty($_POST['ghersteller'])){
               echo "Bitte geben Sie einen Hersteller des Getränks an";
            }
            if(empty($_POST['kategorie'])){
               echo "Bitte geben Sie eine Kategorie an";
            }
            if(empty($_POST['preis'])){
               echo "Bitte geben Sie einen Preis an";
            }

         }



         //Prüfen, dass alle Felder befüllt
         /* if(!isset($_POST['gname']) || strlen($_POST['gname']) == 0 || 
         !isset($_POST['ghersteller']) || strlen($_POST['ghersteller']) == 0 || 
         !isset($_POST['kategorie']) || strlen($_POST['kategorie']) == 0 ||
         !isset($_POST['preis']) || strlen($_POST['preis']) == 0){
            echo "Bitte füllen Sie die erforderlichen Felder aus!";
         } else {
            $gname */
         // Check connection
         
        
         // prepare and bind
         /* $sql = "INSERT INTO getraenke (gname, ghersteller, kategorie, preis) VALUES (?, ?, ?, ?);";
         $stmt = mysqli_stmt_init($conn);
         if(!mysqli_stmt_prepare($stmt, $sql)){
            echo "SQL error";
         } else {
            mysqli_stmt_bind_param($stmt, "sssd", $gname, $ghersteller, $kategorie, $preis);
            mysqli_stmt_execute($stmt);
         } */
         //$stmt = $conn->prepare();
         //$stmt->bind_param("sssd", $gname, $ghersteller, $kategorie, $preis);
      
         

         
         //echo "New records created successfully";
         
         //$stmt->close();
         //$conn->close();
         ?>

   </BODY>
</HTML>