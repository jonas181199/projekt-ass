<!-- Noah Schöne -->
<!-- Beschreibung:
     Diese Klasse stellt die einzelnen Funktionen zur Verfügung, die benötigt werden die eingegebenen Daten des Nutzer 
     bei der Lageraktualisierung zu überprüfen und diese durchzuführen-->
<?php
    include_once '../includes/dbh.inc.php' 
?>

<?php 
    class Lager {

        private $mid;
        private $gname;
        private $ghersteller;
        private $lagerbest;
        private $conn;


        function __construct($mid, $gname, $ghersteller, $lagerbest, $conn)    {
            $this->mid = $mid;
            $this->gname = $gname;
            $this->ghersteller = $ghersteller;
            $this->lagerbest = $lagerbest;
            $this->conn = $conn;
        }

        //Diese Funktion prüft, ob alle Felder befüllt sind
        function felderPrüfen() {
        
            if(empty($this->gname) || empty($this->ghersteller) || empty($this->lagerbest)){
                return true;
            }
        }

        //Diese Funktion prüft, ob die eingegebene Getränk-Hersteller Kombination existiert
        function kombinationPrüfen()    {
            
            $getraenk = $this->conn->query("select * from getraenke where gname = '$this->gname' AND ghersteller = '$this->ghersteller'");
            while($s = $getraenk->fetch_object()){
            $data[] = $s;
            }
            if(empty($data)){
                return true;
            }
        }

        //Diese Funktion prüft, ob in das Lagerbestand-Feld eine ganze Zahl eingegeben Wurde
        function prüfeGanzeZahl()   {
            
            if(intval($this->lagerbest) != $this->lagerbest)   {
                return true;
                }
        }

        function replaceLagerbestand()  {

            //Aufruf der Funktionen, zum prüfen der Eingabe 
            if(!$this->felderPrüfen() == false)   {
                echo "Bitte füllen Sie die erforderlichen Felder aus!";
                $this->conn->close();
                return;
            }
            if(!$this->kombinationPrüfen() == false)   {
                echo "Dieser Hersteller bietet das ausgewählte Getränk nicht an!";
                $this->conn->close();
                return;
            }
            if(!$this->prüfeGanzeZahl() == false)   {
                echo "Der Lagerbestand kann nur eine Ganzzahl sein!";
                $this->conn->close();
                return;
            }
            
            
            //Aufruf der stored function, um die Lager-Tabelle zu aktualisieren und um zu prüfen, ob
            //der eingegebene Bestand nicht negativ ist
            $stmt = $this->conn->prepare("SELECT replacelagerbestand(?, ?, ?, ?) AS replacelagerbestand");
            $stmt->bind_param("sssi", $this->mid, $this->gname, $this->ghersteller, $this->lagerbest);
            $stmt->execute();
            $result = $stmt->get_result();
            $replaceLager = $result->fetch_object();

            if($replaceLager->replacelagerbestand == 0)  {
                echo "Der kleinste Lagerbestand ist 0!";
            } else {
                echo "Der Lagerbestand wurde erfolgreich aktualisiert.";
            }
            $this->conn->close();
        }
    }
?>