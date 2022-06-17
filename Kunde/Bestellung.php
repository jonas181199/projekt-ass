<?php
    include_once '../includes/dbh.inc.php';
    session_start();

    if ((empty($_SESSION['mid']) OR empty($_SESSION['anzPosition'])) AND !isset($_POST['Bestätigen'])) {
        header('Location: ../Index.php');
        exit;
    }

    if (isset($_POST['Bestätigen'])){
        $_SESSION['anzPosition'] = mysqli_real_escape_string($conn, $_POST['anzPosition']);
        $_SESSION['mid']         = mysqli_real_escape_string($conn, $_POST['mid']);
    }
?>

<!-- Julian Alber -->
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
                for ($i = 1; $i <= $_SESSION['anzPosition']; $i++){
                    echo "Bestellposition " . $i;
                    $gname = "gname" . $i;
                    $hname = "hname" . $i;
                    $menge = "menge" . $i;
            ?>
            <p>
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