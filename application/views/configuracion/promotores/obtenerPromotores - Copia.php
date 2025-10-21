<?php
error_reporting(0);
#if(!empty ($promotores))
{
	echo'
	<div style="width:90%;">
		<ul id="pagination-digg" class="ajax-pagPromotores">'.$this->pagination->create_links().'</ul>
	</div>';
	
	$meta				= 0;
	$numeroProspectos	= 0;
	$numeroInscritos	= 0;
	$atendidos			= 0;
	
	foreach ($promotores as $row)
	{
		$meta				+= $row->meta;
		$numeroProspectos	+= $row->numeroProspectos;
		$numeroInscritos	+= $row->numeroInscritos;
		$atendidos			+= $row->atendidos;

	}
	
	$porcentajeAvance	= round(($numeroInscritos/$meta)*100,decimales);
	$pendientes			= $numeroProspectos -$atendidos;
	$conversion			= round(($numeroInscritos /$atendidos)*100,decimales);
	
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="20%" align="center">
				<select id="selectPromotoresBusqueda" name="selectPromotoresBusqueda" onchange="obtenerPromotores()" style="width:130px" class="cajas">
					<option value="0">Promotor</option>';
					
					foreach($usuarios as $row)
					{
						echo '<option '.($row->idUsuario==$idUsuario?'selected="selected"':'').' value="'.$row->idUsuario.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			</th>
			<th class="encabezadoPrincipal" width="20%" align="center">
				<select id="selectCampanasBusqueda" name="selectCampanasBusqueda" onchange="obtenerPromotores()" style="width:130px" class="cajas">
					<option value="0">Campaña</option>';
					
					foreach($campanas as $row)
					{
						echo '<option '.($row->idCampana==$idCampana?'selected="selected"':'').' value="'.$row->idCampana.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			</th>
			<th class="encabezadoPrincipal" align="center">Meta</th>
			<th class="encabezadoPrincipal" align="center">Prospectos <br /> '.$numeroProspectos.'</th>
			<th class="encabezadoPrincipal" align="center">Inscritos <br /> '.$numeroInscritos.'</th>
			<th class="encabezadoPrincipal" align="center">Porcentaje de avance <br /> '.$porcentajeAvance.'%</th>
			<th class="encabezadoPrincipal" align="center">Atendidos <br /> '.$atendidos.'</th>
			<th class="encabezadoPrincipal" align="center">Pendientes <br /> '.$pendientes.'</th>
			<th class="encabezadoPrincipal" align="center">Conversion <br /> '.$conversion.'%</th>
			<th class="encabezadoPrincipal" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=$limite;
	foreach ($promotores as $row)
	{
		$estilo	= $i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
        <tr <?php echo $estilo?> id="filaPromotores<?php echo $row->idMeta?>">
            <td align="center"> <?php echo $i?> </td>
            <td align="center" valign="middle"><?php echo $row->promotor ?></td>
            <td align="center" valign="middle"><?php echo $row->campana ?></td>
            <td align="center" valign="middle"><?php  echo $row->meta ?></td>
            <td align="center" valign="middle"><?php  echo $row->numeroProspectos ?></td>
            <td align="center" valign="middle"><?php  echo $row->numeroInscritos ?></td>
            <td align="center" valign="middle"><?php echo round(($row->numeroInscritos /$row->meta)*100,decimales) ?>%</td>
            <td align="center" valign="middle"><?php  echo $row->atendidos ?></td>
            <td align="center" valign="middle"><?php   echo $row->numeroProspectos -$row->atendidos ?></td>
            <td align="center" valign="middle"><?php echo round(($row->numeroInscritos /$row->atendidos)*100,decimales)   ?>%</td>
            <td align="center" valign="middle">
			<?php  
			echo '
			<img id="btnBorrarPromotores'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" title="Borrar programas" onClick="accesoBorrarPromotores('.$row->idMeta.')" /><br />
			<a>Borrar</a>';
			
			if($permiso[2]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarPromotores'.$i.'\');
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
		<ul id="pagination-digg" class="ajax-pagPromotores">'.$this->pagination->create_links().'</ul>
	</div>';
}
/*else
{
	echo
	'<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
		No se encontraron registros.
	</div>';
}
*/?>