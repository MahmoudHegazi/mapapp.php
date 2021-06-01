<?php
    header('Content-Type: application/json; charset=utf-8');

    $verbindung = include ('db.inc.php');
    $table    = "mhs_helfer_neu";

    // Bei mysqli_query & close müssen die Verbindungsparameter (gepeichert in $verbindung von pgdb.inc.php) immer übergeben werden

    $messages = array();

    $sql = "SELECT * FROM $table";
    $result = mysqli_query($verbindung, $sql);

    if($result){
        while($row = mysqli_fetch_assoc($result)) {
            $message = array(
                "id"=>$row['id'],
                "vn"=>$row['vn'],
                "nn"=>$row['nn'],
                "long"=>floatval($row['longitude']),
                "lat"=>floatval($row['lat']),
                "hilfeart"=>$row['hilfeart'],
                "dringlichkeit"=>intval($row['dringlichkeit']));
                array_push($messages,$message);

        }
    }

    mysqli_close ($verbindung);
    $json = json_encode($messages);
    print($json);
?>