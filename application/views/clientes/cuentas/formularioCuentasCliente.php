<?php
echo'
<table class="admintable" width="100%">
	<tr>
	  <td class="key">Banco:</td>
	  <td>
		<select class="cajas" id="selectBancos" name="selectBancos">';
			foreach($bancos as $row)
			{
				print('<option value="'.$row->idBanco.'">'.$row->nombre.'</option>');	
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
					echo '<option value="'.$row->idEmisor.'">('.$row->rfc.')'.$row->nombre.'</option>';	
				}
				
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
	  <td class="key">No. Cuenta:</td>
	  <td>
		<input name="txtCuenta" id="txtCuenta" type="text" class="cajasNormales" style="width:70%" />
	  </td>
	</tr>	
	<tr>
	  <td class="key">Clabe:</td>
	  <td>
	 	<input name="txtClabe" id="txtClabe" type="text" class="cajasNormales" style="width:70%" maxlength="18" onkeypress="return soloNumerico(event)" />
	  </td>
	</tr>	
</table>';