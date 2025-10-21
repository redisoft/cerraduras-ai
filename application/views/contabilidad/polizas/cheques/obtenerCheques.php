<?php

echo '
<div id="procesandoCheques"></div>
<form id="frmCheques" name="frmCheques">
	<table class="tablaFormularios">
		<tr>
			<th colspan="2">Detalles de transacción</th>
		</tr>
		<tr>
			<td class="etiquetas">Número de cuenta:</td>
			<td>'.$transaccion->numeroCuenta.'</td>
			<input type="hidden" id="txtIdTransaccion" name="txtIdTransaccion" value="'.$transaccion->idTransaccion.'" />
			<input type="hidden" id="txtNumeroCheques" name="txtNumeroCheques" value="'.count($cheques).'" />
		</tr>
		<tr>
			<td class="etiquetas">Concepto:</td>
			<td>'.$transaccion->concepto.'</td>
		</tr>
		<tr>
			<td class="etiquetas">Debe:</td>
			<td>$'.number_format($transaccion->debe,2).'</td>
		</tr>
		<tr>
			<td class="etiquetas">Haber:</td>
			<td>$'.number_format($transaccion->haber,2).'</td>
		</tr>
	</table>';
	
		echo'
		<script>
		$(document).ready(function()
		{
			$("#tablaCheques tr:even").addClass("arriba");
			$("#tablaCheques tr:odd").addClass("abajo");  
		});
		</script>
		
		<table class="tablaDatos" id="tablaCheques">
			<tr>
				<th class="titulos" colspan="9">Lista de cheques</th>
			</tr>
			<tr>
				<th>No.</th>
				<th>Número</th>
				<th>Banco</th>
				<th>Cuenta origen</th>
				<th>Fecha</th>
				<th>Monto</th>
				<th>Beneficiario</th>
				<th>RFC</th>
				<th>Borrar</th>
			</tr>';
		
		$i=1;
		foreach($cheques as $row)
		{
			echo'
			<tr id="filaCheque'.$row->idCheque.'">
				<td class="numeral">'.$i.'</td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtNumeroCheque'.$i.'" name="txtNumeroCheque'.$i.'" value="'.$row->numero.'" placeholder="Número de cheque" maxlength="50" /></td>
				<td align="center">
					<select class="selectTextos" style="width:150px" id="selectBancos'.$i.'" name="selectBancos'.$i.'">';
					foreach($bancos as $ban)
					{
						echo '<option '.($ban->idBanco==$row->idBanco?'selected="selected"':'').' value="'.$ban->idBanco.'">('.$ban->clave.')'.$ban->nombre.'</option>';
					}
					echo'
					</select>
				</td>
				<td align="center">
					<input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaOrigen'.$i.'" name="txtCuentaOrigen'.$i.'" value="'.$row->cuentaOrigen.'" placeholder="Cuenta origen" maxlength="50" />
				</td>
				</td>
				<td align="center"><input  type="text" class="textosFechas" id="txtFecha'.$i.'" name="txtFecha'.$i.'" value="'.$row->fecha.'" readonly="readonly" /></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMonto'.$i.'" name="txtMonto'.$i.'" value="'.$row->monto.'" placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiario'.$i.'" name="txtBeneficiario'.$i.'" value="'.$row->beneficiario.'" placeholder="Beneficiario" maxlength="300"  />
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfc'.$i.'" name="txtRfc'.$i.'" value="'.$row->rfc.'" placeholder="RFC" maxlength="13" />
					<input type="hidden" id="txtIdCheque'.$i.'" name="txtIdCheque'.$i.'" value="'.$row->idCheque.'" />
				</td>
				
				<td class="vinculos">
					<img src="'.base_url().'img/borrar.png" title="Borrar cheque" onclick="borrarCheque('.$row->idCheque.')" />
					<script>
					$(document).ready(function()
					{
						$("#txtFecha'.$i.'").datepicker();
					});
					</script>';
				echo'
				</td>
			</tr>';
			
			$i++;
		}
	
	echo '</table>
</form>';
?>