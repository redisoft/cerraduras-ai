<?php
echo'

<div id="registrandoInformacion"></div>
<form id="frmAsociarCuenta" name="frmAsociarCUenta">

	<table class="admintable" id="tablaFormularioCuenta" width="100%">
		<tr>
			<td width="35%">
				Grupo
				<select class="cajas" id="selectGruposCuenta" name="selectGruposCuenta" style="width:200px" onchange="obtenerCuentasRegistro()">';
				
				foreach($tiposCuenta as $row)
				{
					echo '<option '.($grupo==$row->cuenta?'selected="selected"':'').'>'.$row->cuenta.'</option>';
				}
				
				echo'
				</select>
			</td>
			<td width="25%" id="obtenerCuentasRegistro">
			
			<select class="cajas" id="selectCuentasRegistro" name="selectCuentasRegistro" style="width:200px" onchange="obtenerSubCuentasRegistro()">
				<option value="0">Seleccione cuenta</option>';
				
				foreach($cuentas as $row)
				{
					echo '<option value="'.$row->idCuenta.'">'.$row->codigo.', '.$row->nombre.'</option>';
				}
				
				echo'
			</select>
			
			</td>
			<td width="15%" id="obtenerSubCuentasRegistro">
				<select class="cajas" id="selectSubCuentasRegistro" name="selectSubCuentasRegistro" style="width:200px">
					<option value="0">Seleccione subcuenta</option>';
				
					echo'
				</select>
			</td>
			<!--<td width="25%">Agregar cuenta contable</td>-->
		</tr>		
	</table>
	
	<div id="obtenerCuentasCatalogoAsociar"></div>
	
</form>';
