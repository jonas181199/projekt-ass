<?php

//Verschlüsseln
$passwort = "passwort";
$hash = password_hash($passwort, PASSWORD_BCRYPT);
echo $hash;

//Entschlüsseln
if(password_verify($passwort, $hash)){
    echo "passwort stimmt";
} else {
    echo "passwort stimmt nicht";
}
