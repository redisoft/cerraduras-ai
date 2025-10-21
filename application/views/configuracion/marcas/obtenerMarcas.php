<?php
#if(!empty ($marcas))
{
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<td colspan="3" class="sinBorde">
				<ul class="menuTabs">
					<li class="sinMargen" onclick="window.location.href=\''.base_url().'configuracion/lineas\'">Líneas</li>
					<li class="sinMargen" onclick="window.location.href=\''.base_url().'catalogos/departamentos\'">Departamentos</li>
					<li class="activado sinMargen">Marcas</li>
				</ul>
			</td>
		</tr>
		 <tr >
			<th class="encabezadoPrincipal" width="10%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="50%" align="center">Marca</th>
			<th class="encabezadoPrincipal" width="11%" align="center">Acciones</th>
		 </tr>';
	$i=1;
	foreach ($marcas as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="center">'.$i.'</td>
			<td align="center" valign="middle">'.$row->nombre.'</td>
			<td align="left" valign="middle">
		
				&nbsp;
				<img id="btnEditarMarca'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22"  onClick="obtenerMarca('.$row->idMarca.')" >
				&nbsp;&nbsp;
				<img id="btnBorrarMarca'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onClick="borrarMarca('.$row->idMarca.')" ></a>

				<br />
				
				<a id="a-btnEditarMarca'.$i.'">Editar</a>
				<a id="a-btnBorrarMarca'.$i.'">Borrar</a>';

				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarMarca'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0 )
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarMarca'.$i.'\');
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
