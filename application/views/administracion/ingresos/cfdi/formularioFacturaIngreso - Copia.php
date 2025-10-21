<?php
echo'
<form id="frmFacturaIngreso">
	<input type="hidden" id="txtNotaAbierto" name="txtNotaAbierto" value="1"/>

	<input type="hidden" id="txtSubTotal" 		name="txtSubTotal" 		value="'.$ingreso->subTotal.'"/>
	<input type="hidden" id="txtIvaPorcentaje" 	name="txtIvaPorcentaje" value="'.$ingreso->iva.'"/>
	<input type="hidden" id="txtIva" 			name="txtIva" 			value="'.$ingreso->ivaTotal.'"/>
	<input type="hidden" id="txtTotal" 			name="txtTotal" 		value="'.$ingreso->pago.'"/>
	<input type="hidden" id="txtIdCliente" 		name="txtIdCliente" 	value="'.$ingreso->idCliente.'"/>
	<input type="hidden" id="txtIdIngreso" 		name="txtIdIngreso" 	value="'.$ingreso->idIngreso.'"/>
	
	<table class="admintable" width="100%;">
		<tr>
		  <td class="key">Cliente:</td>
		  <td>'.$ingreso->cliente.'</td>
		</tr>	
		
		<tr>
		  <td class="key">Concepto:</td>
		  <td>
		  	<textarea class="TextArea" id="txtConcepto" name="txtConcepto" style="height:40px; width:400px;">'.$ingreso->producto.'</textarea>
		  </td>
		</tr>	
		
		<tr>
			<td class="key">Subtotal</td>
			<td id="lblSubTotal">'.number_format($ingreso->subTotal,2).'</td>
		</tr>
		<tr>
			<td class="key" >IVA ('.number_format($ingreso->iva,2).'%)</td>
			<td id="lblIva">$'.number_format($ingreso->ivaTotal,2).'</td>
		</tr>
		
		<tr>
			<td class="key">Total</td>
			<td><label id="lblTotalNota">$'.number_format($ingreso->pago,2).'</label></td>
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
		</tr>
		
		<tr>
			<td class="key">Método de pago</td>
			<td>
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
				<input type="text" style="width:400px" class="cajas" id="txtFormaPago" name="txtFormaPago" value="Pago en una sola exhibición" />
			</td>
		</tr>
		 <tr>
			<td class="key">Condiciones de pago</td>
			<td>
				<input type="text" style="width:400px" class="cajas" id="txtCondiciones" name="txtCondiciones" value="30 días a partir de la fecha de entrega" />
			</td>
		</tr>
		<tr>
			<td class="key">Observaciones</td>
			<td>
				<textarea class="TextArea" id="txtObservaciones" name="txtObservaciones" style="width:400px; height:60px"></textarea>
			</td>
		</tr>
	</table>
</form>';