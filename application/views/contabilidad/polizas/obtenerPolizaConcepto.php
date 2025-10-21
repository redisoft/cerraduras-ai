<form id="frmGuardarConcepto" name="frmGuardarConcepto">
<?php
if($concepto!=null)
{
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2" class="encabezadoPrincipal">
				Detalles de póliza
				'.($concepto->cancelada=='0'?'
				<img src="'.base_url().'img/guardar.png" title="Guardar" onclick="guardarConcepto()" id="btnGuardarConcepto"  width="30"/>
				<img src="'.base_url().'img/xml.png" title="Guardar" onclick="obtenerComprobantesConcepto('.$concepto->idConcepto.')" id="btnXmlConcepto"  width="30"/>
				<!--<img src="'.base_url().'img/cancelame.png" title="Cancelar" onclick="cancelarPolizaConcepto('.$concepto->idConcepto.')" id="btnCancelarPolizaConcepto"  width="30"/>
				<img src="'.base_url().'img/borrar.png" title="Borrar" onclick="borrarPolizaConcepto('.$concepto->idConcepto.')" id="btnBorrarPolizaConcepto"  width="30"/>':'').'-->
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
				<input type="text" class="cajas" id="txtFechaConcepto" name="txtFechaConcepto" style="width:100px" value="'.$concepto->fecha.'" readonly="readonly"/>
				'.($concepto->cancelada=='0'?'<script>$("#txtFechaConcepto").datepicker()</script>':'').'
				
				<input type="hidden"  id="txtIdConcepto" name="txtIdConcepto" value="'.$concepto->idConcepto.'"/>
				<input type="hidden"  id="txtNumeroPartidas" name="txtNumeroPartidas" value="'.count($transacciones).'"/>
			</td>
		</tr>
		<tr>
			<td class="key">Concepto:</td>
			<td>
				<textarea maxlength="300" '.($concepto->cancelada=='1'?'readonly="readonly"':'').' class="TextArea" id="txtConceptoPoliza" name="txtConceptoPoliza" style="height:40px; width:200px;">'.$concepto->concepto.'</textarea>
			</td>
		</tr>
	</table>';
}

echo '
<table class="admintable" width="100%" style="margin-top: 10px" id="tablaPartidas">
	<tr>
		<th colspan="7" class="encabezadoPrincipal">Detalles de partidas</th>
	</tr>
	
	<tr>
		<td colspan="5" class="totales" align="right">Sumas iguales</th>
		<td align="right" class="totales" id="lblCargo">$0.00</th>
		<td align="right" class="totales" id="lblAbono">$0.00</th>
	</tr>
	
	<tr>
		<td colspan="5"  class="totales" align="right">Diferencia</th>
		<td align="right" class="totales" id="lblDiferencia">$0.00</th>
		<td></th>
	</tr>
	
	<tr>
		<th width="3%">'.($concepto->cancelada=='0'?'<img src="'.base_url().'img/add.png" width="23" onclick="cargarPartida()" />':'').'</th>
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
			<td align="center">'.($concepto->cancelada=='0'?'<img src="'.base_url().'img/borrar.png" width="22" onclick="quitarPartida('.$par.')" />':'').'</td>
			<td align="center" id="numeroPartida'.$par.'">'.($par+1).'</td>
			<td align="center"><input type="text" '.($concepto->cancelada=='1'?'readonly="readonly"':'').' 	value="'.$row->numeroCuenta.'" class="cajas" id="txtBuscarNumeroCuenta'.$par.'" name="txtBuscarNumeroCuenta'.$par.'" style="width:150px" /></td>
			<td align="center"><input type="text" '.($concepto->cancelada=='1'?'readonly="readonly"':'').'	value="'.$row->descripcion.'" class="cajas" id="txtBuscarNombreCuenta'.$par.'" name="txtBuscarNombreCuenta'.$par.'" style="width:150px" /></td>
			<td align="center"><input type="text" '.($concepto->cancelada=='1'?'readonly="readonly"':'').'	value="'.$row->concepto.'" class="cajas" id="txtConcepto'.$par.'" name="txtConcepto'.$par.'" style="width:200px" maxlength="300"/></td>
			<td align="right"><input type="text" '.($concepto->cancelada=='1'?'readonly="readonly"':'').'	value="'.$row->debe.'" class="cajas cajasDerecha" id="txtCargo'.$par.'" name="txtCargo'.$par.'" style="width:100px" 	onchange="sumarPartidas()" onkeypress="return soloDecimales(event)" maxlength="15"/></td>
			<td align="right"><input type="text" '.($concepto->cancelada=='1'?'readonly="readonly"':'').'	value="'.$row->haber.'" class="cajas cajasDerecha" id="txtAbono'.$par.'" name="txtAbono'.$par.'" style="width:100px" 	onchange="sumarPartidas()" onkeypress="return soloDecimales(event)" maxlength="15"/></td>
			
			<input type="hidden" id="txtPartida'.$par.'"	 		name="txtPartida'.$par.'" 			value="'.$par.'" />
			<input type="hidden" id="txtIdCuentaCatalogo'.$par.'" 	name="txtIdCuentaCatalogo'.$par.'" 	value="'.$row->idCuentaCatalogo.'" />
		</tr>
		
		<script>
	$(document).ready(function()
	{
		$("#txtBuscarNumeroCuenta'.$par.'").autocomplete(
		{
			source:"'.base_url().'contabilidad/obtenerCuentasContablesFiltro/numeroCuenta",
			
			select:function( event, ui)
			{
				$("#txtIdCuentaCatalogo'.$par.'").val(ui.item.idCuentaCatalogo)
				
				window.setTimeout(function() 
				{
					$("#txtBuscarNumeroCuenta'.$par.'").val(ui.item.numeroCuenta)
					$("#txtBuscarNombreCuenta'.$par.'").val(ui.item.descripcion)
				}, 100);  
			}
		});
		
		$("#txtBuscarNombreCuenta'.$par.'").autocomplete(
		{
			source:"'.base_url().'contabilidad/obtenerCuentasContablesFiltro/nombreCuenta",
			
			select:function( event, ui)
			{
				$("#txtIdCuentaCatalogo'.$par.'").val(ui.item.idCuentaCatalogo)
				
				window.setTimeout(function() 
				{
					$("#txtBuscarNumeroCuenta'.$par.'").val(ui.item.numeroCuenta)
					$("#txtBuscarNombreCuenta'.$par.'").val(ui.item.descripcion)
				}, 100);  		
			}
		});
	});
	
	</script>';
		
		$par++;
	}
}

echo '</table>';
?>
</form>
