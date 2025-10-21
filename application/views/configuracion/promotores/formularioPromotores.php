<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Promotor:</td>
		<td>
			<select name="selectPromotores" id="selectPromotores" class="cajas" style="width:300px" onchange="obtenerPromotoresAsignados()">';
			
			foreach($promotores as $row)
			{
				echo '<option value="'.$row->idUsuario.'">'.$row->nombre.'</option>';
			}
				
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Campaña:</td>
		<td>
			<select name="selectCampanas" id="selectCampanas" class="cajas" style="width:300px" onchange="obtenerPromotoresAsignados()">';
			
			foreach($campanas as $row)
			{
				echo '<option value="'.$row->idCampana.'">'.$row->nombre.'</option>';
			}
				
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Prospectos asignados:</td>
		<td>
			<label id="obtenerPromotoresAsignados">0</label>
			<input type="hidden" name="txtAsignados" id="txtAsignados" value="0"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Meta:</td>
		<td>
			<input type="text" class="cajas" name="txtMeta" id="txtMeta" style="width:50px" onkeypress="return soloNumerico(event)" maxlength="4"/>
		</td>
	</tr>
</table>';