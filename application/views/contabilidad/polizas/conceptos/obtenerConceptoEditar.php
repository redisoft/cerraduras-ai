<script>
$(document).ready(function()
{
	$("#txtFechaConcepto").datepicker();
	
	$("#txtBuscarCfdi").autocomplete(
	{
		source:base_url+'configuracion/obtenerComprobantes',
		
		select: function(event,ui)
		{
			 obtenerDatosXmlSistema(ui.item.idFactura);
		}
	});
	
	opcionesTipoPoliza(1);
	
	$("#tablaTransacciones tr:even").addClass("abajo");
	$("#tablaTransacciones tr:odd").addClass("arriba");  
});
</script>
<?php
echo'
<div id="registrandoInformacion"></div>
<form id="frmConceptos" name="frmConceptos">
	
	<input type="hidden" id="txtNumeroTransacciones" name="txtNumeroTransacciones" value="'.count($transacciones).'"/>
	<input type="hidden" id="txtNumeroGrupos" name="txtNumeroGrupos" value="'.count($grupos).'"/>
	<input type="hidden" id="txtIdConcepto" name="txtIdConcepto" value="'.$concepto->idConcepto.'"/>
	
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
			<td class="etiquetas">Buscar CFDI:</td>
			<td colspan="3">
				<input type="text" class="textosUuid" id="txtBuscarCfdi" name="txtBuscarCfdi" maxlength="300" style="width:800px" placeholder="Buscar por RFC, cliente, folio, serie, tipo" />
			</td
		</tr>
		<tr>
			<td class="etiquetas">Tipo de póliza:</td>
			<td>
				<!--<select id="selectTipo" name="selectTipo" class="selectTextos" onchange="opcionesTipoPoliza()">
					<option value="1">Ingreso</option>
					<option value="2" '.($concepto->tipo=='2'?'selected="selected"':'').'>Egreso</option>
					<option value="3" '.($concepto->tipo=='3'?'selected="selected"':'').'>Diario</option>
				</select>-->
				
				'.obtenerTipoPoliza($concepto->tipo).'
				
				<input type="hidden" id="selectTipo" name="selectTipo" value="'.$concepto->tipo.'" />
				
				<div id="tipoIngreso">
					<select id="selectTipoIngreso" name="selectTipoIngreso" class="selectTextos" style="margin-top:2px; width: 140px">
						<option value="1">Cobrado</option>
						<option value="0" '.($concepto->pagada=='0'?'selected="selected"':'').'>No cobrado</option>
					</select>
				</div>
				
				<div id="tipoEgreso" style="display:none">
					<select id="selectTipoEgreso" name="selectTipoEgreso" class="selectTextos" style="margin-top:2px; width: 140px">
						<option value="1">Pagado</option>
						<option value="0" '.($concepto->pagada=='0'?'selected="selected"':'').'>No pagado</option>
					</select>
				</div>
			</td>
		
		
			<td class="etiquetas">Número:</td>
			<td>
				<span id="lblPoliza">'.$polizas->polizaIngresos.'</span><input type="text" class="textosFechas" id="txtNumero" name="txtNumero" maxlength="50" value="'.$concepto->numero.'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Fecha:</td>
			<td>
				<input type="text" class="textosFechas" id="txtFechaConcepto" name="txtFechaConcepto" readonly="readonly" value="'.$concepto->fecha.'" />
				<input type="hidden" id="txtFechaCatalogo" name="txtFechaCatalogo" value="'.$this->input->post('fecha').'" />
			</td>
		
			<td class="etiquetas">Concepto:</td>
			<td>
				<input type="text" class="textosUuid" id="txtConcepto" name="txtConcepto" maxlength="300" value="'.$concepto->concepto.'" />
			</td>
		</tr>
	</table>
	
		
	<table class="tablaDatos" id="tablaTransacciones" style="margin-top:2px">
		<tr>
			<th class="titulos">-</th>
			<th class="titulos">Cuenta</th>
			<th class="titulos">Concepto</th>
			<th class="titulos">Productos o servicios</th>
			<th class="titulos">Debe</th>
			<th class="titulos">Haber</th>
			<th class="titulos">UUID</th>
		</tr>';
		
		$i		 = 0;
		$grupo	 = true;
		$idGrupo = 1000;
		$g		 = 0;
		
		foreach($transacciones as $row)
		{
			$idGrupo 		= $i==0?$row->idGrupo:$idGrupo;
			
			if($idGrupo==$row->idGrupo)
			{
				$grupo		= false;
			}
			
			if($idGrupo!=$row->idGrupo)
			{
				$idGrupo	= $row->idGrupo;
				$grupo		= true;
				$g++;
			}
			
			$grupo	= $i==0?true:$grupo;
				
			echo '
			<tr id="filaTransaccion'.$g.'" class="grupoTransaccion'.$g.'">
				<td class="numeral">'.($grupo?($g+1).' <img src="'.base_url().'img/borrar.png" onclick="borrarTransaccionGrupo('.$g.')" title="Quitar transacción" />':'').' </td>
				<input type="hidden" id="txtGrupo'.$i.'" name="txtGrupo'.$i.'" value="'.$g.'"/>';

				echo'
				<td class="letraChica">
				<select class="selectTextos" id="selectCuentasTransaccion'.$i.'" name="selectCuentasTransaccion'.$i.'" style="width:200px">
					<option value="0">Seleccione</option>';
				
				$idCatalogo = 0;
				$c			= 1;
				foreach($cuentas as $cat)
				{
					$idCatalogo	= $c==1?$cat->idCatalogo:$idCatalogo;
					
					if($idCatalogo!=$cat->idCatalogo)
					{
						break;
					}

					echo'<option title="'.$cat->numeroCuenta.'('.($cat->naturaleza=='A'?'Acreedora':'Deudora').', '.$cat->descripcion.')" '.($cat->idCuentaCatalogo==$row->idCuentaCatalogo?'selected="selected"':'').' value="'.$cat->idCuentaCatalogo.'">'.$cat->numeroCuenta.'('.($cat->naturaleza=='A'?'Acreedora':'Deudora').', '.$cat->descripcion.')</option>';
					
					$c++;
				}
			
				echo '
				</td>
				<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConceptoTransaccion'.$i.'" name="txtConceptoTransaccion'.$i.'" placeholder="Concepto" value="'.$row->concepto.'" /></td>
				<td class="letraChica">';
				
				$p=0;
				$conceptosTransaccion	=$this->contabilidad->obtenerConceptosTransaccion($row->idTransaccion);
				
				foreach($conceptosTransaccion as $con)
				{
					
					echo $p==0?'':'<br /><br />Cantidad: '.$con->cantidad;
                    echo'
                    <br />Descripción: '.$con->descripcion.'
                    <br />Importe: $'.$con->importe;
                    
                    
                    echo'
					<input type="hidden" id="txtCantidad'.$i.'_'.$p.'" name="txtCantidad'.$i.'_'.$p.'" value="'.$con->cantidad.'" />
                    <input type="hidden" id="txtUnidad'.$i.'_'.$p.'" name="txtUnidad'.$i.'_'.$p.'" value="'.$con->unidad.'" />
                    <input type="hidden" id="txtCodigo'.$i.'_'.$p.'" name="txtCodigo'.$i.'_'.$p.'" value="'.$con->codigo.'" />
                    <input type="hidden" id="txtDescripcion'.$i.'_'.$p.'" name="txtDescripcion'.$i.'_'.$p.'" value="'.$con->descripcion.'" />
                    <input type="hidden" id="txtPrecioUnitario'.$i.'_'.$p.'" name="txtPrecioUnitario'.$i.'_'.$p.'" value="'.$con->precioUnitario.'" />
                    <input type="hidden" id="txtImporte'.$i.'_'.$p.'" name="txtImporte'.$i.'_'.$p.'" value="'.$con->importe.'" />';
                    
                    $p++;
				}
				
				echo'
					<input type="hidden" id="txtNumeroProductos'.$i.'" name="txtNumeroProductos'.$i.'" value="'.$p.'" />
				</td>
				<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'.$i.'" name="txtDebe'.$i.'"  value="'.$row->debe.'"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>
				<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'.$i.'" name="txtHaber'.$i.'"  value="'.$row->haber.'" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
				
				//CARGAR EL COMPROBANTE
				
				$comprobante	= $this->contabilidad->obtenerComprobanteTransaccion($row->idTransaccion);
				echo'
				<td class="letraChica">';
					
					if($comprobante!=null)
					{
						echo'
						<input style="width:100px" value="'.$comprobante->uuid.'" onclick="seleccionarTexto(this)" type="text" class="textos" id="txtUuid'.$i.'" name="txtUuid'.$i.'" placeholder="UUID" maxlength="50" />
						<input style="display:none" value="'.$comprobante->monto.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMontoComprobante'.$i.'" name="txtMontoComprobante'.$i.'" placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" />
						<input style="display:none" value="'.$comprobante->rfc.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfcComprobante'.$i.'" name="txtRfcComprobante'.$i.'" placeholder="RFC" maxlength="13" />';
					}
				
				echo'
				</td>
				
				<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda'.$i.'" name="txtMoneda'.$i.'" value="'.$row->moneda.'" maxlength="4" /></td>
				<td style="display:none" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio'.$i.'" name="txtTipoCambio'.$i.'"  value="'.$row->tipoCambio.'" maxlength="15" onkeypress="return soloDecimales(event)" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
			</tr>';
			
			$i++;
			
			if(!isset($transacciones[$i]))
			{
				echo '
				<tr class="grupoTransaccion'.$g.'">
					<td class="letraChica" colspan="2" align="right">SUMA</td>
					<td class="letraChica" colspan="3" align="right"><input type="text" class="textosBalanzaCantidades" id="txtDebeGrupo'.$g.'" name="txtDebeGrupo'.$g.'" value="'.$grupos[$g]->totalDebe.'"  placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
					<td class="letraChica" align="right"><input type="text" class="textosBalanzaCantidades" id="txtHaberGrupo'.$g.'" name="txtHaberGrupo'.$g.'" value="'.$grupos[$g]->totalHaber.'"  placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
					<td></td>
					
					<input type="hidden" id="txtIdGrupo'.$g.'" name="txtIdGrupo'.$g.'" value="'.$g.'"/>
					
					
				</tr>';
			}
			else
			{
				if($row->idGrupo!=$transacciones[$i]->idGrupo)
				{
					echo '
					<tr class="grupoTransaccion'.$g.'">
						<td class="letraChica" colspan="2" align="right">SUMA</td>
						<td class="letraChica" colspan="3" align="right"><input type="text" class="textosBalanzaCantidades" id="txtDebeGrupo'.$g.'" name="txtDebeGrupo'.$g.'" value="'.$grupos[$g]->totalDebe.'"  placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
						<td class="letraChica" align="right"><input type="text" class="textosBalanzaCantidades" id="txtHaberGrupo'.$g.'" name="txtHaberGrupo'.$g.'" value="'.$grupos[$g]->totalHaber.'"  placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
						<input type="hidden" id="txtIdGrupo'.$g.'" name="txtIdGrupo'.$g.'" value="'.$g.'"/>
						<!--<input type="hidden" id="txtDebeGrupo'.$g.'" name="txtDebeGrupo'.$g.'" value="'.$grupos[$g]->totalDebe.'"/>
						<input type="hidden" id="txtHaberGrupo'.$g.'" name="txtHaberGrupo'.$g.'" value="'.$grupos[$g]->totalHaber.'"/>-->
						<td></td>
					</tr>';
				}
			}
			

			/*for($a=0;$a<count($grupos);$a++)
			{
				if($a==$g)
				{
					echo '
					<tr class="grupoTransaccion'.$g.'">
						<td colspan="2" align="right">SUMA</td>
						<td colspan="3" align="right">'.'</td>
						<td align="right">'.'</td>
						<td></td>
					</tr>';
				}
			}*/
		}
	
	echo'
	</table>';
	
	//
	echo'
	<table class="tablaDatos" id="tablaMetodos" style="margin-top:2px; '.($metodosPago==null?'display:none;':'').'">
		<tr>
			<th class="titulos" colspan="8">Otros métodos de pago</th>	
		</tr>
		<tr>
			<th>#</th>
			<th>Método de pago</th>
			<th>Fecha</th>
			<th>Beneficiario</th>
			<th>RFC</th>
			<th>Monto</th>
			<th>Moneda</th>
			<th>Tipo cambio</th>
		</tr>';
		
		$i	= 0;
		$g	= 0;
		foreach($transacciones as $row)
		{
			#$cheques	= $this->contabilidad->obtenerCheques($row->idTransaccion);
			
			foreach($metodosPago as $metodo)
			{
				if($row->idTransaccion==$metodo->idTransaccion)
				{
					#$g++;
					
					echo'
					<tr id="filaMetodoPago'.$i.'" class="grupoTransaccion'.$g.'">
						<td class="numeral">'.($g+1).'</td>
						<td align="center">
							<select class="selectTextos" style="width:150px" id="selectMetodos'.$i.'" name="selectMetodos'.$i.'">';
							
							foreach($metodos as $met)
							{
								echo '<option '.($metodo->idMetodo==$met->idMetodo?'selected="selected"':'').' value="'.$met->idMetodo.'">('.$met->clave.')'.quitarSaltosEspacio($met->concepto).'</option>';
							}
							
							echo'
							</select>
						</td>
						<td align="center"><input value="'.$metodo->fecha.'" onclick="seleccionarTexto(this)" type="text" class="textosFechas" id="txtFechaMetodo'.$i.'" name="txtFechaMetodo'.$i.'" /></td>
						
						<td align="center"><input value="'.$metodo->beneficiario.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiarioMetodo'.$i.'" name="txtBeneficiarioMetodo'.$i.'" placeholder="Beneficiario maxlength="300" /></td>
						<td align="center"><input value="'.$metodo->rfc.'" type="text" class="textosBalanzaCuentasChicas" id="txtRfcMetodo'.$i.'" name="txtRfcMetodo'.$i.'" /></td>
						<td align="center"><input value="'.$metodo->monto.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMontoMetodo'.$i.'" name="txtMontoMetodo'.$i.'" placeholder="$0.00" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" maxlength="15"  /></td>
						<td align="center">
						<select class="selectTextos" style="width:170px" id="selectMonedaMetodo'.$i.'" name="selectMonedaMetodo'.$i.'">';
						
						foreach($monedas as $mon)
						{
							echo '<option '.($metodo->moneda==$mon->codigo?'selected="selected"':'').' value="'.$mon->codigo.'">('.$mon->codigo.')'.quitarSaltosEspacio($mon->nombre).'</option>';
						}
						
						echo'
						</select>
						</td>
						
						<td align="center"><input value="'.round($metodo->tipoCambio,2).'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtTipoCambioMetodo'.$i.'" name="txtTipoCambioMetodo'.$i.'" placeholder="Tipo de cambio" maxlength="300" /></td>
					</tr>
					<script>
					$(document).ready(function()
					{
						$("#txtFechaMetodo'.$i.'").datepicker();
					});
					</script>';					
				}
			}
			
			$i++;
			
			if(!isset($transacciones[$i]))
			{
				#$g++;
			}
			else
			{
				if($row->idGrupo!=$transacciones[$i]->idGrupo)
				{
					$g++;
				}
			}
		}
		
		
	echo'
	</table>';
	
	//CHEQUES
	echo'
	<table class="tablaDatos" id="tablaCheques" style="margin-top:2px; '.($cheques==null?'display:none;':'').'">
		<tr>
			<th class="titulos" colspan="8">Cheques</th>	
		</tr>
		<tr>
			<th>#</th>
			<th>Número</th>
			<th>Banco</th>
			<th>Cuenta origen</th>
			<th>Fecha</th>
			<th>Monto</th>
			<th>Beneficiario</th>
			<th>RFC</th>
		</tr>';
		
		$i	= 0;
		$g	= 0;
		foreach($transacciones as $row)
		{
			#$cheques	= $this->contabilidad->obtenerCheques($row->idTransaccion);
			
			foreach($cheques as $cheque)
			{
				if($row->idTransaccion==$cheque->idTransaccion)
				{
					#$g++;
					
					echo'
					<tr id="filaCheque'.$i.'" class="grupoTransaccion'.$g.'">
						<td class="numeral">'.($g+1).'</td>
						<td align="center"><input value="'.$cheque->numero.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtNumeroCheque'.$i.'" name="txtNumeroCheque'.$i.'" placeholder="Número cheque" maxlength="20"  /></td>
						<td align="center">
						<select class="selectTextos" style="width:150px" id="selectBancos'.$i.'" name="selectBancos'.$i.'">';
						
						foreach($bancos as $ban)
						{
							echo '<option '.($ban->idBanco==$cheque->idBanco?'selected="selected"':'').' value="'.$ban->idBanco.'">('.$ban->clave.')'.$ban->nombre.'</option>';
						}
						
						echo'
							</select>
						</td>
						<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaOrigenCheque'.$i.'" name="txtCuentaOrigenCheque'.$i.'" value="'.$cheque->cuentaOrigen.'" placeholder="Cuenta origen" maxlength="50" /></td>
						<td align="center"><input type="text" class="textosFechas" id="txtFechaCheque'.$i.'" name="txtFechaCheque'.$i.'" value="'.$cheque->fecha.'" readonly="readonly"  /></td>
						<td align="center"><input value="'.$cheque->monto.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMonto'.$i.'" name="txtMonto'.$i.'" placeholder="$0.00" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" maxlength="15"  /></td>
						<td align="center"><input value="'.$cheque->beneficiario.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiario'.$i.'" name="txtBeneficiario'.$i.'" placeholder="Beneficiario" maxlength="300" /></td>
						<td align="center"><input value="'.$cheque->rfc.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfc'.$i.'" name="txtRfc'.$i.'" placeholder="RFC" maxlength="13"  /></td>
					</tr>
					<script>
					$(document).ready(function()
					{
						$("#txtFechaCheque'.$i.'").datepicker();
					});
					</script>';					
				}
			}
			
			$i++;
			
			if(!isset($transacciones[$i]))
			{
				#$g++;
			}
			else
			{
				if($row->idGrupo!=$transacciones[$i]->idGrupo)
				{
					$g++;
				}
			}
		}
		
		
	echo'
	</table>';
	
	echo'
	<table class="tablaDatos" id="tablaTransferencias" style="margin-top:2px; '.($transferencias==null?'display:none;':'').'">
		<tr>
			<th class="titulos" colspan=7">Transferencias</th>	
		</tr>
		<tr>
			<th>#</th>
			<th>Cuenta y banco origen</th>
			<th>Monto</th>
			<th>Cuenta y banco destino</th>
			<th>Fecha</th>
			<th>Beneficiario</th>
			<th>RFC</th>
		</tr>';
		
		$i	= 0;
		$g	= 0;
		
		foreach($transacciones as $row)
		{
			foreach($transferencias as $trans)
			{
				if($row->idTransaccion==$trans->idTransaccion)
				{
					echo '
					<tr id="filaTransferencia'.$i.'" class="grupoTransaccion'.$g.'">';
			
						//CUENTA DE ORIGEN
						echo'
						<td class="numeral">'.($g+1).'</td>
						<td align="center"><input value="'.$trans->cuentaOrigen.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaOrigen'.$i.'" name="txtCuentaOrigen'.$i.'" placeholder="Cuenta origen" maxlength="50" />
						<br /><select class="selectTextos" style="width:150px; margin-top:2px"  id="selectBancosOrigen'.$i.'" name="selectBancosOrigen'.$i.'">';
						
						foreach($bancos as $ban)
						{
							echo '<option '.($ban->idBanco==$trans->idBancoOrigen?'selected="selected"':'').' value="'.$ban->idBanco.'">('.$ban->clave.')'.$ban->nombre.'</option>';
						}
						
						echo'
							</select>
						</td>
						<td align="center"><input value="'.$trans->monto.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMontoTransferencia'.$i.'" name="txtMontoTransferencia'.$i.'"  placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>';
						
						//CUENTA DE DESTINO
						echo'
						<td align="center">
							<input value="'.$trans->cuentaDestino.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaDestino'.$i.'" name="txtCuentaDestino'.$i.'" placeholder="Cuenta origen" maxlength="50" />
							<br /><select class="selectTextos" style="width:150px; margin-top:2px"  id="selectBancosDestino'.$i.'" name="selectBancosDestino'.$i.'">'; 
							
							foreach($bancos as $ban)
							{
								echo '<option '.($ban->idBanco==$trans->idBancoDestino?'selected="selected"':'').' value="'.$ban->idBanco.'">('.$ban->clave.')'.$ban->nombre.'</option>';
							}
							
						
						echo'
							</select>
						</td>
						<td align="center"><input type="text" class="textosFechas" id="txtFecha'.$i.'" name="txtFecha'.$i.'" value="'.$trans->fecha.'" readonly="readonly"  /></td>
						<td align="center"><input value="'.$trans->beneficiario.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiarioTransferencia'.$i.'" name="txtBeneficiarioTransferencia'.$i.'" placeholder="Beneficiario" maxlength="300"  />
						<td align="center"><input value="'.$trans->rfc.'" onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfcTransferencia'.$i.'" name="txtRfcTransferencia'.$i.'"  placeholder="RFC" maxlength="13" />
						</td>
					</tr>
					<script>
					$(document).ready(function()
					{
						$("#txtFecha'.$i.'").datepicker();
					});
					</script>';
				}
			}
			
			$i++;
			
			if(!isset($transacciones[$i]))
			{
			}
			else
			{
				if($row->idGrupo!=$transacciones[$i]->idGrupo)
				{
					$g++;
				}
			}
		}
	
	echo'
	</table>
	
	<div style="display:none" id="obtenerDatosXml" class="">No se ha cargado un archivo xml</div>

</form>';
