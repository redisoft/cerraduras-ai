<?php
echo'
<script>
$(document).ready(function()
{
	$("#tablaComprasInformacion tr:even").addClass("sombreado");
	$("#tablaComprasInformacion tr:odd").addClass("sinSombra");  
});
</script>

<div style="width:90%; margin-top:1%;">
	<ul id="pagination-digg" class="ajax-pagComprasInformacion">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" id="tablaComprasInformacion" style="margin-top:3px; width:100%">
	<tr>
		<th class="encabezadoPrincipal sinBordeDerecha" colspan="5">Detalles de entradas por compras</th>
		<th class="encabezadoPrincipal sinBordeIzquierda">Total: '.round($total,2).'</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Precio</th>
		<th>Orden</th>
		<th>Proveedor</th>
		<th>Cantidad</th>
	</tr>';
	
$cantidad	= 0;
$i			= $limite;
foreach($compras as $row)
{
	$cantidad	+=$row->cantidad;
	echo'
	<tr>
		<td align="right">'.$i.'</td>
		<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
		<td align="right">$ '.number_format($row->precio,2).'</td>
		<td align="center">'.$row->nombre.'</td>
		<td align="left">'.$row->proveedor.'</td>
		<td align="center">'.round($row->cantidad,2).'</td>
	</tr>';
		
	$i++;
}
	
echo '
</table>';
