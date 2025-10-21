<?php
/*if($totalProceso==null)
{
	$totalProceso=0;
}
else
{
	
}

if($prioridad==1) // La prioridad es uno, se toma como base la cantidad total a producir
{
	$cantidadTotal=$orden->cantidad;
}
else // La prioridad es mayor uno, se toma como base la cantidad total del proceso pasado
{
	$totalProcesoPasado		=$this->ordenes->obtenerTotalProceso($procesoAnterior);
	
	if($totalProcesoPasado==null)
	{
		$cantidadTotal=0;
	}
}*/


echo'
<table class="admintable" width="100%">
	<tr>
		<td class="key">Personal:</td>
		<td>
			<select class="cajas" id="selectPersonal" name="selectPersonal" style="width:250px">
				<option value="0">Seleccione</option>';
			
			foreach($personal as $row)
			{
				echo '<option value="'.$row->idPersonal.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Superviso:</td>
		<td>
			<input type="text" name="txtSuperviso" id="txtSuperviso" class="cajas" style="width:160px;" value="'.$this->session->userdata('nombreUsuarioSesion').'"  /> 
		</td>
	</tr>
	<tr>
		<td class="key">Fecha:</td>
			<td>
			<input readonly="readonly" name="txtFechaProducido" id="txtFechaProducido" type="text" class="cajas" style="width:100px" value="'.date("Y-m-d").'" />
	  	</td>
	</tr>	
	<tr>
	  <td class="key">Cantidad:</td>
	  <td>
		  <input type="text" class="cajasSelect" name="txtCantidadProducido" id="txtCantidadProducido" onkeypress="return soloDecimales(event)" />
	  </td>
	</tr>	
</table>
<script>
	$("#txtFechaProducido").datepicker({ changeMonth: true });
</script>';
