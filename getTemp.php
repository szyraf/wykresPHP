<?php
    include("hidden.php");

    $dsn = 'mysql:dbname=' . $dbname . ';host=' . $host . '';
    $dbh = new PDO($dsn, $user, $password);
    $sth = $dbh -> query('SELECT * FROM wykres');
    $rows = $sth -> fetchAll();

    $id = $_REQUEST["id"];

    echo $rows[$id - 1]["temp"] == 0 ? "" : $rows[$id - 1]["temp"];
?>