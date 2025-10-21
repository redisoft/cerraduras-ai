<?php
echo '<div class="ui-state-error" ></div>';
echo'
<table class="admintable" width="100%">
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input type="text" class="cajas" id="txtFechaTraspaso" name="txtFechaTraspaso" value="'.date('Y-m-d H:i').'" readonly="readonly" style="width:120px" />
			<script>
				$("#txtFechaTraspaso").timepicker();
			</script>
		</td>     
	</tr>
	
	<tr>
		<td class="key">Cuenta origen:</td>
		<td > 
		 <select id="selectCuentaOrigen" name="selectCuentaOrigen" class="cajas" style="width:250px;" onchange="obtenerSaldoOrigen(); obtenerCuentasDestino()" >
			<option value="0">Seleccione</option>';

			   foreach($cuentas as $row)
			   {
				   print('<option value="'.$row->idCuenta.'" >'.(strlen($row->cuenta)>0?$row->cuenta:$row->tarjetaCredito).', '.$row->nombre.'</option>');
			   }
			 
			echo'
			</select>
			<div style="padding-left:6px" id="saldoCuentaOrigen"></div>
		</td>
	</tr>
	<tr>
		<td class="key">Cuenta destino:</td>
		<td id="filaCuentaDestino">
			<select id="selectCuentaDestino" name="selectCuentaDestino" class="cajas" style="width:250px;" >
			 <option value="0">Seleccione</option>
			</select>
		</td>     
	</tr>
	
	<tr>
		<td class="key">Monto</td>
		<td>
			<input type="text" class="cajas" id="txtMonto" onkeypress="return soloDecimales(event)" maxlength="15" />
		</td>
	</tr>
	
</table>';