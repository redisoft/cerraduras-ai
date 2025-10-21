<?php

echo '
<div id="procesandoTransferencias"></div>
<form id="frmTransferencias" name="frmTransferencias">
	<table class="tablaFormularios">
		<tr>
			<th colspan="2">Detalles de transacción</th>
		</tr>
		<tr>
			<td class="etiquetas">Número de cuenta:</td>
			<td>'.$transaccion->numeroCuenta.'</td>
			<input type="hidden" id="txtIdTransaccion" name="txtIdTransaccion" value="'.$transaccion->idTransaccion.'" />
			<input type="hidden" id="txtNumeroTransferencias" name="txtNumeroTransferencias" value="'.count($transferencias).'" />
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
			$("#tablaTransferencias tr:even").addClass("arriba");
			$("#tablaTransferencias tr:odd").addClass("abajo");  
		});
		</script>
		
		<table class="tablaDatos" id="tablaTransferencias">
			<tr>
				<th class="titulos" colspan="8">Lista de transferencias</th>
			</tr>
			<tr>
				<th>No.</th>
				<th>Cuenta y banco origen</th>
				<th>Monto</th>
				<th>Cuenta y banco destino</th>
				<th>Fecha</th>
				<th>Beneficiario</th>
				<th>RFC</th>
				<th>Borrar</th>
			</tr>';
		
		$i=1;
		foreach($transferencias as $row)
		{
			echo'
			<tr id="filaTransferencia'.$row->idTransferencia.'">
				<td class="numeral	">'.$i.'</td>
				<td align="center">
					<input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaOrigen'.$i.'" name="txtCuentaOrigen'.$i.'" value="'.$row->cuentaOrigen.'" placeholder="Cuenta origen" maxlength="50" />
					
					<select class="selectTextos" style="width:150px; margin-top: 2px" id="selectBancosOrigen'.$i.'" name="selectBancosOrigen'.$i.'">';
					foreach($bancos as $ban)
					{
						echo '<option '.($ban->idBanco==$row->idBancoOrigen?'selected="selected"':'').' value="'.$ban->idBanco.'">('.$ban->clave.')'.$ban->nombre.'</option>';
					}
					echo'
					</select>
				</td>
				
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMonto'.$i.'" name="txtMonto'.$i.'" value="'.$row->monto.'" placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>

				<td align="center">
					<input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtCuentaDestino'.$i.'" name="txtCuentaDestino'.$i.'" value="'.$row->cuentaDestino.'" placeholder="Cuenta destino" maxlength="50" />
					
					<select class="selectTextos" style="width:150px; margin-top: 2px" id="selectBancosDestino'.$i.'" name="selectBancosDestino'.$i.'">';
					foreach($bancos as $ban)
					{
						echo '<option '.($ban->idBanco==$row->idBancoDestino?'selected="selected"':'').' value="'.$ban->idBanco.'">('.$ban->clave.')'.$ban->nombre.'</option>';
					}
					echo'
					</select>
				</td>
				
				<td align="center"><input type="text" class="textosFechas" id="txtFecha'.$i.'" name="txtFecha'.$i.'" value="'.$row->fecha.'" readonly="readonly" /></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtBeneficiario'.$i.'" name="txtBeneficiario'.$i.'" value="'.$row->beneficiario.'" placeholder="Beneficiario" maxlength="300"  />
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfc'.$i.'" name="txtRfc'.$i.'" value="'.$row->rfc.'" placeholder="RFC" maxlength="13" />
					<input type="hidden" id="txtIdTransferencia'.$i.'" name="txtIdTransferencia'.$i.'" value="'.$row->idTransferencia.'" />
				</td>
				
				<td class="vinculos">
					<img src="'.base_url().'img/borrar.png" title="Borrar transferencia" onclick="borrarTransferencia('.$row->idTransferencia.')" />
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