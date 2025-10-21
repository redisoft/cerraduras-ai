<?php
$datosFiscales	="1";
if(strlen($cliente->rfc)<12 or strlen($cliente->empresa) <3 or strlen($cliente->pais) <3 )
{
	$datosFiscales	="0";
}

echo'
<input type="hidden" id="txtIdCotizacion" value="'.$idCotizacion.'" />
<table class="admintable" width="100%">
	<tr>
		<td class="key">Orden de venta</td>
		<td>'.$cotizacion->ordenCompra.'</td>
	</tr>
	<tr>
		<td class="key">Empresa</td>
		<td>'.$cliente->empresa.( $datosFiscales==0?' <i>(El cliente no tiene los datos fiscales necesarios para crear la factura)</i>':'').'</td>
	</tr>
	<tr>
		<td class="key">Teléfono</td>
		<td>'.$cliente->telefono.'</td>
	</tr>';

echo'</table>';

if($cotizacion->idFactura==0)
{
	#$folio				=$this->facturacion->obtenerFolio();
	$divisas			=$this->configuracion->obtenerDivisas();
	$emisores			=$this->facturacion->obtenerEmisores();
	
	$subTotal			=$cotizacion->subTotal;
	$iva				=$cotizacion->iva;
	$descuento			=$cotizacion->descuento/100;
	
	$descuento			=$subTotal*$descuento;
	$suma				=$subTotal-$descuento;
	$iva				=$suma*$iva;
	
	#PARCIALES
	#---------------------------------------------------------------------------------------------------------#
	#$parciales			=$this->facturacion->obtenerFacturasParciales($idCotizacion);
	$totalParciales		=0;
	
	echo'
	
	<input type="hidden" id="txtDiferencia" value="'.($cotizacion->total-$totalParciales).'" />
	<input type="hidden" id="txtParcial" value="0" />
	<input type="hidden" id="txtIdClienteFactura" value="'.$cotizacion->idCliente.'" />
	<input type="hidden" id="txtDatosFiscales" value="'.$datosFiscales.'" />
	
	<table class="admintable" width="100%;">
		<tr>
			<th colspan="2">Facturación</th>
		</tr>
		<!--tr>
		  <td class="key">Cliente:</td>
		  <td>'.$cliente->empresa.'</td>
		</tr>	
		<tr>
			<td class="key">Subtotal</td>
			<td>'.number_format($cotizacion->subTotal,2).'</td>
		</tr>
		
		 <tr>
			<td class="key">Descuento ('.$cotizacion->descuento.'%)</td>
			<td>$'.number_format($descuento,2).'</td>
		</tr>
		<tr>
			<td class="key" >IVA ('.($cotizacion->iva*100).'%)</td>
			<td>$'.number_format($iva,2).'</td>
		</tr-->
		
		<tr style="display:none">
			<td class="key" >Retención:</td>
			<td>
				<label>Nombre</label> 	<input  value="" type="text" class="cajas" id="txtNombreRetencion" /><br />
				<label>Tasa %</label>&nbsp;&nbsp;<input onchange="calcularRetencion()" value="0" style="width:100px" type="text" class="cajas" id="txtTasa" /><br />
				<label>Importe</label> <input readonly="readonly" value="0" style="width:100px" type="text" class="cajas" id="txtRetencion" />
				
				<input type="hidden" id="txtSubTotal" 	value="'.$subTotal.'" />
				<input type="hidden" id="txtDescuento" 	value="'.$descuento.'" />
				<input type="hidden" id="txtSuma" 		value="'.($subTotal-$descuento).'" />
				<input type="hidden" id="txtIva" 		value="'.$iva.'" />
				<input type="hidden" id="txtTotal" 		value="'.$cotizacion->total.'" />
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Total</td>
			<td>$<label id="lblTotal">'.number_format($cotizacion->total,2).'</label></td>
		</tr>
		
		<tr>
			<td class="key">Emisor:</td>
			<td>
				
				<select style="width:400px" id="selectEmisores" name="selectEmisores" class="cajas" onchange="obtenerFolio()">
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
			<td id="obtenerFolio" colspan="2">
				Seleccionar emisor
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Divisa:</td>
			<td>
				<select class="cajas" id="selectDivisas">';
			
			foreach ($divisas as $row)
			{
				$seleccionado=$row->idDivisa==$cotizacion->idDivisa?'selected="selected"':'';
				
				echo '<option '.$seleccionado.' value="'.$row->idDivisa.'">'.$row->nombre.' ($'.$row->tipoCambio.')</option>';
			}
			
			echo'
				</select>
			</td>
		</tr>';
		
		$formaPago			=strlen($cotizacion->formaPago)>0?$cotizacion->formaPago:'Pago en una sola exhibición';
		$condicionesPago	=strlen($cotizacion->condicionesPago)>0?$cotizacion->condicionesPago:'30 días a partir de la fecha de entrega';
		$metodoPago			=strlen($cotizacion->metodoPago)>0?$cotizacion->metodoPago:'Efectivo';
		
		$condicionesPago	= condicionesPago;
		
		echo'
		<tr>
			<td class="key">Método de pago</td>
			<td>
				<input type="text"  style="width:400px" class="cajas" id="txtMetodoPago" name="txtMetodoPago" value="'.$metodoPago.'" />
			</td>
		</tr>
		<tr>
			<td class="key">Forma de pago</td>
			<td>
				<input type="text" style="width:400px" class="cajas" id="txtFormaPago" name="txtFormaPago" value="'.$formaPago.'" />
			</td>
		</tr>
		 <tr>
			<td class="key">Condiciones de pago</td>
			<td>
				<input type="text" style="width:400px" class="cajas" id="txtCondiciones" name="txtCondiciones" value="'.$condicionesPago.'" />
			</td>
		</tr>
	</table>';
}

echo'
<table class="admintable" width="100%" style="margin-top:3px">
	<tr>
		<th>Código</th>
		<th>Descripción</th>
		<th>Unidad</th>
		<th>Cantidad</th>
		<th>Precio</th>
		<th>Importe</th>
	</tr>';

$i=1;

echo '<input type="hidden" id="txtNumeroProductosFactura" value="'.count($productos).'" />';

foreach($productos as $row)
{
	$estilo=$i%2>0?"class='sinSombra'":'class="sombreado"';

	$nombre=strlen($row->nombre)>2?$row->nombre:$row->producto;
	
	echo'
	<tr '.$estilo.'>
		<td>'.$row->codigoInterno.'</td>
		
		<td>';
			echo "<input type='text' class='cajas' id='txtDescripcionProductoFactura".$i."' value='".$nombre."' style='width:400px' />";
		echo'</td>
		<td align="center">'.$row->unidad.'</td>
		<td align="center">'.number_format($row->cantidad,2).'</td>
		<td align="right">$'.number_format($row->precio,2).'</td>
		<td align="right">$'.number_format($row->importe,2).'</td>
	</tr>';
	
	$i++;
}

$descuento		=$cotizacion->subTotal*($cotizacion->descuento/100);
$suma			=$cotizacion->subTotal-$descuento;
$iva			=$suma*$cotizacion->iva;
$total			=$suma+$iva;

echo'
<tr>
	<td colspan="5" style="text-align:right" class="totales">Subtotal</td>
	<td align="right">$'.number_format($cotizacion->subTotal,2).'</td>
</tr>
<tr>
	<td colspan="5" style="text-align:right" class="totales">Descuento '.number_format($cotizacion->descuento,2).'%</td>
	<td align="right">$'.number_format($descuento,2).'</td>
</tr>
<tr>
	<td colspan="5" style="text-align:right" class="totales">IVA '.number_format($cotizacion->iva*100,2).'%</td>
	<td align="right">$'.number_format($iva,2).'</td>
</tr>
<tr>
	<td colspan="5" style="text-align:right" class="totales">Total</td>
	<td align="right">$'.number_format($cotizacion->total,2).'</td>
</tr>';

echo '</table>';

echo '<input type="hidden" id="txtIdFactura" value="'.$cotizacion->idFactura.'" />';