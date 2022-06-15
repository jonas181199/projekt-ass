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

    function __construct($startdatum, $kategorie, $mid, $conn){
        $this->startdatum = $startdatum;
        $this->kategorie = $kategorie;
        $this->mid = $mid;
        $this->conn = $conn;
        $anzkw = $this->setKW($startdatum);
        $this->KW = $anzkw;
        $KWUmsatz = $this->setUmsatzVonKW($conn, $anzkw);
        $this->KWUmsatz= $KWUmsatz;
    }

    //Diese Funktion bestimmt den letzten Wochentag einer KW
	public function letzterWT($datum) {
		date_default_timezone_set('Europe/Berlin');
		// Wenn Zeitwechsel von Sommerzeit zu Winterzeit
		if ('23' == date("H", $datum))
	  		$datum = $datum + 60*60;
	    $wochentag = date("W", $datum);
	    if ($wochentag == 0)
	    	$letzterTag = $datum + 60 * 60 * 24 - 1;
	    else {
	    	$zusaetzlicherTag = 8 - $wochentag;
	    	$letzterTag = $datum + $zusaetzlicherTag * 60 * 60 * 24 - 1;
	    }
	    // Wenn Zeitwechsel von Winterzeit zu Sommerzeit
	    if ('00' == date("H",$letzterTag))
	  		$letzterTag = $letzterTag - 60*60;
	    return $letzterTag;
	}

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
		} while ( $ZPstart < time());
		return $KW;
    }

    //Diese Funktion ermittelt den Umsatz der KW
    public function setUmsatzVonKW($conn, $KW){
		$ZPstart = $this->startdatum;
        $ZPende = $this->letzterWT($ZPstart);
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
        AND b.bestdatum BETWEEN $ZPstart
        AND $ZPende
        group by b.bestellnr;";
		foreach ($KW as $key => $value) {
			$resultu = $conn->query($sqlu);
			$gu = $resultu->fetch_all();
			$umsatzArray = [];
			foreach ($gu as $umsatz) {
				$umsatzArray[] = $umsatz['Umsatz'];
			}
			$KWUmsatz[$key] = $umsatzArray;
		}
		return $KWUmsatz;
	}

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
		$jetzt = time();
		$subpopulation = [];
		//Die Regressionsrechnung basiert auf den Daten der letzten 12 Wochen.
        //Dies ist wurde intern entschieden.
        //Eine Anpassung ist über $zeitraum möglich 
		$zeitraum = 12;
		$startBetrachtung = $jetzt - 60 * 60 * 24 * 7 * $zeitraum - 1; 
		$betrachtung = $this->setKW($startBetrachtung);
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
		$arithmetisches_mittel_y = (float) $ges_x / $zeitraum;
		$arithmetisches_mittel_x_qu = (float) $arithmetisches_mittel_x * $arithmetisches_mittel_x;
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

}

?>