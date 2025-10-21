<script>
$(document).ready(function()
{
	$("#txtFechaConcepto").datepicker();
});
</script>
<?php
echo'
<div id="registrandoInformacion"></div>
<form id="frmConceptos" name="frmConceptos">
	<table class="tablaFormularios">
		<tr>
			<th colspan="4">
			
				<div style="" id="subirImagen" class="custom-input-file" onclick="seleccionarFichero()">
					Póliza
					<input class="input-file" type="file" id="txtXml"/>
					<img src="'.base_url().'img/xml.png" onclick="" title="Importar xml" />
				</div>
			</th>	
		</tr>
		<tr>
			<td class="etiquetas">Tipo de póliza:</td>
			<td>
				<select id="selectTipo" name="selectTipo" class="selectTextos">
					<option value="1">Ingreso</option>
					<option value="2">Egreso</option>
					<option value="3">Diario</option>
				</select>
			</td>
		
		
			<td class="etiquetas">Número:</td>
			<td>
				<input type="text" class="textos" id="txtNumero" name="txtNumero" maxlength="50" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Fecha:</td>
			<td>
				<input type="text" class="textosFechas" id="txtFechaConcepto" name="txtFechaConcepto" readonly="readonly" value="'.date('Y-m-d').'" />
			</td>
		
			<td class="etiquetas">Concepto:</td>
			<td>
				<input type="text" class="textos" id="txtConcepto" name="txtConcepto" maxlength="300" />
			</td>
		</tr>
	</table>
	
	<table class="tablaDatos">
		<tr>
			<th class="titulos" colspan="8">Transacción</th>	
		</tr>
		
		<tr>
			<th>Número de cuenta</th>
			<th>Concepto</th>
			<th>Debe</th>
			<th>Haber</th>
			<th>Moneda</th>
			<th>Tipo de cambio</th>
		</tr>
		
		<tr>
			<td>
				<select class="selectTextos" id="selectCuentasTransaccion" name="selectCuentasTransaccion">
					<option value="0">Seleccione</option>';
					
					$idCatalogo = 0;
					$c			=1;
					foreach($cuentas as $row)
					{
						$idCatalogo	= $c==1?$row->idCatalogo:$idCatalogo;
						
						if($idCatalogo!=$row->idCatalogo)
						{
							break;
						}
						
						echo'<option value="'.$row->idCuentaCatalogo.'">'.$row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')</option>';
						
						$c++;
					}
					
				echo'
				</select>	
			</td>
			
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion" name="txtConceptoTransaccion" placeholder="Concepto" /></td>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe" name="txtDebe"  value="0.00"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber" name="txtHaber"  value="0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda" name="txtMoneda" value="MXN" maxlength="4" /></td>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio" name="txtTipoCambio"  value="1.00" maxlength="15" onkeypress="return soloDecimales(event)" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
			
		</tr>
		
	</table>
	
	<table class="tablaDatos">
		<tr>
			<th class="titulos" colspan="7">Cheque</th>	
		</tr>
		<tr>
			<th>Número</th>
			<th>Banco</th>
			<th>Cuenta origen</th>
			<th>Fecha</th>
			<th>Monto</th>
			<th>Beneficiario</th>
			<th>RFC</th>
		</tr>
	
		<tr>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtNumeroCheque" name="txtNumeroCheque" placeholder="Número cheque" maxlength="20"  /></td>
			<td align="center">
				<select class="selectTextos" style="width:150px" id="selectBancos" name="selectBancos">';
					foreach($bancos as $row)
					{
						echo '<option value="'.$row->idBanco.'">('.$row->clave.')'.$row->nombre.'</option>';
					}
				echo'
				</select>
			</td>
			
			<td align="center">
				<input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaOrigenCheque" name="txtCuentaOrigenCheque" value="" placeholder="Cuenta origen" maxlength="50" />
			</td>
			
			<td align="center"><input type="text" class="textosFechas" id="txtFechaCheque" name="txtFechaCheque" value="'.date('Y-m-d').'" readonly="readonly"  /></td>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMonto" name="txtMonto" placeholder="$0.00" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" maxlength="15"  /></td>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiario" name="txtBeneficiario" placeholder="Beneficiario" maxlength="300" /></td>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfc" name="txtRfc" placeholder="RFC" maxlength="13"  />

				<script>
				$(document).ready(function()
				{
					$("#txtFechaCheque").datepicker();
				});
				</script>
			</td>
		</tr>
	</table>
		
	<table class="tablaDatos">
		<tr>
			<th class="titulos" colspan=6">Transferencia</th>	
		</tr>
		<tr>
			<th>Cuenta y banco origen</th>
			<th>Monto</th>
			<th>Cuenta y banco destino</th>
			<th>Fecha</th>
			<th>Beneficiario</th>
			<th>RFC</th>
		</tr>
	
		<tr>
			<td align="center">
				<input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaOrigen" name="txtCuentaOrigen" placeholder="Cuenta origen" maxlength="50" />
				<select class="selectTextos" style="width:150px; margin-top:2px" id="selectBancosOrigen" name="selectBancosOrigen">';
				foreach($bancos as $row)
				{
					echo '<option value="'.$row->idBanco.'">('.$row->clave.')'.$row->nombre.'</option>';
				}
				echo'
				</select>
			</td>
			
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMontoTransferencia" name="txtMontoTransferencia"  placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
			
			<td align="center">
				<input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaDestino" name="txtCuentaDestino" placeholder="Cuenta destino" maxlength="50" />
				
				<select class="selectTextos" style="width:150px; margin-top:2px" id="selectBancosDestino" name="selectBancosDestino">';
				foreach($bancos as $row)
				{
					echo '<option value="'.$row->idBanco.'">('.$row->clave.')'.$row->nombre.'</option>';
				}
				echo'
				</select>
			</td>
			
			<td align="center"><input type="text" class="textosFechas" id="txtFecha" name="txtFecha" value="'.date('Y-m-d').'" readonly="readonly"  /></td>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiarioTransferencia" name="txtBeneficiarioTransferencia" placeholder="Beneficiario" maxlength="300"  />
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfcTransferencia" name="txtRfcTransferencia"  placeholder="RFC" maxlength="13" />
			
				<script>
				$(document).ready(function()
				{
					$("#txtFecha").datepicker();
				});
				</script>
			</td>
		</tr>
	</table>
	
	<table class="tablaDatos" id="tablaComprobantes">
		<tr>
			<th class="titulos" colspan="3">Comprobante</th>
		</tr>
		<tr>
			<th>UUID</th>
			<th>Monto</th>
			<th>RFC</th>
		</tr>
		
		<tr>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosUuid" id="txtUuid" name="txtUuid" placeholder="UUID" maxlength="50" /></td>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMontoComprobante" name="txtMontoComprobante" placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
			<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfcComprobante" name="txtRfcComprobante" placeholder="RFC" maxlength="13" /></td>
		<tr>
	</table>
	
</form>';
