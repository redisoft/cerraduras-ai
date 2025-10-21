<?php
#if(!empty ($usuarios))
{
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagUsuarios">'.$this->pagination->create_links().'</ul>
	 </div>';
	?>
	<table width="100%;" class="admintable">
		<thead>
		 <tr >
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="15%" align="center">Nombre</th>
			<th class="encabezadoPrincipal" width="10%" align="center" valign="middle">Usuario</th>
			<th class="encabezadoPrincipal" width="8%" align="center" valign="middle">Vendedor</th>
			<th class="encabezadoPrincipal" width="14%" align="center" valign="middle">
            	<select class="cajas" id="selectRolBusqueda" onchange="obtenerUsuarios()" style="width:150px">
                	<option value="0">Rol</option>
                    
                    <?php
                    foreach($roles as $row)
					{
						echo '<option '.($row->idRol==$idRol?'selected="selected"':'').' value="'.$row->idRol.'">'.$row->nombre.'</option>';
					}
					?>
                </select>
            </th>
			<th class="encabezadoPrincipal" width="15%" align="center" valign="middle">Correo</th>
			<th class="encabezadoPrincipal" width="10%" align="center" valign="middle">Creado</th>
			<th class="encabezadoPrincipal" width="10%" align="center" valign="middle">Último acceso </th>
			<th class="encabezadoPrincipal" width="18%" align="center" valign="middle">Acciones</th>
		 </tr>
		</thead>
	   <tbody>
	
	<?php
	$i=$limite;
	foreach ($usuarios as $row)
	{
		?>
        
		<tr <?php echo $estilo=$i%2>0?"class='sinSombra'":'class="sombreado"';?>>
		<td align="center"> <?php echo $i ?> </td>
		<td align="center" valign="middle"><?php echo ucwords($row->nombre.' '.$row->apellidoPaterno.' '.$row->apellidoMaterno)?></td>
		<td align="center" valign="middle"><?php print($row->usuario); ?></td>
		<td align="center" valign="middle"><?php print($row->vendedor); ?></td>
		<td align="center" valign="middle"><?php print($row->rol); ?></td>
		<td align="center" valign="middle"><?php echo $row->correo;?></td>
		<td align="center" valign="middle"><?php echo obtenerFechaMesCorto($row->fechaCreacion);?></td>
		<td align="center" valign="middle"><?php echo obtenerFechaMesCortoHora($row->fechaAcceso);?></td>
		<td align="left" valign="middle">
			&nbsp;

            <a id="btnEditarUsuario<?php echo $i?>" onclick="accesoEditarUsuario(<?php echo $row->idUsuario?>)">
                <img src="<?php echo base_url()?>img/edit.png" width="22" height="22" title="Editar usuario">
            </a>
            
            &nbsp;&nbsp;&nbsp;
            <img id="btnHorariosUsuario<?php echo $i?>" onclick="obtenerHorarios(<?php echo $row->idUsuario?>)" src="<?php echo base_url()?>img/horarios.png" width="22" height="22" title="Horarios">
            
           

            <?php
			
			if($row->activo=='1')
			{
				echo '
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            	<img id="btnBorrarUsuario'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" title="Borrar usuario" onClick="confirmarBorrarUsuario('.$row->idUsuario.')" >';
			}
			else
			{
				echo '
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            	<img id="btnBorrarUsuario'.$i.'" src="'.base_url().'img/devolver.png" width="22" height="22" title="Borrar usuario" onClick="confirmarReactivarUsuario('.$row->idUsuario.')" >';
			}
			
			
            echo'
			<br />
            <a id="a-btnEditarUsuario'.$i.'">Editar</a>
            <a id="a-btnHorariosUsuario'.$i.'">Horarios</a>';
			
			if($row->activo=='1')
			{
				echo' <a id="a-btnBorrarUsuario'.$i.'">Desactivar</a>';
			}
			else
			{
				echo' <a id="a-btnBorrarUsuario'.$i.'">Reactivar</a>';
			}
            

			if($permiso[1]->activo==0 or $row->superAdmin=="1" or $row->checador=="1" or $row->activo=="0")
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnHorariosUsuario'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0 or $row->checador=="1" or $row->activo=="0")
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarUsuario'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0 or $row->superAdmin=="1" or $row->checador=="1")
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarUsuario'.$i.'\');
				</script>';
			}
			?>
			</td>
		</tr>
		
		<?php
		
		$i++;
	}
	?>
	   </tbody>
	 </table>
	<?php
	
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagUsuarios">'.$this->pagination->create_links().'</ul>
	 </div>';
}
/*else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:99%; margin-bottom: 5px;">
		No se encontraron registros.
	</div>';
}*/
?>
