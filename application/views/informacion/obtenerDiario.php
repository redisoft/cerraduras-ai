<?php
echo'
<script>
$(document).ready(function()
{
	$("#tablaDiarioInformacion tr:even").addClass("sombreado");
	$("#tablaDiarioInformacion tr:odd").addClass("sinSombra");  
});
</script>

<div style="width:90%; margin-top:1%;">
	<ul id="pagination-digg" class="ajax-pagDiarioInformacion">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" id="tablaDiarioInformacion" style="margin-top:3px; width:100%">
	<tr>
		<th class="encabezadoPrincipal sinBordeDerecha" colspan="2">Movimiento diario</th>
		<th class="encabezadoPrincipal sinBordeIzquierda">Total: '.round($total,2).'</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Cantidad</th>
	</tr>';
	
$cantidad	= 0;
$i			= $limite;
foreach($diario as $row)
{
	$cantidad	+=$row->stock;
		
	echo'
	<tr>
		<td align="right">'.$i.'</td>
		<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
		<td align="center">'.number_format($row->stock,2).'</td>
	</tr>';

	$i++;
}
	
echo '
</table>';
