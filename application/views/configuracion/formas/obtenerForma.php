<?php
echo '
<input value="'.$forma->idForma.'" id="txtIdForma" type="hidden" />
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtNombre" style="width:300px" value="'.$forma->nombre.'" id="txtNombre" type="text" class="cajas"  />
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input name="txtFecha" style="width:100px" value="'.$forma->fecha.'"  id="txtFecha" type="text" class="cajas"  readonly="readonly" />
			<script>
				$("#txtFecha").datepicker();
			</script>
		</td>
	</tr>
	
	<tr>
		<td class="key">Porcentaje:</td>
		<td>
			<input name="txtNombre" style="width:100px" value="'.$forma->porcentaje.'" id="txtPorcentaje" type="text" class="cajas"  onkeypress="return soloDecimales(event)" maxlength="5" />
		</td>
	</tr>	
	
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<select class="cajas" id="selectCuentas" name="selectCuentas" style="width:200px">';
			
			foreach($cuentas as $row)
			{
				echo '<option '.($row->idCuenta==$forma->idCuenta?'selected="selected"':'').' value="'.$row->idCuenta.'">'.$row->cuenta.', '.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>	
</table>';
?>