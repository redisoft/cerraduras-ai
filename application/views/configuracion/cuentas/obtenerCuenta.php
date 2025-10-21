<script>
$(document).ready(function()
{
	$("#txtBuscarCliente").autocomplete(
	{
		source:base_url+'configuracion/obtenerClientes',
		
		select:function( event, ui)
		{
			$('#txtIdCliente').val(ui.item.idCliente);
		}
	});
});
</script>
<?php
echo'
<table class="admintable" width="100%">
	<tr>
		<td class="key">Banco:</td>
		<td>
			<select class="cajas" id="selectBancos" name="selectBancos">';
			foreach($bancos as $row)
			{
				echo '<option '.($row->idBanco==$cuenta->idBanco?'selected="selected"':'').' value="'.$row->idBanco.'">'.$row->nombre.'</option>';	
			}
			
			echo'
			</select>
		</td>
	</tr>	
	
	<tr>
		<td class="key">Emisor:</td>
		<td>
			<select class="cajas" id="selectEmisores" name="selectEmisores" style="width:400px">
				<option value="0">Seleccione</option>';
			
				foreach($emisores as $row)
				{
					echo '<option '.($row->idEmisor==$cuenta->idEmisor?'selected="selected"':'').' value="'.$row->idEmisor.'">('.$row->rfc.')'.$row->nombre.'</option>';	
				}
			
			echo'
		</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">No. Cuenta:</td>
		<td>
			<input name="txtCuenta" value="'.$cuenta->cuenta.'" id="txtCuenta" type="text" class="cajasNormales" style="width:40%" />
			<input name="txtIdCuenta" value="'.$cuenta->idCuenta.'" id="txtIdCuenta" type="hidden"/>
		</td>
	</tr>	
	<tr>
		<td class="key">Clabe:</td>
		<td>
			<input name="txtClabe" value="'.$cuenta->clabe.'" id="txtClabe" type="text" class="cajasNormales" style="width:40%" maxlength="18" onkeypress="return soloNumerico(event)" />
		</td>
	</tr>	
	
	<tr>
		<td class="key">Tarjeta de crédito:</td>
		<td>
			<input name="txtTarjetaCredito" value="'.$cuenta->tarjetaCredito.'" id="txtTarjetaCredito" type="text" class="cajasNormales" style="width:40%"  onkeypress="return soloNumerico(event)"  />
		</td>
	</tr>
	
	<tr>
		<td class="key">Cuenta contable:</td>
		<td>
			<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:300px" placeholder="'.$cliente->cuenta.'" value="'.$cuenta->cuentaContable.'" readonly="readonly" />
				<input type="hidden" id="txtIdCuentaCatalogo" name="txtIdCuentaCatalogo" value="'.$cuenta->idCuentaCatalogo.'" />
			
			<label style="cursor:pointer; float:right; margin-right: 300px" onclick="formularioAsociarCuenta()" title="Agregar cuenta" >
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/contabilidad.png" width="28"  /><br />
				Agregar cuenta
			</label>
		</td>
	</tr>
	
	<tr>
		<td class="key">Saldo inicial:</td>
		<td>
			<input name="txtSaldoInicial" id="txtSaldoInicial" type="text" class="cajasNormales" style="width:20%" maxlength="15" onkeypress="return soloDecimales(event)" value="'.round($cuenta->saldoInicial,decimales).'"  />
		</td>
	</tr>
		
	<tr>
		<td class="key">Visible en reportes:</td>
		<td>
			<input name="chkReportes" id="chkReportes" type="checkbox" '.($cuenta->reportes=='1'?'checked="checked"':'').' />
		</td>
	</tr>';	
	
	#if(sistemaActivo=='IEXE')
	{
		echo'
		<tr '.(sistemaActivo!='IEXE'?'style="display:none"':'').'>
			<td class="key">Dashboard default:</td>
			<td>
				<input name="chkDefault" id="chkDefault" type="checkbox" '.($cuenta->dashboard=='1'?'checked="checked"':'').' />
			</td>
		</tr>
		';
	}
	
echo'
</table>';