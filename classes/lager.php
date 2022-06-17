<!-- Noah Schöne -->
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

    function replaceLagerbestand()  {

        $gname = mysqli_real_escape_string($this->conn, $_POST['gname']);
        $ghersteller = mysqli_real_escape_string($this->conn, $_POST['ghersteller']);
        $lagerbest = mysqli_real_escape_string($this->conn, $_POST['lagerbest']);

        //Prüfen, ob alle Felder befüllt
        if(empty($gname) || empty($ghersteller) || empty($lagerbest)){
        echo "Bitte füllen Sie die erforderlichen Felder aus!";
        return;
        } 
        
        //Prüfen, ob Getränk-Hersteller Kombination existiert
        $getraenk = $this->conn->query("select * from getraenke where gname = '$gname' AND ghersteller = '$ghersteller'");
        while($s = $getraenk->fetch_object()){
        $data[] = $s;
        }
        if(empty($data)){
        echo "Dieser Hersteller bietet das ausgewählte Getränk nicht an!";
        return;
        }

        //Prüfen, ob Lagerbestand ganze Zahl
        if(intval($lagerbest) != $lagerbest)   {
        echo "Der Lagerbestand kann nur eine Ganzzahl sein!";
        return;
        }
         
        //Aufruf der stored function, um die Lager-Tabelle zu aktualisieren und um zu prüfen, ob
        //der eingegebene Bestand nicht negativ ist
        $stmt = $this->conn->prepare("SELECT replacelagerbestand(?, ?, ?, ?) AS replacelagerbestand");
        $stmt->bind_param("sssi", $this->mid, $this->gname, $this->ghersteller, $this->lagerbest);
        $stmt->execute();
        $result = $stmt->get_result();
        $replaceLager = $result->fetch_object();
        echo $replaceLager->replacelagerbestand;

        if($replaceLager->replacelagerbestand == 0)  {
            echo "Der kleinste Lagerbestand ist 0!";
        } else {
            echo "Der Lagerbestand wurde erfolgreich aktualisiert.";
        }
        $this->conn->close();
    }
}
?>