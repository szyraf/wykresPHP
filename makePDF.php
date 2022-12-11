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

    
    require_once('TCPDF/tcpdf.php');

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Szymon Rafałowski');
    $pdf->SetTitle('Wykres');

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

    $pdf->setFontSubsetting(true);
    $pdf->SetFont('dejavusans', '', 14, '', true);
    $pdf->AddPage();

    $pdf->setCellPaddings(1, 1, 1, 1);
    $pdf->setCellMargins(1, 1, 1, 1);
    $pdf->SetFillColor(255, 255, 255);

    date_default_timezone_set('Europe/Warsaw');
    $time = date('d-m-Y H:i:s', time());
    $html = '<p style="text-align: center;">PDF wygerenowano: ' . $time . '</p><br>';
    $html .= '<img src="http://localhost/wykres/wykres.php?width=' . $width . '&height=' . $height . '&margin=' . $margin . '&days=' . $days . '&t=1">';
    
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    $pdf->MultiCell(30, 5, 'Legenda:', 0, 'J', 1, 2, 40, 120, true);
    $pdf->MultiCell(50, 5, ' - choroba', 0, 'L', 1, 2, 40, 130, true);
    $pdf->MultiCell(50, 5, ' - brak pomiaru', 0, 'L', 1, 2, 40, 140, true);

    $pdf->MultiCell(30, 5, 'Pomiary:', 0, 'J', 1, 2, 145, 120, true);
    $pdf->SetFont('dejavusans', '', 8, '', true);
    $pdf->MultiCell(20, 5, 'Dzień', 1, 'J', 1, 2, 145, 130, true);
    $pdf->MultiCell(20, 5, 'Temp.', 1, 'J', 1, 2, 155, 130, true);
    for ($i = 1; $i <= $days; $i++) {
        $pdf->MultiCell(20, 5, strval($i), 1, 'J', 1, 2, 145, 130 + $i * 5, true);
        $string = strval($rows[$i - 1]['temp'] == 0 ? ($rows[$i - 1]['stan'] == 'brak' ? 'brak' : 'choroba') : $rows[$i - 1]['temp']);
        $pdf->MultiCell(20, 5, $string, 1, 'J', 1, 2, 155, 130 + $i * 5, true);
    }

    $style_bollino = array('width' => 0.25, 'dash' => 0, 'color' => array(0, 0, 0));
    $pdf->SetAlpha(1);
    $pdf->Circle(40, 135, 2, 0, 360, 'DF', $style_bollino, array(255, 0, 0));
    $pdf->Circle(40, 145, 2, 0, 360, 'DF', $style_bollino, array(128, 128, 128));

    $pdf->Output('wykres.pdf', 'I');
?>