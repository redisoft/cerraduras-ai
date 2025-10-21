<?php
echo'
<div id="errorProcesosProduccion" class="ui-state-error" ></div>
<table class="admintable" width="100%" style="margin-top:3px">
	<tr>
		<th colspan="2">
			Detalles de orden
			<input type="hidden" id="txtIdOrden" value="'.$orden->idOrden.'" />
		</th>
	</tr>
	<tr>
		<td class="key">Orden:</td>
		<td>'.$orden->orden.'</td>
	</tr>
	
	<tr>
		<td class="key">Producto:</td>
		<td>'.$orden->producto.'</td>
	</tr>
	
	<tr>
		<td class="key">Proceso</td>
		<td>
			<select class="cajas" id="selectProcesos" name="selectProcesos">
				<option value="0">Seleccione</option>';
			
				foreach($procesos as $row)
				{
					echo '<option value="'.$row->idProceso.'">'.$row->nombre.'</option>';
				}
			
			echo'
			</select>
		</td>
	</tr>
</table>';