<?php
$i=1;

$subTotalDeudor		= $deudor/(1+($iva/100));
$subTotalAcreedor	= $acreedor/(1+($iva/100));

$ivaAcreedor		= $acreedor-$subTotalAcreedor;
$ivaDeudor			= $deudor-$subTotalDeudor;

echo '
<div id="registrandoInformacion"></div>
<table class="tablaFormularios">
	<tr>
		<th colspan="4">Detalles de balanza</th>
	</tr>
	<tr>
		<td class="etiquetas">RFC:</td>
		<td>
			'.$balanza->rfc.'
			<input type="hidden" id="txtIdBalanza" name="txtIdBalanza" value="'.$balanza->idBalanza.'" />
			<input type="hidden" id="txtNumeroCuentas" name="txtNumeroCuentas" value="'.count($cuentas).'" />
			<input type="hidden" id="txtFechaBalanza" name="txtFechaBalanza" value="'.$balanza->fecha.'" />
		</td>
		
		<td class="etiquetas">Fecha:</td>
		<td>'.obtenerMesAnio($balanza->fecha).'</td>
		
	</tr>
	
	<tr>
		<td class="etiquetas">Número de cuentas deudoras:</td>
		<td>'.$balanza->cuentasDeudoras.'</td>
		<td class="etiquetas">Número de cuentas acreedoras:</td>
		<td>'.$balanza->cuentasAcreedoras.'</td>
	</tr>
	<tr>
		<td class="etiquetas">Número total de cuentas:</td>
		<td>'.count($cuentas).'</td>
		<td class="etiquetas">IVA por pagar:</td>
		<td>$'.number_format($ivaDeudor-$ivaAcreedor,2).'</td>
	</tr>
</table>';
	

echo '
<script>
$(document).ready(function()
{
	$("#tablaCuentasBalanza tr:even").addClass("resaltado");
	$("#tablaCuentasBalanza tr:odd").addClass("normal");  
});
</script>

<table class="tablaDatos" id="tablaCuentasBalanza">
	<tr>
		<th class="titulos" colspan="7">Detalles de cuentas</th>
	</tr>
	<tr>
		<th>No.</th>
		<th>Número de cuenta</th>
		<th>Descripción</th>
		<th>Naturaleza</th>
		<th>Subtotal</th>
		<th>IVA</th>
		<th>Total</th>
	</tr>';

foreach($cuentas as $row)
{
	$subTotal	= $row->iva=='1'?$row->saldoFinal/(1+($iva/100)):$row->saldoFinal;
	
	echo'
	<tr>
		<td class="numeral	">'.$i.'</td>
		<td align="center">'.$row->numeroCuenta.'</td>
		<td align="left">'.$row->descripcion.'</td>
		<td align="center">'.($row->naturaleza=='A'?'Acreedora':'Deudora').'</td>
		<td align="right">$'.number_format($subTotal,2).'</td>
		<td align="right">$'.number_format($row->saldoFinal-$subTotal,2).'</td>
		<td align="right">$'.$row->saldoFinal.'</td>
	<tr>';
	
	$i++;
}

echo '</table>';
?>