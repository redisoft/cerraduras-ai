<?php
if($envios!=null)
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
		<ul id="pagination-digg" class="ajax-pagEnvios">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%" id="tablaEnvios">	
		<tr>
			<th colspan="12" class="encabezadoPrincipal">
				Detalles de traspasos
				<img src="'.base_url().'img/pdf.png" width="22" height="20" onclick="reporteEnvios()" />
				<img src="'.base_url().'img/excel.png" width="22" height="22" onclick="excelEnvios()" />
			</th>
		</tr>
		<tr>
			<th class="">#</th>
			<th class="">Fecha</th>
			<th class="">Folio</th>
			<th class="">Cantidad</th>
			<th class="">UPC</th>
			<th class="">Producto</th>
			<th class="">Línea</th>
			<th class="">Tienda salida</th>
			<th class="">Tienda entrada</th>
			
		</tr>';
	
	$i=$limite;
	foreach($envios as $row)	
	{
		echo '
		<tr>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.$row->folio.'<br /><i style="font-weight:100">'.$row->usuario.'</i></td>
			<td align="center">'.number_format($row->cantidad,2).'</td>
			<td>'.$row->upc.'</td>
			<td>'.$row->producto.'</td>
			<td>'.$row->linea.'</td>
			<td>'.($row->idTiendaOrigen==0?'Matriz':$row->tiendaOrigen).'</td>
			<td>'.($row->idTienda==0?'Matriz':$row->tienda).'</td>
		</tr>';
		
		$i++;
	}
	
	
	echo '</table>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagEnvios">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de envíos</div>';
}
	
	