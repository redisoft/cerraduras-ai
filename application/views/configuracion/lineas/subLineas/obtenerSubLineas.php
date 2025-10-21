
<?php

echo '
<input type="hidden" name="txtIdLinea" id="txtIdLinea" value="'.$linea->idLinea.'" />
<table width="100%" class="admintable" >
	<tr>
		<td class="key">Línea: </td>
		<td>'.$linea->nombre.'</td>
	</tr>
</table>';

if(!empty ($sublineas))
{
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="50%" align="center">Nombre</th>
			<th class="encabezadoPrincipal" width="17%" align="center">Acciones</th>
		</tr>';

	$i=1;
	foreach ($sublineas as $row)
	{
		echo '
		<tr '.($i%2>0?' class="sinSombra" ':' class="sombreado"').'>
		<td align="center"'.$i.'</td>
		<td align="center" valign="middle">'.$row->nombre.'</td>
		<td align="center" valign="middle">';
			
			echo '
			<img id="btnEditarSubLinea'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" border="0" title="Conversiones" onClick="accesoEditarSubLinea('.$row->idSubLinea.')" >
            
            &nbsp;&nbsp;
           
			<img id="btnBorrarSubLinea'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" title="Borrar" onClick="accesoBorrarSubLinea('.$row->idSubLinea.')" >
            <br />
			<a id="a-btnEditarSubLinea'.$i.'">Editar</a>
            <a id="a-btnBorrarSubLinea'.$i.'">Borrar</a>';
		
			if($permiso[2]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarSubLinea'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarSubLinea'.$i.'\');
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
	echo
	'<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
		No se encontraron registros.
	</div>';
}
?>