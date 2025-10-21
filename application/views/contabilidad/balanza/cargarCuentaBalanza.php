<?php
$cuenta='<tr id="filaCuenta'.$i.'">';
$cuenta.='<td class="numeral">'.$i.'</td>';
$cuenta.='<td align="center">
	<select class="selectTextosGrandes" id="selectCuentas'.$i.'" name="selectCuentas'.$i.'">
		<option value="0">Seleccione</option>';
	
	$idCatalogo = 0;
	$c			=1;
	foreach($cuentas as $row)
	{
		$idCatalogo	= $c==1?$row->idCatalogo:$idCatalogo;
		
		if($idCatalogo!=$row->idCatalogo)
		{
			break;
		}
		
		$cuenta.='<option value="'.$row->idCuentaCatalogo.'">'.$row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.')</option>';
		
		$c++;
	}

	$cuenta.='
	</select>
</td>';
	
$cuenta.='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtSaldoInicial'.$i.'" name="txtSaldoInicial'.$i.'" value="0.00" onchange="calcularSaldoFinal('.$i.')" maxlength="15" /></td>';
$cuenta.='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'.$i.'" name="txtDebe'.$i.'"  value="0.00" onchange="calcularSaldoFinal('.$i.')" maxlength="15"/></td>';
$cuenta.='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'.$i.'" name="txtHaber'.$i.'"  value="0.00" onchange="calcularSaldoFinal('.$i.')" maxlength="15"/></td>'; 
$cuenta.='<td align="center"><input type="text" class="textosBalanzaCantidades" id="txtSaldoFinal'.$i.'" name="txtSaldoFinal'.$i.'"  value="0.00" readonly="readonly" maxlength="15" /></td>'; 
$cuenta.='<td align="center"><input type="checkbox" id="chkIva'.$i.'" name="chkIva'.$i.'"  value="1" title="Confirmar si se va a desglosar el iva" /></td>'; 
$cuenta.='<td class="vinculos"><img src="'.base_url().'img/borrar.png" title="Borrar cuenta" onclick="borrarCuentaNueva('.$i.')" />';
$cuenta.='<input type="hidden" id="txtIdDetalle'.$i.'" name="txtIdDetalle'.$i.'" value="0" /></td>';
$cuenta.='</tr>';

echo $cuenta;