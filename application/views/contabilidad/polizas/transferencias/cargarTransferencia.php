<?php
echo'
<tr id="filaTransferencia'.$i.'">
	<td class="numeral">'.$i.'</td>
	<td align="center">
		<input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaOrigen'.$i.'" name="txtCuentaOrigen'.$i.'" placeholder="Cuenta origen" maxlength="50" />
		<select class="selectTextos" style="width:150px; margin-top:2px" id="selectBancosOrigen'.$i.'" name="selectBancosOrigen'.$i.'">';
		foreach($bancos as $row)
		{
			echo '<option value="'.$row->idBanco.'">('.$row->clave.')'.$row->nombre.'</option>';
		}
		echo'
		</select>
	</td>
	
	<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMonto'.$i.'" name="txtMonto'.$i.'"  placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
	
	<td align="center">
		<input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaDestino'.$i.'" name="txtCuentaDestino'.$i.'" placeholder="Cuenta destino" maxlength="50" />
		
		<select class="selectTextos" style="width:150px; margin-top:2px" id="selectBancosDestino'.$i.'" name="selectBancosDestino'.$i.'">';
		foreach($bancos as $row)
		{
			echo '<option value="'.$row->idBanco.'">('.$row->clave.')'.$row->nombre.'</option>';
		}
		echo'
		</select>
	</td>
	
	<td align="center"><input type="text" class="textosFechas" id="txtFecha'.$i.'" name="txtFecha'.$i.'" value="'.date('Y-m-d').'" readonly="readonly"  /></td>
	<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiario'.$i.'" name="txtBeneficiario'.$i.'" placeholder="Beneficiario" maxlength="300"  />
	<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfc'.$i.'" name="txtRfc'.$i.'"  placeholder="RFC" maxlength="13" /></td>
	<input type="hidden" id="txtIdTransferencia'.$i.'" name="txtIdTransferencia'.$i.'" value="0" />
	</td>
	
	<td class="vinculos">
		<img src="'.base_url().'img/borrar.png" title="Borrar cheque" onclick="borrarTransferenciaNueva('.$i.')" />
		<script>
		$(document).ready(function()
		{
			$("#txtFecha'.$i.'").datepicker();
		});
		</script>';
	echo'
	</td>
<tr>';
?>