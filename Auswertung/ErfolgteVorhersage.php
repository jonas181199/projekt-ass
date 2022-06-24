<?php
    session_start();
    include_once '../classes/auswertung.php';
    include_once '../includes/dbh.inc.php';

    //Nur wenn als Markt angemeldet Zugang zu dieser Seite
    if (empty($_SESSION['mid'])) {
        header('Location: ../Anmeldung/Marktanmeldung.php');
        exit;
     }
?>

<!-- Jonas Schirm -->
<!-- Beschreibung:
	   Die Umsatzprognose für die kommende Woche wird angezeigt.
       Wenn eine Kategorie ausgewählt wurde, wird die Umsatzprognose für diese Kategorie durchgeführt. -->
<?php
//Prüfen, ob Post gesetzt ist
if(isset($_POST['vorhersage'])){
    //Datum in Form von Jahr Monat Tag speichern
    $eingabe_datum = date('Y-m-d');
    //Datumsstring bei - teilen --> array [0] y, [1] m, [2] d
    $eingabe_datum_arr = explode('-', $eingabe_datum);
    //Gesetzte Auswertungskategorie - default "%" als Platzhalter
    $auswertung_kat = $_POST['kategorie'];

    //speichert aktuelles Jahr
    $akJahr   = idate('Y');
    //speichert aktuelle Woche
	$akWoche  = date('W');
    //Stringtotimestamp
    $timestamp_montag  = date("Y-m-d", strtotime("{$akJahr}-W{$akWoche}"));
    //Der Sonntag der Vorwoche
    $timestamp_sonntag = date("Y-m-d", strtotime("{$akJahr}-W{$akWoche}-7")); 

    //heute unix Zeitstempel für ein Datum - zusammengesetzt aus explode
    $startdatum_time = mktime(0,0,0,$eingabe_datum_arr[1],$eingabe_datum_arr[2],$eingabe_datum_arr[0]);
    //Konstruktor Auswertung: Übergabe heutiges Datum, Kategorie , mid, DB Verbindung
    $auswertung = new Auswertung($startdatum_time, $auswertung_kat, $_SESSION['mid'], $conn);
    //Rückgabewert der getGesamtumsatz von Objekt Auswertung speichern
    $umsaetze = $auswertung->getGesamtumsatz($timestamp_montag, $timestamp_sonntag);
    $regressionswert = $auswertung->lineareRegression_calc($umsaetze);
    ?>

    <p>Erwarteter Umsatz für die kommende Woche: <?php echo $regressionswert ?> </p>
<?php } ?>