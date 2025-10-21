<script>
$(document).ready(function()
{
	$("#txtBuscarClientes").autocomplete(
	{
		source:base_url+'configuracion/obtenerClientes',
		
		select:function( event, ui)
		{
			$('#txtIdClienteGlobal').val(ui.item.idCliente);
		}
	});
	
	obtenerFolio();
});
</script>
<?php

echo'
<form id="frmFacturacion" name="frmFacturacion">
	<input type="hidden" id="txtComprobante" 	name="txtComprobante" value="ingreso" />
	
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">Cliente:</td>
			<td>
				<input type="text" style="width:600px" class="cajas" id="txtBuscarClientes" name="txtBuscarClientes" placeholder="Seleccione cliente" value="'.($cliente!=null?$cliente->razonSocial:'').'" />
				<input type="hidden" id="txtIdClienteGlobal" name="txtIdClienteGlobal" value="'.($cliente!=null?$cliente->idCliente:'0').'"  />
			</td>
		</tr>	
		
		<tr>
			<td class="key">Emisor:</td>
			<td>
				<select id="selectEmisores" name="selectEmisores" onchange="obtenerFolio()" class="cajas" style="width:400px">';
				
				if($emisores!=null)
				{
					foreach($emisores as $row)
					{
						echo '<option value="'.$row->idEmisor.'">'.$row->nombre.'</option>';
					}
				}
				else
				{
					echo '<option value="0">Registre un emisor</option>';
				}
				
					
				echo'
				</select>
			</td>
		</tr>

		<tr>
			<td class="key">Folio:</td>
			<td id="obtenerFolio"></td>
		</tr>
		
		<tr>
			<td class="key">Uso del CFDI:</td>
			<td>
				<select id="selectUsoCfdi" name="selectUsoCfdi" class="cajas" style="width:400px">';
				
				#'.($row->idUso=='2'?'selected="selected"':'').' 
				foreach($usos as $row)	
				{
					echo '<option  value="'.$row->clave.'">'.$row->clave.' '.$row->descripcion.'</option>';
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
					echo '<option value="'.$row->clave.'">'.$row->clave.' '.$row->concepto.'</option>';
				}
				
			echo'
				</select>
				
				<!--<input type="text"  style="width:120px" class="cajas" id="txtCuentaPago" name="txtCuentaPago" value="" placeholder="Cuenta" />
				<input type="hidden" style="width:400px" class="cajas" id="txtMetodoPagoTexto" name="txtMetodoPagoTexto" value="01 Efectivo" />-->
			</td>
		</tr>
		
		<tr>
			<td class="key">Forma  y cuenta de pago:</td>
			<td>
				<select id="txtFormaPago" name="txtFormaPago" class="cajas" style="width:400px">';
				
				foreach($formas as $row)	
				{
					echo '<option value="'.$row->clave.'">'.$row->clave.' '.$row->concepto.'</option>';
				}
				
				echo'
				</select>
				
				<input type="text"  style="width:120px" class="cajas" id="txtCuentaPago" name="txtCuentaPago" value="" placeholder="Cuenta" />
			</td>
		</tr>
		
		 <tr>
			<td class="key">Condiciones de pago:</td>
			<td>
				<input type="text" style="width:400px" class="cajas" id="txtCondiciones" name="txtCondiciones" value="Contado" />
			</td>
		</tr>
		<tr>
			<td class="key">Observaciones:</td>
			<td>
				<textarea class="TextArea" id="txtObservaciones" name="txtObservaciones" style="height:50px; width:400px"></textarea>
			</td>
		</tr>
	</table>
	
	<input type="hidden" id="txtSubTotal" name="txtSubTotal" value="0" />
	<input type="hidden" id="txtIva" name="txtIva" value="0" />
	<input type="hidden" id="txtTotal" name="txtTotal" value="0" />
	<input type="hidden" id="txtNumeroProductos" name="txtNumeroProductos" value="1" />

	<table class="admintable" width="100%" >
		<tr>
			<td style="width:80%">
				<table class="admintable" style="width:100%" id="tablaFacturacion">
					<tr>
						<th colspan="7">
							Detalles de Factura
							
							<img src="'.base_url().'img/add.png" width="22" height="22" onclick="cargarConceptoFactura()" title="Agregar concepto" />
						</th>
					</tr>
					<tr>
						<th></th>
						<th width="30%">Concepto</th>
						<th width="16%">Unidad</th>
						<th width="16%">Clave producto</th>
						<th width="10%">Cantidad</th>
						<th width="12%">Precio</th>
						<th width="12%">Importe</th>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table class="admintable" style="width:100%">
					<tr>
						<td class="key">Subtotal</td>
						<td width="100px" id="lblSubTotal">'.number_format(0,2).'</td>
					</tr>
					<tr>
						<td class="key">IVA %</td>
						<td>
							<select id="txtIvaPorcentaje" name="txtIvaPorcentaje" class="cajas" style="width:90px" onchange="calcularTotalesFactura()">
								<option value="0.160000">16.00</option>
								<option value="0.000000">0.00</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="key">IVA</td>
						<td id="lblIva">$'.number_format(0,2).'</td>
					</tr>
					<tr>
						<td class="key">Total</td>
						<td id="lblTotal">$'.number_format(0,2).'</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>';
?>