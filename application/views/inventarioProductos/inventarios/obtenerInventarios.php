<?php
if(!empty($inventarios))
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagin">'.$this->pagination->create_links().'</ul>
	</div>';
	?>

	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" style="width:2%;">#</th>
            <th class="encabezadoPrincipal">Código</th>
			<th class="encabezadoPrincipal" width="40%">Nombre</th>
            <th class="encabezadoPrincipal">Unidad</th>
            <th class="encabezadoPrincipal">Proveedor</th>
            <th class="encabezadoPrincipal" width="13%">Costo</th>
			<th class="encabezadoPrincipal" width="13%">Existencia</th>
			<th class="encabezadoPrincipal" style="width:14%;">Acciones </th>
		</tr>
	<?php
	$i=$limite;
	foreach($inventarios as $row)
	{
		$estilo=$i%2>0?'class="sombreado"':'class="sinSombra"';

		?>
		<tr <?php echo $estilo?> id="filaInventario<?php echo $row->idInventario?>">
			<td align="left" valign="middle"> <?php print($i); ?> </td>
            <td align="center" valign="middle"><?php echo $row->codigoInterno ?></td>
			<td align="center" valign="middle"><?php echo $row->nombre ?></td>
            <td align="center" valign="middle"><?php echo $row->unidad ?></td>
			<td align="left" valign="middle"> <?php echo $row->empresa; ?> </td>
            <td align="right">$<?php echo number_format($row->costo,2)?></td>
            <td align="center" valign="middle"><?php echo number_format($row->cantidad,2); ?></td>
			<td align="center"   valign="middle"> 
				<img id="btnEditarMobiliario<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" style="cursor:pointer" onclick="accesoEditarMobiliario('<?php echo $row->idInventario ?>')" title="Editar " />
				
                &nbsp;&nbsp;
                <img id="btnBorrarMobiliario<?php echo $i?>"  onclick="accesoBorrarMobiliario(<?php echo $row->idInventario?>)" src="<?php echo base_url()."img/borrar.png"?>" width="22" height="22" title="Borrar mobiliario" />
				
                &nbsp;
                <img id="btnUsosMobiliario<?php echo $i?>"  src="<?php echo base_url()?>img/uso.png" width="22" style="cursor:pointer" title="Usos de producto" onclick="obtenerUsosInventario('<?php echo $row->idInventario ?>')" />
                
                <br />
                <a id="a-btnEditarMobiliario<?php echo $i?>" >Editar</a>
                <a id="a-btnBorrarMobiliario<?php echo $i?>">Borrar</a>
                <a id="a-btnUsosMobiliario<?php echo $i?>">Uso</a>
				<?php
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarMobiliario'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarMobiliario'.$i.'\');
					</script>';
				}
				
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnUsosMobiliario'.$i.'\');
					</script>';
				}
				
                ?>
			
			</td>
		</tr>
		
		  <?php
	 $i++;
	 }
	?>
	</table>
	<?php
	
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagin">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de Mobiliario/equipo</div>';
}
?>