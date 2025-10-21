<?php
if(!empty($departamentos))
{
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaDepartamentos tr:even").addClass("sombreado");
		$("#tablaDepartamentos tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<div id="procesandoInformacion"></div>
	
    <table width="100%" class="admintable" id="tablaDepartamentos" >
		 <tr >
			<th class="encabezadoPrincipal" width="5%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" align="center">Nombre</th>
			<th class="encabezadoPrincipal" width="15%" align="center" valign="middle">Acciones</th>
		 </tr>';

	$i=1;
	foreach($departamentos as $row)
	{
		echo '
		<tr>
			<td align="center">'.$i.'</td>
			<td align="center" valign="middle">'.$row->nombre.'</td>
			<td align="center" class="vinculos" valign="middle">
			
				<img id="btnEditarDepartamento'.$i.'" onclick="accesoEditarDepartamentoNomina('.$row->idDepartamento.')" src="'.base_url().'img/editar.png" title="Editar departamento">
				&nbsp;
				<img id="btnBorrarDepartamento'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar departamento '.$row->nombre.'" onclick="accesoBorrarDepartamentoNomina('.$row->idDepartamento.')" /><br />
				<a id="a-btnEditarDepartamento'.$i.'">Editar</a>
				<a id="a-btnBorrarDepartamento'.$i.'">Borrar</a>';
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarDepartamento'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarDepartamento'.$i.'\');
					</script>';
				}
					
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:99%; margin-bottom: 5px;">
		Sin registro de departamentos
	</div>';
}
?>
