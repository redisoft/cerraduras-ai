<?php
if($producto!=null)
{
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaStockSucursales tr:even").addClass("sombreado");
		$("#tablaStockSucursales tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Producto:</td>
			<td>'.$producto->nombre.'</td>
		</tr>
		<tr>
			<td class="key">UPC:</td>
			<td>'.$producto->upc.'</td>
		</tr>
		<tr>
			<td class="key">Línea:</td>
			<td>'.$producto->linea.'</td>
		</tr>
	</table>
	
	<table class="admintable" width="100%" id="tablaStockSucursales">		
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Sucursal</th>
			<th class="encabezadoPrincipal">Stock</th>
		</tr>';
		
	$i=1;
	foreach($tiendas as $row)	
	{
		echo '
		<tr>
			<td align="right">'.$i.'</td>
			<td>'.$row->nombre.'</td>
			<td align="right">'.number_format($row->stock,2).'</td>
		</tr>';
		
		$i++;
	}
	
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin detalles de stock</div>';
}