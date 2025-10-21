<?php
echo'
<form id="frmEditarVenta">
	<input type="hidden" id="txtIdCotizacion" 		name="txtIdCotizacion" 		value="'.$cotizacion->idCotizacion.'" />
	
	<input type="hidden" id="txtNumeroProductos" 	name="txtNumeroProductos" 		value="'.count($productos).'" />
	
	<input type="hidden" id="txtSubTotal" 			name="txtSubTotal" 				value="'.$cotizacion->subTotal.'" />

	<input type="hidden" id="txtIvaPorcentaje" 		name="txtIvaPorcentaje" 		value="'.$cotizacion->ivaPorcentaje.'" />
	<input type="hidden" id="txtIvaTotal" 			name="txtIvaTotal" 				value="'.$cotizacion->iva.'" />
	<input type="hidden" id="txtTotal"				name="txtTotal" 				value="'.$cotizacion->total.'" />
	<input type="hidden" id="txtTotalOriginal"		name="txtTotalOriginal" 				value="'.$cotizacion->total.'" />
	
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Fecha</td>
			<td>'.obtenerFechaMesCortoHora($cotizacion->fechaCompra).'</td>
		</tr>
		<tr>
			<td class="key">Venta: </td>
			<td>'.$cotizacion->ordenCompra.'</td>
		</tr>

		<tr>
			<td class="key">Estación: </td>
			<td>'.$cotizacion->estacion.'</td>
		</tr>

		<tr>
			<td class="key">Cliente: </td>
			<td>'.$cotizacion->cliente.'</td>
		</tr>
	</table>

	<table class="admintable" width="100%" style="margin-top:3px">
		<tr>
			<th width="15%">Código</th>
			<th width="35%">Descripción</th>
			<th width="10%">UM</th>
			<th width="10%">Cantidad</th>		
			<th width="10%">Precio</th>
			<th width="10%">Descuento</th>
			<th width="10%">Importe</th>
		</tr>';

	$i=0;

	foreach($productos as $row)
	{
		$estilo		= $i%2>0?"class='sinSombra'":'class="sombreado"';
		$nombre		= strlen($row->nombre)>2?$row->nombre:$row->producto;

		$cantidad	= $row->cantidad-$row->devueltos;

		echo'
		<tr '.$estilo.'>
			<td>'.$row->codigoInterno.'</td>
			<td>'.$nombre.'</td>
			<td>'.$row->unidad.'</td>
			<td align="center">
				<input type="text" class="cajas" id="txtCantidad'.$i.'" name="txtCantidad'.$i.'" 			value="'.round($row->cantidad,4).'" style="width: 98%" maxlength="5" onchange="calcularEditarVenta()" onkeypress="return soloDecimales(event)" />
				<input type="hidden" id="txtCantidadTotal'.$i.'"  		name="txtCantidadTotal'.$i.'" 		value="'.round($row->cantidad,4).'" />
				<input type="hidden" id="txtPrecio'.$i.'"  				name="txtPrecio'.$i.'" 				value="'.round($row->precio,4).'" />
				<input type="hidden" id="txtImporte'.$i.'"  			name="txtImporte'.$i.'" 			value="'.round($row->importe,4).'" />
				<input type="hidden" id="txtIdRelacion'.$i.'"  			name="txtIdRelacion'.$i.'" 			value="'.round($row->idProducto,4).'" />
				<input type="hidden" id="txtIdProducto'.$i.'"  			name="txtIdProducto'.$i.'" 			value="'.round($row->idProduct,4).'" />

			</td>
			<td align="right">$'.number_format($row->precio,4).'</td>
			<td align="right">$'.number_format($row->descuento,2).'</td>
			<td align="right" id="lblImporte'.$i.'">$'.number_format($row->importe,4).'</td>
		</tr>';

		$i++;
	}

	echo'
		<tr>
			<td colspan="6" style="text-align:right" class="totales">Subtotal</td>
			<td align="right" id="lblSubTotal">$'.number_format($cotizacion->subTotal,2).'</td>
		</tr>
		<tr>
			<td colspan="6" style="text-align:right" class="totales">Descuento</td>
			<td align="right">$'.number_format($cotizacion->descuento,2).'</td>
		</tr>
		<tr>
			<td colspan="6" style="text-align:right" class="totales">Impuestos</td>
			<td align="right" id="lblIva">$'.number_format($cotizacion->iva,2).'</td>
		</tr>
		<tr>
			<td colspan="6" style="text-align:right" class="totales">Total</td>
			<td align="right" id="lblTotal">$'.number_format($cotizacion->total,2).'</td>
		</tr>
	</table>
</form>';
