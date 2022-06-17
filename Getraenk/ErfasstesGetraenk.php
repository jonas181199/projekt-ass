<?php
   include_once '../includes/dbh.inc.php';
   session_start();
   if (empty($_SESSION['mid']) OR !isset($_POST['ghinzufuegen'])) {
      header('Location: ../Anmeldung/Marktanmeldung.php');
      exit;
   }
?>

<!-- Jonas Schirm -->
<!-- Beschreibung:
      Es wird überprüft, dass alle Felder befüllt sind.
      Das Getränk darf in der Kombination Getränkename und -hersteller noch nicht existieren und muss einen Preis größer 0,00 haben.
      Für das Hinzufügen in die Datenbank wird eine Stored Function genutzt.
      Nach Hinzufügen kann der Marktverantwortliche weitere Getränke hinzufügen oder zurück zu den Funktionen des Markts. -->
<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Hinzufügen eines Getränks</title>
   </HEAD>
   <BODY>
      <?php
         //Variablen erzeugen
         $gname = mysqli_real_escape_string($conn, $_POST['gname']);
         $ghersteller = mysqli_real_escape_string($conn, $_POST['ghersteller']);
         $kategorie = mysqli_real_escape_string($conn, $_POST['kategorie']);
         $preis = mysqli_real_escape_string($conn, $_POST['preis']);

         //Prüfen, ob alle Felder befüllt
         if(empty($gname) || empty($ghersteller) || empty($kategorie) || empty($preis)){
               echo "Bitte füllen Sie die erforderlichen Felder aus!";
               return;
         }

         //Stored Function (insertgetraenke) aufrufen
         $stmt = $conn->prepare("SELECT insertgetraenk(?, ?, ?, ?) AS insertgetraenk");
         $stmt->bind_param("sssi", $ghersteller, $gname, $kategorie, $preis);
         $stmt->execute();
         $result = $stmt->get_result();
         $insertErgebnis = $result->fetch_object();
         ?>

         <!-- Weiterleitung -->
         <form action="Getraenkeerfassen.php">
            <p>
               <input type="submit" name="gerfassen" value="Erfassen Sie ein weiteres Getränk">
            </p>
         </form>

         <form action="../Anmeldung/Markt.php">
            <p>
               <input type="submit" name="markt" value="Zurück zu den Funktionen des Markts">
            </p>
         </form>

         <?php
         //Fehlerbehandlung Stored Function (insertgetraenke)
         if($insertErgebnis->insertgetraenk == 0){
            echo "Das Getränk wurde hinzugefügt";
            return;
         } 
         if ($insertErgebnis->insertgetraenk == 1){
            echo "Der Preis muss größer 0,00 sein";
            return;
         }
         if ($insertErgebnis->insertgetraenk == 2){
            echo "Das Getränk in der Kombination Getränkename, Getränkehersteller ist bereits vorhanden.";
            return;
         }
         
         //Möglichkeit eines Inserts ohne Stored Function
         /* $sql = "insert into getraenke (gname, ghersteller, kategorie, preis) values ('" . $gname. "', '" . $ghersteller. "', '" . $kategorie. "', '" . $preis. "')";
          if ($conn->query($sql) == false){
             echo "Fehler <br>";
             if(mysqli_errno($conn) == 1062){ //Catch Duplicate Key
                echo "Das Getränk ist bereits vorhanden";
             } else {
                echo $conn->error;
             }
          }
          else {
             echo "Das Getränk wurde erfolgreich der Datenbank hinzugefügt";
          }
          $conn->close();
         */ 
         ?>




   </BODY>
</HTML>