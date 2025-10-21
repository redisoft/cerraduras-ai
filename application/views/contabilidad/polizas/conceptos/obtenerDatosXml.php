<script>
$(document).ready(function()
{
	$("#txtFechaConcepto").datepicker();
	
	//$('#txtConcepto').val('<?php echo $xml[11].$xml[12].' | '.$xml[15].' | '.$xml[2]?>');
	
	seleccionado='';
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//AGREGAR LA PRIMERA CUENTA A LA TRANSACCIÓN
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	c = p; //EN TEORIA CADA PÓLIZA TIENE UN CHEQUE O UNA TRANSFERENCIA
	
	data='<tr id="filaTransaccion'+g+'">';
	data+='<td class="numeral">'+(g+1)+' <img src="'+base_url+'img/borrar.png" onclick="borrarTransaccionCargada('+g+')" title="Quitar transacción" /></td>';
	data+='<td class="letraChica">';
	data+='<input type="hidden" id="txtGrupo'+p+'" name="txtGrupo'+p+'" value="'+g+'" />';
	data+='<select class="selectTextos" id="selectCuentasTransaccion'+p+'" name="selectCuentasTransaccion'+p+'" style="width:200px">';
	data+='<option value="0">Seleccione</option>';
	
	<?php
	
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
					?>
					seleccionado='selected="selected"';
					<?php
					$a=1;
				}
				
				if($a==0)
				{
					if($row->codigoAgrupador=='102.01' or $row->codigoAgrupador=='102.02')
					{
						?>
						seleccionado='selected="selected"';
						<?php
					}
				}
			}
			
			if($cobrada==0)
			{
				if($row->codigoAgrupador=='105')
				{
					?>
					seleccionado='selected="selected"';
					<?php
					$a=1;
				}
				
				if($a==0)
				{
					if($row->codigoAgrupador=='105.01' or $row->codigoAgrupador=='105.02' or $row->codigoAgrupador=='105.03' or $row->codigoAgrupador=='105.04')
					{
						?>
						seleccionado='selected="selected"';
						<?php
					}
				}
			}
		}
		
		if($tipoPoliza=='2')
		{
			if($pagada==1)
			{
				/*if($row->codigoAgrupador=='201')
				{
					?>
					seleccionado='selected="selected"';
					<?php
					$a=1;
				}
				
				if($a==0)
				{
					if($row->codigoAgrupador=='201.01' or $row->codigoAgrupador=='201.02' or $row->codigoAgrupador=='201.03' or $row->codigoAgrupador=='201.04')
					{
						?>
						seleccionado='selected="selected"';
						<?php
					}
				}*/
				
				if($row->codigoAgrupador=='102')
				{
					?>
					seleccionado='selected="selected"';
					<?php
					$a=1;
				}
				
				if($a==0)
				{
					if($row->codigoAgrupador=='102.01' or $row->codigoAgrupador=='102.02')
					{
						?>
						seleccionado='selected="selected"';
						<?php
					}
				}
			}
			
			if($pagada==0)
			{
				/*if($row->codigoAgrupador=='502')
				{
					?>
					seleccionado='selected="selected"';
					<?php
					$a=1;
				}
				
				if($a==0)
				{
					if($row->codigoAgrupador=='502.01' or $row->codigoAgrupador=='502.02' or $row->codigoAgrupador=='502.03' or $row->codigoAgrupador=='502.04')
					{
						?>
						seleccionado='selected="selected"';
						<?php
					}
				}*/
				
				if($row->codigoAgrupador=='201')
				{
					?>
					seleccionado='selected="selected"';
					<?php
					$a=1;
				}
				
				if($a==0)
				{
					if($row->codigoAgrupador=='201.01' or $row->codigoAgrupador=='201.02')
					{
						?>
						seleccionado='selected="selected"';
						<?php
					}
				}
			}
		}
		?>
		
		data+='<option '+seleccionado+' value="<?php echo $row->idCuentaCatalogo?>"><?php echo $row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')'?></option>';
		seleccionado='';
		<?php
		
		/*echo'<option '.$seleccionado.' value="'.$row->idCuentaCatalogo.'">'.$row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')</option>';
		$seleccionado	= '';*/
		
		$c++;
	}
	?>
	
	data+='</td>';
	//data+='<td class="letraChica"></td>';

	data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion'+p+'" name="txtConceptoTransaccion'+p+'" placeholder="Concepto" value="<?php echo(''.(isset($xml[24])?$xml[24]:'').', '.(isset($xml[25])?$xml[25]:''))?>" /></td>';
	
	//CARGAR TODOS LOS CONCEPTOS
	data+='<td class="letraChica">';
	<?php
	if(isset($xml[34]))
	{
		$p=0;
		foreach($xml[34] as $row)
		{
			?>
			
			data+='<?php echo $p==0?'':'<br /><br />'?>Cantidad: <?php echo $row[0]?>';
			data+='<br />Descripción: <?php echo $row[2]?>';
			data+='<br />Importe: $<?php echo $row[4]?>';
			
			
			data+='<input type="hidden" id="txtCantidad'+p+'_<?php echo $p?>" name="txtCantidad'+p+'_<?php echo $p?>" value="<?php echo ((string)$row[0])?>" />';
			data+='<input type="hidden" id="txtUnidad'+p+'_<?php echo $p?>" name="txtUnidad'+p+'_<?php echo $p?>" value="<?php echo ((string)$row[1])?>" />';
			data+='<input type="hidden" id="txtCodigo'+p+'_<?php echo $p?>" name="txtCodigo'+p+'_<?php echo $p?>" value="<?php echo ((string)$row[5])?>" />';
			data+='<input type="hidden" id="txtDescripcion'+p+'_<?php echo $p?>" name="txtDescripcion'+p+'_<?php echo $p?>" value="<?php echo ((string)$row[2])?>" />';
			data+='<input type="hidden" id="txtPrecioUnitario'+p+'_<?php echo $p?>" name="txtPrecioUnitario'+p+'_<?php echo $p?>" value="<?php echo ((float)$row[3])?>" />';
			data+='<input type="hidden" id="txtImporte'+p+'_<?php echo $p?>" name="txtImporte'+p+'_<?php echo $p?>" value="<?php echo ((float)$row[4])?>" />';
			<?php
			
			$p++;
		}
	}
	?>
	data+='<input type="hidden" id="txtNumeroProductos'+p+'" name="txtNumeroProductos'+p+'" value="<?php echo $p?>" />';
	data+='</td>';
	
	data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'+p+'" name="txtDebe'+p+'"  value="<?php if($tipoPoliza!=2) {echo ((isset($xml[4])?$xml[4]:'0.00'));} else {echo '0.00';}?>"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
	data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'+p+'" name="txtHaber'+p+'"  value="<?php if($tipoPoliza==2) echo ((isset($xml[4])?$xml[4]:'0.00')); else echo '0.00';?>" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
	
	//CARGAR EL COMPROBANTE
	data+='<td class="letraChica">';
	data+='<input style="width:100px" value="<?php echo (isset($xml[40])?$xml[40]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textos" id="txtUuid'+p+'" name="txtUuid'+p+'" placeholder="UUID" maxlength="50" />';
	data+='<input style="display:none" value="<?php echo (isset($xml[4])?$xml[4]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMontoComprobante'+p+'" name="txtMontoComprobante'+p+'" placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" />';
	data+='<input style="display:none" value="<?php echo (isset($xml[24])?$xml[24]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfcComprobante'+p+'" name="txtRfcComprobante'+p+'" placeholder="RFC" maxlength="13" />';
	data+='</td>';
	
	data+='<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda'+p+'" name="txtMoneda'+p+'" value="MXN" maxlength="4" /></td>';
	data+='<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio'+p+'" name="txtTipoCambio'+p+'"  value="1.00" maxlength="15" onkeypress="return soloDecimales(event)" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>';
	data+='</tr>';
	
	$('#tablaTransacciones').append(data);
	
	$("#tablaTransacciones tr:even").addClass("abajo");
	$("#tablaTransacciones tr:odd").addClass("arriba");  

	p++;
	
	<?php
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//AGREGAR OTRA EN CASO DE HABER ALGUN DESCUENTO
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	if(isset($xml[44]))
	{
		if((float)$xml[44]>0)
		{
			?>
			
			data='<tr id="filaDescuento'+g+'">';
			data+='<td></td>';
			data+='<td class="letraChica">';
			data+='<input type="hidden" id="txtGrupo'+p+'" name="txtGrupo'+p+'" value="'+g+'" />';
			data+='<select class="selectTextos" id="selectCuentasTransaccion'+p+'" name="selectCuentasTransaccion'+p+'" style="width:200px">';
			data+='<option value="0">Seleccione</option>';
			
			<?php
			
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
						
						if($row->codigoAgrupador=='402.01')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}
						
						
						/*if($a==0)
						{
							if($row->codigoAgrupador=='401')
							{
								?>
								seleccionado='selected="selected"';
								<?php
							}
						}*/
					}
					
					if($cobrada=='011')
					{
						if($row->codigoAgrupador=='401.03')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}

					}
				}
				
				if($tipoPoliza=='2')
				{
					if($pagada==1)
					{
						if($row->codigoAgrupador=='503.01')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}
					}
					
					if($pagada==0)
					{
						if($row->codigoAgrupador=='502.0111')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}
					}
				}
				?>
				
				data+='<option '+seleccionado+' value="<?php echo $row->idCuentaCatalogo?>"><?php echo $row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')'?></option>';
				seleccionado='';
				<?php
				
				/*echo'<option '.$seleccionado.' value="'.$row->idCuentaCatalogo.'">'.$row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')</option>';
				$seleccionado	= '';*/
				
				$c++;
			}
			?>
			
			data+='</td>';
			//data+='<td class="letraChica"></td>';
		
			//data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion'+p+'" name="txtConceptoTransaccion'+p+'" placeholder="Concepto" value="<?php echo $tipoPoliza!=2?'Ventas y/o servicios':'Compras'?>" /></td>';
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion'+p+'" name="txtConceptoTransaccion'+p+'" placeholder="Concepto" value="<?php echo(''.(isset($xml[24])?$xml[24]:'').', '.(isset($xml[25])?$xml[25]:''))?>" /></td>';
			
			//LA PARTE DE DESCUENTOS NO LLEVARA CONCEPTOS
			data+='<td class="letraChica">Descuentos';
			data+='<input type="hidden" id="txtNumeroProductos'+p+'" name="txtNumeroProductos'+p+'" value="0" />';
			data+='</td>';
			
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'+p+'" name="txtDebe'+p+'"    value="<?php if($tipoPoliza==1) echo ((isset($xml[44])?$xml[44]:'0.00')); else echo '0.00';?>"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'+p+'" name="txtHaber'+p+'"  value="<?php if($tipoPoliza!=1) echo ((isset($xml[44])?$xml[44]:'0.00')); else echo '0.00';?>" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
			
			//NO HABRA COMPROBANTE EN LA PARTE DE DESCUENTOS
			data+='<td class="letraChica">';
			data+='</td class="letraChica">';
			
			data+='<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda'+p+'" name="txtMoneda'+p+'" value="<?php echo $xml[14]?>" maxlength="4" /></td>';
			data+='<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio'+p+'" name="txtTipoCambio'+p+'"  value="<?php echo $xml[13]?>" maxlength="15" onkeypress="return soloDecimales(event)" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>';
			data+='</tr>';
			
			$('#tablaTransacciones').append(data);
			
			$("#tablaTransacciones tr:even").addClass("abajo");
			$("#tablaTransacciones tr:odd").addClass("arriba");  
			
			p++;
			
			 <?php
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//AGREGAR OTRA EN CASO DE HABER RETENCIÓN DEL ISR O IVA
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	if(isset($xml[46]))
	{
		if((float)$xml[45]>0)
		{
			?>
			
			data='<tr id="filaRetencionIva'+g+'">';
			data+='<td></td>';
			data+='<td class="letraChica">';
			data+='<input type="hidden" id="txtGrupo'+p+'" name="txtGrupo'+p+'" value="'+g+'" />';
			data+='<select class="selectTextos" id="selectCuentasTransaccion'+p+'" name="selectCuentasTransaccion'+p+'" style="width:200px">';
			data+='<option value="0">Seleccione</option>';
			
			<?php
			
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
					if($cobrada==111)
					{
						
						if($row->codigoAgrupador=='402.01')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}
					}
					
					if($cobrada=='0')
					{
						if($row->codigoAgrupador=='216.10')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}

					}
				}
				
				if($tipoPoliza=='2')
				{
					if($pagada==11)
					{
						if($row->codigoAgrupador=='502.01')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}
					}
					
					if($pagada==10)
					{
						if($row->codigoAgrupador=='502.01')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}
					}
				}
				?>
				
				data+='<option '+seleccionado+' value="<?php echo $row->idCuentaCatalogo?>"><?php echo $row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')'?></option>';
				seleccionado='';
				<?php
				
				/*echo'<option '.$seleccionado.' value="'.$row->idCuentaCatalogo.'">'.$row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')</option>';
				$seleccionado	= '';*/
				
				$c++;
			}
			?>
			
			data+='</td>';
			//data+='<td class="letraChica"></td>';
		
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion'+p+'" name="txtConceptoTransaccion'+p+'" placeholder="Concepto" value="Retención" /></td>';
			
			//LA PARTE DE RETENCIÓNES NO LLEVARA CONCEPTOS
			data+='<td class="letraChica">';
			data+='<input type="hidden" id="txtNumeroProductos'+p+'" name="txtNumeroProductos'+p+'" value="0" />';
			data+='</td>';
			
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'+p+'" name="txtDebe'+p+'"    value="<?php if($tipoPoliza==1) echo ((isset($xml[45])?$xml[45]:'0.00')); else echo '0.00';?>"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'+p+'" name="txtHaber'+p+'"  value="<?php if($tipoPoliza!=1) echo ((isset($xml[45])?$xml[45]:'0.00')); else echo '0.00';?>" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
			
			//NO HABRA CONCEPTOS EN LA PARTE DE RETENCIONES
			data+='<td class="letraChica">';
			data+='</td class="letraChica">';
			
			data+='<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda'+p+'" name="txtMoneda'+p+'" value="<?php echo $xml[14]?>" maxlength="4" /></td>';
			data+='<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio'+p+'" name="txtTipoCambio'+p+'"  value="<?php echo $xml[13]?>" maxlength="15" onkeypress="return soloDecimales(event)" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>';
			data+='</tr>';
			
			$('#tablaTransacciones').append(data);
			
			$("#tablaTransacciones tr:even").addClass("abajo");
			$("#tablaTransacciones tr:odd").addClass("arriba");  
			
			p++;
			
			 <?php
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//AGREGAR OTRA PÓLIZA QUER SERIA PARA VENTAS O COMPRAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	if(isset($xml[5]))
	{
		if((float)$xml[5]>0)
		{
			?>
			
			data='<tr id="filaVenta'+g+'">';
			data+='<td></td>';
			data+='<td class="letraChica">';
			data+='<input type="hidden" id="txtGrupo'+p+'" name="txtGrupo'+p+'" value="'+g+'" />';
			data+='<select class="selectTextos" id="selectCuentasTransaccion'+p+'" name="selectCuentasTransaccion'+p+'" style="width:200px">';
			data+='<option value="0">Seleccione</option>';
			
			<?php
			
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
						
						if($row->codigoAgrupador=='401.01')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}
						
						
						if($a==0)
						{
							if($row->codigoAgrupador=='401')
							{
								?>
								seleccionado='selected="selected"';
								<?php
							}
						}
					}
					
					if($cobrada==0)
					{
						if($row->codigoAgrupador=='401.03')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}
						
						
						/*if($a==0)
						{
							if($row->codigoAgrupador=='209')
							{
								?>
								seleccionado='selected="selected"';
								<?php
							}
						}*/
					}
				}
				
				if($tipoPoliza=='2')
				{
					if($pagada==1)
					{
						if($row->codigoAgrupador=='502.01')
						{
							?>
							seleccionado='selected="selected"';
							<?php
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
						if($row->codigoAgrupador=='502.01')
						{
							?>
							seleccionado='selected="selected"';
							<?php
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
				?>
				
				data+='<option '+seleccionado+' value="<?php echo $row->idCuentaCatalogo?>"><?php echo $row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')'?></option>';
				seleccionado='';
				<?php
				
				/*echo'<option '.$seleccionado.' value="'.$row->idCuentaCatalogo.'">'.$row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')</option>';
				$seleccionado	= '';*/
				
				$c++;
			}
			?>
			
			data+='</td>';
			//data+='<td class="letraChica"></td>';
		
			//data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion'+p+'" name="txtConceptoTransaccion'+p+'" placeholder="Concepto" value="<?php echo $tipoPoliza!=2?'Ventas y/o servicios':'Compras'?>" /></td>';
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion'+p+'" name="txtConceptoTransaccion'+p+'" placeholder="Concepto" value="<?php echo(''.(isset($xml[24])?$xml[24]:'').', '.(isset($xml[25])?$xml[25]:''))?>" /></td>';
			
			//LA PARTE DE VENTAS NO LLEVARA CONCEPTOS
			data+='<td class="letraChica">';
			data+='<input type="hidden" id="txtNumeroProductos'+p+'" name="txtNumeroProductos'+p+'" value="0" />';
			data+='</td>';
			
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'+p+'" name="txtDebe'+p+'"    value="<?php if($tipoPoliza==2) echo ((isset($xml[5])?$xml[5]:'0.00')); else echo '0.00';?>"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'+p+'" name="txtHaber'+p+'"  value="<?php if($tipoPoliza!=2) echo ((isset($xml[5])?$xml[5]:'0.00')); else echo '0.00';?>" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
			
			//NO HABRA COMPROBANTE EN LA PARTE DE VENTAS
			data+='<td class="letraChica">';
			data+='</td class="letraChica">';
			
			data+='<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda'+p+'" name="txtMoneda'+p+'" value="MXN" maxlength="4" /></td>';
			data+='<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio'+p+'" name="txtTipoCambio'+p+'"  value="1.00" maxlength="15" onkeypress="return soloDecimales(event)" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>';
			data+='</tr>';
			
			$('#tablaTransacciones').append(data);
			
			$("#tablaTransacciones tr:even").addClass("abajo");
			$("#tablaTransacciones tr:odd").addClass("arriba");  
			
			p++;
			
			 <?php
		}
	}
	
	 
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//AGREGAR OTRA PÓLIZA SI ES QUE EXISTIERA EL IVA
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	if(isset($xml[36]))
	{
		if((float)$xml[36]>0)
		{
			?>
			
			data='<tr id="filaIva'+g+'">';
			data+='<td></td>';
			data+='<td class="letraChica">';
			data+='<input type="hidden" id="txtGrupo'+p+'" name="txtGrupo'+p+'" value="'+g+'" />';
			data+='<select class="selectTextos" id="selectCuentasTransaccion'+p+'" name="selectCuentasTransaccion'+p+'" style="width:200px">';
			data+='<option value="0">Seleccione</option>';
			
			<?php
			
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
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}
						
						
						if($a==0)
						{
							if($row->codigoAgrupador=='208')
							{
								?>
								seleccionado='selected="selected"';
								<?php
							}
						}
					}
					
					if($cobrada==0)
					{
						if($row->codigoAgrupador=='209.01')
						{
							?>
							seleccionado='selected="selected"';
							<?php
							$a=1;
						}
						
						
						if($a==0)
						{
							if($row->codigoAgrupador=='209')
							{
								?>
								seleccionado='selected="selected"';
								<?php
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
							?>
							seleccionado='selected="selected"';
							<?php
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
							?>
							seleccionado='selected="selected"';
							<?php
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
				?>
				
				data+='<option '+seleccionado+' value="<?php echo $row->idCuentaCatalogo?>"><?php echo $row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')'?></option>';
				seleccionado='';
				<?php
				
				/*echo'<option '.$seleccionado.' value="'.$row->idCuentaCatalogo.'">'.$row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')</option>';
				$seleccionado	= '';*/
				
				$c++;
			}
			?>
			
			data+='</td>';
			//data+='<td class="letraChica"></td>';
		
			//data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion'+p+'" name="txtConceptoTransaccion'+p+'" placeholder="Concepto" value="IVA" /></td>';
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion'+p+'" name="txtConceptoTransaccion'+p+'" placeholder="Concepto" value="<?php echo(''.(isset($xml[24])?$xml[24]:'').', '.(isset($xml[25])?$xml[25]:''))?>" /></td>';
			
			//LA PARTE DEL IVA NO LLEVARA CONCEPTOS
			data+='<td class="letraChica">';
			data+='<input type="hidden" id="txtNumeroProductos'+p+'" name="txtNumeroProductos'+p+'" value="0" />';
			data+='</td>';
			
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'+p+'" name="txtDebe'+p+'"    value="<?php if($tipoPoliza==2) echo ((isset($xml[36])?$xml[36]:'0.00')); else echo '0.00';?>"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
			data+='<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'+p+'" name="txtHaber'+p+'"  value="<?php if($tipoPoliza!=2) echo ((isset($xml[36])?$xml[36]:'0.00')); else echo '0.00';?>" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
			
			//NO HABRA COMPROBANTE EN LA PARTE DEL IVA
			data+='<td class="letraChica">';
			data+='</td class="letraChica">';
			
			data+='<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda'+p+'" name="txtMoneda'+p+'" value="MXN" maxlength="4" /></td>';
			data+='<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio'+p+'" name="txtTipoCambio'+p+'"  value="1.00" maxlength="15" onkeypress="return soloDecimales(event)" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>';
			data+='</tr>';
			
			$('#tablaTransacciones').append(data);
			
			$("#tablaTransacciones tr:even").addClass("abajo");
			$("#tablaTransacciones tr:odd").addClass("arriba");  
			
			p++;
			
			
			<?php
		}
	}
	
	?>
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//AGREGAR LA SUMATORIA DE LAS PÓLIZAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	data='<tr id="filaSuma'+g+'">';
	data+='<td class="letraChica" colspan="2" align="right">SUMA</td>';
	/*data+='<td class="letraChica" colspan="3" align="right"><?php echo isset($xml[4])?$xml[4]:'0.00'?></td>';
	data+='<td class="letraChica" align="right"><?php echo isset($xml[4])?$xml[4]:'0.00'?></td>';*/
	
	data+='<td class="letraChica" colspan="3" align="right"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebeGrupo'+g+'" name="txtDebeGrupo'+g+'"    value="<?php echo isset($xml[4])?$xml[4]:'0.00'?>"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
	data+='<td class="letraChica" align="right"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaberGrupo'+g+'" name="txtHaberGrupo'+g+'"    		   value="<?php echo isset($xml[4])?$xml[4]:'0.00'?>"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
	
	data+='<td class="letraChica" align="right"></td>';
	data+='<input type="hidden" id="txtIdGrupo'+g+'" name="txtIdGrupo'+g+'" value="'+g+'" />';
	//data+='<input type="hidden" id="txtDebeGrupo'+g+'" name="txtDebeGrupo'+g+'" value="<?php echo isset($xml[4])?$xml[4]:'0.00'?>" />';
	//data+='<input type="hidden" id="txtHaberGrupo'+g+'" name="txtHaberGrupo'+g+'" value="<?php echo isset($xml[4])?$xml[4]:'0.00'?>" />';
	data+='</tr>';
	
	$('#tablaTransacciones').append(data);
			
	//$("#tablaTransacciones tr:even").addClass("abajo");
	//$("#tablaTransacciones tr:odd").addClass("arriba");  
	
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//OTROS MÉTODOS DE PAGO
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	<?php
	
	if(isset($xml[10]))
	{
		if($xml[10]!='cheque' and $xml[10]!='transferencia')
		{
			?>
			
			data='<tr id="filaMetodoPago'+g+'">';
			data+='<td class="numeral">'+(g+1)+'</td>';
			
			data+='<td align="center">';
			data+='<select class="selectTextos" style="width:150px" id="selectMetodos'+c+'" name="selectMetodos'+c+'">';
			
			<?php
			foreach($metodos as $row)
			{
				?>
				data+='<?php echo '<option '.($row->idMetodo==$xml[10]?'selected="selected"':'').' value="'.$row->idMetodo.'">('.$row->clave.')'.quitarSaltosEspacio($row->concepto).'</option>'; ?>';
				<?php
			}
			?>
			
			data+='</select>';
			data+='</td>';
			
			data+='<td align="center"><input type="text" class="textosFechas" id="txtFechaMetodo'+c+'" name="txtFechaMetodo'+c+'" value="<?php echo date('Y-m-d')?>" readonly="readonly"  /></td>';
			data+='<td align="center"><input value="<?php echo (isset($xml[16])?$xml[16]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiarioMetodo'+c+'" name="txtBeneficiarioMetodo'+c+'" placeholder="Beneficiario" maxlength="300" /></td>';
			data+='<td align="center"><input value="<?php echo (isset($xml[15])?$xml[15]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfcMetodo'+c+'" name="txtRfcMetodo'+c+'" placeholder="RFC" maxlength="13"  /></td>';
			data+='<td align="center"><input value="<?php echo (isset($xml[4])?$xml[4]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMontoMetodo'+c+'" name="txtMontoMetodo'+c+'" placeholder="$0.00" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" maxlength="15"  /></td>';
			
			data+='<td align="center"><select class="selectTextos" id="selectMonedaMetodo'+c+'" name="selectMonedaMetodo'+c+'" style="width:170px" >';
			data+='<option value="">Seleccione</option>';
			<?php
			foreach($monedas as $row)
			{
				?>
				data+='<?php echo '<option '.($xml[14]==$row->codigo?'selected="selected"':'').' value="'.$row->codigo.'">('.$row->codigo.')'.quitarSaltosEspacio($row->nombre).'</option>'; ?>';
				<?php
			}
			?>
			
			data+='</select></td>';
			
			data+='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtTipoCambioMetodo'+c+'" name="txtTipoCambioMetodo'+c+'" value="<?php echo $xml[13]?>" placeholder="Tipo de cambio" maxlength="10" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>';
			data+='</tr>';
			
			$('#tablaMetodos').append(data);
			$('#tablaMetodos').fadeIn();
			
			$("#tablaMetodos tr:even").addClass("abajo");
			$("#tablaMetodos tr:odd").addClass("arriba");  
			
			$(document).ready(function()
			{
				$("#txtFechaMetodo"+c).datepicker();
			});
			
			
			<?php
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//CHEQUES
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	if(isset($xml[10]))
	{
		if($xml[10]=='cheque')
		{
			?>
			
			data='<tr id="filaCheque'+g+'">';
			data+='<td class="numeral">'+(g+1)+'</td>';
			data+='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtNumeroCheque'+c+'" name="txtNumeroCheque'+c+'" placeholder="Número cheque" maxlength="20"  /></td>';
			data+='<td align="center">';
			data+='<select class="selectTextos" style="width:150px" id="selectBancos'+c+'" name="selectBancos'+c+'">';
			
			<?php
			foreach($bancos as $row)
			{
				?>
				data+='<?php echo '<option value="'.$row->idBanco.'">('.$row->clave.')'.quitarSaltosEspacio($row->nombre).'</option>'; ?>';
				<?php
			}
			?>
			
			data+='</select>';
			data+='</td>';
			
			data+='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaOrigenCheque'+c+'" name="txtCuentaOrigenCheque'+c+'" value="" placeholder="Cuenta origen" maxlength="50" /></td>';
			
			data+='<td align="center"><input type="text" class="textosFechas" id="txtFechaCheque'+c+'" name="txtFechaCheque'+c+'" value="<?php echo date('Y-m-d')?>" readonly="readonly"  /></td>';
			data+='<td align="center"><input value="<?php echo (isset($xml[4])?$xml[4]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMonto'+c+'" name="txtMonto'+c+'" placeholder="$0.00" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" maxlength="15"  /></td>';
			data+='<td align="center"><input value="<?php echo (isset($xml[16])?$xml[16]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiario'+c+'" name="txtBeneficiario'+c+'" placeholder="Beneficiario" maxlength="300" /></td>';
			data+='<td align="center"><input value="<?php echo (isset($xml[15])?$xml[15]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfc'+c+'" name="txtRfc'+c+'" placeholder="RFC" maxlength="13"  />';

			data+='</td></tr>';
			
			$('#tablaCheques').append(data);
			$('#tablaCheques').fadeIn();
			
			$("#tablaCheques tr:even").addClass("abajo");
			$("#tablaCheques tr:odd").addClass("arriba");  
			
			$(document).ready(function()
			{
				$("#txtFechaCheque"+c).datepicker();
			});
			
			
			<?php
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//TRANSFERENCIAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	if(isset($xml[10]))
	{
		if($xml[10]=='transferencia')
		{
			?>
			
			data='<tr id="filaTransferencia'+g+'">';
			data+='<td class="numeral">'+(g+1)+'</td>';
			
			//CUENTA DE ORIGEN
			data+='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaOrigen'+c+'" name="txtCuentaOrigen'+c+'" placeholder="Cuenta origen" maxlength="50" />';
			data+='<br /><select class="selectTextos" style="width:150px; margin-top:2px"  id="selectBancosOrigen'+c+'" name="selectBancosOrigen'+c+'">';
			
			<?php
			foreach($bancos as $row)
			{
				?>
				data+='<?php echo '<option value="'.$row->idBanco.'">('.$row->clave.')'.quitarSaltosEspacio($row->nombre).'</option>'; ?>';
				<?php
			}
			?>
			
			data+='</select>';
			data+='</td>';
			
			data+='<td align="center"><input value="<?php echo (isset($xml[4])?$xml[4]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMontoTransferencia'+c+'" name="txtMontoTransferencia'+c+'"  placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>';
			
			//CUENTA DE DESTINO
			data+='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaDestino'+c+'" name="txtCuentaDestino'+c+'" placeholder="Cuenta destino" maxlength="50" />';
			data+='<br /><select class="selectTextos" style="width:150px; margin-top:2px"  id="selectBancosDestino'+c+'" name="selectBancosDestino'+c+'">';
			
			<?php
			foreach($bancos as $row)
			{
				?>
				data+='<?php echo '<option value="'.$row->idBanco.'">('.$row->clave.')'.quitarSaltosEspacio($row->nombre).'</option>'; ?>';
				<?php
			}
			?>
			
			data+='</select>';
			data+='</td>';
			
			data+='<td align="center"><input type="text" class="textosFechas" id="txtFecha'+c+'" name="txtFecha'+c+'" value="<?php echo date('Y-m-d')?>" readonly="readonly"  /></td>';
			data+='<td align="center"><input value="<?php echo (isset($xml[16])?$xml[16]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiarioTransferencia'+c+'" name="txtBeneficiarioTransferencia'+c+'" placeholder="Beneficiario" maxlength="300"  />';
			data+='<td align="center"><input value="<?php echo (isset($xml[15])?$xml[15]:'')?>" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfcTransferencia'+c+'" name="txtRfcTransferencia'+c+'"  placeholder="RFC" maxlength="13" />';
			
			
			data+='</td></tr>';
			
			$('#tablaTransferencias').append(data);
			$('#tablaTransferencias').fadeIn();
			
			$("#tablaTransferencias tr:even").addClass("abajo");
			$("#tablaTransferencias tr:odd").addClass("arriba");  
			
			$(document).ready(function()
			{
				$("#txtFecha"+c).datepicker();
			});
			
			<?php
		}
	}
	
	?>
	
	g++; //El grupo sera por cada xml

});

$('#txtNumeroTransacciones').val(p);
</script>

