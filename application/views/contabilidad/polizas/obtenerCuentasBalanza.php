<?php
$i=1;

echo '
<form id="frmBalanza" name="frmBalanza">
	<div id="registrandoInformacion"></div>
	<table class="tablaFormularios">
		<tr>
			<th colspan="2">Detalles de balanza</th>
		</tr>
		<tr>
			<td class="etiquetas">RFC:</td>
			<td>
				'.$balanza->rfc.'
				<input type="hidden" id="txtIdBalanza" name="txtIdBalanza" value="'.$balanza->idBalanza.'" />
				<input type="hidden" id="txtNumeroCuentas" name="txtNumeroCuentas" value="'.count($cuentas).'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Fecha:</td>
			<td>'.obtenerMesAnio($balanza->fecha).'</td>
		</tr>
		<tr>
			<td class="etiquetas">Número de cuentas:</td>
			<td>'.count($cuentas).'</td>
		</tr>
	</table>';
	
	if($cuentas!=null)
	{
		echo '
		<div id="procesandoInformacion"></div>
		
		<script>
		$(document).ready(function()
		{
			$("#tablaCuentasBalanza tr:even").addClass("resaltado");
			$("#tablaCuentasBalanza tr:odd").addClass("normal");  
		});
		</script>
		
		<table class="tablaDatos" id="tablaCuentasBalanza">
			<tr>
				<th colspan="7">Detalles de cuentas</th>
			</tr>
			<tr>
				<th>No.</th>
				<th>Número de cuenta</th>
				<th>Saldo inicial</th>
				<th>Debe</th>
				<th>Haber</th>
				<th>Saldo final</th>
				<th width="12%">Operaciones</th>
			</tr>';
		
		foreach($cuentas as $row)
		{
			echo'
			<tr id="filaCuenta'.$i.'">
				<td class="numeral	">'.$i.'</td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtCuenta'.$i.'" name="txtCuenta'.$i.'" value="'.$row->numeroCuenta.'" placeholder="Número de cuenta" maxlength="100" /></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtSaldoInicial'.$i.'" name="txtSaldoInicial'.$i.'" value="'.$row->saldoInicial.'" onchange="calcularSaldoFinal('.$i.')" onkeypress="return soloDecimales(event)" maxlength="15" /></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'.$i.'" name="txtDebe'.$i.'" value="'.$row->debe.'" onchange="calcularSaldoFinal('.$i.')"onkeypress="return soloDecimales(event)" maxlength="15"/></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'.$i.'" name="txtHaber'.$i.'" value="'.$row->haber.'" onchange="calcularSaldoFinal('.$i.')" onkeypress="return soloDecimales(event)" maxlength="15"/></td>
				<td align="center"><input type="text" class="textosBalanzaCantidades" id="txtSaldoFinal'.$i.'" name="txtSaldoFinal'.$i.'" value="'.$row->saldoFinal.'" readonly="readonly" maxlength="15" /></td>
				<td class="vinculos">
					<img src="'.base_url().'img/borrar.png" title="Borrar cuenta" onclick="borrarCuentaBalanza('.$row->idDetalle.')" />
					
					<input type="hidden" id="txtIdDetalle'.$i.'" name="txtIdDetalle'.$i.'" value="'.$row->idDetalle.'" />
				</td>
			<tr>';
			
			$i++;
		}
		
		echo '</table>';
	}
	else
	{
		echo '<div class="erroresDatos">Aun no se han registrado cuentas</div>';
	}
	
echo '
</form>';
?>