<?php
if(!empty($contactos))
{
	?>
	<table class="admintable" width="100%;" style="margin-top:20px;" >
        <tr>
            <th class="encabezadoPrincipal">#</th>
            <th class="encabezadoPrincipal">Contacto</th>
            <th class="encabezadoPrincipal">Teléfono</th>
            <th class="encabezadoPrincipal">Extensión</th>
            <th class="encabezadoPrincipal">Email</th>
            <th class="encabezadoPrincipal">Acciones</th>
        </tr>

	<?php
	$i=1;
	
	$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
	
	echo'
	<tr '.$estilo.'>
		<td align="center" valign="middle">'.$i.'</td>
		<td align="center" valign="middle">'.$cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno.' <i>(Prospecto)</i></td>
		<td align="center" valign="middle">';
		
		if(sistemaActivo=='IEXE')
		{
			echo '
			'.(strlen($cliente->telefono)>0?'<input type="text" class="cajas" value="'.$cliente->telefono.'" style="width: 110px" readonly="readonly"	/>':'').'
			
			
			
			'.(strlen($cliente->movil)>0?'<br /><input type="text" class="cajas" value="'.$cliente->movil.'" style="width: 110px" readonly="readonly"	/>':'');
		}
		else
		{
			echo $row->telefono;	
		}
		
		echo'
		</td>
		<td align="center" valign="middle"></td>
		<td align="center" valign="middle">';
		
		echo '<input type="text" class="cajas" value="'.$cliente->email.'" style="width: 220px" readonly="readonly"/>';

		echo '</td>
		<td align="center" valign="middle">';
			
			echo '
			<img id="btnEditarContacto'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" onclick="obtenerContactoCliente('.$cliente->idCliente.')" />
			
			<br />
			<a id="a-btnEditarContacto'.$i.'">Editar</a>';
			
			if($permiso[2]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnEditarContacto'.$i.'\');
				</script>';
			}


		echo'
		</td>
	</tr>';
	
	$i++;
	
	foreach ($contactos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		
		echo'
		<tr '.$estilo.' id="filaContacto'.$row->idContacto.'">
			<td align="center" valign="middle">'.$i.'</td>
			<td align="center" valign="middle">'.$row->nombre.'</td>
			<td align="center" valign="middle">';
			
			if(sistemaActivo=='IEXE')
			{
				echo '
				<input type="text" class="cajas" value="'.$row->telefono.'" style="width: 110px" readonly="readonly"	/>
				'.(strlen($row->movil1)>0?'<br /><input type="text" class="cajas" value="'.$row->movil1.'" style="width: 110px" readonly="readonly"	/>':'').'
				'.(strlen($row->movil2)>0?'<br /><input type="text" class="cajas" value="'.$row->movil2.'" style="width: 110px" readonly="readonly"	/>':'');
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
				<img id="btnEditarContacto'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" onclick="obtenerContacto('.$row->idContacto.')" />
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