<?php
$i=0;
if($conceptos!=null)
{
	#var_dump($xml[34]);
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaConceptosTransacciones tr:even").addClass("arriba");
		$("#tablaConceptosTransacciones tr:odd").addClass("abajo");  
	});
	</script>
	<table class="tablaDatos" id="tablaConceptosTransacciones">
		<tr>
			<th class="titulos" colspan="6">Conceptos del CFDI</th>
		</tr>
		<tr>
			<th>Cantidad</th>
			<th>Unidad</th>
			<th>Código</th>
			<th>Descripción</th>
			<th>Precio Unitario</th>
			<th>Importe</th>
		</tr>';
	
	foreach($conceptos as $row)
	{
		echo '
		<tr>
			<td align="center">'.number_format($row->cantidad,2).'</td>
			<td>'.$row->unidad.'</td>
			<td>'.$row->codigo.'</td>
			<td>'.$row->descripcion.'</td>
			<td align="right">$'.number_format($row->precioUnitario,2).'</td>
			<td align="right">$'.number_format($row->importe,2).'</td>
		</tr>';
		
		$i++;
	}

	echo '
	</table>';
}
else
{
	echo '<div class="erroresDatos">Sin registro de conceptos</div>';
}

