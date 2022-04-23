<?php

$servername = "localhost";
$dbusername = "root";
$dbpasswort = "";
$dbname = "formforuser";

$conn = mysqli_connect($servername, $dbusername, $dbpasswort, $dbname);

if (!$conn) {
die("CONNECTION FAILED: " .mysqli_connect_error());  // check if connection is FAILED

}

error_reporting(E_ALL);
ini_set("display_errors", 1);
