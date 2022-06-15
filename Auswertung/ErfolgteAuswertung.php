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
            if (date("m", strtotime($_POST['start'])) == "01" && (date("W", strtotime($_POST['start'])) == 52 || date("W", strtotime($_POST['start'])) == 53)){
                $eJahr--;
            }    
            else if (date("m", strtotime($_POST['start'])) == "12" && date("W", strtotime($_POST['start'])) == 01){
                $eJahr++;
            }

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

                    if (empty($sgu->gpreis)){
                        $data[$h]['Gesamtumsatz'] = 0;
                    } else{
                        $data[$h]['Gesamtumsatz'] = $sgu->gpreis;
                    }


                    //Größte Bestellung
                    $sqlgb = "SELECT MAX(summe) AS maxSumme FROM (SELECT SUM(g.preis) AS summe, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr) AS summen";                 
                    $resultgb = $conn->query($sqlgb);
                    $sgb = $resultgb->fetch_object();

                    if (empty($sgb->maxSumme)){
                        $data[$h]['Größte Bestellung'] = 0;
                    } else{
                        $data[$h]['Größte Bestellung'] = $sgb->maxSumme;
                    }


                    //Standardabweichung
                    $savg    = "SELECT AVG(g.preis) as average, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr";                       
                    $sanz    = "SELECT count(*) as anzahl, b.bestellnr  FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr";                       
                    $spreise = "SELECT g.preis, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr";                       
                    $abw     = 0;
                    $ianz    = 0; 
                    $iavg    = 0;

                    $resultavg = $conn->query($savg);
                    $avg       = $resultavg->fetch_object();
                    if (!empty($avg->average)){
                        $iavg = $avg->average; 
                    } 

                    $resultanz = $conn->query($sanz);
                    $anz       = $resultanz->fetch_object();
                    if (!empty($anz->anzahl)){
                        $ianz = $anz->anzahl; 
                    } 

                    $resultpreise = $conn->query($spreise);
                    while($preise = $resultpreise->fetch_object()){           
                        $abw += ($preise->preis - $iavg) * ($preise->preis - $iavg);
                    }                   
                    $result = sqrt((1 / ($ianz - 1)) * $abw);
                    $data[$h]['Standardabweichung'] = $result;
                    

                    //Median
                    $sqlm  = "SELECT SUM(g.preis) AS gpreis, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $marktid AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr"; 
                    $resultm = $conn->query($sqlm);
                    $resultm2 = $conn->query($sqlm);
                    $k = 0;                   
                    while($sm = $resultm->fetch_object()){          
                        $preisa[$k] = (double)$sm->gpreis;
                        $k++;
                    }

                    if (!empty($resultm2->fetch_object())){
                        $anzahlElemente = count($preisa);
                        sort($preisa);
                        $mittelwert = floor(($anzahlElemente -1)/2); 

                        if($anzahlElemente % 2 == 0 OR $anzahlElemente == 1) { 
                            $median = $preisa[$mittelwert];
                        } else { 
                            $low    = $preisa[$mittelwert];
                            $high   = $preisa[$mittelwert+1];
                            $median = (($low+$high)/2);
                        }
                        $data[$h]['Median'] = $median;        
                    } 
                    else {
                        $data[$h]['Median'] = 0;
                    }    
                    unset($preisa);              
                    $h++;
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