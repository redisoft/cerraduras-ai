<?php
if($concepto!=null)
{
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2" class="encabezadoPrincipal">
				Detalles de póliza
				
				'.($concepto->cancelada=='1'?'<i>(Cancelada)</i>':'').'
				
				<img src="'.base_url().'img/close.png" title="Cerrar" onclick="obtenerPolizas()" width="30"/>
			</th>
		</tr>
		<tr>
			<td class="key">Póliza:</td>
			<td>'.obtenerTipoPoliza($concepto->tipo).'</td>
		</tr>
		<tr>
			<td class="key">Folio:</td>
			<td>'.($concepto->numero).'</td>
		</tr>
		<tr>
			<td class="key">Fecha:</td>
			<td>
				'.obtenerFechaMesCorto($concepto->fecha).'
				
				<input type="hidden"  id="txtIdConcepto" name="txtIdConcepto" value="'.$concepto->idConcepto.'"/>
				<input type="hidden"  id="txtNumeroPartidas" name="txtNumeroPartidas" value="'.count($transacciones).'"/>
			</td>
		</tr>
		<tr>
			<td class="key">Concepto:</td>
			<td>'.$concepto->concepto.'</td>
		</tr>
	</table>';
}

echo '
<table class="admintable" width="100%" style="margin-top: 10px" id="tablaPartidas">
	<tr>
		<th colspan="6" class="encabezadoPrincipal">Detalles de partidas</th>
	</tr>
	
	<tr>
		<td colspan="4" class="totales" align="right">Sumas iguales</th>
		<td align="right" class="totales" id="lblCargo">$0.00</th>
		<td align="right" class="totales" id="lblAbono">$0.00</th>
	</tr>
	
	<tr>
		<td colspan="4"  class="totales" align="right">Diferencia</th>
		<td align="right" class="totales" id="lblDiferencia">$0.00</th>
		<td></th>
	</tr>
	
	<tr>
		<th>Partida</th>
		<th>Número de cuenta</th>
		<th>Nombre cuenta</th>
		<th>Concepto del movimiento</th>
		<th>Cargo</th>
		<th>Abono</th>
	<tr>';

if($transacciones!=null)
{
	$par=0;
	foreach($transacciones as $row)
	{
		echo '
		<tr id="filaPartida'.$par.'" '.($par%2>0?'class="sombreado"':'class="sinSombra"').'>
			<td align="center" id="numeroPartida'.$par.'">'.($par+1).'</td>
			<td align="center">'.$row->numeroCuenta.'</td>
			<td align="center">'.$row->descripcion.'</td>
			<td align="center">'.$row->concepto.'</td>
			<td align="right">$'.$row->debe.'</td>
			<td align="right">'.$row->haber.'</td>
			
			<input type="hidden" id="txtPartida'.$par.'"	 		name="txtPartida'.$par.'" 			value="'.$par.'" />
			<input type="hidden" id="txtIdCuentaCatalogo'.$par.'" 	name="txtIdCuentaCatalogo'.$par.'" 	value="'.$row->idCuentaCatalogo.'" />
			
			<input type="hidden" value="'.$row->debe.'" 	id="txtCargo'.$par.'" name="txtCargo'.$par.'" maxlength="15"/>
			<input type="hidden" value="'.$row->haber.'" 	id="txtAbono'.$par.'" name="txtAbono'.$par.'" maxlength="15"/>
			
		</tr>';
		
		$par++;
	}
}

echo '</table>';
?>
