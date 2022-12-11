<?php
    include("hidden.php");

    $dsn = 'mysql:dbname=' . $dbname . ';host=' . $host . '';
    $dbh = new PDO($dsn, $user, $password);
    $sth = $dbh -> query('SELECT * FROM wykres');
    $rows = $sth -> fetchAll();

    $width  = isset($_GET['width'])  ? $_GET['width']  : 1000;
    $height = isset($_GET['height']) ? $_GET['height'] : 500;
    $margin = isset($_GET['margin']) ? $_GET['margin'] : 100;
    $days   = isset($_GET['days'])   ? $_GET['days']   : 28;

    $chart_width =  $width  - 2 * $margin - 50;
    $chart_height = $height - 2 * $margin - 50;
?>

<!-- localhost:8080 for mac -->
<!-- http://localhost/wykres/index.php?width=1000&height=500&margin=100&days=28 -->

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Wykres</title>
        <style>
            .dialog {
                visibility: hidden;
                position: absolute;
                top: 50%;
                left: 50%;
                margin-left: -100px;
                margin-top: -100px;
                width: 200px;
                height: 200px;
                background-color: #00fff2;
                border: 1px solid black;
                text-align: center;
            }

            input[type=submit], input[type=text], button {
                width: 140px;
                margin-top: 15px;
            }
        </style>
    </head>
    <body>
        <?php
            // localhost:8080 for mac
            echo '<img src="http://localhost/wykres/wykres.php?width=' . $width . '&height=' . $height . '&margin=' . $margin . '&days=' . $days . '&t=0" alt="Wykres" usemap="#wykres" id="wykres">'
        ?>
        <map name="wykres">
            <?php
                for ($i = 1; $i <= $days; $i++) {
                    $areas = '<area shape="circle" id="area' . $i . '" coords="';

                    if ($rows[$i - 1]["stan"] == "brak" || $rows[$i - 1]["stan"] == "choroba") {
                        $x = $margin + 50 + ($chart_width / $days) * $i;
                        $y = $margin + $chart_height;
                        $areas .= strval($x) . ',' . strval($y) . ',10';
                    }
                    else {
                        $x = $margin + 50 + ($chart_width / $days) * $i;
                        $y = $margin + ($chart_height / 6.0) * ((37.2 - $rows[$i - 1]["temp"]) / 0.2);
                        $areas .= strval($x) . ',' . strval($y) . ',10';
                    }
                    
                    $areas .= '" href="javascript:showWindow(' . $i . ')">';
                    // echo "<script>console.log(" . json_encode($areas, JSON_HEX_TAG) . ")</script>";
                    echo $areas;
                }
            ?>
        </map>

        <br><a href="makePDF.php">Pobierz plik PDF</a>

        <div class="dialog" id="dialog">
            <input type="text" value="" name="temp" id="temp" placeholder="temperatura" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' autocomplete="off"><br>
            <button onclick="save()">Zapisz temperaturę</button><br>
            <button onclick="choroba()">Choroba</button><br>
            <button onclick="brak()">Brak pomiaru</button><br>
            <button onclick="hideDialog()">Anuluj</button><br>
        </div>

        <script>
            let lastId = 1

            function showWindow(id) {
                lastId = id
                let xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById('temp').value = this.responseText
                        document.getElementById('dialog').style.visibility = 'visible'
                    }
                }
                xmlhttp.open('GET', 'getTemp.php?id=' + id, true)
                xmlhttp.send()
            }

            function save() {
                if (parseFloat(document.getElementById('temp').value) >= 36.0 && parseFloat(document.getElementById('temp').value) <= 37.2) {
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById('wykres').src = document.getElementById('wykres').src.substring(0, document.getElementById('wykres').src.indexOf('&t=')) + '&t=' + new Date().getTime()
                        }
                    }
                    xmlhttp.open('GET', 'updateDatabase.php?id=' + lastId + '&action=0&temp=' + document.getElementById('temp').value, true)
                    xmlhttp.send()
                    updateArea(true)
                }
                hideDialog()
            }

            function choroba() {
                let xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById('wykres').src = document.getElementById('wykres').src.substring(0, document.getElementById('wykres').src.indexOf('&t=')) + '&t=' + new Date().getTime()
                    }
                }
                xmlhttp.open('GET', 'updateDatabase.php?id=' + lastId + '&action=1&temp=0', true)
                xmlhttp.send()
                updateArea(false)
                hideDialog()
            }

            function brak() {
                let xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById('wykres').src = document.getElementById('wykres').src.substring(0, document.getElementById('wykres').src.indexOf('&t=')) + '&t=' + new Date().getTime()
                    }
                }
                xmlhttp.open('GET', 'updateDatabase.php?id=' + lastId + '&action=2&temp=0', true)
                xmlhttp.send()
                updateArea(false)
                hideDialog()
            }

            function hideDialog() {
                document.getElementById('dialog').style.visibility = 'hidden'
            }
            
            function updateArea(isTemp) {                
                console.log(document.getElementById('area' + lastId.toString()).coords)
                let width  = !isNaN(parseInt(findGetParameter('width')))  ? parseInt(findGetParameter('width'))  : 1000
                let height = !isNaN(parseInt(findGetParameter('height'))) ? parseInt(findGetParameter('height')) : 500
                let margin = !isNaN(parseInt(findGetParameter('margin'))) ? parseInt(findGetParameter('margin')) : 100
                let days   = !isNaN(parseInt(findGetParameter('days')))   ? parseInt(findGetParameter('days'))   : 28
                
                let chart_width =  width  - 2 * margin - 50;
                let chart_height = height - 2 * margin - 50;

                let string = ''
                if (!isTemp) {
                    let x = margin + 50 + (chart_width / days) * lastId
                    let y = margin + chart_height
                    string += x.toString() + ',' + y.toString() + ',10';
                }
                else {
                    let x = margin + 50 + (chart_width / days) * lastId;
                    let y = margin + (chart_height / 6.0) * ((37.2 - parseFloat(document.getElementById('temp').value)) / 0.2);
                    string += x.toString() + ',' + y.toString() + ',10';
                }

                document.getElementById('area' + lastId.toString()).coords = string                
            }

            function findGetParameter(parameterName) {
                let result = null, tmp = []
                location.search.substr(1).split("&").forEach(function (item) {
                    tmp = item.split("=");
                    if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
                });
                return result;
            }

            function makePDF() {
                let xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        
                    }
                }
                xmlhttp.open('GET', 'makePDF.php', true)
                xmlhttp.send()
            }
        </script>
    </body>
</html>