<?php
if(!empty ($puestos))
{
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaPuestos tr:even").addClass("sombreado");
		$("#tablaPuestos tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<div id="procesandoInformacion"></div>
	
    <table width="100%" class="admintable" id="tablaPuestos">
		 <tr >
			<th class="encabezadoPrincipal" width="5%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" align="center">Nombre</th>
			<th class="encabezadoPrincipal" width="15%" align="center" valign="middle">Acciones</th>
		 </tr>';

	$i=1;
	foreach($puestos as $row)
	{
		echo '
		<tr>
			<td align="center">'.$i.'</td>
			<td align="center" valign="middle">'.$row->nombre.'</td>
			<td align="center" class="vinculos" valign="middle">';

				echo '
				<img id="btnEditarPuesto'.$i.'" onclick="accesoEditarPuestoNomina('.$row->idPuesto.')" src="'.base_url().'img/editar.png" title="Editar puesto">
				&nbsp;
				<img id="btnBorrarPuesto'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar puesto '.$row->nombre.'" onclick="accesoBorrarPuestoNomina('.$row->idPuesto.')" /><br />
				<a id="a-btnEditarPuesto'.$i.'">Editar</a>
				<a id="a-btnBorrarPuesto'.$i.'">Borrar</a>';

				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarPuesto'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarPuesto'.$i.'\');
					</script>';
				}		
		echo '
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
		Sin registro de puestos
	</div>';
}
?>
