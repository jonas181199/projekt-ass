<?php
    include_once '../includes/dbh.inc.php';
    session_start();
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
            $marktid = $_SESSION['mid'];
            $akJahr   = idate('Y');
            $akWoche  = date('W');
            $ewoche   = date('W', strtotime($_POST['start']));
            $data[][] = null;
            $h        = 0;

            // Jahresgrenzending
            $eJahr = date("Y", strtotime($_POST['start']));
            if (date("m", strtotime($_POST['start'])) == "01" && (date("W", strtotime($_POST['start'])) == 52 || date("W", strtotime($_POST['start'])) == 53))
                $eJahr--;
            else if (date("m", strtotime($_POST['start'])) == "12" && date("W", strtotime($_POST['start'])) == 01)
                $eJahr++;


            for($j = $eJahr; $j <= $akJahr; $j++)  {

                if($j < $akJahr) {
                    $anzW = idate('W', mktime(0, 0, 0, 12, 28, $j));
                } elseif($j == $akJahr)  {
                    $anzW = $akWoche;
                }

                for($i = $ewoche; $i <= $anzW; $i++) {

                    $timestamp_montag  = date("Y-m-d", strtotime("{$j}-W{$i}"));
                    $timestamp_sonntag = date("Y-m-d", strtotime("{$j}-W{$i}-7"));
                    
                    $data[$h]['KW'] = $i;                   

                    //Gesamtumsatz
                    $sqlgu = "SELECT SUM(g.preis) AS gpreis FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag'";                       
                    $resultgu = $conn->query($sqlgu);
                    $sgu = $resultgu->fetch_object();
                    echo $sqlgu;
                    echo $sgu->gpreis;
                    if (empty($sgu->gpreis)){
                        $data[$h]['Gesamtumsatz'] = 0;
                    } else{
                        $data[$h]['Gesamtumsatz'] = $sgu->gpreis;
                    }


                    //Größte Bestellung
                    //$sqlgb = "SELECT SUM(g.preis) as summe, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.bestdatum >= $timestamp_montag AND b.bestdatum <= $timestamp_sonntag GROUP BY b.bestellnr HAVING MAX(SUM(g.preis))";                 
                    $sqlgb = "SELECT MAX(summe) AS maxSumme FROM (SELECT SUM(g.preis) AS summe, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr) AS summen";                 
                    $resultgb = $conn->query($sqlgb);
                    $sgb = $resultgb->fetch_object();

                    if (empty($sgb->maxSumme)){
                        $data[$h]['Größte Bestellung'] = 0;
                    } else{
                        $data[$h]['Größte Bestellung'] = $sgb->maxSumme;
                    }


                    //Standartabweichung
                    $savg    = "SELECT AVG(g.preis) as average, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr";                       
                    $sanz    = "SELECT count(*) as anzahl, b.bestellnr  FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr";                       
                    $spreise = "SELECT g.preis, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr";                       
                    $abw     = 0;
                    $ianz    = 0; 

                    $resultavg = $conn->query($savg);
                    $avg       = $resultavg->fetch_object();

                    $resultanz = $conn->query($sanz);
                    $anz       = $resultanz->fetch_object();

                    if (!empty($anz->anzahl)){
                        $ianz = $anz->anzahl; 
                    } 

                    $resultpreise = $conn->query($spreise);
                    while($preise = $resultpreise->fetch_object()){
            
                        $abw += ($preise->preis - $avg->average) * ($preise->preis - $avg->average);
                    }
                    $result = sqrt((1 / ($ianz - 1)) * $abw);
                    $data[$h]['Standartabweichung'] = $result;
                    
                    //Median
                    if ($ianz > 0){
                        $sqlm = "SELECT SUM(g.preis) AS preis FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag'";                       
                        $resultm = $conn->query($sqlm);
                        $sm = $resultm->fetch_object();

                        $preisa[] = $resultpreise->fetch_assoc(); 

                        if ($ianz > 0){
                            $mittelwert = floor(($ianz -1)/2); 
                        }
                        else {
                            $mittelwert = 0;
                        }
                        
                        if($ianz % 2) { 
                            $median = $preisa[$mittelwert];
                        } else { 
                            $low = $preisa[$mittelwert];
                            $high = $preisa[$mittelwert+1];
                            $median = (($low+$high)/2);
                        }
                        $data[$h]['Median'] = $median;
                    } else {
                        $data[$h]['Median'] = 0;
                    }

                    $h++;
                }
                $ewoche = 1;
            }

            print_r($data);
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