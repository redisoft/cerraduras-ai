<form id="frmVehiculos">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Modelo:</td>
			<td>
				<input type="text" class="cajas" id="txtModelo" name="txtModelo" value="<?php echo $registro->modelo?>"  style="width:300px"  />
				<input type="hidden" id="txtIdVehiculo" name="txtIdVehiculo" value="<?php echo $registro->idVehiculo?>"  />
			</td>
		</tr>
		
		<tr>
			<td class="key">Marca:</td>
			<td>
				<input type="text" class="cajas" id="txtMarca" name="txtMarca" value="<?php echo $registro->marca?>"  style="width:300px"  />
			</td>
		</tr>
	</table>
</form>