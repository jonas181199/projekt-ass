<!-- Jonas Schirm -->
<?php

    include_once '../includes/dbh.inc.php'
?>

<?php 
class Auswertung{

    private $startdatum;
    private $kategorie;
    private $mid;
    private $KW;
    private $zeitraumRegression;
    private $KWUmsatz;
    private $KWUmsatzSum;
    private $umsatzFolgewoche;
    private $marktid;
    private $kalenderWochen;
    private $betrachtungszeitraumRegression;
    private $wochenUmsaetze;
    private $wochenUmsatzSummen;
    private $groessteWochenumsaetze;
    private $mediane;
    private $standardabweichungen;

    function __construct($startdatum, $kategorie, $mid, $conn) {
    	$this->startdatum = $startdatum;
    	$this->kategorie = $kategorie;
    	$this->mid = $mid;
    	$this->conn = $conn;

    	$kws = $this->setKalenderWochen($startdatum);
    	$this->kalenderWochen = $kws;
    	$wochenUmsaetze = $this->setWochenUmsaetze($kws);
    	$this->wochenUmsaetze = $wochenUmsaetze;
    }

	public function letzterWochentag($datum) {
		date_default_timezone_set('Europe/Berlin');
		// Zeitzonenwechsel: Sommer nach Winter
		if ('23' == date("H",$datum))
	  		$datum = $datum + 60*60;
	    $day_of_week = date("w", $datum);
	    if ($day_of_week == 0)
	    	$end_of_week = $datum + 60 * 60 * 24 - 1;
	    else {
	    	$additional_days = 8 - $day_of_week;
	    	$end_of_week = $datum + $additional_days * 60 * 60 * 24 - 1;
	    }
	    // Zeitzonenwechsel: Winter nach Sommer
	    if ('00' == date("H",$end_of_week))
	  		$end_of_week = $end_of_week - 60*60;
	    return $end_of_week;
	}

	private function setKalenderWochen($startdatum) {
    	date_default_timezone_set('Europe/Berlin');
    	$startzeitpunkt = $startdatum;
    	// KalenderWochen definieren
    	$kalenderWochen = [];
    	do {
			$kw = date("W Y", $startzeitpunkt);
			$endzeitpunkt = $this->letzterWochentag($startzeitpunkt);
			$startzeitpunkt_formatiert = date("Y-m-d", $startzeitpunkt);
			$endzeitpunkt_formatiert = date("Y-m-d", $endzeitpunkt);
			
			//Kalenderwoche mit Start und Ende erzeugen
			$kalenderWochen[$kw]['startzeitpunkt'] = $startzeitpunkt_formatiert;
			$kalenderWochen[$kw]['endzeitpunkt'] = $endzeitpunkt_formatiert;

			// Nächste Woche einläuten
			$startzeitpunkt = $endzeitpunkt + 1;
			$endzeitpunkt = $this->letzterWochentag($startzeitpunkt);
		} while ( $startzeitpunkt < time());

		return $kalenderWochen;
    }

	public function setWochenUmsaetze($kalenderWochen){
		$mid = $this->mid;
		$kategorie = $this->kategorie;
		$wochenUmsaetze = [];
		$stmt = $this->conn->prepare("SELECT SUM(p.ganzahl * g.preis) as Umsatz 
			from bestellpos p, getraenke g, bestellung b 
			where p.bestellnr = b.bestellnr 
			AND g.ghersteller = p.ghersteller 
			AND g.gname = p.gname 
			AND b.mid = ?
			AND g.kategorie like ?
			AND b.bestdatum BETWEEN ?
			AND ?
			group by b.bestellnr;");
		foreach ($kalenderWochen as $key => $value) {
			$stmt->bind_param("ssii", $mid, $kategorie, $value['startzeitpunkt'], $value['endzeitpunkt']);
			$stmt->execute();
			$result = $stmt->get_result();
			$ergebnis = $result->fetch_all();
			$umsatzArray = [];
			foreach ($ergebnis as $umsatz) {
				$umsatzArray[] = $umsatz['Umsatz'];
			}
			$wochenUmsaetze[$key] = $umsatzArray;
			}
			return $wochenUmsaetze;
	}

	private function berechnungUmsatzSummen($kalenderWochen, $umsaetze) {
		$wochenUmsatzSummen = [];
		foreach ($kalenderWochen as $key => $value) {
			$umsatzsumme = 0;
			if (count($umsaetze[$key])) {
				foreach ($umsaetze[$key] as $umsatz) {
					$umsatzsumme += $umsatz;
				}
			}
			$wochenUmsatzSummen[$key] = $umsatzsumme;
		}
		return $wochenUmsatzSummen;
	}



	//Lineare Regression zur Umsatzprognose der aktuellen Woche folgenden Woche
	public function berechnungLineareRegression ($aktuellerWert) {
		$mid = $this->mid;
		$kategorie = $this->kategorie;
		$jetzt = time();
		$subpopulation = [];
		// Als Datengrundlage für die Regressionsrechnung werden jeweils die letzten 12 Wochen vor der aktuellen Woche verwendet.
		// Dies wurde von der Gruppe so entschieden, und lässt sich über den Parameter $betrachtungszeitraum ändern. 
		$betrachtungszeitraum = 6;
		$startBetrachtung = $jetzt - 60 * 60 * 24 * 7 * $betrachtungszeitraum - 1; 
		$betrachtung = $this->setKalenderWochen($startBetrachtung);
		$this->betrachtungszeitraumRegression = $betrachtung;
		$umsaetze = $this->setWochenUmsaetze($betrachtung);
		$wochenUmsaetze = $this->berechnungUmsatzSummen($betrachtung, $umsaetze);
		$i = 0;
		$ges_x = 0;
		$ges_y = 0;
		$ges_x_qu = 0;
		$ges_x_mal_y = 0;
		foreach ($this->betrachtungszeitraumRegression as $key => $value) {
			// Datum der folgewoche ermitteln.
			// Da es noch keine Zukunftswerte in der Datenbank gibt, kann dort auch nichts ausgelesen werden.
			if ($i < $betrachtungszeitraum) {
				$date = explode(' ', $key);
				$folgewoche = strtotime(sprintf('%dW%02d', $date[1], $date[0]));
				$folgeKW = date("W Y", $folgewoche+604800);
			}
			$subpopulation[$i]['Woche'] = $key;
			$subpopulation[$i]['Umsatz x'] = $wochenUmsaetze[$key];
			$ges_x += $subpopulation[$i]['Umsatz x'];
			$subpopulation[$i]['nachfolgenderUmsatz y'] = $wochenUmsaetze[$folgeKW];
			$ges_y += $subpopulation[$i]['nachfolgenderUmsatz y'];
			$subpopulation[$i]['x-quadrat'] = $wochenUmsaetze[$key]*$wochenUmsaetze[$key];
			$ges_x_qu += $subpopulation[$i]['x-quadrat'];
			$subpopulation[$i]['x*y'] = $wochenUmsaetze[$key]*$wochenUmsaetze[$folgeKW];
			$ges_x_mal_y += $subpopulation[$i]['x*y'];
			$i++;
		}
		// Da die aktuelle Woche nicht abgeschlossen ist, und ihr Zukunftswert nicht bekannt ist, wird sie entfernt.
		unset($subpopulation[$betrachtungszeitraum]);

		$arith_mittel_x = (float) $ges_x/$betrachtungszeitraum;
		$arith_mittel_y = (float) $ges_x/$betrachtungszeitraum;
		$arith_mittel_x_qu = (float) $arith_mittel_x * $arith_mittel_x;

		// Berechnung der Regressionsgeraden y
		$b_dividend = $ges_x_mal_y - $betrachtungszeitraum * $arith_mittel_x * $arith_mittel_y;
		$b_divisor = $ges_x_qu - $betrachtungszeitraum * $arith_mittel_x_qu;
		$y = 0;
		if ($b_divisor <> 0) {
			$b = $b_dividend/$b_divisor;
			$a = $arith_mittel_y-$b*$arith_mittel_x;
			$y = $a + $b * $aktuellerWert;
		}
		return $y;

	}

	public function getUmsatzFolgewoche($aktuellerWert) {
		$conn = $this->conn;
		$umsatzFolgewoche = $this->berechnungLineareRegression($aktuellerWert);
		$this->umsatzFolgewoche = $umsatzFolgewoche;
		return $this->umsatzFolgewoche;
	}

	public function getWochenumsatz() {
		$kalenderWochen = $this->kalenderWochen;
		$umsaetze = $this->wochenUmsaetze;
		$this->wochenUmsatzSummen = $this->berechnungUmsatzSummen($kalenderWochen, $umsaetze);
		return $this->wochenUmsatzSummen;
	}

































































	/*
    // //Diese Funktion bestimmt den letzten Wochentag einer KW
	// public function letzterWT($datum) {
	// 	date_default_timezone_set('Europe/Berlin');
	// 	// Wenn Zeitwechsel von Sommerzeit zu Winterzeit
	// 	if ('23' == date("H", $datum))
	//   		$datum = $datum + 60 * 60;
	//     $wochentag = date("W", $datum);
	//     if ($wochentag == 0)
	//     	$letzterTag = $datum + 60 * 60 * 24 - 1;
	//     else {
	//     	$zusaetzlicherTag = 8 - $wochentag;
	//     	$letzterTag = $datum + $zusaetzlicherTag * 60 * 60 * 24 - 1;
	//     }
	//     // Wenn Zeitwechsel von Winterzeit zu Sommerzeit
	//     if ('00' == date("H", $letzterTag))
	//   		$letzterTag = $letzterTag - 60 * 60;
	//     return $letzterTag;
	// }

    //Diese Funktion bestimmt die KW
    private function setKW($startdatum) {
    	date_default_timezone_set('Europe/Berlin');
    	$ZPstart = $startdatum;
    	//Definieren von Kalenderwochen
    	$KW = [];
    	do {
			$kw = date("W Y", $ZPstart);
			$ZPende = $this->letzterWT($ZPstart);

            //Start und Ende in das richtige Datumsformat bringen
			$ZPstart_formatiert = date("Y-m-d H:i:s", $ZPstart);
			$ZPende_formatiert = date("Y-m-d H:i:s", $ZPende);

			//Erstellen einer KW mit KWStart und KWEnde
			$KW[$kw]['ZPstart'] = $ZPstart_formatiert;
			$KW[$kw]['ZPende'] = $ZPende_formatiert;

			//Definition der nächsten Woche
			$ZPstart = $ZPende + 1;
			$ZPende = $this->letzterWT($ZPstart);
		} while ($ZPstart < time());
		return $KW;
    }

    //Diese Funktion ermittelt den Umsatz der KW
    public function setUmsatzVonKW($conn, $KW){

		// $ZPstart = $this->startdatum;
        // $ZPende = $this->letzterWT($ZPstart);
		$akJahr   = idate('Y');
		$akWoche  = date('W');
		$ewoche   = date('W', strtotime("-12 weeks"));
		$h        = 0;
		$eTag = date('Y-m-d', strtotime("-84 days"));


		// Jahresgrenzending
		//wenn Woche 1 bspw. im alten Jahr beginnt oder W 53 in neuem Jahr
		$eJahr = date("Y", strtotime($eTag));
		if (date("m", strtotime($eTag)) == "01" && (date("W", strtotime($eTag)) == 52 || date("W", strtotime($eTag)) == 53)){
			$eJahr--;
		}    
		else if (date("m", strtotime($eTag)) == "12" && date("W", strtotime($eTag)) == 01){
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
				
				$mid = $this->mid;
				$kategorie = $this->kategorie;
				$KWUmsatz = [];
				$sqlu = "SELECT SUM(p.ganzahl * g.preis) as Umsatz 
				from bestellpos p, getraenke g, bestellung b 
				where p.bestellnr = b.bestellnr 
				AND g.ghersteller = p.ghersteller 
				AND g.gname = p.gname 
				AND b.mid = $mid;
				AND g.kategorie like $kategorie
				AND b.bestdatum BETWEEN $timestamp_montag
				AND $timestamp_sonntag
				group by b.bestellnr;";
				
				$resultu = $conn->query($sqlu);
	 			$gu = $resultu->fetch_object();
				$KWUmsatz[$h] = $gu->Umsatz;
				$h++;
			}
			$ewoche = 1;
        }
		return $KWUmsatz;
	}

    //     $mid = $this->mid;
	// 	$kategorie = $this->kategorie;
	// 	$KWUmsatz = [];
    //     $sqlu = "SELECT SUM(p.ganzahl * g.preis) as Umsatz 
    //     from bestellpos p, getraenke g, bestellung b 
    //     where p.bestellnr = b.bestellnr 
    //     AND g.ghersteller = p.ghersteller 
    //     AND g.gname = p.gname 
    //     AND b.mid = $mid;
    //     AND g.kategorie like $kategorie
    //     AND b.bestdatum BETWEEN $ZPstart
    //     AND $ZPende
    //     group by b.bestellnr;";
	// 	foreach ($KW as $key => $value) {
	// 		$resultu = $conn->query($sqlu);
	// 		$gu = $resultu->fetch_object();
	// 		$umsatzArray = [];
	// 		foreach ($gu as $umsatz) {
	// 			$umsatzArray[] = $umsatz['Umsatz'];
	// 		}
	// 		$KWUmsatz[$key] = $umsatzArray;
	// 	}
	// 	return $KWUmsatz;
	// }

    //Durch diese Funktion werden die bisherige gesamte Umsatzsumme berechnet
    private function berechnungUmsatzSummen($KW, $umsaetze) {
		$KWUmsatzSum = [];
		foreach ($KW as $key => $value) {
			$umsatzsumme = 0;
			if (count($umsaetze[$key])) {
				foreach ($umsaetze[$key] as $umsatz) {
					$umsatzsumme += $umsatz;
				}
			}
			$KWUmsatzSum[$key] = $umsatzsumme;
		}
		return $KWUmsatzSum;
	}

	
    //Berechnung der linearen Regression zur Prognose des Umsatzes für die der aktuellen Woche folgenden Woche
	public function lineareRegression ($conn, $aktuellerWert) {
		$mid = $this->mid;
		$kategorie = $this->kategorie;
		$jetzt = date('Y-m-d');
		$subpopulation = [];
		//Die Regressionsrechnung basiert auf den Daten der letzten 12 Wochen.
        //Dies ist wurde intern entschieden.
        //Eine Anpassung ist über $zeitraum möglich 
		$zeitraum = 12;
		$startBetrachtung = date('W', strtotime("-12 weeks"));
		$betrachtung = $startBetrachtung;
		$this->zeitraumRegression = $betrachtung;
		$umsaetze = $this->setUmsatzVonKW($conn, $betrachtung);
		$KWUmsatz = $this->berechnungUmsatzSummen($betrachtung, $umsaetze);
		$i = 0;
		$ges_x = 0;
		$ges_y = 0;
		$ges_x_qu = 0;
		$ges_x_mal_y = 0;
		foreach ($this->zeitraumRegression as $key => $value) {
			//Ermittlung des Datums für kommmende Woche
			if ($i < $zeitraum) {
				$date = explode(' ', $key); //teilt das Datum auf
				$kommendeWoche = strtotime(sprintf('%dW%02d', $date[1], $date[0])); //KW zu timestamp umwandeln, [1] = woche, [0] = jahr
				$kommendeKW = date("W Y", $kommendeWoche+604800); //Sekunden einer Woche
			}
			$subpopulation[$i]['Woche'] = $key;
			$subpopulation[$i]['Umsatz x'] = $KWUmsatz[$key];
			$ges_x += $subpopulation[$i]['Umsatz x'];
			$subpopulation[$i]['kommenderUmsatz y'] = $KWUmsatz[$kommendeKW];
			$ges_y += $subpopulation[$i]['kommenderUmsatz y'];
			$subpopulation[$i]['x-quadrat'] = $KWUmsatz[$key] * $KWUmsatz[$key];
			$ges_x_qu += $subpopulation[$i]['x-quadrat'];
			$subpopulation[$i]['x*y'] = $KWUmsatz[$key] * $KWUmsatz[$kommendeKW];
			$ges_x_mal_y += $subpopulation[$i]['x*y'];
			$i++;
		}

        //Die aktuelle KW ist noch nicht abgeschlossen - der Zukunftswert noch nicht bekannt. Somit wird sie gelöscht.
		unset($subpopulation[$zeitraum]);

		$arithmetisches_mittel_x = (float) $ges_x / $zeitraum;
		$arithmetisches_mittel_y = (float) $ges_y / $zeitraum;
		$arithmetisches_mittel_x_qu = (float) $arithmetisches_mittel_x * $arithmetisches_mittel_y;
		//Berechnen der Regressionsgeraden y
		$b_dividend = $ges_x_mal_y - $zeitraum * $arithmetisches_mittel_x * $arithmetisches_mittel_y;
		$b_divisor = $ges_x_qu - $zeitraum * $arithmetisches_mittel_x_qu;
		$y = 0;
		if ($b_divisor <> 0) {
			$b = $b_dividend/$b_divisor;
			$a = $arithmetisches_mittel_y - $b * $arithmetisches_mittel_x;
			$y = $a + $b * $aktuellerWert;
		}
		return $y;

	}
	

    public function getUmsatzNaechsteWoche($aktuellerWert) {
		$conn = $this->conn;
		$umsatzFolgewoche = $this->lineareRegression($conn, $aktuellerWert);
		$this->umsatzFolgewoche = $umsatzFolgewoche;
		return $this->umsatzFolgewoche;
	}

	*/



	private function getGesamtumsatz($timestamp_montag, $timestamp_sonntag){
		$sqlgu = "SELECT SUM(g.preis * bp.ganzahl) AS gpreis FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $this->mid AND g.kategorie like '$this->kategorie' AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag'";                       
		$resultgu = $this->conn->query($sqlgu);
		$sgu = $resultgu->fetch_object();
		if (empty($sgu->gpreis)){
			return 0;
		} else{
			return $sgu->gpreis;
		}
	}


	private function getGroesteBestellung($timestamp_montag, $timestamp_sonntag){
		$sqlgb = "SELECT MAX(summe) AS maxSumme FROM (SELECT SUM(g.preis * bp.ganzahl) AS summe, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $this->mid AND g.kategorie like '$this->kategorie' AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr) AS summen";                 
		$resultgb = $this->conn->query($sqlgb);
		$sgb = $resultgb->fetch_object();
		if (empty($sgb->maxSumme)){
			return 0;
		} else{
			return $sgb->maxSumme;
		}
	}


	private function getStandardabweichung($timestamp_montag, $timestamp_sonntag){		
	
		$savg    = "SELECT AVG(g.preis * bp.ganzahl) as average FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $this->mid AND g.kategorie like '$this->kategorie' AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag'";                                          
		$spreise = "SELECT (g.preis * bp.ganzahl) as gpreis, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $this->mid AND g.kategorie like '$this->kategorie' AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr";                       
		$iavg    = 0;
		$abw     = 0;
		$ianz    = 0; 

		$resultavg = $this->conn->query($savg);
		$avg       = $resultavg->fetch_object();
		if (!empty($avg->average)){
			$iavg = $avg->average; 
		} 

		$resultpreise = $this->conn->query($spreise);
		while($preise = $resultpreise->fetch_object()){           
			$abw += ($preise->gpreis - $iavg) * ($preise->gpreis - $iavg);
			$ianz++;
		}  

		if($ianz > 1){	                 
			$result = sqrt((1 / ($ianz - 1)) * $abw);
			return $result;
		} else{
			return 0;
		}                  
	}


	private function getMedian($timestamp_montag, $timestamp_sonntag){
		
		$smedian  = "SELECT SUM(g.preis * bp.ganzahl) AS gpreis, b.bestellnr FROM bestellpos bp, bestellung b, getraenke g where bp.bestellnr = b.bestellnr AND bp.ghersteller = g.ghersteller AND bp.gname = g.gname AND b.mid = $this->mid AND g.kategorie like '$this->kategorie' AND b.bestdatum >= '$timestamp_montag' AND b.bestdatum <= '$timestamp_sonntag' GROUP BY b.bestellnr"; 
		$resultmedian = $this->conn->query($smedian);
		unset($preisa);
		$k = 0;                   
		while($sm = $resultmedian->fetch_object()){          
			$preisa[$k] = (double)$sm->gpreis;
			$k++;
		}

		if ($k != 0){
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
			return $median;        
		} 
		else {
			return 0;
		}    
	}



	public function getAuswertungsTabelle(){

		$akJahr   = idate('Y');
		$akWoche  = date('W');
		$ewoche   = date('W', strtotime($this->startdatum));
		$data[][] = null;
		$h        = 0;

		// Jahresgrenzending
		$eJahr = date("Y", strtotime($this->startdatum));
		if (date("m", strtotime($this->startdatum)) == "01" && (date("W", strtotime($this->startdatum)) == 52 || date("W", strtotime($this->startdatum)) == 53)){
			$eJahr--;
		}    
		else if (date("m", strtotime($this->startdatum)) == "12" && date("W", strtotime($this->startdatum)) == 01){
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
				$data[$h]['Gesamtumsatz'] = $this->getGesamtumsatz($timestamp_montag, $timestamp_sonntag);

				//Größte Bestellung
				$data[$h]['Größte Bestellung'] = $this->getGroesteBestellung($timestamp_montag, $timestamp_sonntag);

				//Standardabweichung
				$data[$h]['Standardabweichung'] = $this->getStandardabweichung($timestamp_montag, $timestamp_sonntag);

				//Median
				$data[$h]['Median'] = $this->getMedian($timestamp_montag, $timestamp_sonntag);
		
				$h++;
			}
			$ewoche = 1;
        }
		return $data;
	}
}

?>