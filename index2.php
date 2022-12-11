<?php
    session_start();
    if (!isset($_SESSION["visibility"])) $_SESSION["visibility"] = "hidden";
    if (!isset($_SESSION["id"])) $_SESSION["id"] = 1;
    echo $_SESSION["id"];
    if (!isset($_COOKIE['refreshId'])) setcookie("refreshId", 0);
    if (!isset($_COOKIE['id'])) setcookie("id", 1);
    echo "<br>";
    echo $_COOKIE['refreshId'];
    echo "<br>";
    echo $_COOKIE['id'];
    

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

    // if (isset($_POST['wyloguj'])) {
    //     $_SESSION["username"] = "";
    //     $_SESSION["role"]     = "";
    //     header("Location: index.php");
    // }

    if (isset($_POST['cancel'])) {
        $_SESSION["visibility"] = "hidden";
        setcookie("refreshId", 0);
        header("Refresh:0");
    }
    
?>

<!-- http://localhost:8080/wykres/index2.php?width=1000&height=500&margin=100&days=28 -->
<!-- http://localhost/wykres/index2.php?width=1000&height=500&margin=100&days=28 -->

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Wykres</title>
        <style>
            .dialog {
                visibility: <?php echo $_SESSION["visibility"] ?>;
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

            input[type=submit], input[type=text] {
                width: 140px;
                margin-top: 15px;
            }
        </style>
    </head>
    <body>
        <?php
            // echo '<img src="http://localhost:8080/wykres/index.php?width=' . $width . '&height=' . $height . '&margin=' . $margin . '&days=' . $days . '" alt="Wykres" usemap="#wykres">'
            echo '<img src="http://localhost/wykres/index.php?width=' . $width . '&height=' . $height . '&margin=' . $margin . '&days=' . $days . '" alt="Wykres" usemap="#wykres">'
        ?>
        <map name="wykres">
            <?php
                for ($i = 1; $i <= $days; $i++) {
                    $areas = '<area shape="circle" coords="';

                    if ($rows[$i - 1]["stan"] == "brak") {
                        $x = $margin + 50 + ($chart_width / $days) * $i;
                        $y = $margin + $chart_height;
                        $areas .= strval($x) . ',' . strval($y) . ',10';
                    }
                    else if ($rows[$i - 1]["stan"] == "choroba") {
                        $x = $margin + 50 + ($chart_width / $days) * $i;
                        $y = $margin + $chart_height;
                        $areas .= strval($x) . ',' . strval($y) . ',10';
                    }
                    else {
                        $x = $margin + 50 + ($chart_width / $days) * $i;
                        $y = $margin + ($chart_height / 6.0) * ((37.2 - $rows[$i - 1]["temp"]) / 0.2);
                        $areas .= strval($x) . ',' . strval($y) . ',10';
                    }

                    //$areas .= '" href="" onclick="showWindow(' . $i . ')">';
                    
                    $areas .= '" href="javascript:showWindow(' . $i . ')">';
                    // echo "<script>console.log(" . json_encode($areas, JSON_HEX_TAG) . ")</script>";
                    echo $areas;
                }
            ?>
        </map>

        <div class="dialog" id="dialog">
            <form action="" method="post">
                <input type="text" value=<?php echo $rows[$_SESSION["id"] - 1]["temp"] ?> name="temp" id="temp" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)'><br>
                <input type="submit" name="save" value="Zapisz temperaturę"><br>
                <input type="submit" name="choroba" value="Choroba"><br>
                <input type="submit" name="brak" value="Brak pomiaru"><br>
                <input type="submit" name="cancel" value="Anuluj"><br>
            </form>
        </div>

        <script>
            function showWindow(id) {
                // document.cookie="id=" + id.toString();
                // document.cookie="refreshId=1";
                alert(id)
                
                // document.getElementById("dialog").style.visibility = "visible"
            }
        </script>
    </body>
</html>