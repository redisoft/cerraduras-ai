<?php

echo '
<div id="procesandoComprobantes"></div>
<form id="frmComprobantes" name="frmComprobantes">
	<table class="tablaFormularios">
		<tr>
			<th colspan="2">Detalles de transacción</th>
		</tr>
		<tr>
			<td class="etiquetas">Número de cuenta:</td>
			<td>'.$transaccion->numeroCuenta.'</td>
			<input type="hidden" id="txtIdTransaccion" name="txtIdTransaccion" value="'.$transaccion->idTransaccion.'" />
			<input type="hidden" id="txtNumeroComprobantes" name="txtNumeroComprobantes" value="'.count($comprobantes).'" />
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
			$("#tablaComprobantes tr:even").addClass("arriba");
			$("#tablaComprobantes tr:odd").addClass("abajo");  
		});
		</script>
		
		<table class="tablaDatos" id="tablaComprobantes">
			<tr>
				<th class="titulos" colspan="5">Lista de comprobantes</th>
			</tr>
			<tr>
				<th>No.</th>
				<th>UUID</th>
				<th>Monto</th>
				<th>RFC</th>
				<!--<th>Borrar</th>-->
			</tr>';
		
		$i=1;
		foreach($comprobantes as $row)
		{
			echo'
			<tr id="filaComprobante'.$row->idComprobante.'">
				<td class="numeral	">'.$i.'</td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosUuid" id="txtUuid'.$i.'" name="txtUuid'.$i.'" value="'.$row->uuid.'" placeholder="UUID" maxlength="50" /></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMonto'.$i.'" name="txtMonto'.$i.'" value="'.$row->monto.'" placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfc'.$i.'" name="txtRfc'.$i.'" value="'.$row->rfc.'" placeholder="RFC" maxlength="13" /></td>
				<!--<td class="vinculos">
					<img src="'.base_url().'img/borrar.png" title="Borrar comprobante" onclick="borrarComprobante('.$row->idComprobante.')" />
					<input type="hidden" id="txtIdComprobante'.$i.'" name="txtIdComprobante'.$i.'" value="'.$row->idComprobante.'" />
				</td>-->
			</tr>';
			
			$i++;
		}
	
	echo '</table>
</form>';
?>