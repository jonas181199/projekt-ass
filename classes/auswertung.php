<!-- Jonas Schirm -->
<?php
    include_once '../includes/dbh.inc.php'
?>

<?php 
class Auswertung {

    private $start_datum;
    private $kategorie;
    private $mid;
    private $UmsatzKommendeW;
    private $kws;
    private $bzeitraumregression;
    private $wUmsaetze;
    private $wUmsatzSum;

    function __construct($start_datum, $kategorie, $mid, $conn) {
    	$this->start_datum = $start_datum;
    	$this->kategorie = $kategorie;
    	$this->mid = $mid;
    	$this->conn = $conn;
    }


	//Lineare Regression zur Umsatzprognose der aktuellen Woche folgenden Woche
	public function lineareRegression_calc ($aktuellerWert) {
		$conn = $this->conn;
		$akJahr   = idate('Y');
		$eJahr    = date('Y', strtotime("-16 weeks"));
		$akWoche  = date('W');
		$ewoche   = date('W', strtotime("-16 weeks"));
		$this->start_datum = date("Y-m-d", strtotime("{$eJahr}-W{$ewoche}"));
		$data = [];
		$h    = 0;


		// Jahresgrenzending
		if (date("m", strtotime($this->start_datum)) == "01" && (date("W", strtotime($this->start_datum)) == 52 || date("W", strtotime($this->start_datum)) == 53)){
			$eJahr--;
		}    
		else if (date("m", strtotime($this->start_datum)) == "12" && date("W", strtotime($this->start_datum)) == 01){
			$eJahr++;
		}

		for($j = $eJahr; $j <= $akJahr; $j++)  {

			if($j < $akJahr) {
				$anzW = idate('W', mktime(0, 0, 0, 12, 28, $j));
			} elseif($j == $akJahr)  {
				$anzW = $akWoche;
			}

			for($i = $ewoche+1; $i <= $anzW; $i++) {

				$timestamp_montag  = date("Y-m-d", strtotime("{$j}-W{$i}"));
				$timestamp_sonntag = date("Y-m-d", strtotime("{$j}-W{$i}-7"));                                 
				
				//Gesamtumsatz
				$data[$h]['Gesamtumsatz'] = $this->getGesamtumsatz($timestamp_montag, $timestamp_sonntag);

				$h++;
				echo $h;
			}
			$ewoche = 1;
        }

		var_dump($data);

		$hilfsarray = [];
		$i = 0;
		$ges_x = 0;
		$ges_y = 0;
		$ges_x_qu = 0;
		$ges_x_mal_y = 0;
		foreach ($data as $key => $value) {
			$hilfsarray[$i]['Woche'] = $key;
			$hilfsarray[$i]['Umsatz x'] = $value['Gesamtumsatz'];
			$ges_x += $hilfsarray[$i]['Umsatz x'];
			$hilfsarray[$i]['nachfolgenderUmsatz y'] = $data[$i+1]['Gesamtumsatz'];
			$ges_y += $hilfsarray[$i]['nachfolgenderUmsatz y'];
			$hilfsarray[$i]['x-quadrat'] = $value['Gesamtumsatz']*$value['Gesamtumsatz'];
			$ges_x_qu += $hilfsarray[$i]['x-quadrat'];
			$hilfsarray[$i]['x*y'] = $value['Gesamtumsatz']*$data[$i+1]['Gesamtumsatz'];
			$ges_x_mal_y += $hilfsarray[$i]['x*y'];
			$i++;
			if($i==14){
				break;
			}
		}
		
		$arith_mittel_x    = (float) $ges_x/16;
		$arith_mittel_y    = (float) $ges_x/16;
		$arith_mittel_x_qu = (float) $arith_mittel_x * $arith_mittel_x;

		// Berechnung der Regressionsgeraden y
		$b_dividend = $ges_x_mal_y - 16 * $arith_mittel_x * $arith_mittel_y;
		$b_divisor = $ges_x_qu - 16 * $arith_mittel_x_qu;
		$y = 0;
		if ($b_divisor != 0) {
			$b = $b_dividend/$b_divisor;
			$a = $arith_mittel_y-$b*$arith_mittel_x;
			$y = $a + $b * $aktuellerWert;				//aktueller Wert des Umsatz der aktuellen Woche
		}
		return $y;
	}



	public function getGesamtumsatz($timestamp_montag, $timestamp_sonntag){
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
		$ewoche   = date('W', strtotime($this->start_datum));
		$data[][] = null;
		$h        = 0;

		// Jahresgrenzending
		$eJahr = date("Y", strtotime($this->start_datum));
		if (date("m", strtotime($this->start_datum)) == "01" && (date("W", strtotime($this->start_datum)) == 52 || date("W", strtotime($this->start_datum)) == 53)){
			$eJahr--;
		}    
		else if (date("m", strtotime($this->start_datum)) == "12" && date("W", strtotime($this->start_datum)) == 01){
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