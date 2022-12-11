<?php
    include("hidden.php");

    $dsn = 'mysql:dbname=' . $dbname . ';host=' . $host . '';
    $dbh = new PDO($dsn, $user, $password);
    $sth = $dbh -> query('SELECT * FROM wykres');
    $rows = $sth -> fetchAll();

    $id = $_REQUEST["id"];
    $action = $_REQUEST["action"];
    $temp = $_REQUEST["temp"];

    if ($action == 0) {
        $sql = "UPDATE wykres SET stan = 'pomiar', temp = '$temp' WHERE id = $id";
        $dbh -> query($sql);
    }
    else if ($action == 1) {
        $sql = "UPDATE wykres SET stan = 'choroba', temp = '0' WHERE id = $id";
        $dbh -> query($sql);
    }
    else {
        $sql = "UPDATE wykres SET stan = 'brak', temp = '0' WHERE id = $id";
        $dbh -> query($sql);
    }
?>