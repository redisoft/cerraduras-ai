<?php
echo'
<script>
$(document).ready(function()
{
	$("#tablaVentasInformacion tr:even").addClass("sombreado");
	$("#tablaVentasInformacion tr:odd").addClass("sinSombra");  
});
</script>

<div style="width:90%; margin-top:1%;">
	<ul id="pagination-digg" class="ajax-pagVentasInformacion">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" id="tablaVentasInformacion" style="margin-top:3px; width:100%">
	<tr>
		<th class="encabezadoPrincipal sinBordeDerecha" colspan="5">Detalles de salidas por ventas</th>
		<th class="encabezadoPrincipal sinBordeIzquierda">Total: '.round($total,2).'</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Precio</th>
		<th>Orden</th>
		<th>Cliente</th>
		<th>Cantidad</th>
	</tr>';
	
$cantidad	= 0;
$i			= $limite;
foreach($ventas as $row)
{
	$cantidad	+=$row->cantidad;
		
	echo'
	<tr>
		<td align="right">'.$i.'</td>
		<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
		<td align="right">$ '.number_format($row->precio,2).'</td>
		<td align="center">'.$row->ordenCompra.'</td>
		<td align="left">'.$row->empresa.'</td>
		<td align="center">'.round($row->cantidad,2).'</td>
	</tr>';

	$i++;
}
	
echo '
</table>';
