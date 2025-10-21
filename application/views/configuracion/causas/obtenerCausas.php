<?php
if(!empty ($causas))
{
	echo'
	<div style="width:90%;">
		<ul id="pagination-digg" class="ajax-pagCausas">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="40%" align="center">Nombre</th>
			<th class="encabezadoPrincipal" width="17%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=$limite;
	foreach ($causas as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
        <tr <?php echo $estilo?> id="filaCausas<?php echo $row->idCausa?>">
            <td align="center"> <?php echo $i?> </td>
            <td align="center" valign="middle"><?php echo $row->nombre ?></td>
            <td align="center" valign="middle" class="vinculos">

                <img id="btnEditarCausas<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" title="Editar causas" onClick="accesoEditarCausas('<?php echo $row->idCausa?>')" >
                
                &nbsp;&nbsp;
                <img id="btnBorrarCausas<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" title="Borrar causas" onClick="accesoBorrarCausas(<?php echo $row->idCausa?>)" >
                <br />
                <a id="a-btnEditarCausas<?php echo $i?>">Editar</a>
                <a id="a-btnBorrarCausas<?php echo $i?>">Borrar</a>
                
                <?php 

					if($permiso[2]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnEditarCausas'.$i.'\');
						</script>';
					}
					
					if($permiso[3]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnBorrarCausas'.$i.'\');
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
		<ul id="pagination-digg" class="ajax-pagCausas">'.$this->pagination->create_links().'</ul>
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