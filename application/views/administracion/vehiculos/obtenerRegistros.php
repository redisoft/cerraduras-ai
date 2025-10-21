<?php
if(!empty ($registros))
{
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaVehiculos tr:even").addClass("sombreado");
		$("#tablaVehiculos tr:odd").addClass("sinSombra");  
	});
	</script>
	<br>

	<div id="procesandoInformacion"></div>
	
    <table width="100%" class="admintable" id="tablaVehiculos">
		 <tr>
			<th class="encabezadoPrincipal" width="5%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" align="center">Modelo</th>
			<th class="encabezadoPrincipal" align="center">Marca</th>
			<th class="encabezadoPrincipal" width="15%" align="center" valign="middle">Acciones</th>
		 </tr>';

	$i=1;
	foreach($registros as $row)
	{
		echo '
		<tr>
			<td align="center">'.$i.'</td>
			<td align="center" valign="middle">'.$row->modelo.'</td>
			<td align="center" valign="middle">'.$row->marca.'</td>
			<td align="center" class="vinculos" valign="middle">';

				echo '
				<img id="btnEditar'.$i.'" onclick="accesoEditarVehiculo('.$row->idVehiculo.')" src="'.base_url().'img/editar.png" title="Editar puesto">
				&nbsp;
				<img id="btnBorrar'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar registro" onclick="accesoBorrarVehiculo('.$row->idVehiculo.')" /><br />
				<a id="a-btnEditar'.$i.'">Editar</a>
				<a id="a-btnBorrar'.$i.'">Borrar</a>';

				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditar'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrar'.$i.'\');
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
	<div class="Error_validar" style="margin-top:2px; width:99%; margin-top: 20px;">
		Sin registros
	</div>';
}
?>
