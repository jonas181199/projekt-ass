<?php
   include_once '../includes/dbh.inc.php';
   session_start();

   //Nur wenn als Markt angemeldet Zugang zu dieser Seite und wenn zuvor ein Getränk hinzugefügt wurde.
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
         if(!isset($gname) || strlen($gname) == 0 || 
         !isset($ghersteller) || strlen($ghersteller) == 0 || 
         !isset($kategorie) || strlen($kategorie) == 0 ||
         !isset($preis) || strlen($preis) == 0){
            echo "Bitte füllen Sie die erforderlichen Felder aus!";
            return;
         }

         //Stored Function (insertgetraenke) aufrufen
         $stmt = $conn->prepare("SELECT insertgetraenk(?, ?, ?, ?) AS insertgetraenk");
         $stmt->bind_param("sssd", $ghersteller, $gname, $kategorie, $preis);
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
            echo "Der Preis muss größer 0.00 sein";
            return;
         }
         if ($insertErgebnis->insertgetraenk == 2){
            echo "Das Getränk in der Kombination Getränkename, Getränkehersteller ist bereits vorhanden.";
            return;
         }
         ?>




   </BODY>
</HTML>