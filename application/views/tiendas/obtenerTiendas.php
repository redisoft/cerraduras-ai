<?php
if($tiendas!=null)
{
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaTiendas tr:even").addClass("sombreado");
		$("#tablaTiendas tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<table class="admintable" width="100%" id="tablaTiendas">	
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Nombre</th>
			<th class="encabezadoPrincipal">Teléfono</th>
			<th class="encabezadoPrincipal">Email</th>
			<th class="encabezadoPrincipal">Acciones</th>
		</tr>';
	
	$i=1;
	foreach($tiendas as $row)	
	{
		echo '
		<tr>
			<td align="right">'.$i.'</td>
			<td>'.$row->nombre.'</td>
			<td>'.$row->telefono.'</td>
			<td>'.$row->email.'</td>
			<td class="vinculos" align="center">
				<img id="btnEditarTienda'.$i.'" src="'.base_url().'img/editar.png" onclick="accesoEditarTienda('.$row->idTienda.')" title="Editar tienda" />
				&nbsp;&nbsp;
				<img id="btnRegistrarTienda'.$i.'" src="'.base_url().'img/borrar.png" onclick="accesoBorrarTienda('.$row->idTienda.')" title="Borrar tienda" />
				<br />
				<a id="a-btnEditarTienda'.$i.'">Editar</a>
				<a id="a-btnRegistrarTienda'.$i.'">Borrar</a>';
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarTienda'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarTienda'.$i.'\');
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
	echo '<div class="Error_validar">Sin registro de tiendas</div>';
}