<?php
echo'
<script>
$(document).ready(function()
{
	$("#tablaEntradasTraspasos tr:even").addClass("sombreado");
	$("#tablaEntradasTraspasos tr:odd").addClass("sinSombra");  
});
</script>

<div style="width:90%; margin-top:1%;">
	<ul id="pagination-digg" class="ajax-pagEntradasTraspasos">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" id="tablaEntradasTraspasos" style="margin-top:3px; width:100%">
	<tr>
		<th class="encabezadoPrincipal sinBordeDerecha" colspan="4">Detalles de entradas por traspasos</th>
		<th class="encabezadoPrincipal sinBordeIzquierda">Total: '.round($total,2).'</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Tienda origen</th>
		<th>Folio</th>
		<th>Cantidad</th>
	</tr>';
	
$cantidad	= 0;
$i			= $limite;

foreach($recepciones as $row)
{
	$cantidad	+=$row->cantidad;
		
	echo'
	<tr>
		<td align="right">'.$i.'</td>
		<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
		<td align="center">'.$row->sucursal.'</td>
		<td align="center">'.$row->folio.'</td>
		<td align="center">'.round($row->cantidad,2).'</td>
	</tr>';
		
	$i++;
}
	
echo '
</table>';
