<?php
echo'
<script>
$(document).ready(function()
{
	$("#tablaMovimientosInformacion tr:even").addClass("sombreado");
	$("#tablaMovimientosInformacion tr:odd").addClass("sinSombra");  
});
</script>

<div style="width:90%; margin-top:1%;">
	<ul id="pagination-digg" class="ajax-pagMovimientosInformacion">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" id="tablaMovimientosInformacion" style="margin-top:3px; width:100%">
	<tr>
		<th class="encabezadoPrincipal sinBordeDerecha" colspan="5">Ajuste manual</th>
		<th class="encabezadoPrincipal sinBordeIzquierda">Total: '.round($total,2).'</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Cantidad</th>
		<th>Movimiento</th>
		<th>Inventario anterior</th>
		<th>Inventario actual</th>
	</tr>';
	
$cantidad	= 0;
$i			= $limite;
foreach($movimientos as $row)
{
	$cantidad	+=$row->cantidad;
		
	echo'
	<tr>
		<td align="right">'.$i.'</td>
		<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
		<td align="center">'.number_format($row->cantidad,2).'</td>
		<td align="center">'.$row->movimiento.'</td>
		<td align="center">'.number_format($row->inventarioAnterior,2).'</td>
		<td align="center">'.number_format($row->inventarioActual,2).'</td>
	</tr>';

	$i++;
}
	
echo '
</table>';
