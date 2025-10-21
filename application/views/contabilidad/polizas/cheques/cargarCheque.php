

<?php
echo '
<tr id="filaCheque'.$i.'">
	<td class="numeral">'.$i.'</td>
	<td align="center"><input ondblclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtNumeroCheque'.$i.'" name="txtNumeroCheque'.$i.'" placeholder="Número cheque" maxlength="20"  /></td>
	<td align="center">
		<select class="selectTextos" style="width:150px" id="selectBancos'.$i.'" name="selectBancos'.$i.'">';
			foreach($bancos as $row)
			{
				echo '<option value="'.$row->idBanco.'">('.$row->clave.')'.$row->nombre.'</option>';
			}
		echo'
		</select>
	</td>
	
	<td align="center">
		<input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaOrigen'.$i.'" name="txtCuentaOrigen'.$i.'" value="" placeholder="Cuenta origen" maxlength="50" />
	</td>
	
	<td align="center"><input type="text" class="textosFechas" id="txtFecha'.$i.'" name="txtFecha'.$i.'" value="'.date('Y-m-d').'" readonly="readonly"  /></td>
	<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMonto'.$i.'" name="txtMonto'.$i.'" placeholder="$0.00" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" maxlength="15"  /></td>
	<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiario'.$i.'" name="txtBeneficiario'.$i.'" placeholder="Beneficiario" maxlength="300" /></td>
	<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfc'.$i.'" name="txtRfc'.$i.'" placeholder="RFC" maxlength="13"  /></td>
	<td align="center" class="vinculos">
		<img src="'.base_url().'img/borrar.png" title="Borrar cuenta" onclick="borrarChequeNuevo('.$i.')" />
		<input type="hidden" id="txtIdCheque'.$i.'" name="txtIdCheque'.$i.'" value="0" />
		<script>
		$(document).ready(function()
		{
			$("#txtFecha'.$i.'").datepicker();
		});
		</script>
	</td>
</tr>';

?>