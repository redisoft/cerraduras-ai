<script>
	
$("#txtBuscarClienteFactura").autocomplete(
{
	source:base_url+'configuracion/obtenerClientes',
	
	select:function( event, ui)
	{
		$("#txtIdClienteFactura").val(ui.item.idCliente);
		
		obtenerDireccionesCfdi(ui.item.idCliente);
	}
});
	
$('#txtFechaFactura').timepicker();
</script>
<?php
$subTotal			= $cotizacion->subTotal;
#$iva				= $cotizacion->iva;
$descuento			= $cotizacion->descuento;

#$descuento			= $subTotal*$descuento;
#$suma				= $subTotal-$descuento;
$iva				= $cotizacion->iva;
$total				= $cotizacion->total;

/*#PARCIALES
#---------------------------------------------------------------------------------------------------------#
$parciales			=$this->facturacion->obtenerFacturasParciales($idCotizacion);
$totalParciales		=0;

if($parciales!=null)
{
	$totalParciales		=$this->facturacion->sumarFacturasParciales($idCotizacion);
}
#---------------------------------------------------------------------------------------------------------#*/

if($parciales!=null)
{
	//COMENTADO PARA LAS RETENCIONES 28 ABRIL 2017
	/*$total				= $cotizacion->total-$totalParciales;
	$totalIva			= $total/(1+$cotizacion->ivaPorcentaje/100);
	$iva				= $total-$totalIva;
	
	$subTotal			= $cotizacion->descuentoPorcentaje>0?$totalIva/(1-($cotizacion->descuentoPorcentaje/100)):$totalIva;
	$descuento			= $subTotal-$totalIva;*/
}


echo'
<form id="frmFacturacion">
<input type="hidden" id="txtIdCotizacion"			name="txtIdCotizacion" 			value="'.$idCotizacion.'" />
<input type="hidden" id="txtDiferencia" 			value="'.($cotizacion->total-$totalParciales).'" />
<input type="hidden" id="txtParcial" 				value="'.count($parciales).'" />
<input type="hidden" id="txtIdClienteFactura"		name="txtIdClienteFactura" 		value="'.$cotizacion->idCliente.'" />
<input type="hidden" id="txtTotalParcialFactura" 	value="'.$totalParciales.'" />

<input type="hidden" id="txtIeps" 					value="'.$configuracion->ieps.'" />
<input type="hidden" id="txtRetencionIeps" 			value="'.$configuracion->retencionIeps.'" />
<input type="hidden" id="txtRetencionIva" 			value="'.$configuracion->retencionIva.'" />

<input type="hidden" id="txtIepsTotal" 				value="0" />
<input type="hidden" id="txtRetencionIepsTotal" 	value="0" />
<input type="hidden" id="txtRetencionIvaTotal" 		value="0" />

<input type="hidden" id="txtSubTotal"				name="txtSubTotal" 				value="'.($subTotal-$descuento).'" />
<input type="hidden" id="txtDescuento" 				name="txtDescuento"		value="'.$descuento.'" />
<input type="hidden" id="txtSuma" 					name="txtSuma"			value="'.($subTotal-$descuento).'" />
<input type="hidden" id="txtIva" 					name="txtIva"			value="'.$iva.'" />
<input type="hidden" id="txtTotal" 					name="txtTotal"			value="'.$cotizacion->total.'" />
<input type="hidden" id="txtIvaPorcentaje" 			name="txtIvaPorcentaje"		value="'.$cotizacion->ivaPorcentaje.'" />


<input type="hidden" id="txtSubTotalCfdi" 			name="txtSubTotalCfdi"		value="'.$subTotal.'" />
<input type="hidden" id="txtIvaCfdi" 				name="txtIvaCfdi"			value="'.$iva.'" />
<input type="hidden" id="txtTotalCfdi" 				name="txtTotalCfdi"			value="'.$cotizacion->total.'" />

<input type="hidden" id="txtIdUltimaFactura" 		name="txtIdUltimaFactura"	value="'.($factura!=null?$factura->idFactura:0).'" />
<input type="hidden" id="txtPendiente" 				name="txtPendiente"			value="'.($factura!=null?$factura->pendiente:0).'" />

<input type="hidden" id="txtNumeroProductos" 		name="txtNumeroProductos"	value="'.count($productos).'" />



<table class="admintable" width="100%;">
	<tr>
	  <td class="key">Cliente:</td>
	  <td><!--'.$cliente->empresa.'-->';
	  	
		echo"<input type='text' style='width:400px' class='cajas' id='txtBuscarClienteFactura' name='txtBuscarClienteFactura' value='".$cliente->empresa."' placeholder='Seleccione' />";
	  echo'
		</td>
	</tr>	
	
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input type="text" style="width:120px" class="cajas" id="txtFechaFactura" name="txtFechaFactura" value="'.date('Y-m-d H:i').'" />
		</td>
	</tr>	
	
	<tr>
		<td class="key">Subtotal:</td>
		<td>$'.number_format($subTotal-$descuento,2).'</td>
	</tr>
	
	 <tr style="display:none">
		<td class="key">Descuento ('.number_format($cotizacion->descuentoPorcentaje,2).'%)</td>
		<td>$'.number_format($descuento,2).'</td>
	</tr>
	
	<tr style="display:none">
		<td class="key" ><input type="checkbox" id="chkIeps" name="chkIeps" onchange="calcularRetenciones()" /> IEPS ('.number_format($configuracion->ieps,2).'%)</td>
		<td id="lblIeps">$0.00</td>
	</tr>

	<tr>
		<td class="key" >Impuestos:</td>
		<td id="lblIva">$'.number_format($iva,2).'</td>
	</tr>
	
	<tr style="display:none">
		<td class="key" ><input type="checkbox" id="chkRetencionIeps" name="chkRetencionIeps" onchange="calcularRetenciones()"/> RET IEPS ('.number_format($configuracion->retencionIeps,2).'%)</td>
		<td id="lblRetencionIeps">$0.00</td>
	</tr>
	
	
	<tr style="display:none">
		<td class="key" ><input type="checkbox" id="chkRetencionIva" name="chkRetencionIva" onchange="calcularRetenciones()"/> RET IVA ('.number_format($configuracion->retencionIva,2).'%)</td>
		<td id="lblRetencionIva">$0.00</td>
	</tr>
	
	
	<tr style="display:none">
		<td class="key" >Retención:</td>
		<td>
			<label>Nombre</label> 	<input  value="" type="text" class="cajas" id="txtNombreRetencion" /><br />
			<label>Tasa %</label>&nbsp;&nbsp;<input onchange="calcularRetencion()" value="0" style="width:100px" type="text" class="cajas" id="txtTasa" /><br />
			<label>Importe</label> <input readonly="readonly" value="0" style="width:100px" type="text" class="cajas" id="txtRetencion" />
			
			
		</td>
	</tr>
	
	<tr>
		<td class="key">Total:</td>
		<td><label id="lblTotal">$'.number_format($total,2).'</label></td>
	</tr>
	
	<tr>
		<td class="key">Dirección: </td>

		<td id="obtenerDireccionesCfdi">
			<select style="width:550px" id="selectDireccionesCfdi" name="selectDireccionesCfdi" class="cajas" >
				<option value="0">Seleccione</option>';

				foreach($direcciones as $row)
				{
					echo '<option value="'.$row->idDireccion.'">'.$row->razonSocial.', '.$row->calle.' '.$row->numero.' '.$row->colonia.'</option>';
				}

			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Emisor:</td>
		<td>
			
			<select style="width:400px" id="selectEmisores" name="selectEmisores" class="cajas" onchange="obtenerFolio()">
				<option value="0">Seleccione</option>';
			
			foreach($emisores as $row)
			{
				$seleccionado='';#$row->idEmisor==$cliente->idEmisor?'selected="selected"':'';
				echo '<option '.($row->idEmisor==$cotizacion->idEmisor?'selected="selected"':'').' value="'.$row->idEmisor.'">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
			}
			
		echo'
		</td>
	</tr>
	
	<tr>
		<td class="key">Folio:</td>
		<td id="obtenerFolio" colspan="2">
			Seleccionar emisor
		</td>
	</tr>
	
	<tr style="display:none">
		<td class="key">Divisa:</td>
		<td>
			<select class="cajas" id="selectDivisas" name="selectDivisas">';
		
		foreach ($divisas as $row)
		{
			$seleccionado=$row->idDivisa==$cotizacion->idDivisa?'selected="selected"':'';
			
			echo '<option '.$seleccionado.' value="'.$row->idDivisa.'">'.$row->nombre.' ($'.$row->tipoCambio.')</option>';
		}
		
		echo'
			</select>
		</td>
	</tr>';
	
	$formaPago		= strlen($cotizacion->formaPago)>0?$cotizacion->formaPago:'Pago en una sola exhibición';
	$condicionesPago= strlen($cotizacion->condicionesPago)>0?$cotizacion->condicionesPago:'30 días a partir de la fecha de entrega';
	$metodoPago		= strlen($cotizacion->metodoPago)>0?$cotizacion->metodoPago:'Efectivo';
	
	if($factura!=null)
	{
		$formaPago			= $factura->formaPago;
		$condicionesPago	= $factura->condicionesPago;
		$metodoPago			= $factura->metodoPago;
	}
	
	$condicionesPago	= condicionesPago;
	
	echo'
	
	<tr>
		<td class="key">Uso del CFDI:</td>
		<td>
			<select id="selectUsoCfdi" name="selectUsoCfdi" class="cajas" style="width:400px">';
			
			foreach($usos as $row)	
			{
				echo '<option '.($row->idUso==$cotizacion->idUso?'selected="selected"':'').' value="'.$row->clave.'">'.$row->clave.', '.$row->descripcion.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Método de pago</td>
		<td>
			<select id="txtMetodoPago" name="txtMetodoPago" class="cajas" style="width:400px" >';
			
			foreach($metodos as $row)	
			{
				echo '<option '.($row->idMetodo==$cotizacion->idMetodo?'selected="selected"':'').' value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
			}
			
		echo'
			</select>
			
			
		</td>
	</tr>
	
	<tr>
		<td class="key">Forma  y cuenta de pago:</td>
		<td>
			<select id="txtFormaPago" name="txtFormaPago" class="cajas" style="width:400px">';
			
			foreach($formas as $row)	
			{
				echo '<option '.($row->idForma==$cotizacion->idFormaSat?'selected="selected"':'').' value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
			}
			
			echo'
			</select>
			
			<input type="text"  style="width:120px" class="cajas" id="txtCuentaPago" name="txtCuentaPago" value="" placeholder="Cuenta" />
		</td>
	</tr>
	
	 <!--<tr>
		<td class="key">Condiciones de pago:</td>
		<td>
			<input type="text" style="width:400px" class="cajas" id="txtCondiciones" name="txtCondiciones" value="'.$condicionesPago.'" />
		</td>
	</tr>-->
	
	<tr>
		<td class="key">Condiciones de pago:</td>
		<td>
			<select id="txtCondiciones" name="txtCondiciones" class="cajas" style="width:400px">
				<option>CONTADO</option>
				<option>CRÉDITO</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Observaciones:</td>
		<td>
			<textarea class="TextArea" id="txtObservaciones" name="txtObservaciones" style="width:400px; height:60px">'.$cotizacion->observaciones.'</textarea>
		</td>
	</tr>
</table>';

//LISTA DE PRODUCTOS
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
		<th width="26%">Pedimento</th>
	</tr>';

$i=1;

echo '<input type="hidden" id="txtNumeroProductosFactura" value="'.count($productos).'" />';

foreach($productos as $row)	
{
	$estilo				= $i%2>0?'class="sinSombra"':'class="sombreado"';
	$cantidadParcial	= $this->facturacion->sumarProductosParciales($cotizacion->idCotizacion,$row->idProducto);

	$cantidad			= $row->cantidad-$cantidadParcial;
	$descuento			= $row->descuento>0?$row->descuento/$row->cantidad:0;
	$descuentoTotal		= $descuento*$cantidad;
	$importe			= $cantidad*$row->precio-$descuentoTotal;
	
	$cantidad			= $row->cantidad-$row->devueltos;
	
	if($cantidad>0)
	{
		$importe	= $cantidad*$row->precio;
		
		echo '
		<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td>'.$row->codigoInterno.'</td>
			<td>';

				echo "<input type='text' class='cajas' id='txtDescripcionProductoFactura".$i."' name='txtDescripcionProductoFactura".$i."' value='".$row->nombre."' style='width:400px' />";

			echo'
			</td>
			<td align="center">'.$row->unidad.'</td>
			<td align="center" >'.number_format($cantidad,2).'</td>
			<td align="right">$'.number_format($row->precio,2).'</td>
			<td align="right">$'.number_format($descuentoTotal,2).'</td>
			<td align="right">$'.number_format($importe,2).'</td>

			<td>
				<input type="text" name="txtPedimento1'.$i.'" id="txtPedimento1'.$i.'" value="'.$row->anio.'" class="cajas" placeholder="Año validación, (Últimos 2 dígitos)" maxlength="2"  onkeypress="return soloNumerico(event)" style="width:97%"/> 
			  
				<input type="text" name="txtPedimento2'.$i.'" id="txtPedimento2'.$i.'" value="'.$row->aduana.'" class="cajas" placeholder="Aduana despacho, (2 dígitos)" maxlength="2" onkeypress="return soloNumerico(event)" style="width:97%"/> 
			  
				<input type="text" name="txtPedimento3'.$i.'" id="txtPedimento3'.$i.'" value="'.$row->patente.'" class="cajas" placeholder="Número patente, (4 dígitos)" maxlength="4" onkeypress="return soloNumerico(event)" style="width:97%"/> 
			  
				<input type="text" name="txtPedimento4'.$i.'" id="txtPedimento4'.$i.'" value="'.$row->digitos.'" class="cajas" placeholder="1 Dígito año en curso + 6 dígitos numeración progresiva" maxlength="7" onkeypress="return soloNumerico(event)" style="width:97%"/> 
				
				<input type="text" class="cajas" name="txtFecha'.$i.'" value="'.$row->fecha.'" id="txtFecha'.$i.'" style="width:30%"/>
			</td>
		</tr>

		<script>
			$("#txtFecha'.$i.'").datepicker();
		</script>';

		$i++;
	}
	
}
	
echo '</table>
</form>';

//PARA LAS FACTURAS PARCIALES
if($parciales!=null)
{
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="8">Facturas parciales</th>
		</tr>
		<tr>
			<th width="3%">#</th>
			<th>Fecha</th>
			<th>Folio</th>
			<th width="30%">Total</th>
			<th>Acciones</th>
		</tr>';
	
	$i=1;
	foreach($parciales as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cancelada	=$row->cancelada==1?'<i>(Cancelada)</i>':'';
		
		echo '
		<tr '.$estilo.'>
			<td align="center">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha,2).'</td>
			<td align="center">'.$row->serie.$row->folio.$cancelada.'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			<td align="center">
				<img title="Factura" onclick="window.open(\''.base_url().'pdf/crearFactura/'.$row->idFactura.'\')" src="'.base_url().'img/pdf.png" width="20" />
				<img title="XML" onclick="window.location.href=\''.base_url().'facturacion/descargarXML/'.$row->idFactura.'\'" src="'.base_url().'img/xml.png" width="20" />
				&nbsp;&nbsp;
				<img title="Enviar" onclick="formularioCorreoFactura('.$row->idFactura.')" src="'.base_url().'img/correo.png" width="20" />
				<img title="Zip" onclick="zipearFactura('.$row->idFactura.')" src="'.base_url().'img/zip.png" width="20" />
				<br />
				<a>PDF</a>
				<a>XML</a>
				<a>Enviar</a>
				<a>Zip</a>
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
