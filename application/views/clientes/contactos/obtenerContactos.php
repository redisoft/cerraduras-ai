<?php
if(!empty($contactos))
{
	?>
	<table class="admintable" width="100%;" style="margin-top:20px;" >
        <tr>
            <th class="encabezadoPrincipal">#</th>
            <th class="encabezadoPrincipal">Contacto</th>
            <th class="encabezadoPrincipal">Departamento</th>
            <th class="encabezadoPrincipal">Puesto</th>
            <th class="encabezadoPrincipal">Teléfono</th>
            <th class="encabezadoPrincipal">Extensión</th>
            <th class="encabezadoPrincipal">Email</th>
            <th class="encabezadoPrincipal">Acciones</th>
        </tr>

	<?php
	$i=1;
	foreach ($contactos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo'
		<tr '.$estilo.' id="filaContacto'.$row->idContacto.'">
			<td align="center" valign="middle">'.$i.'</td>
			<td align="center" valign="middle">'.$row->nombre.'</td>
			<td align="center" valign="middle">'.$row->direccion.'</td>
			<td align="center" valign="middle">'.$row->puesto.'</td>
			<td align="center" valign="middle">';
			
			if(sistemaActivo=='IEXE')
			{
				echo '<input type="text" class="cajas" value="'.$row->telefono.'" style="width: 110px" readonly="readonly"	/>';
			}
			else
			{
				echo $row->telefono;	
			}
			
			echo'
			</td>
			<td align="center" valign="middle">'.$row->extension.'</td>
			<td align="center" valign="middle">';
			
			if(sistemaActivo=='IEXE')
			{
				echo '<input type="text" class="cajas" value="'.$row->email.'" style="width: 220px" readonly="readonly"/>';
			}
			else
			{
				echo $row->email;	
			}

			echo '</td>
			<td align="center" valign="middle">';
				
				echo '
				<img id="btnEditarContacto'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" onclick="accesoEditarContactoCliente('.$row->idContacto.')" />
				&nbsp;&nbsp;
				<img id="btnBorrarContacto'.$i.'"  onclick="accesoBorrarContactoCliente('.$row->idContacto.',\'¿Realmente desea borrar el contacto?\','.$row->idCliente.')" src="'.base_url().'img/borrar.png" width="22" height="22" title="Borrar contacto" />
				
				<br />
				<a id="a-btnEditarContacto'.$i.'">Editar</a>
				<a id="a-btnBorrarContacto'.$i.'">Borrar</a>';
				
				if($permiso[2]->activo==0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnEditarContacto'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnBorrarContacto'.$i.'\');
					</script>';
				}

			echo'
			</td>
		</tr>';
		
		$i++;
		
	}
	?>
	</table> 
	<?php
}
else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:67%;">
       No hay registro de contactos
	</div>';
}
?>