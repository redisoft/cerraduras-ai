	<?php
echo '
<table class="admintable" width="100%" >
	<tr>
		<td class="etiquetaGrande">Asunto:</td>
		<td class="textoGrande">
			<input readonly="readonly" style="width:150px; type="text" class="cajas" id="txtTotal" value="0.00" />
		</td>
	</tr>
	
	<tr>
		<td class="etiquetaGrande">Subtotal:</td>
		<td class="textoGrande"><input readonly="readonly" style="width:150px; type="text" class="cajas" id="txtSubTotal" value="0.00" /></td>
	</tr>
	<tr>
		<td class="etiquetaGrande">IVA :</td>
		<td align="left" class="textoGrande">
			<select id="selectIva" name="selectIva" class="cajas" style="width:100px" onchange="calcularTotales()">
				<option>'.$ivas->iva.'</option>	
				<option>'.$ivas->iva2.'</option>
				<option>'.$ivas->iva3.'</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="etiquetaGrande">Total:</td>
		<td class="textoGrande">
			<input readonly="readonly" style="width:150px; type="text" class="cajas" id="txtTotal" value="0.00" />
		</td>
	</tr>
	
	<tr>
		<td class="etiquetaGrande">Fecha de cotización:</td>
		<td class="textoGrande">
			<input readonly="readonly" value="'.date('Y-m-d H:i').'" type="text" class="cajas" id="txtFechaCotizacion"  style="width:160px"  />
		</td>
		<script>
			$("#txtFechaCotizacion").datetimepicker({changeMonth: true});
		</script>
	</tr>
	
	<tr>
		<td class="etiquetaGrande">Fecha de entrega:</td>
		<td class="textoGrande">
			<input readonly="readonly" value="'.date('Y-m-d H:i').'" type="text" class="cajas" id="txtFechaEntrega"  style="width:160px"  />
		</td>
		<script>
			$("#txtFechaEntrega").datetimepicker({changeMonth: true});
		</script>
	</tr>
	
	<tr>
		<td class="etiquetaGrande">Días de crédito:</td>
		<td class="textoGrande">
			<input type="text" style="width:100px" class="cajas" id="txtDiasCredito" name="txtDiasCredito" value="'.round($diasCredito,0).'" />
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
	
</table>';