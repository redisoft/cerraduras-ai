<?php
$subTotal			= $cotizacion->subTotal;
$iva				= $cotizacion->iva;
$descuento			= $cotizacion->descuento;
$total				= $cotizacion->total;

#PARCIALES
#---------------------------------------------------------------------------------------------------------#
$parciales			=$this->facturacion->obtenerFacturasParciales($idCotizacion);

if($parciales!=null)
{
	$totalParciales		=$this->facturacion->sumarFacturasParciales($idCotizacion);
	
	$total				=$cotizacion->total-$totalParciales;
	$totalIva			=$total/(1+$cotizacion->ivaPorcentaje/100);
	$iva				=$total-$totalIva;
	
	$subTotal			=$cotizacion->descuento>0?$totalIva/(1-($cotizacion->descuentoPorcentaje/100)):$totalIva;
	$descuento			=$subTotal-$totalIva;
}

echo'
<table class="admintable" width="100%;">
	<tr>
	  <td class="key">Cliente:</td>
	  <td>'.$cliente->empresa.'</td>
	</tr>	
	
	<tr style="display:none"">
		<td class="key" >Retención:</td>
		<td>
			<label>Nombre</label> 	<input value="" type="text" class="cajas" id="txtNombreRetencionParcial" /><br />
			<label>Tasa %</label>&nbsp;&nbsp;<input  value="0" style="width:100px" type="text" class="cajas" id="txtTasaParcial" /><br />
			<label>Importe</label> <input  onchange="calcularDatosParcial()" value="0" style="width:100px" type="text" class="cajas" id="txtRetencionParcial" />
			
			<input type="hidden" id="porcentajeDescuento" 	value="'.$cotizacion->descuentoPorcentaje.'" />
			<input type="hidden" id="porcentajeIva" 		value="'.$cotizacion->ivaPorcentaje.'" />
			<input type="hidden" id="txtSubTotalParcial" 		value="'.$subTotal.'" />
			<input type="hidden" id="txtDescuentoParcial" 	value="'.$descuento.'" />
			<input type="hidden" id="txtSumaParcial" 		value="'.($subTotal-$descuento).'" />
			<input type="hidden" id="txtIvaParcial" 		value="'.$iva.'" />
			<input type="hidden" id="txtTotalParcial" 		value="'.$total.'" />
		</td>
	</tr>
	
	<tr style="display:none">
		<td class="key">Concepto:</td>
		<td>
			<input type="text" class="cajas" id="txtConcepto" style="width:550px"  value="" />
		</td>
	</tr>
	
	<tr style="display:none">
		<td class="key">Unidad:</td>
		<td>
			<input type="text" class="cajas" id="txtUnidad"  value="PZA" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Emisor:</td>
		<td>
			<select style="width:400px" id="selectEmisoresParcial" name="selectEmisores" class="cajas" onchange="obtenerFolioParcial()">
				<option value="0">Seleccione</option>';
			
			foreach($emisores as $row)
			{
				$seleccionado=$row->idEmisor==$cliente->idEmisor?'selected="selected"':'';
				echo '<option '.$seleccionado.' value="'.$row->idEmisor.'">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
			}
			
		echo'
		</td>
	</tr>
	
	<tr>
		<td class="key">Folio:</td>
		<td id="obtenerFolioParcial" >
			Seleccionar emisor
		</td>
	</tr>
	
	<tr style="display:none">
		<td class="key">Divisa:</td>
		<td>
			<select class="cajas" id="selectDivisasParcial">';
		
		foreach ($divisas as $row)
		{
			$seleccionado=$row->idDivisa==$cotizacion->idDivisa?'selected="selected"':'';
			
			echo '<option '.$seleccionado.' value="'.$row->idDivisa.'">'.$row->nombre.' ($'.$row->tipoCambio.')</option>';
		}
		
		echo'
			</select>
		</td>
	</tr>


	<tr>
		<td class="key">Método de pago:</td>
		<td>
			<select style="width:400px" id="selectMetodoPagoParcial" name="selectMetodoPagoParcial" class="cajas"  >';
			
			foreach($metodos as $row)
			{
				echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	<tr>
		<td class="key">Forma de pago:</td>
		<td>
			<input type="text" style="width:400px" class="cajas" id="txtFormaPagoParcial" name="txtFormaPagoParcial" value="Pago en una sola exhibición" />
		</td>
	</tr>
	 <tr>
		<td class="key">Condiciones de pago:</td>
		<td>
			<input type="text" style="width:400px" class="cajas" id="txtCondicionesParcial" name="txtCondicionesParcial" value="'.condicionesPago.'" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Observaciones</td>
		<td>
			<textarea class="TextArea" id="txtObservacionesParcial" style="width:400px; height:60px">'.$cotizacion->observaciones.'</textarea>
		</td>
	</tr>
	
	<tr>
		<td class="key">Criterio de facturación:</td>
		<td>
			<select id="selectCriterio" name="selectCriterio" class="cajas" style="width:120px" onchange="criterioFacturacion()">
				<option value="0">Porcentaje</option>
				<option value="1">Cantidad</option>
			</select>
		</td>
	</tr>
	
	<tr id="filaPorcentaje">
		<td class="key">Porcentaje a facturar:</td>
		<td>
			<input type="text" style="width:70px" class="cajas" id="txtPorcentajeFacturar" name="txtPorcentajeFacturar" value="100" onchange="calcularPorcentajeProductos()" onkeypress="return soloDecimales(event)" maxlength="15"/> %
		</td>
	</tr>
	
	<tr>
		<td class="key">Subtotal:</td>
		<td id="lblSubTotalParcial">
			$'.number_format($subTotal,2).'
		</td>
	</tr>
	
	 <tr>
		<td class="key">Descuento ('.number_format($cotizacion->descuentoPorcentaje,2).'%):</td>
		<td>
			<label id="lblDescuento">$'.number_format($descuento,2).'</label>
		</td>
	</tr>
	<tr>
		<td class="key" >IVA ('.number_format($cotizacion->ivaPorcentaje,2).'%):</td>
		<td>
			<label id="lblIva">$'.number_format($iva,2).'</label>
		</td>
	</tr>
	
	<tr>
		<td class="key">Total:</td>
		<td> 
			<label id="lblTotalParcial">$'.number_format($total,2).'</label>
		</td>
	</tr>
</table>';

echo '
<table class="admintable" width="100%;">
	<tr>
		<th>#</th>
		<th>Código</th>
		<th width="500px">Producto</th>
		<th>Unidad</th>
		<th>Cantidad</th>
		<th>Precio unitario</th>
		<th>Descuento</th>
		<th>Importe</th>
	</tr>';

$i=1;

echo '<input type="hidden" id="txtNumeroProductos" value="'.count($productos).'" />';

foreach($productos as $row)	
{
	$estilo				= $i%2>0?'class="sinSombra"':'class="sombreado"';
	
	/*$cantidadParcial	= $this->facturacion->sumarProductosParciales($cotizacion->idCotizacion,$row->idProducto);
	$cantidad			= $row->cantidad-$cantidadParcial;
	$importe			= $cantidad*$row->precio;*/
	
	$cantidadParcial	= $this->facturacion->sumarProductosParciales($cotizacion->idCotizacion,$row->idProducto);
	$cantidad			= $row->cantidad-$cantidadParcial;
	$descuento			= $row->descuento>0?$row->descuento/$row->cantidad:0;
	$descuentoTotal		= $descuento*$cantidad;
	$importe			= $cantidad*$row->precio-$descuentoTotal;

	echo '
	<tr '.$estilo.'>
		<td>'.$i.'</td>
		<td>'.$row->codigoInterno.'</td>
		<td>';
			echo "<input type='text' class='cajas' id='txtDescripcionProducto".$i."' value='".$row->nombre."' style='width:400px' />";
		echo'
		</td>
		<td align="center">'.$row->unidad.'</td>
		<td align="center">
			<label id="lblCantidad'.$i.'">'.number_format($cantidad,2).'</label>
			<input  type="text" class="cajas" style="display:none; width:70px" id="txtCantidadFacturar'.$i.'" value="'.$cantidad.'" onchange="calcularPorcentajeProductos()" onkeypress="return soloDecimales(event)" maxlength="15" />
		</td>
		<td align="right">$'.number_format($row->precio,2).'</td>
		<td align="right" id="lblDescuento'.$i.'">$'.number_format($descuentoTotal,2).'</td>
		<td align="right" id="lblImporte'.$i.'">$'.number_format($importe,2).'</td>
		
		<input type="hidden" id="txtCantidadProducto'.$i.'" 	value="'.$cantidad.'" />
		<input type="hidden" id="txtPrecioProducto'.$i.'" 		value="'.$row->precio.'" />
		<input type="hidden" id="txtImporteProducto'.$i.'" 		value="'.$importe.'" />
		<input type="hidden" id="txtImporteFacturar'.$i.'" 		value="'.$importe.'" />
		<input type="hidden" id="txtDescuentoProducto'.$i.'" 	value="'.$descuentoTotal.'" />
		<input type="hidden" id="txtDescuentoPorcentaje'.$i.'" 	value="'.$row->descuentoPorcentaje.'" />
	</tr>';
	
	$i++;
}
	
echo '</table>';
