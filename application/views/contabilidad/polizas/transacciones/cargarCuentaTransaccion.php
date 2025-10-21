<?php
$cuenta='<tr id="filaTransaccion'.$i.'">';
$cuenta.='<td class="numeral">'.$i.'</td>';

$cuenta.='<td align="center">
	<select class="selectTextos" id="selectCuentasTransaccion'.$i.'" name="selectCuentasTransaccion'.$i.'">
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

$cuenta.='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConcepto'.$i.'" name="txtConcepto'.$i.'" placeholder="Concepto" /></td>';
$cuenta.='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'.$i.'" name="txtDebe'.$i.'"  value="0.00"  maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
$cuenta.='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'.$i.'" name="txtHaber'.$i.'"  value="0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>';
$cuenta.='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda'.$i.'" name="txtMoneda'.$i.'" value="MXN" maxlength="4" /></td>'; 
$cuenta.='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio'.$i.'" name="txtTipoCambio'.$i.'"  value="1.00" maxlength="15" onkeypress="return soloDecimales(event)" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>'; 
$cuenta.='<td class="vinculos"><img src="'.base_url().'img/borrar.png" title="Borrar cuenta" onclick="borrarCuentaNueva('.$i.')" />';
$cuenta.='<input type="hidden" id="txtIdTransaccion'.$i.'" name="txtIdTransaccion'.$i.'" value="0" /></td>';
$cuenta.='</tr>';

echo $cuenta;