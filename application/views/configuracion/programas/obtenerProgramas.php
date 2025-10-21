<?php
if(!empty ($programas))
{
	echo'
	<div style="width:90%;">
		<ul id="pagination-digg" class="ajax-pagProgramas">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="35%" align="center">Nombre</th>
			<th class="encabezadoPrincipal" align="center">Periodicidad inscripción</th>
			<th class="encabezadoPrincipal" align="center">Periodicidad colegiatura</th>
			<th class="encabezadoPrincipal" align="center">Periodicidad reinscripción</th>
			
			<th class="encabezadoPrincipal" align="center">Día pago</th>
			<th class="encabezadoPrincipal" align="center">Periodo</th>
			<th class="encabezadoPrincipal" align="center">Grado</th>
			
			<th class="encabezadoPrincipal" width="12%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=$limite;
	foreach ($programas as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
        <tr <?php echo $estilo?> id="filaProgramas<?php echo $row->idPrograma?>">
            <td align="center"> <?php echo $i?> </td>
            <td align="center" valign="middle"><?php echo $row->nombre ?></td>
            <td align="center" valign="middle"><?php echo $row->cantidadInscripcion ?></td>
            <td align="center" valign="middle"><?php echo $row->cantidadColegiatura ?></td>
            <td align="center" valign="middle"><?php echo $row->cantidadReinscripcion ?></td>
            <td align="center" valign="middle"><?php echo $row->diaPago ?></td>
            <td align="center" valign="middle"><?php echo $row->periodo ?></td>
            <td align="center" valign="middle"><?php echo $row->grado ?></td>
            <td align="center" valign="middle" class="vinculos">

                <img id="btnEditarProgramas<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" title="Editar programas" onClick="accesoEditarProgramas('<?php echo $row->idPrograma?>')" >
                
                &nbsp;&nbsp;
                <img id="btnBorrarProgramas<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" title="Borrar programas" onClick="accesoBorrarProgramas(<?php echo $row->idPrograma?>)" >
                <br />
                <a id="a-btnEditarProgramas<?php echo $i?>">Editar</a>
                <a id="a-btnBorrarProgramas<?php echo $i?>">Borrar</a>
                
                <?php 

					if($permiso[2]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnEditarProgramas'.$i.'\');
						</script>';
					}
					
					if($permiso[3]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnBorrarProgramas'.$i.'\');
						</script>';
					}
				?>
            </td>
		</tr>
		
		<?php
		
		$i++;
	}
	
	echo '</table>';
	
	echo'
	<div style="width:90%;">
		<ul id="pagination-digg" class="ajax-pagProgramas">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo
	'<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
		No se encontraron registros.
	</div>';
}
?>