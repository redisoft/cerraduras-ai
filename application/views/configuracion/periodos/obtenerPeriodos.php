<?php
if(!empty ($periodos))
{
	echo'
	<div style="width:90%;">
		<ul id="pagination-digg" class="ajax-pagPeriodos">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="40%" align="center">Nombre</th>
			<th class="encabezadoPrincipal" align="center">Fecha inicial</th>
			<th class="encabezadoPrincipal" align="center">Fecha final</th>
			<th class="encabezadoPrincipal" width="17%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=$limite;
	foreach ($periodos as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
        <tr <?php echo $estilo?> id="filaPeriodos<?php echo $row->idPeriodo?>">
            <td align="center"> <?php echo $i?> </td>
            <td align="center" valign="middle"><?php echo $row->nombre ?></td>
            <td align="center" valign="middle"><?php echo obtenerFechaMesCorto($row->fechaInicial) ?></td>
            <td align="center" valign="middle"><?php echo obtenerFechaMesCorto($row->fechaFinal) ?></td>
            <td align="center" valign="middle" class="vinculos">

                <img id="btnEditarPeriodos<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" title="Editar periodos" onClick="accesoEditarPeriodos('<?php echo $row->idPeriodo?>')" >
                
                &nbsp;&nbsp;
                <img id="btnBorrarPeriodos<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" title="Borrar periodos" onClick="accesoBorrarPeriodos(<?php echo $row->idPeriodo?>)" >
                <br />
                <a id="a-btnEditarPeriodos<?php echo $i?>">Editar</a>
                <a id="a-btnBorrarPeriodos<?php echo $i?>">Borrar</a>
                
                <?php 

					if($permiso[5]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnEditarPeriodos'.$i.'\');
						</script>';
					}
					
					if($permiso[5]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnBorrarPeriodos'.$i.'\');
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
		<ul id="pagination-digg" class="ajax-pagPeriodos">'.$this->pagination->create_links().'</ul>
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