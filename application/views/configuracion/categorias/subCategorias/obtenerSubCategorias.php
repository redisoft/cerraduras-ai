<?php
echo '
<table width="100%" class="admintable" >
	<tr>
		<th colspan="2" class="encabezadoPrincipal">Detalles de categoría</th>
 	</tr>
	<tr>
		<td class="key">Categoría</td>
		<td>'.$categoria->nombre.'</td>
		<input type="hidden" id="txtIdCategoria" name="txtIdCategoria" value="'.$categoria->idCategoria.'" />
 	</tr>
</table>';

if(!empty ($subCategorias))
{
	echo '
	<table width="100%" class="admintable" >
		 <tr >
			<th class="encabezadoPrincipal" width="10%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="50%" align="center">Categoría</th>
			<th class="encabezadoPrincipal" width="11%" align="center">Acciones</th>
		 </tr>';
	$i=1;
	
	foreach ($subCategorias as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="center">'.$i.'</td>
			<td align="center" valign="middle">'.$row->nombre.'</td>
			<td align="center" valign="middle">
		
				
				<img id="btnEditarSubCategoria'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22"  onClick="obtenerSubCategoria('.$row->idSubCategoria.')" >
				&nbsp;
				<img id="btnBorrarSubCategoria'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onClick="borrarSubCategoria('.$row->idSubCategoria.')" ></a>
				<br />
				
				<a id="a-btnEditarSubCategoria'.$i.'">Editar</a>
				<a id="a-btnBorrarSubCategoria'.$i.'">Borrar</a>';

				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarSubCategoria'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarSubCategoria'.$i.'\');
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
