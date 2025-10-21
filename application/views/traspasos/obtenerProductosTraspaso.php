<?php
if($productos!=null)
{
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaEnvios tr:even").addClass("sombreado");
		$("#tablaEnvios tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagProductosTraspaso">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">	
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Código interno</th>
			<th class="encabezadoPrincipal">Producto</th>
			<th class="encabezadoPrincipal">Línea</th>
			<th class="encabezadoPrincipal">Stock</th>
			<th class="encabezadoPrincipal">Cantidad</th>
		</tr>';
	
	$i=$limite;
	foreach($productos as $row)	
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="right">'.$i.'</td>
			<td align="center">'.$row->codigoInterno.'</td>
			<td>'.$row->nombre.'</td>
			<td>'.$row->linea.'</td>
			<td align="center" id="stockTienda'.$i.'">'.number_format($row->stock,decimales).'</td>
			<td class="vinculos" align="center">
				<input type="text" class="cajas" id="txtTraspasoCantidad'.$i.'" style="width:60px" onkeypress="return soloDecimales(event)" onchange="cargarProductoTraspaso('.$i.')" />		
				
				<input type="hidden" id="txtCodigoInterno'.$i.'" 	value="'.$row->codigoInterno.'"/>
				<input type="hidden" id="txtNombre'.$i.'" 	value="'.$row->nombre.'"/>
				<input type="hidden" id="txtLinea'.$i.'" 	value="'.$row->linea.'"/>
						
				<input type="hidden" id="txtStock'.$i.'" 			value="'.round($row->stock,decimales).'"/>
				<input type="hidden" id="txtProductoId'.$i.'"		 value="'.$row->idProducto.'"/>
				<input type="hidden" id="txtInventarioId'.$i.'" 	value="'.$row->idInventario.'"/>
			</td>
		</tr>';
		
		$i++;
	}
	
	
	echo '</table>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagProductosTraspaso">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de productos</div>';
}
	
	