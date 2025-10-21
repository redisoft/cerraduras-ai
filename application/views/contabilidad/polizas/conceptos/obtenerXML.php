<script>
$(document).ready(function()
{
	$("#txtFechaConcepto").datepicker();
	
	$('#txtConcepto').val('<?php echo $xml[11].$xml[12].' | '.$xml[15].' | '.$xml[2]?>');
});
</script>
<?php

echo'
<table class="tablaDatos">
	<tr>
		<th class="titulos" colspan="8">Transacciones</th>	
	</tr>
	
	<tr>
		<th>Número de cuenta</th>
		<th>Concepto</th>
		<th>Debe</th>
		<th>Haber</th>
		<th>Moneda</th>
		<th>Tipo de cambio</th>
	</tr>';
	
	echo'
	<tr>
		<td>
			<select class="selectTextos" id="selectCuentasTransaccion" name="selectCuentasTransaccion">
				<option value="0">Seleccione</option>';
				
				$idCatalogo 	= 0;
				$c				= 1;
				$a 				= 0; //Comprobar si existe la cuenta mayor, sino tomara la subCuenta
				$seleccionado	= "";
				
				foreach($cuentas as $row)
				{
					$idCatalogo	= $c==1?$row->idCatalogo:$idCatalogo;
					
					if($idCatalogo!=$row->idCatalogo)
					{
						break;
					}
					
					if($tipoPoliza=='1')
					{
						if($cobrada==1)
						{
							if($row->codigoAgrupador=='102')
							{
								$seleccionado='selected="selected"';
								$a=1;
							}
							
							if($a==0)
							{
								if($row->codigoAgrupador=='102.01' or $row->codigoAgrupador=='102.02')
								{
									$seleccionado='selected="selected"';
								}
							}
						}
						
						if($cobrada==0)
						{
							if($row->codigoAgrupador=='105')
							{
								$seleccionado='selected="selected"';
								$a=1;
							}
							
							if($a==0)
							{
								if($row->codigoAgrupador=='105.01' or $row->codigoAgrupador=='105.02' or $row->codigoAgrupador=='105.03' or $row->codigoAgrupador=='105.04')
								{
									$seleccionado='selected="selected"';
								}
							}
						}
					}
					
					if($tipoPoliza=='2')
					{
						if($pagada==1)
						{
							if($row->codigoAgrupador=='201')
							{
								$seleccionado='selected="selected"';
								$a=1;
							}
							
							if($a==0)
							{
								if($row->codigoAgrupador=='201.01' or $row->codigoAgrupador=='201.02' or $row->codigoAgrupador=='201.03' or $row->codigoAgrupador=='201.04')
								{
									$seleccionado='selected="selected"';
								}
							}
						}
						
						if($pagada==0)
						{
							if($row->codigoAgrupador=='502')
							{
								$seleccionado='selected="selected"';
								$a=1;
							}
							
							if($a==0)
							{
								if($row->codigoAgrupador=='502.01' or $row->codigoAgrupador=='502.02' or $row->codigoAgrupador=='502.03' or $row->codigoAgrupador=='502.04')
								{
									$seleccionado='selected="selected"';
								}
							}
						}
					}
					
					echo'<option '.$seleccionado.' value="'.$row->idCuentaCatalogo.'">'.$row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')</option>';
					$seleccionado	= '';
					
					$c++;
				}
				
			echo'
			</select>	
		</td>
		
		<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion" name="txtConceptoTransaccion" placeholder="Concepto" value="'.('Factura: '.(isset($xml[11])?$xml[11]:'').(isset($xml[12])?$xml[12]:'').', Cliente: '.(isset($xml[25])?$xml[25]:'')).'" /></td>
		<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe" name="txtDebe"  value="'.((isset($xml[4])?$xml[4]:'0.00')).'"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>
		<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber" name="txtHaber"  value="0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>
		<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda" name="txtMoneda" value="MXN" maxlength="4" /></td>
		<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio" name="txtTipoCambio"  value="1.00" maxlength="15" onkeypress="return soloDecimales(event)" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
	</tr>';
	
	//AGREGAR OTRA PÓLIZA SI ES QUE EXISTIERA EL IVA
	if(isset($xml[36]))
	{
		if((float)$xml[36]>0)
		{
			echo'
			<tr>
				<td>
					<select class="selectTextos" id="selectCuentasTransaccion2" name="selectCuentasTransaccion2">
						<option value="0">Seleccione</option>';
						
						$idCatalogo 	= 0;
						$c				= 1;
						$a 				= 0; //Comprobar si existe la cuenta mayor, sino tomara la subCuenta
						$seleccionado	= "";
						
						foreach($cuentas as $row)
						{
							$idCatalogo	= $c==1?$row->idCatalogo:$idCatalogo;
							
							if($idCatalogo!=$row->idCatalogo)
							{
								break;
							}
							
							if($tipoPoliza=='1')
							{
								if($cobrada==1)
								{
									
									if($row->codigoAgrupador=='208.01')
									{
										$seleccionado='selected="selected"';
										$a=1;
									}
									
									
									if($a==0)
									{
										if($row->codigoAgrupador=='208')
										{
											$seleccionado='selected="selected"';
											
										}
									}
								}
								
								if($cobrada==0)
								{
									if($row->codigoAgrupador=='209.01')
									{
										$seleccionado='selected="selected"';
										$a=1;
									}
									
									
									if($a==0)
									{
										if($row->codigoAgrupador=='209')
										{
											$seleccionado='selected="selected"';
											
										}
									}
								}
							}
							
							if($tipoPoliza=='2')
							{
								if($pagada==1)
								{
									if($row->codigoAgrupador=='118.01')
									{
										$seleccionado='selected="selected"';
										$a=1;
									}
									
									/*if($a==0)
									{
										if($row->codigoAgrupador=='201.01' or $row->codigoAgrupador=='201.02' or $row->codigoAgrupador=='201.03' or $row->codigoAgrupador=='201.04')
										{
											$seleccionado='selected="selected"';
										}
									}*/
								}
								
								if($pagada==0)
								{
									if($row->codigoAgrupador=='119.01')
									{
										$seleccionado='selected="selected"';
										$a=1;
									}
									
									/*if($a==0)
									{
										if($row->codigoAgrupador=='502.01' or $row->codigoAgrupador=='502.02' or $row->codigoAgrupador=='502.03' or $row->codigoAgrupador=='502.04')
										{
											$seleccionado='selected="selected"';
										}
									}*/
								}
							}
							
							echo'<option '.$seleccionado.' value="'.$row->idCuentaCatalogo.'">'.$row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')</option>';
							$seleccionado	= '';
							
							$c++;
						}
						
					echo'
					</select>	
				</td>
				
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion2" name="txtConceptoTransaccion2" placeholder="Concepto" value="IVA" /></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe2" name="txtDebe2"  value="'.((isset($xml[36])?$xml[36]:'0.00')).'"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber2" name="txtHaber2"  value="0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda2" name="txtMoneda2" value="MXN" maxlength="4" /></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio2" name="txtTipoCambio2"  value="1.00" maxlength="15" onkeypress="return soloDecimales(event)" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
			</tr>';
		}
	}

echo'
</table>';

$i=0;
if(isset($xml[34]))
{
	#var_dump($xml[34]);
	echo '
	<table class="tablaDatos" id="tablaConceptos">
		<tr>
			<th class="titulos" colspan="6">Conceptos del CFDI</th>
		</tr>
		<tr>
			<th>Cantidad</th>
			<th>Unidad</th>
			<th>Código</th>
			<th>Descripción</th>
			<th>Precio Unitario</th>
			<th>Importe</th>
		</tr>';
	
	foreach($xml[34] as $row)
	{
		echo '
		<tr>
			<td align="center">'.$row[0].'</td>
			<td>'.$row[1].'</td>
			<td>'.$row[5].'</td>
			<td>'.$row[2].'</td>
			<td align="right">$'.number_format((float)$row[3],2).'</td>
			<td align="right">$'.number_format((float)$row[4],2).'</td>
			
			<input type="hidden" id="txtCantidad'.$i.'" name="txtCantidad'.$i.'" value="'.((string)$row[0]).'" />
			<input type="hidden" id="txtUnidad'.$i.'" name="txtUnidad'.$i.'" value="'.((string)$row[1]).'" />
			<input type="hidden" id="txtCodigo'.$i.'" name="txtCodigo'.$i.'" value="'.((string)$row[5]).'" />
			<input type="hidden" id="txtDescripcion'.$i.'" name="txtDescripcion'.$i.'" value="'.((string)$row[2]).'" />
			<input type="hidden" id="txtPrecioUnitario'.$i.'" name="txtPrecioUnitario'.$i.'" value="'.((float)$row[3]).'" />
			<input type="hidden" id="txtImporte'.$i.'" name="txtImporte'.$i.'" value="'.((float)$row[4]).'" />
			
		</tr>';
		
		$i++;
	}

	echo '
		<input type="hidden" id="txtNumeroProductos" name="txtNumeroProductos" value="'.$i.'" />
	</table>';
}

if(isset($xml[10]))
{
	if($xml[10]=='cheque')
	{
		echo'
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
				<td align="center"><input value="'.(isset($xml[4])?$xml[4]:'').'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMonto" name="txtMonto" placeholder="$0.00" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" maxlength="15"  /></td>
				<td align="center"><input value="'.(isset($xml[16])?$xml[16]:'').'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiario" name="txtBeneficiario" placeholder="Beneficiario" maxlength="300" /></td>
				<td align="center"><input value="'.(isset($xml[15])?$xml[15]:'').'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfc" name="txtRfc" placeholder="RFC" maxlength="13"  />
		
					<script>
					$(document).ready(function()
					{
						$("#txtFechaCheque").datepicker();
					});
					</script>
				</td>
			</tr>
		</table>';
	}
}

if(isset($xml[10]))
{
	if($xml[10]=='transferencia')
	{
	
		echo'
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
				
				<td align="center"><input value="'.(isset($xml[4])?$xml[4]:'').'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMontoTransferencia" name="txtMontoTransferencia"  placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
				
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
				<td align="center"><input value="'.(isset($xml[16])?$xml[16]:'').'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiarioTransferencia" name="txtBeneficiarioTransferencia" placeholder="Beneficiario" maxlength="300"  />
				<td align="center"><input value="'.(isset($xml[15])?$xml[15]:'').'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfcTransferencia" name="txtRfcTransferencia"  placeholder="RFC" maxlength="13" />
				
					<script>
					$(document).ready(function()
					{
						$("#txtFecha").datepicker();
					});
					</script>
				</td>
			</tr>
		</table>';
	}
}

echo'
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
		<td align="center"><input value="'.(isset($xml[40])?$xml[40]:'').'" onclick="seleccionarTexto(this)" type="text" class="textosUuid" id="txtUuid" name="txtUuid" placeholder="UUID" maxlength="50" /></td>
		<td align="center"><input value="'.(isset($xml[4])?$xml[4]:'').'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMontoComprobante" name="txtMontoComprobante" placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
		<td align="center"><input value="'.(isset($xml[15])?$xml[15]:'').'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfcComprobante" name="txtRfcComprobante" placeholder="RFC" maxlength="13" /></td>
	<tr>
</table>';

