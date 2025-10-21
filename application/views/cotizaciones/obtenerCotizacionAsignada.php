<?php
echo'
<div class="ui-state-error" ></div>
<form id="frmAsignada" name="frmAsignada">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Cliente</td>
			<td>'.$cliente->empresa.'</td>
		</tr>	
		<tr>
			<td class="key">Serie:</td>
			<td>'.$cotizacion->serie.'</td>
		</tr>	
		
		<tr>
			<td class="key">Total:</td>
			<td>$'.number_format($cotizacion->total,2).'</td>
		</tr>	
	
		<tr>
			<td class="key">Fecha</td>
			<td>
				<input type="text" class="cajas" name="txtFechaAsignacion" id="txtFechaAsignacion" style="width:100px" value="'.date('Y-m-d').'" />
				<input type="hidden" name="txtIdCotizacion" id="txtIdCotizacion" value="'.$cotizacion->idCotizacion.'" />
				<script>
					$("#txtFechaAsignacion").datepicker();
				</script>
			</td>
		</tr>
		
		<tr>
			<td class="key">Motivos:</td>
			<td>
				<div id="obtenerMotivos" style="float:left">
					<select class="cajas" id="selectMotivos" name="selectMotivos" style="width:250px">
						<option value="0">Seleccione</option>'; 
						
						foreach($motivos as $row) 
						{
							echo '<option value="'.$row->idMotivo.'">'.$row->nombre.'</option>';  
						}
						
					echo'
					</select>
				</div>
				
				<img src="'.base_url().'img/add.png" width="18" onclick="obtenerListaMotivos()" title="Colores" style="float:left; margin-left: 5px"/>
			</td>
		</tr>
	</table>
</form>';

