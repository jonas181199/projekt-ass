<?php
    include_once '../includes/dbh.inc.php'
?>

<?php 
	class Auswertung {

		private $start_datum;
		private $kategorie;
		private $mid;

		function __construct($start_datum, $kategorie, $mid, $conn) {
			$this->start_datum = $start_datum;
			$this->kategorie = $kategorie;
			$this->mid = $mid;
			$this->conn = $conn;
		}

		/** Jonas Schirm */
		//Berechnung der Lineare Regression zur Umsatzprognose der aktuellen Woche folgenden Woche
		public function lineareRegression_calc ($aktuellerWert) {
			$conn = $this->conn;
			$akJahr   = idate('Y');
			$eJahr    = date('Y', strtotime("-16 weeks"));
			$akWoche  = date('W');
			$ewoche   = date('W', strtotime("-16 weeks"));
			$this->start_datum = date("Y-m-d", strtotime("{$eJahr}-W{$ewoche}"));
			$data = [];
			$h    = 0;


			//Jahresgrenzending
			if (date("m", strtotime($this->start_datum)) == "01" && (date("W", strtotime($this->start_datum)) == 52 || date("W", strtotime($this->start_datum)) == 53)){
				$eJahr--;
			}    
			else if (date("m", strtotime($this->start_datum)) == "12" && date("W", strtotime($this->start_datum)) == 01){
				$eJahr++;
			}

			//
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
				}
				$ewoche = 1;
			}

			$hilfsarray = [];
			$i = 0;
			$gesamtx = 0;
			$gesamty = 0;
			$quadratgesamtx = 0;
			$produktxy = 0;
			foreach ($data as $key => $value) {
				$hilfsarray[$i]['Woche'] = $key;
				$hilfsarray[$i]['Umsatz x'] = $value['Gesamtumsatz'];
				$gesamtx += $hilfsarray[$i]['Umsatz x'];
				$hilfsarray[$i]['nachfolgenderUmsatz y'] = $data[$i+1]['Gesamtumsatz'];
				$gesamty += $hilfsarray[$i]['nachfolgenderUmsatz y'];
				$hilfsarray[$i]['x-quadrat'] = $value['Gesamtumsatz'] * $value['Gesamtumsatz'];
				$quadratgesamtx += $hilfsarray[$i]['x-quadrat'];
				$hilfsarray[$i]['x*y'] = $value['Gesamtumsatz'] * $data[$i+1]['Gesamtumsatz'];
				$produktxy += $hilfsarray[$i]['x*y'];
				$i++;
				//Indexposition 15->W16->akt Woche soll nicht eingerechnet werden
				if($i==14){
					break;
				}
			}
			
			$arithmetischesmittelx    = (float) $gesamtx / 16;
			$arithmetischesmittely    = (float) $gesamtx / 16;
			$arithmetischesmittelxx = (float) $arithmetischesmittelx * $arithmetischesmittelx;

			// Berechnung der Regressionsgeraden y
			$dividend = $produktxy - 16 * $arithmetischesmittelx * $arithmetischesmittely;
			$divisor = $quadratgesamtx - 16 * $arithmetischesmittelxx;
			$y = 0;
			if ($divisor != 0) {
				$b = $dividend / $divisor;
				$a = $arithmetischesmittely - $b * $arithmetischesmittelx;
				$y = $a + $b * $aktuellerWert;				//aktueller Wert des Umsatz der aktuellen Woche
			}
			return $y;
		}


		/** Noah Schöne */
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


		/** Julian Alber */
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