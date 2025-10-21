
<?php
echo '
<script>
$(document).ready(function()
{
	$("#tablaRegistros tr:even").addClass("sombreado");
	$("#tablaRegistros tr:odd").addClass("sinSombra");	  
});
</script>
<form id="frmEntregas" action="javascript:registrarEntregas()">
	<input type="hidden" id="txtNumeroCotizaciones" name="txtNumeroCotizaciones" value="'.count($registros).'" />
	<input type="hidden" id="txtIdPersonal" name="txtIdPersonal" value="'.$personal->idPersonal.'" />
	<input type="hidden" id="txtChofer" name="txtChofer" value="'.$personal->nombre.'" />
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2" class="encabezadoPrincipal">Detalles de entregas</th>
		<tr>
		<tr>
			<td class="key">Fecha</td>
			<td>'.obtenerFechaMesCorto(date('Y-m-d')).'</td>
		<tr>
		<tr>
			<td class="key">Chofer</td>
			<td>'.$personal->nombre.'</td>
		<tr>
	</table>
	<table class="admintable" width="100%" id="tablaRegistros">
		<tr>
			<th width="3%" align="right">#</th>
			<th>Nota</th>
			<th>Folio</th>
			<th>Producto</th>
			<th>Stock</th>
			<th>Cantidad</th>
			<th align="center">Entregado</th>
			<th align="center">No entregado</th>
			<th align="center" width="30%">Comentarios</th>
		</tr>';
	    
	$i		= 0;
	$c		= 0;
	foreach($registros as $reg)
	{
		$productos	= $this->reportes->obtenerProductosVentasEntregas($reg->idVenta,1);

		echo '<input type="hidden" id="txtIdCotizacion'.$c.'" name="txtIdCotizacion'.$c.'" value="'.$reg->idVenta.'" />';

		foreach($productos as $row)
		{
			echo '
			<tr>
				<td align="right">'.($i+1).'</td>
				<td align="center">'.$reg->estacion.$reg->folio.'</td>
				<td align="center">'.$reg->folioTicket.'</td>
				<td align="left">'.$row->nombre.'</td>
				<td align="center" '.($row->stock<$row->cantidad?'style="background-color: yellow"':'').'>'.round($row->stock,2).'</td>
				<td align="center">'.round($row->cantidad,2).'</td>
				<td align="center" id="lblEntregado'.$i.'">'.round($row->cantidad,2).'</td>
				<td align="center">
					<input type="number" id="txtCantidad'.$i.'" name="txtCantidad'.$i.'" type="number" min="0" max="'.$row->cantidad.'" step="any" class="cajas" value="0" required="true" onchange="revisarCantidadEnvio('.$i.')" />

					<input type="hidden" id="txtEntrega'.$i.'" name="txtEntrega'.$i.'" value="'.$row->cantidad.'" />
					<input type="hidden" id="txtIdProducto'.$i.'" name="txtIdProducto'.$i.'" value="'.$row->idProduct.'" />
					<input type="hidden" id="txtIdProductoDetalle'.$i.'" name="txtIdProductoDetalle'.$i.'" value="'.$row->idProducto.'" />
				</td>

				<td align="center">
					<textarea id="txtComentarios'.$i.'" name="txtComentarios'.$i.'" class="TextArea" style="width: 98%"></textarea>
				</td>
			</tr>';
		
			$i++;
		}

		$c++;
	}	

echo '
	<input type="hidden" id="txtNumeroProductos" name="txtNumeroProductos" value="'.$i.'" />

	</table>
</form>';
