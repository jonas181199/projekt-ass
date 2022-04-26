<?php
session_start();
session_destroy();
header('Location: Marktanmeldung.php');
exit;
?>