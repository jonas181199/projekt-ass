<?php
    include_once '../includes/dbh.inc.php';
    include_once '../classes/auswertung.php';
    session_start();

    if (empty($_SESSION['mid'])) {
        header('Location: ../Anmeldung/Marktanmeldung.php');
        exit;
    }
    if (empty($_POST['start'])) {
        header('Location: Auswertung.php');
        exit;
    }
?>

<!-- Julian Alber -->
<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Getränkeverwaltung</title>
    </HEAD>
    <BODY>
        <?php
            //Objekt erzeugen
            $auswertung = new Auswertung($_POST['start'], $_POST['kategorie'], $_SESSION['mid'], $conn);  
            $data[][] = null;          
            $data = $auswertung->getAuswertungsTabelle();
        ?>

        <div class="row">
            <table border="2" cellspacing=2 cellpadding=5>
                <thead>
                    <tr>
                        <th scope="col">Kalenderwoche</th>
                        <th scope="col">Gesamtumsatz</th>
                        <th scope="col">Größte Bestellung</th>
                        <th scope="col">Standardabweichung</th>
                        <th scope="col">Median aller Bestellungen</th>
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
                                echo $content['KW'];
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $content['Gesamtumsatz'];
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $content['Größte Bestellung'];
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $content['Standardabweichung'];
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $content['Median'];
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