<?php
   include_once '../includes/dbh.inc.php';
?>

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
                for ($i = 0; $i < $_POST['anzPosition']; $i++){
                    echo "Bestellposition " . $i+1;
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
                <input type="hidden" name="anzPosition" id="anzPosition" value="<?php echo $_POST['anzPosition']; ?>">
                <input type="hidden" name="mid" id="mid" value="<?php echo $_POST['mid']; ?>">
            </p>
            <p>
               <input type="submit" name="bPrüfen" value="Bestellung prüfen">
            </p>
         </fieldset>
      </form>
   </BODY>
</HTML>