<?php
$mensaje	= "";
$faltante	= "";

foreach($materiales as $row)
{
	$cantidad	= $cantidadOrden;
	$inventario	= $row->inventario-$row->salidas;
	
	if($row->idConversion>0 and $row->valor>0)
	{
		$cantidad	= (1/$row->valor)*$cantidadOrden;
	}
	
	if($cantidad>$inventario)
	{
		$faltante.="\n".$row->nombre.', Faltante: '.round($cantidad,3);
	}
}

if(strlen($faltante)>0)
{
	$mensaje="Los siguientes materiales no tienen suficiente inventario ¿Realmente desea continuar? \n";
}
else
{
	$mensaje="¿Realmente desea registrar la orden?";
}

echo $mensaje.$faltante;