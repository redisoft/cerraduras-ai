<?php
echo '
<script>
$("#txtBuscarCliente").autocomplete(
{
	source:"'.base_url().'configuracion/obtenerClientes",
	
	select:function( event, ui)
	{
		$("#txtIdCliente").val(ui.item.idCliente);
		obtenerBancos();
		obtenerProductosVenta()
	}
});
</script>

<table class="admintable" width="100%">
	
	<tr>
		<td class="key">Buscar cliente:</td>
		<td colspan="5">
			<input placeholder="PÚBLICO GENERAL" type="text" class="cajas" id="txtBuscarCliente" placeholder="" style="width:600px"  />
			<input type="hidden" id="txtIdCliente" value="1" />
			<img src="'.base_url().'img/clientes.png" onclick="formularioClientes(\'venta\')" title="Nuevo cliente" width="22" />
		</td>
	</tr>
	<tr>
		<td class="key">Buscar producto:</td>
		<td colspan="5">
			<input type="text" class="cajas" id="txtBuscarProducto" onkeyup="obtenerProductosVenta()" style="width:600px"  />
		</td>
	</tr>
	
	<tr>
		
		<td class="key">Divisa:</td>
		<td>
			<select id="selectDivisas" name="selectDivisas" class="cajas" style="width:120px">';
				
				foreach($divisas as $row)
				{
					echo '<option value="'.$row->idDivisa.'">'.$row->nombre.' ('.$row->tipoCambio.')</option>';
				}
			
			echo'
			</select>
		</td>
		
		<td class="key">Facturar:</td>
		<td>
			&nbsp;<input type="checkbox" id="chkFacturar" name="chkFacturar"  />
		</td>
		
		<td class="key">Días de crédito:</td>
		<td>
			<input type="text" style="width:100px" class="cajas" id="txtDiasCredito" name="txtDiasCredito" value="0" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Mostrador/Envío:</td>
		<td>
			<select class="cajas" id="selectMostrador" name="selectMostrador" style="width:120px">
				<option value="0">Mostrador</option>
				<option value="1">Envío</option>
			</select>
		</td>
		
		<td class="key">Forma de pago:</td>
		<td>
			<input type="text" style="width:240px" class="cajas" id="txtFormaPago" name="txtFormaPago" value="Pago en una sola exhibición" />
		</td>
		
		<td class="key">Condiciones de pago:</td>
		<td>
			<input type="text" style="width:240px" class="cajas" id="txtCondicionesPago" name="txtCondicionesPago" value="30 días a partir de la fecha de entrega" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Emisor:</td>
		<td colspan="3">
			
			<select style="width:400px" id="selectEmisores" name="selectEmisores" class="cajas" onchange="obtenerFolio()">
				<option value="0">Seleccione</option>';
			
			foreach($emisores as $row)
			{
				$seleccionado=$row->idEmisor==$cliente->idEmisor?'selected="selected"':'';
				echo '<option '.$seleccionado.' value="'.$row->idEmisor.'">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
			}
			
		echo'
		</td>
		
		<td class="key">Folio:</td>
		<td id="obtenerFolio" colspan="2">
			Seleccionar emisor
		</td>
		
	</tr>
	
	<tr>
		<td class="key">Fecha:</td>
		<td colspan="2">
			<input type="text" style="width:120px" class="cajas" id="txtFechaVenta" name="txtFechaVenta" value="'.date('Y-m-d H:i').'" />
			<script>
				$("#txtFechaVenta").timepicker()
			</script>
		</td>
		
		<td class="key">Observaciones:</td>
		<td colspan="2">
			<textarea id="txtObservacionesVenta" name="txtObservacionesVenta" class="TextArea" style="width:320px" ></textarea>
		</td>
	</tr>
	
</table>
<div id="obtenerProductosVenta"></div>
<table class="admintable" width="100%" >
	<tr>
		<td width="81%">
			<table class="admintable" width="100%" id="tablaVentas">
				<tr>
					<th>#</th>
					<th>Código</th>
					<th style="display:none">Tipo</th>
					<th>Nombre</th>
					
					<th>Línea</th>
					<th>Modelo</th>
					<th>Color</th>
					<th>Talla</th>
					<th>Marca</th>
					
					<th>Cantidad</th>
					<th>P. unitario</th>
					<th>Total</th>
					
				</tr>
			</table>
		</td>
		<td style="position:absolute;" width="17%">
			<table class="admintable" width="100%" >
				<tr>
					<td class="key">Subtotal:</td>
					<td><input readonly="readonly" style="width:90px; type="text" class="cajas" id="txtSubTotal" value="0.00" /></td>
				</tr>
				
				<tr>
					<td class="key">% Descuento:</td>
					<td><input style="width:90px; type="text" class="cajas" id="txtDescuentoPorcentaje" onchange="calcularTotales()"  /></td>
				</tr>
				
				<tr>
					<td class="key">Descuento total:</td>
					<td><input readonly="readonly" style="width:90px; type="text" class="cajas" id="txtDescuentoTotal" value="0" /></td>
				</tr>
				
				<tr>
					<td class="key">IVA :</td>
					<td align="left">
						<select id="txtIva" class="cajas" style="width:100px" onchange="calcularTotales()">
							<option>'.$ivas->iva.'</option>	
							<option>'.$ivas->iva2.'</option>
							<option>'.$ivas->iva3.'</option>
						</select>
						<!--input readonly="readonly" style="width:90px; type="text" class="cajas" id="txtIva"  value="'.$this->session->userdata('iva').'" /-->
					</td>
				</tr>
				<tr>
					<td class="key">Total:</td>
					<td>
						<input readonly="readonly" style="width:90px; type="text" class="cajas" id="txtTotal" value="0.00" />
					</td>
				</tr>
				<tr>
					<td class="key">Pago:</td>
					<td>
						<input style="width:90px; type="text" class="cajas" id="txtPago" value="0.00" onkeyup="calcularCambio()" />
					</td>
				</tr>
				<tr>
					<td class="key">Cambio:</td>
					<td>
						<input style="width:90px; type="text" readonly="readonly" class="cajas" id="txtCambio" value="0.00" />
					</td>
				</tr>
				
				<tr>
					<td class="key">Forma de cobro:</td>
					<td>
						<select id="TipoPago" name="TipoPago" class="cajas" style="width:100px;" onchange="mostrarDatos()">
							<option value="1">Efectivo</option>
							
							<option value="5">Tarjeta de crédito</option>
							<option value="6">Tarjeta de débito</option>
							
							<option value="2">Cheque</option>
							<option value="3">Transferencia</option>
							<option value="4" style="display:none">No identificado</option>
						</select>   
					 </td>
				</tr>
				<tr style="display:none;" id="mostrarCheques">
					<td class="key">Número cheque:</td>
					<td>
						<input type="text" class="cajas" style="width:90px" id="numeroCheque" name="numeroCheque" />   
					</td>
				</tr>
				<tr style="display:none;" id="mostrarTransferencia">
					<td class="key">Número Transferencia:</td>
					<td>
					<input type="text" class="cajas" style="width:90px" id="numeroTransferencia" name="numeroTransferencia" />    </td>
				</tr>
				
				<tr style="display:none;" id="filaNombre">
					<td class="key">Nombre del receptor:</td>
					<td>
						<input type="text" class="cajas" style="width:90px" id="txtNombreReceptor" name="txtNombreReceptor" />
					</td>
				</tr>
				
				<tr style="display:none;" id="filaBanco">
					<td class="key">Bancos:</td>
					<td id="obtenerBancos"> 
					 <select id="listaBancos" name="listaBancos" class="cajas" style="width:90px;" onchange="buscarCuentas()" >
						<option selected="selected" value="1">Efectivo</option>';
						
						   /*foreach($bancos as $row)
						   {
							   if($row->idBanco>1)
							   {
								   echo'<option value="'.$row->idBanco.'" >'.$row->nombre.'</option>';
							   }
						   }*/
						 
						echo'</select>
						</td>
					</tr>
					<tr style="display:none;" id="filaCuenta">
						<td class="key">Cuentas</td>
						<td id="cargarCuenta">
						<select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:90px;" >
							<option value="1">Efectivo</option>
						</select>
					</td>     
				</tr>
				
			</table>
		</td>
	</tr>
</table>';