<!-- Julian Alber -->
<?php
    include_once '../includes/dbh.inc.php'
?>

<?php 
    class registrierungKundeKl{

        private $email;
        private $kname;
        private $plz;
        private $ort;
        private $strasse;
        private $hausnummer;
        private $kkennwort;
        private $conn;

        function __construct($email, $kname, $plz, $ort, $strasse, $hausnummer, $kkennwort, $conn){
            $this->email      = $email;
            $this->kname      = $kname;
            $this->plz        = $plz;
            $this->ort        = $ort;
            $this->strasse    = $strasse;
            $this->hausnummer = $hausnummer;
            $this->kkennwort  = $kkennwort;
            $this->conn       = $conn;
        }

        //Diese Funktion prüft, ob der Benutzer sämtliche erforderliche Felder befüllt hat
        function alleFelderBelegt(){
            if(!isset($this->email)      || strlen($this->email) == 0 || 
               !isset($this->kname)      || strlen($this->kname) == 0 || 
               !isset($this->plz)        || strlen($this->plz)   == 0 || 
               !isset($this->ort)        || strlen($this->ort)   == 0 || 
               !isset($this->strasse)    || strlen($this->strasse)    == 0 || 
               !isset($this->hausnummer) || strlen($this->hausnummer) == 0 || 
               !isset($this->kkennwort)  || strlen($this->kkennwort)  == 0) {
            
                return false;
            } 
            return true;
        }
        //Diese Funktion prüft, ob die eingegebene E-mail bereits in der DB existiert
        function emailPruefen(){
            $emails = $this->conn->query("select email from kunde");
            while(($s = $emails->fetch_object()) != false){
               if($s->email == $this->email){
                  return false;
               }
            }
            return true;
        }
        //Diese Funktion fügt den neuen Kunde der DB hinzu
        function kundeHinzufuegen(){
            $sql = "insert into kunde values ('" . $this->email. "', '" . $this->kkennwort . "', '" . $this->kname. "', '" . $this->plz. "', '" . $this->ort. "', '" . $this->strasse. "', '" . $this->hausnummer. "')";
            if ($this->conn->query($sql) == false){
                return false;
            }
            else {
                return true;
            }
        }
    }
?>