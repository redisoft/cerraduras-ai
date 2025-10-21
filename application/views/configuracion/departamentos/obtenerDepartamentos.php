<?php
#if(!empty ($departamentos))
{
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<td colspan="3" class="sinBorde">
				<ul class="menuTabs">
					<li class="sinMargen" onclick="window.location.href=\''.base_url().'configuracion/lineas\'">Líneas</li>
					<li class="activado sinMargen">Departamentos</li>
					<li class="sinMargen" onclick="window.location.href=\''.base_url().'catalogos/marcas\'">Marcas</li>
				</ul>
			</td>
		</tr>
		 <tr >
			<th class="encabezadoPrincipal" width="10%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="50%" align="center">Departamento</th>
			<th class="encabezadoPrincipal" width="11%" align="center">Acciones</th>
		 </tr>';
	$i=1;
	foreach ($departamentos as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="center">'.$i.'</td>
			<td align="center" valign="middle">'.$row->nombre.'</td>
			<td align="left" valign="middle">
		
				&nbsp;
				<img id="btnEditarDepartamento'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22"  onClick="obtenerDepartamento('.$row->idDepartamento.')" >
				&nbsp;&nbsp;
				<img id="btnBorrarDepartamento'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onClick="borrarDepartamento('.$row->idDepartamento.')" ></a>

				<br />
				
				<a id="a-btnEditarDepartamento'.$i.'">Editar</a>
				<a id="a-btnBorrarDepartamento'.$i.'">Borrar</a>';

				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarDepartamento'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0 )
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

	echo ' </table>';
}
/*else
{
	echo'
	<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
		No se encontraron registros.
	</div>';
}*/
?>
