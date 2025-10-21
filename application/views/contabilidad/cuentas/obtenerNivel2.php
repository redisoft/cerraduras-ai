<?php
$i=1;

echo '
<table class="tablaFormularios">
	<tr>
		<th colspan="2">Detalles de cuenta</th>
	</tr>
	<tr>
		<td class="etiquetas">Código agrupador:</td>
		<td>'.$cuenta->codigo.'</td>
	</tr>
	<tr>
		<td class="etiquetas">Cuenta:</td>
		<td>'.$cuenta->nombre.'</td>
	</tr>
</table>';

if($cuentas!=null)
{
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaNivel2 tr:even").addClass("abajo");
		$("#tablaNivel2 tr:odd").addClass("arriba");  
	});
	</script>
	
	<table class="tablaDatos" id="tablaNivel2">
		<tr>
			<th class="titulos" colspan="4">Lista de cuentas nivel 2</th>
		</tr>
		<tr>
			<th>No.</th>
			<th>Código agrupador</th>
			<th>Cuenta</th>
			<th>Subcuentas</th>
		</tr>';
	
	foreach($cuentas as $row)
	{
		echo'
		<tr>
			<td class="numeral	">'.$i.'</td>
			<td align="center">'.$row->codigo.'</td>
			<td align="left">'.$row->nombre.'</td>
			<td align="center" class="vinculos">
				<img src="'.base_url().'img/cuentas.png" title="Subcuentas nivel 3" onclick="obtenerNivel3('.$row->idSubCuenta.')" />
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="erroresDatos">Sin registros</div>';
}
?>