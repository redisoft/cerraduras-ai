<script>
$(document).ready(function()
{
	$("#tablaSucursales tr:even").addClass("sombreado");
	$("#tablaSucursales tr:odd").addClass("sinSombra");  
});
</script>

<?php
echo'
<form id="frmAsignarPorcentajes">
	<table class="admintable" style="width:100%">
		<tr>
			<td class="key">Producto</td>
			<td>
				'.$producto->nombre.'
				<input type="hidden" id="txtIdProducto" name="txtIdProducto" value="'.$producto->idProducto.'">
				
				<input type="hidden" id="txtPrecioA" name="txtPrecioA" value="'.$producto->precioA.'">
				<input type="hidden" id="txtPrecioB" name="txtPrecioB" value="'.$producto->precioB.'">
				<input type="hidden" id="txtPrecioC" name="txtPrecioC" value="'.$producto->precioC.'">
				<input type="hidden" id="txtNumeroSucursales" name="txtNumeroSucursales" value="'.count($licencias).'">
				<input type="hidden" id="txtRegistroSucursales" name="txtRegistroSucursales" value="1">
			</td>
		</tr>
		
		<tr>
			<td class="key">Clave</td>
			<td>
				'.$producto->codigoInterno.'
			</td>
		</tr>
	</table>
	
	<table class="admintable" style="width:100%">
		<tr>
			<th></th>
			<th>Porcentaje</th>
			<th>Precio</th>
			<th>Incremento</th>
			<th>Total</th>
		</tr>
		
		<tr class="sinSombra">
			<td>Precio público:</td>
			<td align="center"><input type="text" style="width:100px" class="cajas" id="txtPorcentaje1" name="txtPorcentaje1" onkeypress="return soloDecimales(event)" maxlength="5" onchange="configurarPorcentajes()" /></td>
			<td align="right">$'.number_format($producto->precioA,decimales).'</td>
			<td id="lblIncrementoA" align="right">$'.number_format(0,decimales).'</td>
			<td id="lblTotalPrecioA" align="right">$'.number_format($producto->precioA,decimales).'</td>
		</tr>
		
		<tr class="sombreado">
			<td>Precio mayoreo:</td>
			<td align="center"><input type="text" style="width:100px" class="cajas" id="txtPorcentaje2" name="txtPorcentaje2" onkeypress="return soloDecimales(event)" maxlength="5" onchange="configurarPorcentajes()" /></td>
			<td align="right">$'.number_format($producto->precioB,decimales).'</td>
			<td id="lblIncrementoB" align="right">$'.number_format(0,decimales).'</td>
			<td id="lblTotalPrecioB" align="right">$'.number_format($producto->precioB,decimales).'</td>
		</tr>
		
		<tr class="sinSombra">
			<td>Precio 1:</td>
			<td align="center"><input type="text" style="width:100px" class="cajas" id="txtPorcentaje3" name="txtPorcentaje3" onkeypress="return soloDecimales(event)" maxlength="5" onchange="configurarPorcentajes()"/></td>
			<td align="right">$'.number_format($producto->precioC,decimales).'</td>
			<td id="lblIncrementoC" align="right">$'.number_format(0,decimales).'</td>
			<td id="lblTotalPrecioC" align="right">$'.number_format($producto->precioC,decimales).'</td>
		</tr>

	</table>
	<br>
	<table class="admintable" style="width:100%" id="tablaSucursales">
		<tr>
			<th>#</th>
			<th>Sucursal (Los precios pueden variar)</th>
			<th>Seleccionar</th>
		</tr>';
		$i=0;
		foreach($licencias as $row)
		{
			echo '
			<tr>
				<td width="5%" align="center">'.($i+1).'</td>
				<td>'.$row->nombre.'</td>
				<td width="15%" align="center"><input type="checkbox" id="chkSucursal'.$i.'" name="chkSucursal'.$i.'" value="'.$row->idLicencia.'" '.($row->idLicencia==1?'checked="checked"':'').'/></td>
			</tr>';
			
			$i++;
		}
	
	echo'
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
