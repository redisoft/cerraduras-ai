<script>
$('#txtBuscarNota').keypress(function(e)
	{
	if(e.which == 13) 
	{
		obtenerVentaEnvio();

		 e.preventDefault();
		return false;
	}
});
</script>
<form id="frmRegistro" action="javascript:registrarRegistroEnvios()">
	<input type="hidden" id="txtNumeroEnvios" name="txtNumeroEnvios" value="0" />

	<table class="admintable" width="100%">
		<tr>
			<td class="key">Chofer:</td>
			<td>
				<select name="selectPersonalEnvio" id="selectPersonalEnvio" class="cajas" style="width: 200px" required="true">
					<option value="">Seleccione</option>
					<?php
					foreach($personal as $row)
					{
						echo '<option value="'.$row->idPersonal.'">'.$row->nombre.'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="key">Vehículo:</td>
			<td>
				<select name="selectVehiculoEnvio" id="selectVehiculoEnvio" class="cajas" style="width: 200px" required="true">
					<option value="">Seleccione</option>
					<?php
					foreach($vehiculos as $row)
					{
						echo '<option value="'.$row->idVehiculo.'">'.$row->modelo.', '.$row->marca.'</option>';
					}
					?>
				</select>
			</td>
		</tr>
	</table>
	<br />
	<div class="text-center">
		<input type="text" id="txtBuscarNota" name="txtBuscarNota" style="width: 500px" placeholder="Buscar nota" class="cajas" />
	</div>
	<br />
	<table class="admintable" width="100%" id="tablaVentasEnvios">
		<thead>
			<tr>	
				<th width="5%">-</th>
				<th width="20%">Nota</th>
				<th width="52%">Cliente</th>
				<th width="25%">Ruta</th>
			</tr>
		</thead>
	</table>
</form>
