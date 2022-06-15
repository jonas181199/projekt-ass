<?php
    session_start();
    include_once '../classes/auswertung.php';
    include_once '../includes/dbh.inc.php'
?>

<?php
if(isset($_POST['vorhersage'])){
    $eingabedatum = $_POST['start'];
	$kategorie = $_POST['kategorie'];
    $auswertung = new Auswertung($eingabedatum, $kategorie, $_SESSION['mid'], $conn);
    $regression = $auswertung->getUmsatzNaechsteWoche($umsaetze[date("W Y", time())]);?>

    <p>Erwarteter Umsatz für die kommende Woche: <?php $regression ?> </p>
<?php } ?>