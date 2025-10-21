<script>
$(document).ready(function()
{
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
				<input readonly="readonly" style="display: none" type="text" class="cajas" id="txtSubTotal" name="txtSubTotal" value="0.00" />
			</td>
		</tr>
	
		<tr>
			<td class="etiquetaGrande">Descuento:</td>
			<td class="textoGrande">
				<span id="lblDescuentoVenta">$0.00</span>
				<input readonly="readonly" style="display: none" type="text" class="cajas" id="txtDescuentoTotal" name="txtDescuentoTotal" value="0.00" />
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
				<input readonly="readonly" style="display: none" type="text" class="cajas" id="txtTotal" name="txtTotal" value="0.00" />
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
				
				<!--<input style="width:228px; type="text" class="cajas" id="txtBuscarUsuario" name="txtBuscarUsuario" placeholder="Seleccione" value="'.$usuario.'"  />
				<input type="hidden" id="txtIdUsuarioVendedor" name="txtIdUsuarioVendedor" value="'.$idUsuario.'"/>-->
				
				
				<select style="width:228px; type="text" class="cajas" id="txtIdUsuarioVendedor" name="txtIdUsuarioVendedor">
					<option value="0">Seleccione</option>';
					
					foreach($usuarios as $row)
					{
						echo '<option '.($idUsuario==$row->idUsuario?'selected="selected"':'').'  value="'.$row->idUsuario.'">'.$row->usuario.'</option>';
					}
				
				echo'
				</select>
				
			</td>
		</tr>
		
		<tr>
			<td class="etiquetaGrande">Pago:</td>
			<td class="textoGrande">
				<input style="width:228px; type="text" class="cajas" id="txtPago" name="txtPago" placeholder="$0.00" onkeyup="calcularCambio()" value="" onkeypress="return soloDecimales(event)" maxlength="10" />
			</td>
		</tr>
		
		
		
		<tr style="display:none">
			<td class="etiquetaGrande">Cambio:</td>
			<td class="textoGrande">
				<input style="width:150px; type="text" readonly="readonly" class="cajas" id="txtCambio" name="txtCambio" value="0.00" />
				<input type="hidden" id="txtCambioActivo" name="txtCambioActivo" value="0" />
			</td>
		</tr>';
		
		if(sistemaActivo=='olyess')
		{
			echo'
			<tr id="filaAcrilicoCobros">
				<td class="etiquetaGrande">Depósito acrílico:</td>
				<td class="textoGrande">
					<input style="width:150px; type="text" class="cajas" id="txtAcrilico" name="txtAcrilico" placeholder="$0.00"  value="" onkeypress="return soloDecimales(event)" maxlength="10" />
				</td>
			</tr>';
		}
		
		
		
		echo'
		<tr style="display:none;" id="mostrarCheques">
			<td class="etiquetaGrande">Número cheque:</td>
			<td class="textoGrande">
				<input type="text" class="cajas" style="width:150px" id="numeroCheque" name="numeroCheque" />   
			</td>
		</tr>
		<tr style="display:none;" id="mostrarTransferencia">
			<td class="etiquetaGrande">Número Transferencia:</td>
			<td class="textoGrande">
				<input type="text" class="cajas" style="width:150px" id="numeroTransferencia" name="numeroTransferencia" />    
			</td>
		</tr>
		
		<tr style="display:none;" id="filaNombre">
			<td class="etiquetaGrande">Nombre del receptor:</td>
			<td>
				<input type="text" class="cajas" style="width:150px" id="txtNombreReceptor" name="txtNombreReceptor" />
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
	</table>
	
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Prefactura: </td>
			<td class="">
				&nbsp;<input type="checkbox" id="chkFacturar" name="chkFacturar" value="1"  />
			</td>
			
			<td class="key">Días de crédito: </td>
			<td class="">
				<input type="text" style="width:100px" class="cajas" id="txtDiasCredito" name="txtDiasCredito" value="'.$this->input->post('diasCredito').'" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Mostrador/Envío: </td>
			<td class="">
				<select class="cajas" id="selectMostrador" name="selectMostrador" style="width:120px">
					<option value="0">Mostrador</option>
					<option '.(sistemaActivo=='olyess'?'selected="selected"':'').' value="1">Envío</option>';
					
					#echo $idTienda==0?'':'';
				
				echo'
				</select>
			</td>
			
			<td class="key">Forma de pago: </td>
			
			<td class="">
				<!--<input type="text" style="width:225px" class="cajas" id="txtFormaPago" name="txtFormaPago" value="Pago en una sola exhibición" />-->
				
				
				<select style="width:270px" id="selectFormaPagoSat" name="selectFormaPagoSat" class="cajas"  >';
				
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
			<td class="">
				<input type="text" style="width:225px" class="cajas" id="txtCondicionesPago" name="txtCondicionesPago" value="Pago en una sola exhibición" />
			</td>
			
			<td '.($reutilizar==1?'style="display:none"':'').' class="key">Emisor: </td>
			
			<td colspan="2" class="" '.($reutilizar==1?'style="display:none"':'').'>
				<select style="width:270px" id="selectEmisores" name="selectEmisores" class="cajas" onchange="obtenerFolio()">
					<option value="0">Seleccione</option>';
				
				foreach($emisores as $row)
				{
					#$seleccionado	= $row->idEmisor==$cliente->idEmisor?'selected="selected"':'';
					$seleccionado	= '';
					echo '<option selected="selected" value="'.$row->idEmisor.'">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
				}
				
			echo'
				</select>
				<label style="font-size:12px">Folio: </label><label id="obtenerFolio" style="font-size:12px" class="textoGrande"></label>
			</td>
		</tr>
		
		
		<tr '.($reutilizar==1?'style="display:none"':'').'>
			<td class="key">Método de pago:</td>
			<td class="">
				<select style="width:270px" id="selectMetodoPago" name="selectMetodoPago" class="cajas"  >';
				
				foreach($metodos as $row)
				{
					echo '<option value="'.$row->idMetodo.'">'.$row->clave.', '.$row->concepto.'</option>';
				}
				
				echo'
				</select>
				
				
			</td>
			
			<td class="key">Correo: </td>
			<td colspan="2" >
				<input type="text" class="cajas" id="txtCorreoFactura" name="txtCorreoFactura" value="'.$correo.'" style="width:270px" />
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
				<select style="width:600px" id="selectUsoCfdi" name="selectUsoCfdi" class="cajas"  >';
				
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