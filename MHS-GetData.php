<?php
    header('Content-Type: application/json; charset=utf-8'); // ausgabe von json liefert und keine HTML

    $verbindung = include ('db.inc.php');
    $table    = "mhs_hilfesuchend";

    // Bei mysqli_query & close müssen die Verbindungsparameter (gepeichert in $verbindung von pgdb.inc.php) immer übergeben werden

    $messages = array(); // erzuegen wir array wo wir alle daten speichern

    $sql = "SELECT * FROM $table";
    $result = mysqli_query($verbindung, $sql);

    if($result){
        while($row = mysqli_fetch_assoc($result)) { // wir haben in row alle eintraege in einzelne zeile //hier wir speichern alle in associativz array
            $message = array(
                "id"=>$row['id'],
                "vn"=>$row['vn'],
                "nn"=>$row['nn'],
                "long"=>floatval($row['longitude']), // floatval nur zum sichern dass um zahlen handelt
                "lat"=>floatval($row['latitude']),
                "hilfeart"=>$row['hilfeart'],
                "dringlichkeit"=>intval($row['dringlichkeit']));
                array_push($messages,$message); // array_push — Fügt ein oder mehr Elemente an das Ende eines Arrays an

        }
    }

    mysqli_close ($verbindung);
    $json = json_encode($messages); // json_encode — Liefert die JSON-Darstellung eines Wertes also wir wandeln unsere array zum json um
    print($json);
?>
