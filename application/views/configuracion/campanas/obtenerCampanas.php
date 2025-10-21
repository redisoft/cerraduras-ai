<?php
if(!empty ($campanas))
{
	echo'
	<div style="width:90%;">
		<ul id="pagination-digg" class="ajax-pagCampanas">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="40%" align="center">Nombre</th>
			<th class="encabezadoPrincipal" align="center">Fecha inicial</th>
			<th class="encabezadoPrincipal" align="center">Fecha final</th>
			<th class="encabezadoPrincipal" align="center">Sin atrasos</th>
			<th class="encabezadoPrincipal" width="17%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=$limite;
	foreach ($campanas as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
        <tr <?php echo $estilo?> id="filaCampanas<?php echo $row->idCampana?>">
            <td align="center"> <?php echo $i?> </td>
            <td align="center" valign="middle"><?php echo $row->nombre ?></td>
            <td align="center" valign="middle"><?php echo obtenerFechaMesCorto($row->fechaInicial) ?></td>
            <td align="center" valign="middle"><?php echo obtenerFechaMesCorto($row->fechaFinal) ?></td>
            <td align="center" valign="middle"><?php echo $row->atrasos=='1'?'Si':'No' ?></td>
            <td align="center" valign="middle" class="vinculos">

                <img id="btnEditarCampanas<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" title="Editar campanas" onClick="accesoEditarCampanas('<?php echo $row->idCampana?>')" >
                
                &nbsp;&nbsp;
                <img id="btnBorrarCampanas<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" title="Borrar campanas" onClick="accesoBorrarCampanas(<?php echo $row->idCampana?>)" >
                <br />
                <a id="a-btnEditarCampanas<?php echo $i?>">Editar</a>
                <a id="a-btnBorrarCampanas<?php echo $i?>">Borrar</a>
                
                <?php 

					if($permiso[2]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnEditarCampanas'.$i.'\');
						</script>';
					}
					
					if($permiso[3]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnBorrarCampanas'.$i.'\');
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
		<ul id="pagination-digg" class="ajax-pagCampanas">'.$this->pagination->create_links().'</ul>
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