<?php
    include_once '../includes/dbh.inc.php'
?>

<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <!-- HTML-Kopf -->
      <meta charset=“utf-8“>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Vorhersage</title>
   </HEAD>
   <BODY>

   <?php
    $akJahr  = idate('Y');
    $akWoche = idate('W');
    $ewoche  = idate('W', strtotime("now"));
    $data[][] = null;

    for($i = $ewoche; $i <= $akWoche; $i++){
        $timestamp_montag  = strtotime("{$akJahr}-W{$i}");
        $timestamp_sonntag = strtotime("{$akJahr}-W{$i}-7");
        
        $data[0]['KW'] = $i;

        //Umsatz je Bestellung für eine Kalenderwoche
        $sqlumsatz = "SELECT SUM(g.preis) FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.bestdatum >= $timestamp_montag AND b.bestdatum >= $timestamp_sonntag GROUP BY b.bestellnr";
        $resultumsatz = $conn->query($sqlumsatz);
        //$x = $resultumsatz->fetch_object();
        //$data[0]['Gesamtumsatz'] = $x;
        echo $resultumsatz;

        //Anzahl der Bestellung für eine Kalenderwoche
        $sqlanzahl = "SELECT count(*) FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.bestdatum >= $timestamp_montag AND b.bestdatum >= $timestamp_sonntag GROUP BY b.bestellnr";
        $resultanzahl = $conn->query($sqlanzahl);
        print_r($resultanzahl);

    }

    function linear_regression( $x, $y ) {

        $n     = count($x);     // number of items in the array --> Bestellungen
        $x_sum = array_sum($x); // sum of all X values 
        $y_sum = array_sum($y); // sum of all Y values
    
        $xx_sum = 0;
        $xy_sum = 0;
    
        for($i = 0; $i < $n; $i++) {
            $xy_sum += ( $x[$i]*$y[$i] );
            $xx_sum += ( $x[$i]*$x[$i] );
        }
    
        // Slope
        $slope = ( ( $n * $xy_sum ) - ( $x_sum * $y_sum ) ) / ( ( $n * $xx_sum ) - ( $x_sum * $x_sum ) );
    
        // calculate intercept
        $intercept = ( $y_sum - ( $slope * $x_sum ) ) / $n;
    
        return array( 
            'slope'     => $slope,
            'intercept' => $intercept,
        );
    }

    foreach( $array as $item ) {
        $number = ( $trendarray['slope'] * $item['name'] ) + $trendarray['intercept'];
        $number = ( $number <= 0 )? 0 : $number;
        echo '"'.$number.'", ';
    }

    ?>
   </BODY>
</HTML>

