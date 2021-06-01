<?php

 // Dies sind die Standart Zugangsdaten bei Verwendung von XAMPP
    $server = "localhost";
    $user   = "";
    $pass   = "";
    $db     = "map2";

// In diesem Schritt wird die Verbindung zur Datenbank hergestellt. Der Operator "DIE" sorgt bei Fehlschlag für eine

	$verbindung = new mysqli($server , $user , $pass , $db) or die ("Konnte Verbindung zur Datenbank nicht herstellen!");

    //echo "<br>Verbindung hergestellt.";
    return $verbindung;
?>
