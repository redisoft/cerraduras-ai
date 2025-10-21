<?php
echo
'<form id="frmEditarServicio" name="frmEditarServicio">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Nombre:</td>
			<td>
				<input type="text" class="cajas" style="width:500px" id="txtNombreServicio" name="txtNombreServicio" value="'.$servicio->nombre.'" />
				
				<input type="hidden" id="txtIdServicio" name="txtIdServicio" value="'.$servicio->idServicio.'" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Código:</td>
			<td>
				<input type="text" name="txtCodigo" id="txtCodigo" class="cajas" style="width:200px" value="'.$servicio->codigoInterno.'" /> 
			</td>
		</tr>
		
		<tr>
			<td class="key">Unidad:</td>
			<td>
				<select name="selectUnidad" id="selectUnidad" class="cajas" style="width:200px">';
				
				foreach($unidades as $row)
				{
					echo '<option '.($row->idUnidad==$servicio->idUnidad?'selected="selected"':'').' value="'.$row->idUnidad.'">'.$row->descripcion.'</option>';
				}
					
				echo'
				</select>
				
			</td>
		</tr>
		
	</table>
</form>';
