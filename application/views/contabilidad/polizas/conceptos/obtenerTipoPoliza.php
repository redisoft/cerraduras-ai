<?php
$tipo="";
switch($tipoPoliza)
{
	case "1":
	$tipo	= $polizas->polizaIngresos;
	break;
	
	case "2":
	$tipo	= $polizas->polizaEgresos;
	break;
	
	case "3":
	$tipo	= $polizas->polizaDiario;
	break;
}

echo json_encode(array(0=>$tipo,1=>$numero));

?>