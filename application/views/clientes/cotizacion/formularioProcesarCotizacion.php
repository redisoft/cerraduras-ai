	<?php
echo '
<form id="frmProcesarCotizacion">
	<table class="admintable" width="100%" >
		<tr>
			<td class="key">Asunto:</td>
			<td>
				<input style="width:450px; type="text" class="cajas" id="txtAsunto" name="txtAsunto"  value="Asunto: Cotización de" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Presentación:</td>
			<td>
				<textarea name="txtPresentacion" id="txtPresentacion" rows="3" class="TextArea" style="width:450px">Agradecemos la oportunidad que se brinda a</textarea>
			</td>
		</tr>
		
		<tr>
			<td class="key">Subtotal:</td>
			<td>
				<span id="lblSubTotalVenta">$0.00</span>
				<input readonly="readonly" style="width:120px; display: none" type="text" class="cajas" id="txtSubTotal" name="txtSubTotal" value="0.00" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Descuento:</td>
			<td>
				<span id="lblDescuentoVenta">$0.00</span>
				<input readonly="readonly" style="width:120px; display: none" type="text" class="cajas" id="txtDescuentoTotal" name="txtDescuentoTotal" value="0.00" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Impuestos :</td>
			<td align="left">
				
				<span id="lblImporteIva">$0.00</span>
				
				<select id="selectIva" name="selectIva" class="cajas" style="width:100px; display: none" onchange="calcularTotales()">
					<option>'.$ivas->iva.'</option>	
					<option>'.$ivas->iva2.'</option>
					<option>'.$ivas->iva3.'</option>
				</select>
				
				<input type="hidden" id="txtIvaTotal" name="txtIvaTotal" value="0.00" />
				
			</td>
		</tr>
		<tr>
			<td class="key">Total:</td>
			<td>
				<span id="lblTotalVenta">$0.00</span>
				<input readonly="readonly" style="width:120px; display: none" type="text" class="cajas" id="txtTotal" name="txtTotal" value="0.00" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Fecha de cotización:</td>
			<td>
				<input readonly="readonly" value="'.date('Y-m-d H:i').'" type="text" class="cajas" id="txtFechaCotizacion" name="txtFechaCotizacion"  style="width:120px"  />
			</td>
			<script>
				$("#txtFechaCotizacion").datetimepicker({changeMonth: true});
			</script>
		</tr>
		
		<tr>
			<td class="key">Fecha de entrega:</td>
			<td>
				<input readonly="readonly" value="'.date('Y-m-d H:i').'" type="text" class="cajas" id="txtFechaEntrega" name="txtFechaEntrega"  style="width:120px"  />
			</td>
			<script>
				$("#txtFechaEntrega").datetimepicker({changeMonth: true});
			</script>
		</tr>
		
		<tr>
			<td class="key">Días de crédito:</td>
			<td>
				<input type="text" style="width:120px" class="cajas" id="txtDiasCredito" name="txtDiasCredito" value="'.round($diasCredito,0).'" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Condiciones de pago:</td>
			<td>
				<input style="width:450px; type="text" class="cajas" id="txtCondicionesPago" name="txtCondicionesPago" value="LAB. Anticipo 50%" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Terminos:</td>
			<td>
				<input style="width:450px; type="text" class="cajas" id="txtTerminos" name="txtTerminos" value="Se requiere autorización" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Agradecimientos:</td>
			<td>
				<input style="width:450px; type="text" class="cajas" id="txtAgradecimientos" name="txtAgradecimientos" value="Esperamos que nuestros productos sean de su interés" />
			</td>
		</tr>';
		
		if($this->session->userdata('rol')!='1')
		{
			echo'
			<tr>
				<td class="key">Usuario:</td>	
				<td>'.($usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno).'('.$usuario->correo.')
				<input type="hidden" id="selectUsuariosEnviar" name="selectUsuariosEnviar"  value="'.$usuario->idUsuario.'" /></td>
			</tr>';
		}
		
		if($this->session->userdata('rol')=='1')
		{
			echo'
			<tr>
				<td class="key">Usuario:</td>
				<td>
					<select class="cajas" id="selectUsuariosEnviar" name="selectUsuariosEnviar" style="width:450px" onchange="sugerirFirma(this.value)">';
			
					foreach($usuarios as $row)
					{
						echo '<option '.($row->idUsuario==$usuario->idUsuario?'selected="selected"':'').' value="'.$row->idUsuario.'">'.$row->nombre.'('.$row->correo.')</option>';	
					}
			
					echo '
					</select>';
					
					foreach($usuarios as $row)
					{
						echo '<input type="hidden" id="txtFirma'.$row->idUsuario.'" name="txtFirma'.$row->idUsuario.'" value="'.$row->firma.'" />';
					}
					
					echo'
				</td>
			</tr>';
		}
		
		echo'
		
		<tr>
			<td class="key">Firma: </td>
			<td>
				<textarea name="txtFirma" id="txtFirma" name="txtFirma" rows="5" class="TextArea" style="width:450px">'.$usuario->firma.'</textarea>
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
		
	</table>
</form>';