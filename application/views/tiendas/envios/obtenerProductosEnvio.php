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
		<ul id="pagination-digg" class="ajax-pagProductosEnvio">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%" id="tablaEnvios">	
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Upc</th>
			<th class="encabezadoPrincipal">Producto</th>
			<th class="encabezadoPrincipal">Línea</th>
			<th class="encabezadoPrincipal" width="9%">Tienda salida</th>
			<th class="encabezadoPrincipal">Stock</th>
			<th class="encabezadoPrincipal">Acciones</th>
		</tr>';
	
	$i=$limite;
	foreach($productos as $row)	
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="right">'.$i.'</td>
			<td align="center">'.$row->upc.'</td>
			<td>'.$row->nombre.'</td>
			<td>'.$row->linea.'</td>
			<td>
			<select class="cajas" id="selectTiendas'.$i.'" onchange="obtenerStockTienda('.$i.')" style="width:120px" >
            	<option value="0">Matriz</option>';
				
				foreach($tiendas as $tie)
				{
					echo '<option value="'.$tie->idTienda.'">'.$tie->nombre.'</option>';
				}
			
			echo'
			</select>
			</td>
			<td align="center" id="stockTienda'.$i.'">'.number_format($row->stock,2).'</td>
			<td class="vinculos" align="center">
				<input type="text" class="cajas" id="txtCantidadEnviar'.$i.'" value="" style="width:60px" placeholder="Cantidad" /><br />
				<input type="hidden" id="txtStock'.$i.'" value="'.$row->stock.'"/>
				<input type="hidden" id="txtStockMatriz'.$i.'" value="'.$row->stock.'"/>
				<input type="hidden" id="txtIdProducto'.$i.'" value="'.$row->idProducto.'"/>
				<img src="'.base_url().'img/truck.png" onclick="registrarEnvio('.$i.')" title="Enviar" />
				<br />
				<a>Enviar</a>
			</td>
		</tr>';
		
		$i++;
	}
	
	
	echo '</table>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagProductosEnvio">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de productos</div>';
}
	
	