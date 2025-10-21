<?php
echo'
<form id="frmNotaCredito">
	<input type="hidden" id="txtNotaAbierto" name="txtNotaAbierto" value="1"/>
	
	<input type="hidden" id="txtIvaPorcentaje" name="txtIvaPorcentaje" value="'.$cotizacion->ivaPorcentaje.'">
	<input type="hidden" id="txtDescuentoPorcentaje" name="txtDescuentoPorcentaje" value="'.$cotizacion->descuentoPorcentaje.'">
	
	<input type="hidden" id="txtSubTotal" 		name="txtSubTotal" 		value="0">
	<input type="hidden" id="txtIva" 			name="txtIva" 			value="0">
	<input type="hidden" id="txtTotalNota" 		name="txtTotalNota" 	value="0">
	<input type="hidden" id="txtDescuentoNota" 	name="txtDescuentoNota" value="0">
	
	<table class="admintable" width="100%;">
		<tr>
		  <td class="key">Cliente:</td>
		  <td>'.$cliente->empresa.'</td>
		</tr>	
		<tr>
			<td class="key">Subtotal</td>
			<td id="lblSubTotal">'.number_format(0,2).'</td>
		</tr>
		
		 <tr>
			<td class="key">Descuento ('.number_format($cotizacion->descuentoPorcentaje,2).'%)</td>
			<td id="lblDescuentoNota">$'.number_format(0,2).'</td>
		</tr>
		<tr>
			<td class="key" >IVA ('.number_format($cotizacion->ivaPorcentaje,2).'%)</td>
			<td id="lblIva">$'.number_format(0,2).'</td>
		</tr>
		
		<tr>
			<td class="key">Total</td>
			<td><label id="lblTotalNota">$'.number_format(0,2).'</label></td>
		</tr>
		
		<tr>
			<td class="key">Emisor:</td>
			<td>
				
				<select style="width:400px" id="selectEmisores" name="selectEmisores" class="cajas" onchange="obtenerFolio()">
					<option value="0">Seleccione</option>';
				
				foreach($emisores as $row)
				{
					$seleccionado='selected="selected"';#$row->idEmisor==$cliente->idEmisor?'selected="selected"':'';
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
		$condicionesPago= strlen($cotizacion->condicionesPago)>3?$cotizacion->condicionesPago:'30 días a partir de la fecha de entrega';
		$metodoPago		= strlen($cotizacion->metodoPago)>0?$cotizacion->metodoPago:'Efectivo';
		
		#$condicionesPago	= condicionesPago;
		
		echo'
		<tr>
			<td class="key">Método de pago</td>
			<td>
				<!--<input type="text"  style="width:400px" class="cajas" id="txtMetodoPago" name="txtMetodoPago" value="'.$metodoPago.'" />-->
				
				<select style="width:400px" id="txtMetodoPago" name="txtMetodoPago" class="cajas"  onchange="sugerirMetodoPago()">';
			
				foreach($metodos as $row)
				{
					echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
				}
				
				echo'
				</select>
				<input type="hidden" class="cajas" id="txtMetodoPagoTexto" name="txtMetodoPagoTexto" value="01, Efectivo" style="width:250px"/>
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
		<tr>
			<td class="key">Observaciones</td>
			<td>
				<textarea class="TextArea" id="txtObservaciones" name="txtObservaciones" style="width:400px; height:60px">'.$cotizacion->observaciones.'</textarea>
			</td>
		</tr>
	</table>
</form>';