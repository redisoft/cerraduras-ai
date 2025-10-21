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
			<th class="encabezadoPrincipal" width="40%" align="left">Programa</th>
			<th class="encabezadoPrincipal" align="center">Importe</th>
			<th class="encabezadoPrincipal" align="center">Comisión</th>
			<th class="encabezadoPrincipal" width="17%" align="center">Acciones</th>
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
            <td align="left" valign="middle"><?php echo $row->nombre ?></td>
            <td align="center" valign="middle">$<?php echo number_format($row->importe,decimales) ?></td>
            <td align="center" valign="middle">$<?php echo number_format($row->comision,decimales) ?></td>
            <td align="center" valign="middle" class="vinculos">

                <img id="btnEditarProgramas<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" title="Editar programas" onClick="accesoEditarProgramas('<?php echo $row->idPrograma?>')" >

                <br />
                <a id="a-btnEditarProgramas<?php echo $i?>">Editar</a>
                
                <?php 

					if($permiso[2]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnEditarProgramas'.$i.'\');
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