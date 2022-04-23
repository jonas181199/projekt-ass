<?php

include_once 'includes/dbh.inc.php';


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

         // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO getraenke (gname, ghersteller, kategorie, preis) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $gname, $ghersteller, $kategorie, $preis);
    
        $gname = ($conn, $_POST['gname']);
        $ghersteller = ($conn, $_POST['ghersteller']);
        $kategorie = ($conn, $_POST['kategorie']);
        $preis = ($conn, $_POST['preis']);

        
        echo "New records created successfully";
        
        $stmt->close();
        $conn->close();
        ?>

   </BODY>
</HTML>