<?php
echo'
<script>
$(document).ready(function()
{
	$("#tablaEnviosInformacion tr:even").addClass("sombreado");
	$("#tablaEnviosInformacion tr:odd").addClass("sinSombra");  
});
</script>

<div style="width:90%; margin-top:1%;">
	<ul id="pagination-digg" class="ajax-pagEnviosInformacion">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" id="tablaEnviosInformacion" style="margin-top:3px; width:100%">
	<tr>
		<th class="encabezadoPrincipal sinBordeDerecha" colspan="4">Detalles de salidas por traspasos</th>
		<th class="encabezadoPrincipal sinBordeIzquierda">Total: '.round($total,2).'</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Tienda destino</th>
		<th>Folio</th>
		<th>Cantidad</th>
	</tr>';
	
$cantidad	= 0;
$i			= $limite;
foreach($envios as $row)
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
