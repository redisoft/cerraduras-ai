<?php
echo '
<form id="frmEditarVentaServicio" name="frmEditarVentaServicio" action="javascript:editarVentaServicios()" method="POST" >
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Detalles de venta</th>
	</tr>
	
	<tr>
		<td class="key">Fecha:</td>
		<td>'.$venta->fechaCompra.'</td>
	</tr>
	
	<tr>
		<td class="key">Cliente:</td>
		<td>'.$venta->cliente.'</td>
	</tr>
	
	<tr>
		<td class="key">Subtotal:</td>
		<td id="lblSubTotalVenta">'.number_format($venta->subTotal,decimales).'</td>
	</tr>
	
	<tr>
		<td class="key">Descuento('.number_format($venta->descuentoPorcentaje,decimales).'%):</td>
		<td id="lblDescuentoVenta">'.number_format($venta->descuento,decimales).'</td>
	</tr>
	
	<tr>
		<td class="key">IVA('.number_format($venta->ivaPorcentaje,decimales).'%):</td>
		<td id="lblIvaVenta">'.number_format($venta->iva,decimales).'</td>
	</tr>
	
	<tr>
		<td class="key">Total:</td>
		<td id="lblTotalVenta">'.number_format($venta->total,decimales).'</td>
	</tr>
</table>
<table class="admintable" width="100%">
	<tr>
		<th colspan="6">Detalles de servicios</th>
	</tr>
	
	<tr>
		<th>Servicio</th>
		<th>Cantidad</th>
		<th>Precio</th>
		<th>Descuento</th>
		<th>Importe</th>
		<!--<th>Acciones</th>-->
	</tr>
	
	<input type="hidden" id="txtSubTotalVenta" name="txtSubTotalVenta" value="'.$venta->subTotal.'" />
	<input type="hidden" id="txtDescuentoPorcentajeVenta" name="txtDescuentoPorcentajeVenta" value="'.$venta->descuentoPorcentaje.'" />
	<input type="hidden" id="txtDescuentoVenta" name="txtDescuentoVenta" value="'.$venta->descuento.'" />
	<input type="hidden" id="txtIvaPorcentaje" name="txtIvaPorcentaje" value="'.$venta->ivaPorcentaje.'" />
	<input type="hidden" id="txtIva" name="txtIva" value="'.$venta->iva.'" />
	<input type="hidden" id="txtTotalVenta" name="txtTotalVenta" value="'.$venta->total.'" />
	
	<input type="hidden" id="txtCantidad" name="txtCantidad" value="'.$servicio->cantidad.'" />
	<input type="hidden" id="txtDescuentoPorcentaje" name="txtDescuentoPorcentaje" value="'.$servicio->descuentoPorcentaje.'" />
	<input type="hidden" id="txtDescuento" name="txtDescuento" value="'.$servicio->descuento.'" />
	<input type="hidden" id="txtImporte" name="txtImporte" value="'.$servicio->importe.'" />
	<input type="hidden" id="txtIdCotizacion" name="txtIdCotizacion" value="'.$servicio->idCotizacion.'" />
	<input type="hidden" id="txtIdProducto" name="txtIdProducto" value="'.$servicio->idProducto.'" />
	
	<input type="hidden" id="txtNombreProducto" name"txtNombreProducto" value="'.$servicio->nombre.'" />
	
	
	
	<tr>
		<td>'.$servicio->nombre.'</td>
		<td>'.number_format($servicio->cantidad,decimales).'</td>
		<td align="center">
			<input type="text" class="cajas" style="width:100px" value="'.round($servicio->precio,decimales).'" onkeypress="return soloDecimales(event)" id="txtPrecioProducto" name="txtPrecioProducto" onchange="obtenerImportesVentaServicio()" />
		</td>
		<td align="center" id="lblDescuentoProducto">$'.number_format($servicio->descuento,decimales).'</td>
		<td align="center" id="lblImporte">$'.number_format($servicio->importe,decimales).'</td>
		<!--<td align="center">
			<img src="'.base_url().'img/editar.png" title="Editar" onclick="editarPrecioVentaServicio()"  width="22"/><br />		
			Editar
		</td>-->
	</tr>';
echo'
</table>
</form>';

