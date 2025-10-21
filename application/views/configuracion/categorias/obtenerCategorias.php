<?php
if(!empty ($categorias))
{
	echo '
	<table width="100%" class="admintable" >
		 <tr >
			<th class="encabezadoPrincipal" width="10%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="50%" align="center">Categoría</th>
			<th class="encabezadoPrincipal" width="11%" align="center">Acciones</th>
		 </tr>';
	$i=1;
	foreach ($categorias as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="center">'.$i.'</td>
			<td align="center" valign="middle">'.$row->nombre.'</td>
			<td align="left" valign="middle">
		
				&nbsp;
				<img id="btnEditarCategoria'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22"  onClick="obtenerCategoria('.$row->idCategoria.')" >
				&nbsp;&nbsp;
				<img id="btnBorrarCategoria'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onClick="borrarCategoria('.$row->idCategoria.')" ></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnSubCategorias'.$i.'" src="'.base_url().'img/add.png" width="22" height="22" onClick="obtenerSubCategorias('.$row->idCategoria.')" >
				<br />
				
				<a id="a-btnEditarCategoria'.$i.'">Editar</a>
				<a id="a-btnBorrarCategoria'.$i.'">Borrar</a>
				<a id="a-btnSubCategorias'.$i.'">Subcategorías</a>';

				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnSubCategorias'.$i.'\');
					</script>';
				}
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarCategoria'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0 or $row->numeroSubCategorias>0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarCategoria'.$i.'\');
					</script>';
				}
			
			
			echo'
			</td>
		</tr>';

		$i++;
	}

	echo ' </table>';
}
else
{
	echo'
	<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
		No se encontraron registros.
	</div>';
}
?>
