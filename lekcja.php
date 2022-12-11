<?php
    header('Content-Type: image/png');
    
    $img = imagecreatetruecolor(200,200);
    $c=imagecolorallocate($img, 255, 0, 0);
    $b=imagecolorallocate($img, 0, 0, 0);
    
    imagefilledrectangle($img,0,0,200,200,$c);
    imagestring($img, 15, 0, 0, 'Hello world!', $b);
    
    $arr=[$c, $c, $c, $b, $b, $b];
    imagesetstyle($img, $arr);
    imageline($img, 0,0,200,200,IMG_COLOR_STYLED);
    
    imagepng($img);
    imagedestroy($img);
?>