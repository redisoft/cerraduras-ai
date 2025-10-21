<?php
if($envios!=null)
{
	$this->load->view('tiendas/envios/encabezado');
	
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaReporteEnvios tr:even").addClass("sombreado");
		$("#tablaReporteEnvios tr:odd").addClass("sinSombra");  
	});
	</script>

	<table class="admintable" width="100%" id="tablaReporteEnvios">';
	
	$i=1;
	foreach($envios as $row)	
	{
		echo '
		<tr>
			<td width="3%" align="right">'.$i.'</td>
			<td width="10%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td width="10%" align="center">'.$row->folio.'<br /><i style="font-weight:100">'.$row->usuario.'</i></td>
			<td width="5%" align="center">'.number_format($row->cantidad,2).'</td>
			<td width="10%">'.$row->upc.'</td>
			<td width="32%">'.$row->producto.'</td>
			<td width="10%">'.$row->linea.'</td>
			<td width="10%">'.($row->idTiendaOrigen==0?'Matriz':$row->tiendaOrigen).'</td>
			<td width="10%">'.($row->idTienda==0?'Matriz':$row->tienda).'</td>
		</tr>';
		
		$i++;
	}
	
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de envíos</div>';
}
	
	