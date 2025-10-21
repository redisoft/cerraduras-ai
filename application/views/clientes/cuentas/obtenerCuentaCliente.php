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
		<input name="txtCuenta" value="'.$cuenta->cuenta.'" id="txtCuenta" type="text" class="cajasNormales" style="width:70%" />
		<input name="txtIdCuenta" value="'.$idCuenta.'" id="txtIdCuenta" type="hidden"/>
	  </td>
	</tr>	
	<tr>
	  <td class="key">Clabe:</td>
	  <td>
		<input name="txtClabe" value="'.$cuenta->clabe.'" id="txtClabe" type="text" class="cajasNormales" style="width:70%" maxlength="18" onkeypress="return soloNumerico(event)" />
	  </td>
	</tr>	
</table>';