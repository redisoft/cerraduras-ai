<?php
echo '<table class="tablaVentas" style="width:100%">';
if($arqueo!=null)
{
	$fondoInicial	= $arqueo->editado==0?$fondoInicial:$arqueo->fondoInicial;
	$efectivo		= $arqueo->editado==0?$efectivo:$arqueo->efectivo;
	$retiros		= $arqueo->editado==0?$retiros:$arqueo->retiros;
	
	$totalEfectivo		= $fondoInicial+$efectivo+$retiros;
	$sumaDenominaciones	= $arqueo->editado==0?$sumaDenominaciones:$arqueo->efectivoReportado;
	
	echo '
	
	
	<input type="hidden" id="txtFondoInicialArqueo" value="'.$fondoInicial.'" />
	<input type="hidden" id="txtEfectivoArqueo" value="'.$efectivo.'" />
	<input type="hidden" id="txtRetirosArqueo" value="'.$retiros.'" />
	<input type="hidden" id="txtTotalEfectivoArqueo" value="'.$totalEfectivo.'" />
	<input type="hidden" id="txtEfectivoReportadoArqueo" value="'.$sumaDenominaciones.'" />
	
	
	<tr>
		<td colspan="4" class="altoDefinido24 textoBlanco" align="center">Arqueo de caja</td>
	</tr>
	<tr>
		<td class="altoDefinido24" width="15%"></td>
		<td class="altoDefinido24 textoBlanco">Fondo inicial:</td>
		<td class="altoDefinido24 textoBlanco" align="right">$ '.number_format($fondoInicial,decimales).'</td>
		<td class="altoDefinido24" width="15%"></td>
	</tr>
	<tr>
		<td class="altoDefinido24" width="15%"></td>
		<td class="altoDefinido24 textoBlanco">Efectivo cobrado:</td>
		<td class="altoDefinido24 textoBlanco" align="right">$ '.number_format($efectivo,decimales).'</td>
		<td class="altoDefinido24" width="15%"></td>
	</tr>
	<tr>
		<td class="altoDefinido24" width="15%"></td>
		<td class="altoDefinido24 textoBlanco">Retiro de efectivo:</td>
		<td class="altoDefinido24 textoBlanco" align="right">$ '.number_format($retiros,decimales).'</td>
		<td class="altoDefinido24" width="15%"></td>
	</tr>
	<tr>
		<td class="altoDefinido24" width="15%"></td>
		<td class="altoDefinido24 textoBlanco">Total de efectivo:</td>
		<td class="altoDefinido24 textoBlanco" align="right">$ '.number_format($totalEfectivo,decimales).'</td>
		<td class="altoDefinido24" width="15%"></td>
	</tr>
	<tr>
		<td class="altoDefinido24" width="15%"></td>
		<td class="altoDefinido24 textoBlanco">Efectivo reportado:</td>
		<td class="altoDefinido24 textoBlanco" align="right">$ '.number_format($sumaDenominaciones,decimales).'</td>
		<td class="altoDefinido24" width="15%"></td>
	</tr>
	<tr>
		<td class="altoDefinido24" width="15%"></td>
		<td class="altoDefinido24 textoBlanco">FALTANTE/SOBRANTE:</td>
		<td class="altoDefinido24 textoBlanco" align="right">$ '.number_format($sumaDenominaciones-$totalEfectivo,decimales).'</td>
		<td class="altoDefinido24" width="15%"></td>
	</tr>
	
	<tr>
		<td colspan="4" class="altoDefinido40 textoBlanco" align="center">
		&nbsp;
		</td>
	</tr>
	
	<tr>
		<td colspan="4" class="altoDefinido24 textoBlanco" align="center">
			<div style="line-height: 4.5vh; background-color: #000; height:5vh; width:150px;" onclick="registrarArqueo()">
				TERMINAR
			</div>
		</td>
	</tr>';
		
}
else
{
	echo '
	<tr>
		<td class="informacionProducto totalRenglonListaTiendas textoCentrado">Sin arqueo</td>
	</tr>';
}

echo '</table>';

