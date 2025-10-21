<?php
echo'
<form id="frmAsignarPorcentajes">
	<table class="admintable" style="width:100%">
		<tr>
			<td class="key">Proveedor</td>
			<td>
				'.$proveedor->empresa.'
				<input type="hidden" class="cajas" id="txtIdProveedorAsignar" name="txtIdProveedorAsignar" value="'.$proveedor->idProveedor.'">
			</td>
		</tr>

		<tr>
			<td class="key">Precio público:</td>
			<td>
				<input type="text" style="width:100px" class="cajas" id="txtPorcentaje1" name="txtPorcentaje1" onkeypress="return soloDecimales(event)" maxlength="5" />
			</td>
		</tr>

		<tr>
			<td class="key">Precio mayoreo:</td>
			<td>
				<input type="text" style="width:100px" class="cajas" id="txtPorcentaje2" name="txtPorcentaje2" onkeypress="return soloDecimales(event)" maxlength="5" />
			</td>
		</tr>

		<tr>
			<td class="key">Precio 1:</td>
			<td>
				<input type="text" style="width:100px" class="cajas" id="txtPorcentaje3" name="txtPorcentaje3" onkeypress="return soloDecimales(event)" maxlength="5" />
			</td>
		</tr>

	</table>
</form>';

if($porcentajes!=null)
{
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaPorcentaje tr:even").addClass("sombreado");
		$("#tablaPorcentaje tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<table class="admintable" style="width:100%" id="tablaPorcentaje">
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Precio público</th>
			<th>Precio mayoreo</th>
			<th>Precio 1</th>
		</tr>';
	
	$i=1;
	foreach($porcentajes as $row)
	{
		echo '
		<tr>
			<td>'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.number_format($row->porcentaje1,decimales).'</td>
			<td align="center">'.number_format($row->porcentaje2,decimales).'</td>
			<td align="center">'.number_format($row->porcentaje3,decimales).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
