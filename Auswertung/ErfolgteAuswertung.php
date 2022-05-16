<?php
    include_once '../includes/dbh.inc.php'
?>

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
            $akJahr  = idate('Y');
            $akWoche = date('W');
            $ewoche  = date('W', strtotime($_POST['start']));
            $data[][] = null;

            // Jahresgrenzending
            $eJahr = date("Y", strtotime($_POST['start']));
            if (date("m", strtotime($_POST['start'])) == "01" && (date("W", strtotime($_POST['start'])) == 52 || date("W", strtotime($_POST['start'])) == 53))
                $eJahr--;
            else if (date("m", strtotime($_POST['start'])) == "12" && date("W", strtotime($_POST['start'])) == 01)
                $eJahr++;


            for($j =  $eJahr; $j <= $akJahr; $j++)  {

                if($j < $akJahr) {
                    $anzW = idate('W', mktime(0, 0, 0, 12, 28, $j));
                } elseif($j == $akJahr)  {
                    $anzW = $akWoche;
                }

                for($i = $ewoche; $i <= $anzW; $i++) {

                    $timestamp_montag  = date("Y-m-d", strtotime("{$j}-W{$i}"));
                    $timestamp_sonntag = date("Y-m-d", strtotime("{$j}-W{$i}-7"));
                    
                    $data[0]['KW'] = $i;
                    

                    //Gesamtumsatz
                    $sqlgu = "SELECT SUM(g.preis) FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.bestdatum >= $timestamp_montag AND b.bestdatum <= $timestamp_sonntag";                       
                    $resultgu = $conn->query($sqlgu);
                    $sgu = $resultgu->fetch_object();
                    $data[0]['Gesamtumsatz'] = $sgu;

                    print_r($data);

                    //Größte Bestellung
                    $sqlgb = "SELECT SUM(g.preis) as summe, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.bestdatum >= $timestamp_montag AND b.bestdatum >= $timestamp_sonntag GROUP BY b.bestellnr HAVING MAX(SUM(g.preis))";                 
                    $resultgb = $conn->query($sqlgb);
                    $sgb = $resultgb->fetch_object();
                    $data[0]['Größte Bestellung'] = $sgb->summe;

                    //Standartabweichung
                    $avg = "SELECT AVG FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.bestdatum >= $timestamp_montag AND b.bestdatum >= $timestamp_sonntag GROUP BY b.bestellnr";                       
                    $anz = "SELECT count * FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.bestdatum >= $timestamp_montag AND b.bestdatum >= $timestamp_sonntag GROUP BY b.bestellnr";                       
                    $preise = "SELECT g.preis FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.bestdatum >= $timestamp_montag AND b.bestdatum >= $timestamp_sonntag GROUP BY b.bestellnr";                       
                    $abw = 0;

                    $resultst = $conn->query($preise);
                    while($sst = $resultst->fetch_object()){
                        $abw += ($sst->preis - $avg) * ($sst->preis - $avg);
                    }
                    $result = sqrt( (1/($anz-1)) * $abw);
                    $data[0]['Standartabweichung'] = $result;
                    
                    //Median
                    $sqlm = "SELECT SUM(g.preis) FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.bestdatum >= $timestamp_montag AND b.bestdatum >= $timestamp_sonntag )";                       
                    $resultm = $conn->query($sqlm);
                    $sm = $resultm->fetch_object();

                    $preisa[] = $resultst->fetch_assoc(); 

                    $mittelwert = floor(($anz-1)/2); 
                    if($anz % 2) { 
                        $median = $preisa[$mittelwert];
                    } else { 
                        $low = $preisa[$mittelwert];
                        $high = $preisa[$mittelwert+1];
                        $median = (($low+$high)/2);
                    }
                    $data[0]['Median'] = $median;

                    print_r($data);
                }
                $ewoche = 1;
            }
        ?>

        <div class="row">
            <table border="2" cellspacing=2 cellpadding=5>
                <thead>
                    <tr>
                        <th scope="col">Kalenderwoche</th>
                        <th scope="col">Gesamtumsatz</th>
                        <th scope="col">Größte Bestellung</th>
                        <th scope="col">Standartabweichung</th>
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
                                echo $content['Standartabweichung'];
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
