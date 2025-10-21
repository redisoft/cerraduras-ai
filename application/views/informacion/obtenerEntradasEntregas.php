<?php
echo'
<script>
$(document).ready(function()
{
	$("#tablaEntradasEntregasInformacion tr:even").addClass("sombreado");
	$("#tablaEntradasEntregasInformacion tr:odd").addClass("sinSombra");  
});
</script>

<div style="width:90%; margin-top:1%;">
	<ul id="pagination-digg" class="ajax-pagEntradasEntregasInformacion">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" id="tablaEntradasEntregasInformacion" style="margin-top:3px; width:100%">
	<tr>
		<th class="encabezadoPrincipal sinBordeDerecha" colspan="4">Detalles de entradas por producto no entregado</th>
		<th class="encabezadoPrincipal sinBordeIzquierda">Total: '.round($total,2).'</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Nota</th>
		<th>Comentarios</th>
		<th>Cantidad</th>
	</tr>';
	
$cantidad	= 0;
$i			= $limite;
foreach($registros as $row)
{
	$cantidad	+=$row->noEntregados;
		
	echo'
	<tr>
		<td align="right">'.$i.'</td>
		<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
		<td align="center">'.$row->estacion.$row->folio.'</td>
		<td align="left">'.nl2br($row->comentarios).'</td>
		<td align="center">'.round($row->noEntregados,2).'</td>
	</tr>';

	$i++;
}
	
echo '
</table>';
