<?php
    include("hidden.php");

    $dsn = 'mysql:dbname=' . $dbname . ';host=' . $host . '';
    $dbh = new PDO($dsn, $user, $password);
    $sth = $dbh -> query('SELECT * FROM wykres');
    $rows = $sth -> fetchAll();

    // http://localhost/wykres/index.php?width=1000&height=500&margin=100&days=28
    // http://localhost:8080/wykres/index.php?width=1000&height=500&margin=100&days=28

    $width  = isset($_GET['width'])  ? $_GET['width']  : 1000;
    $height = isset($_GET['height']) ? $_GET['height'] : 500;
    $margin = isset($_GET['margin']) ? $_GET['margin'] : 100;
    $days   = isset($_GET['days'])   ? $_GET['days']   : 28;

    // 6 x (28 - 31)

    // x: margin + 50px + reszta + margin
    // y: margin + reszta + 50px + margin

    $chart_width = $width - 2 * $margin - 50;
    $chart_height = $height - 2 * $margin - 50;
    
    $img = imagecreatetruecolor($width, $height);

    $red   = imagecolorallocate($img, 255, 0, 0);
    $green = imagecolorallocate($img, 0, 255, 0);
    $blue  = imagecolorallocate($img, 0, 0, 255);
    $gray  = imagecolorallocate($img, 128, 128, 128);
    $white = imagecolorallocate($img, 255, 255, 255);
    $black = imagecolorallocate($img, 0, 0, 0);

    imagefilledrectangle($img, 0, 0, $width, $height, $white);

    $style = [$black, $black, $black, $white, $white, $white];
    imagesetstyle($img, $style);

    for ($i = 0; $i < 7; $i++) {
        imageline($img, $margin + 50, $margin + ($chart_height / 6.0) * $i, $margin + 50 + $chart_width, $margin + ($chart_height / 6.0) * $i, IMG_COLOR_STYLED);
        imagestring($img, 15, $margin + 50 - 50, $margin + ($chart_height / 6.0) * $i - 10, number_format((37.2 - $i * 0.2), 1, '.', ''), $black);
    }
    
    imagesetthickness($img, 3);
    imageline($img, $margin + 50, $margin + ($chart_height / 6.0) * 1, $margin + 50 + $chart_width, $margin + ($chart_height / 6.0) * 1, $red);
    imagesetthickness($img, 1);

    for ($i = 0; $i <= $days; $i++) {
        imageline($img, $margin + 50 + ($chart_width / $days) * $i, $margin, $margin + 50 + ($chart_width / $days) * $i, $margin + $chart_height, IMG_COLOR_STYLED);
        if ($i != 0) imagestring($img, 15, $margin + 50 + ($chart_width / $days) * $i - 10, $margin + $chart_height + 10, $i, $black);
    }

    imageline($img, $margin + 50, $margin, $margin + 50, $margin + $chart_height, $black);
    imageline($img, $margin + 50, $margin + $chart_height, $margin + 50 + $chart_width, $margin + $chart_height, $black);

    $dzien = iconv('UTF-8', 'ISO-8859-2', "Dzień miesiąca");
    $temp = iconv('UTF-8', 'ISO-8859-2', "Temperatura [°C]");

    imagestring($img, 15, $margin + 50 + $chart_width / 2 - 50, $margin + $chart_height + 50, $dzien, $black);
    imagestringup($img, 15, $margin - 50, $margin + $chart_height / 2 + 50, $temp, $black);

    for ($i = 1; $i <= $days; $i++) {
        if ($rows[$i - 1]["stan"] == "brak") {
            $x = $margin + 50 + ($chart_width / $days) * $i;
            $y = $margin + $chart_height;
            imagefilledellipse($img, $x, $y, 10, 10, $gray);
        }
        else if ($rows[$i - 1]["stan"] == "choroba") {
            $x = $margin + 50 + ($chart_width / $days) * $i;
            $y = $margin + $chart_height;
            imagefilledellipse($img, $x, $y, 10, 10, $red);
        }
        else {
            $x = $margin + 50 + ($chart_width / $days) * $i;
            $y = $margin + ($chart_height / 6.0) * ((37.2 - $rows[$i - 1]["temp"]) / 0.2);
            imagefilledellipse($img, $x, $y, 10, 10, $blue);
        }

        if ($i != 1) {
            if ($rows[$i - 2]["stan"] == "pomiar" && $rows[$i - 1]["stan"] == "pomiar") {
                imageline($img, $margin + 50 + ($chart_width / $days) * ($i - 1), $margin + ($chart_height / 6.0) * ((37.2 - $rows[$i - 2]["temp"]) / 0.2), $x, $y, $blue);
            }
        }
    }

    header('Content-Type: image/png');
    imagepng($img);
    imagedestroy($img);
?>