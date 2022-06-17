<?php
    include_once '../includes/dbh.inc.php';
    session_start();

    if (empty($_POST['mid']) OR empty($_POST['anzPosition']) OR !isset($_POST['Bestätigen'])) {
        header('Location: ../Index.php');
        exit;
    }
    if (isset($_POST['Bestätigen'])){
        $_SESSION['anzPosition'] = mysqli_real_escape_string($conn, $_POST['anzPosition']);
        $_SESSION['mid']         = mysqli_real_escape_string($conn, $_POST['mid']);
    }
    unset($_POST['Bestätigen']);
?>

<!-- Julian Alber -->
<!-- Beschreibung:
     Dem Nutzer werden genau so viele Eingabefelder für Bestellpositionen angezeigt wie er zuvor eingegeben hat
     Dieser kann über Drop-Down Listen jeweils das Getränk und den Hersteller und die gewünschte Anzahl auswählen -->
<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Startseite</title>
   </HEAD>
   <BODY>
      <form action="BestellungPrüfen.php" method="POST" >
         <fieldset>
            <legend>Bitte die Daten für Ihre Bestellung eingeben</legend>
            <?php          
                // Genau so viele Eingabefelder für Bestellpositionen anzeigen, wie in der index.php eingegeben wurden     
                for ($i = 1; $i <= $_SESSION['anzPosition']; $i++){
                    echo "Bestellposition " . $i;
                    $gname = "gname" . $i;
                    $hname = "hname" . $i;
                    $menge = "menge" . $i;
            ?>
            <p>
                <!-- Sämtliche Getränkenamen aus der Datenbank in einer Auswahlliste anzeigen -->
                <label for="gname">Getränkename: </label>
                <select name="<?php echo $gname; ?>">
                    <?php
                        $getraenke  = $conn->query("select distinct gname from getraenke");
                        while(($s = $getraenke->fetch_object()) != false){
                    ?>  
                        <option><?php echo $s->gname; ?></option>
                    <?php      
                        }
                    ?>
                </select> 

                <!-- Sämtliche Getränkehersteller aus der Datenbank in einer Auswahlliste anzeigen -->
                <label for="hname">Hersteller: </label>
                <select name="<?php echo $hname; ?>">
                    <?php
                        $hersteller  = $conn->query("select distinct ghersteller from getraenke");
                        while(($s = $hersteller->fetch_object()) != false){
                    ?>  
                        <option><?php echo $s->ghersteller; ?></option>
                    <?php      
                        }
                    ?>
                </select> 

                <label for="menge">Menge: </label>
                <input type="text" name="<?php echo $menge; ?>" id="menge">
            </p>
            <?php
                }
            ?>
            <p>
               <input type="submit" name="bPrüfen" value="Bestellung prüfen">
            </p>
         </fieldset>
      </form>
   </BODY>
</HTML>