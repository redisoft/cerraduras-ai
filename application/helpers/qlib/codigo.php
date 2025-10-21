<?php

include_once("qrlib.php");

$cadenaCodigoBarras = "?re=LOAD850511SX3&rr=LOAD850511SX3&tt=0000000123.123456&id=ad662d33-6934-459c-a128-BDf0393f0f44";
QRcode::png($cadenaCodigoBarras, 'test.png', 'L', 4, 2);

echo '<img src="'.$PNG_WEB_DIR.basename("test.png").'" />'

?>

    