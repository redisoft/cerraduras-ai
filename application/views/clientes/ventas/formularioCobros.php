<script>
$(document).ready(function()
{
	$('#txtIdUsuarioVendedor').select2({ placeholder: "Seleccione", dropdownParent: $("#ventanaCobrosVenta")});

	$('#txtFechaEntrega').datepicker({changeYear:true});
	$("#txtPago").keypress(function(e)
	 {
		if(e.which == 13) 
		{
			e.preventDefault();
			registrarVenta();
			
		}
	});
	
	$("#txtBuscarUsuario").autocomplete(
	{
		source:base_url+"configuracion/autoCompletadoUsuarios",
		autoFocus:true,
		select: function(event,ui)
		{
			$("#txtIdUsuarioVendedor").val(ui.item.idUsuario);
			$("#txtPago").focus();
		}
	});
});
</script>
<?php
echo '
<form id="frmCobros" name="frmCobros">
	<div align="center" style="background-color: #FFF; height:162px; width:397px; font-size: 30px; position: absolute; left: 480px; top: 9px; line-height: 155px" id="lblCambioCliente">Cambio: $0.00</div>
	<table class="admintable" width="100%" >
		<tr>
			<td class="etiquetaGrande">Subtotal:</td>
			<td class="textoGrande">
				<span id="lblSubTotalVenta">$0.00</span>
				<input type="hidden" id="txtSubTotal" name="txtSubTotal" value="0.00"/>
			</td>
		</tr>
	
		<tr>
			<td class="etiquetaGrande">Descuento:</td>
			<td class="textoGrande">
				<span id="lblDescuentoVenta">$0.00</span>
				<input type="hidden" id="txtDescuentoTotal" name="txtDescuentoTotal" value="0.00"/>
			</td>
		</tr>
		
		<tr>
			<td class="etiquetaGrande">Impuestos :</td>
			<td align="left" class="textoGrande">
				
				<span id="lblImporteIva">$0.00</span>
				
				<select id="selectIva" name="selectIva" class="cajas" style="width:100px; display: none" onchange="calcularTotales()">
					<option '.($iva==$ivas->iva?'selected="selected"':'').'>'.$ivas->iva.'</option>	
					<option '.($iva==$ivas->iva2?'selected="selected"':'').'>'.$ivas->iva2.'</option>
					<option '.($iva==$ivas->iva3?'selected="selected"':'').'>'.$ivas->iva3.'</option>
				</select>
				
				<input type="hidden" id="txtIvaTotal" name="txtIvaTotal" value="0.00" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetaGrande">Total:</td>
			<td class="textoGrande">
				<span id="lblTotalVenta">$0.00</span>
				<input type="hidden" id="txtTotal" name="txtTotal" value="0.00" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetaGrande">Forma de cobro:</td>
			<td class="textoGrande">
				<select id="selectFormas" name="selectFormas" class="cajas" style="width:230px;" onchange="opcionesFormasPago(1); interesesVentasTotales() ">';
					#opcionesFormasPagoVentas(1)
					foreach($formas as $row)
					{
						if($row->idForma!=4)
						{
							#$seleccionado	= $cotizacion->idForma==$row->idForma?'selected="selected"':'';
							$seleccionado	= '';
							echo '<option '.$seleccionado.' value="'.$row->idForma.'|'.round($row->porcentaje,decimales).'|'.$row->idCuenta.'">'.$row->nombre.($row->porcentaje>0?' ('.number_format($row->porcentaje,decimales).'%)':'').'</option>';
						}
					}
		
				echo'
				</select>   
			 </td>
		</tr>
		
		<tr>
			<td class="etiquetaGrande">Vendedor:</td>
			<td class="textoGrande">
				
				<select id="txtIdUsuarioVendedor" name="txtIdUsuarioVendedor" onchange="opcionesVendedores()" style="width:228px" class="cajas">
					<option value="0">Seleccione</option>';
					
					foreach($usuarios as $row)
					{
						echo '<option '.($idUsuario==$row->idUsuario?'selected="selected"':'').'  value="'.$row->idUsuario.'">'.$row->vendedor.'</option>';
					}
				
				echo'
				</select>
				
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="etiquetaGrande">Pago:</td>
			<td class="textoGrande">
				<input type="text" id="txtPago" name="txtPago" value="" style="width:228px;" class="cajas" placeholder="$0.00" onkeyup="calcularCambio()"  onkeypress="return soloDecimales(event)" maxlength="10"/>
			</td>
		</tr>
		
		
		
		<tr style="display:none">
			<td class="etiquetaGrande">Cambio:</td>
			<td class="textoGrande">
				<input type="text" id="txtCambio" name="txtCambio" value="0.00" style="width:150px;" readonly="readonly" class="cajas"/>
				<input type="hidden" id="txtCambioActivo" name="txtCambioActivo" value="0" />
			</td>
		</tr>
		
		<tr style="display:none;" id="mostrarCheques">
			<td class="etiquetaGrande">Número cheque:</td>
			<td class="textoGrande">
				<input type="text" id="numeroCheque" name="numeroCheque" class="cajas" style="width:150px" />   
			</td>
		</tr>
		<tr style="display:none;" id="mostrarTransferencia">
			<td class="etiquetaGrande">Número Transferencia:</td>
			<td class="textoGrande">
				<input type="text" id="numeroTransferencia" name="numeroTransferencia" class="cajas" style="width:150px" />    
			</td>
		</tr>
		
		<tr style="display:none;" id="filaNombre">
			<td class="etiquetaGrande">Nombre del receptor:</td>
			<td>
				<input type="text" id="txtNombreReceptor" name="txtNombreReceptor" class="cajas" style="width:150px"/>
			</td>
		</tr>
		
		<tr style="display:none;" id="filaBanco">
			<td class="etiquetaGrande">Bancos:</td>
			<td id="obtenerBancos" class="textoGrande"> 
			 <select id="listaBancos" name="listaBancos" class="cajas" style="width:150px;" onchange="buscarCuentasCliente()" >
				<option value="1">Efectivo</option>';
				
				foreach($bancos as $row)
				{
					if($row->idBanco!=1)
					{
						echo '<option value="'.$row->idBanco.'">'.$row->nombre.'</option>';
					}
				}
		
				echo'</select>
				</td>
			</tr>
			<tr style="display:none;" id="filaCuenta">
				<td class="etiquetaGrande">Cuentas</td>
				<td id="cargarCuenta" class="textoGrande">
				<select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:150px;" >
					<option value="1">Efectivo</option>
				</select>
			</td>     
		</tr>
		
		<tr>
			<td class="etiquetaGrande"><strong>Prefactura: </strong></td>
			<td class="textoGrande">
				&nbsp;<input type="checkbox" id="chkFacturar" name="chkFacturar" value="1" style="width: 22px; height: 22px"  />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetaGrande">Días de crédito: </td>
			<td class="textoGrande">
				<input type="text" id="txtDiasCredito" name="txtDiasCredito" value="'.$this->input->post('diasCredito').'" style="width:100px" class="cajas" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetaGrande">Mostrador/Envío: </td>
			<td class="textoGrande">
				<select class="cajas" id="selectMostrador" name="selectMostrador" style="width:228px" onchange="opcionesRutaEntrega()">
					<option value="0">Mostrador</option>
					<option value="1">Envío</option>
					<option value="1">Envío teléfono</option>
					<option value="0">Traspaso</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="etiquetaGrande">Cliente:</td>
			<td class="textoGrande" id="lblClienteVenta">
				'.$cliente->empresa.'
			</td>
		</tr>
		
		<tbody id="filaRutas" style="display: none">
			<tr>
				<td class="etiquetaGrande">Rutas: </td>

				<td class="textoGrande">
					<select id="selectRutas" name="selectRutas" class="cajas" style="width:228px">
						<option value="0">Seleccione</option>';

						foreach($rutas as $row)
						{
							echo '<option value="'.$row->idRuta.'">'.$row->nombre.'</option>';
						}

					echo'
					</select>
					
					<textarea id="txtObservacionesEnvio" name="txtObservacionesEnvio" class="TextArea" style="position: absolute" placeholder="Observaciones"></textarea>
				</td>
			</tr>

			<tr>
				<td class="etiquetaGrande">Fecha de entrega:</td>
				<td class="textoGrande">
					<input type="text" id="txtFechaEntrega" name="txtFechaEntrega"  value="'.date('Y-m-d').'" maxlength="10" style="width:120px;" class="cajas" />
				</td>
			</tr>

			<tr>
				<td class="etiquetaGrande">Dirección: </td>

				<td class="textoGrande" id="obtenerDireccionesCliente">
					<select id="selectDirecciones" name="selectDirecciones" class="cajas" style="width:550px" >
						<option value="0">Seleccione</option>';

						foreach($direcciones as $row)
						{
							echo '<option value="'.$row->idDireccion.'">'.$row->razonSocial.', '.$row->calle.' '.$row->numero.' '.$row->colonia.'</option>';
						}

					echo'
					</select>
				</td>
			</tr>
		
		</tbody>
		
	</table>
	
	<table class="admintable" width="100%" style="display: none">
		<tr>
			<td class="key">Forma de pago: </td>
			<td>
				<select id="selectFormaPagoSat" name="selectFormaPagoSat" class="cajas" style="width:270px"  >';
				
				foreach($formasSat as $row)
				{
					echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
				}
				
				echo'
				</select>
				
			</td>
		</tr>
		
		<tr>
			<td class="key">Condiciones de pago:</td>
			<td>
				<input type="text" id="txtCondicionesPago" name="txtCondicionesPago" value="Pago en una sola exhibición" style="width:225px" class="cajas"  />
			</td>
			
			<td '.($reutilizar==1?'style="display:none"':'').' class="key">Emisor: </td>
			
			<td colspan="2" class="" '.($reutilizar==1?'style="display:none"':'').'>
				<select id="selectEmisores" name="selectEmisores" class="cajas" onchange="obtenerFolio()" style="width:270px">
					<option value="0">Seleccione</option>';
				
				foreach($emisores as $row)
				{
					#$seleccionado	= $row->idEmisor==$cliente->idEmisor?'selected="selected"':'';
					$seleccionado	= '';
					echo '<option  value="'.$row->idEmisor.'" selected="selected">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
				}
				
			echo'
				</select>
				<label style="font-size:12px">Folio: </label><label id="obtenerFolio" style="font-size:12px" class="textoGrande"></label>
			</td>
		</tr>
		
		
		<tr '.($reutilizar==1?'style="display:none"':'').'>
			<td class="key">Método de pago:</td>
			<td class="">
				<select id="selectMetodoPago" name="selectMetodoPago" class="cajas" style="width:270px">';
				
				foreach($metodos as $row)
				{
					echo '<option value="'.$row->idMetodo.'">'.$row->clave.', '.$row->concepto.'</option>';
				}
				
				echo'
				</select>
				
				
			</td>
			
			<td class="key">Correo: </td>
			<td colspan="2" >
				<input type="text" id="txtCorreoFactura" name="txtCorreoFactura" value="'.$correo.'" style="width:270px" class="cajas"  />
			</td>
		</tr>
	
		<tr style="display:none">
			<td class="textoGrande">
				<select id="selectDivisas" name="selectDivisas" class="cajas" style="width:120px">';
					
					foreach($divisas as $row)
					{
						echo '<option value="'.$row->idDivisa.'">'.$row->nombre.' ('.$row->tipoCambio.')</option>';
					}
				
				echo'
				</select>
			</td>
		</tr>
		
		
		<tr>
			<td class="key">Uso del CFDI: </td>
			<td class="" colspan="3">
				<select id="selectUsoCfdi" name="selectUsoCfdi" class="cajas" style="width:600px">';
				
				foreach($usos as $row)
				{
					echo '<option value="'.$row->idUso.'">'.$row->clave.', '.$row->descripcion.'</option>';
				}
				
				echo'
				</select>
				
			</td>
		</tr>
		
	</table>
	</div>
</form>';
