<?php
if($productos!=null)
{
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaProductosLista tr:even").addClass("sombreado");
		$("#tablaProductosLista tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagProductosLista">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%" id="tablaProductosLista">	
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Código interno</th>
			<th class="encabezadoPrincipal">Producto</th>
			<th class="encabezadoPrincipal">Línea</th>
			<th class="encabezadoPrincipal">Precio</th>
		</tr>';
	
	$i=$limite;
	foreach($productos as $row)	
	{
		echo '
		<tr onclick="cargarProductoLista('.$i.')">
			<td align="right">'.$i.'</td>
			<td align="left">'.$row->codigoInterno.'</td>
			<td>'.$row->nombre.'</td>
			<td>'.$row->linea.'</td>
			<td align="right" >$'.number_format($row->precioA,decimales).'</td>
				<input type="hidden" id="txtCodigoInterno'.$i.'" 	value="'.$row->codigoInterno.'"/>
				<input type="hidden" id="txtNombre'.$i.'" 			value="'.$row->nombre.'"/>
				<input type="hidden" id="txtLinea'.$i.'" 			value="'.$row->linea.'"/>
						
				<input type="hidden" id="txtPrecio'.$i.'" 			value="'.round($row->precioA,decimales).'"/>
				<input type="hidden" id="txtProductoId'.$i.'"		value="'.$row->idProducto.'"/>
		</tr>';
		
		$i++;
	}
	
	
	echo '</table>';
	
	if(count($productos)>9)
	{
		echo'
		<div style="width:90%; margin-top:0%;">
			<ul id="pagination-digg" class="ajax-pagProductosLista">'.$this->pagination->create_links().'</ul>
		</div>';
	}
}
else
{
	echo '<div class="Error_validar">Sin registro de productos</div>';
}
	
