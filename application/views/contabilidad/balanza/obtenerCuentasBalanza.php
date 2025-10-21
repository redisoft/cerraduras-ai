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
				<input type="hidden" id="txtFechaBalanza" name="txtFechaBalanza" value="'.$balanza->fecha.'" />
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
	

		echo '
		<div id="procesandoInformacion"></div>
		
		<script>
		$(document).ready(function()
		{
			$("#tablaCuentasBalanza tr:even").addClass("abajo");
			$("#tablaCuentasBalanza tr:odd").addClass("arriba");  
		});
		</script>
		
		<table class="tablaDatos" id="tablaCuentasBalanza">
			<tr>
				<th class="titulos" colspan="8">Detalles de cuentas</th>
			</tr>
			<tr>
				<th>No.</th>
				<th>Número de cuenta</th>
				<th>Saldo inicial</th>
				<th>Debe</th>
				<th>Haber</th>
				<th>Saldo final</th>
				<th>IVA</th>
				<th width="12%">Operaciones</th>
			</tr>';
		
		foreach($cuentas as $row)
		{
			echo'
			<tr id="filaCuenta'.$i.'">
				<td class="numeral	">'.$i.'</td>
				<td align="center">
					<select class="selectTextosGrandes" id="selectCuentas'.$i.'" name="selectCuentas'.$i.'">
						<option value="0">Seleccione</option>';
					
					$idCatalogo = 0;
					$c			=1;
					foreach($cuentasCatalogo as $cat)
					{
						$idCatalogo	= $c==1?$cat->idCatalogo:$idCatalogo;
						
						if($idCatalogo!=$cat->idCatalogo)
						{
							break;
						}
						
						echo'<option title="'.$cat->numeroCuenta.'('.($cat->naturaleza=='A'?'Acreedora':'Deudora').', '.$cat->descripcion.')" '.($cat->idCuentaCatalogo==$row->idCuentaCatalogo?'selected="selected"':'').' value="'.$cat->idCuentaCatalogo.'">'.$cat->numeroCuenta.'('.($cat->naturaleza=='A'?'Acreedora':'Deudora').', '.$cat->descripcion.')</option>';
						
						$c++;
					}
					
				echo'
				</td>
				</select>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtSaldoInicial'.$i.'" name="txtSaldoInicial'.$i.'" value="'.$row->saldoInicial.'" onchange="calcularSaldoFinal('.$i.')" onkeypress="return soloDecimales(event)" maxlength="15" /></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'.$i.'" name="txtDebe'.$i.'" value="'.$row->debe.'" onchange="calcularSaldoFinal('.$i.')"onkeypress="return soloDecimales(event)" maxlength="15"/></td>
				<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'.$i.'" name="txtHaber'.$i.'" value="'.$row->haber.'" onchange="calcularSaldoFinal('.$i.')" onkeypress="return soloDecimales(event)" maxlength="15"/></td>
				<td align="center"><input type="text" class="textosBalanzaCantidades" id="txtSaldoFinal'.$i.'" name="txtSaldoFinal'.$i.'" value="'.$row->saldoFinal.'" readonly="readonly" maxlength="15" /></td>
				<td align="center"><input type="checkbox" id="chkIva'.$i.'" name="chkIva'.$i.'"  value="1" title="Confirmar si se va a desglosar el iva" '.($row->iva==1?'checked="checked"':'').' /></td>
				<td class="vinculos">
					<img src="'.base_url().'img/borrar.png" title="Borrar cuenta" onclick="borrarCuentaBalanza('.$row->idDetalle.')" />
					
					<input type="hidden" id="txtIdDetalle'.$i.'" name="txtIdDetalle'.$i.'" value="'.$row->idDetalle.'" />
				</td>
			</tr>';
			
			$i++;
		}
		
		echo '</table>';
	
echo '
</form>';
?>