<?php
if(!empty($servicios))
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagServicios">'.$this->pagination->create_links().'</ul>
	</div>';
	?>

	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" style="width:2%;">#</th>
            <th class="encabezadoPrincipal">Código</th>
			<th class="encabezadoPrincipal" width="40%">Nombre</th>
            <th class="encabezadoPrincipal">Unidad</th>
            <th class="encabezadoPrincipal">Costo</th>
			<th class="encabezadoPrincipal" style="width:14%;">Acciones </th>
		</tr>
	<?php
	$i=$limite;
	foreach($servicios as $row)
	{
		?>
		<tr <?php echo $i%2>0?'class="sombreado"':'class="sinSombra"'?> id="filaServicio<?php echo $row->idServicio?>">
			<td align="left" valign="middle"> <?php print($i); ?> </td>
            <td align="center" valign="middle"><?php echo $row->codigoInterno ?></td>
			<td align="center" valign="middle"><?php echo $row->nombre ?></td>
            <td align="center" valign="middle"><?php echo $row->unidad ?></td>
            <td align="right" valign="middle">$<?php echo number_format($row->costo,decimales) ?></td>
			<td align="center"   valign="middle"> 
            	<img id="btnEditarServicio<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" style="cursor:pointer" onclick="accesoEditarServicioConsumo('<?php echo $row->idServicio ?>')" title="Editar servicio " />

                &nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnProveedoresServicio<?php echo $i?>" src="<?php echo base_url()?>img/proveedores.png" width="22" style="cursor:pointer" title="Agregar proveedor" onclick="formularioAgregarProveedor('<?php echo $row->idServicio ?>')" />
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnBorrarServicio<?php echo $i?>" onclick="accesoBorrarServicioConsumo(<?php echo $row->idServicio?>)" src="<?php echo base_url()."img/borrar.png"?>" width="22" height="22" title="Borrar servicio" />
                
                <br />
                <a id="a-btnEditarServicio<?php echo $i?>">Editar</a>
                <a id="a-btnProveedoresServicio<?php echo $i?>">Proveedor</a>
                <a id="a-btnBorrarServicio<?php echo $i?>">Borrar</a>
                
				<?php
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnProveedoresServicio'.$i.'\');
					</script>';
				}
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarServicio'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarServicio'.$i.'\');
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
		<ul id="pagination-digg" class="ajax-pagServicios">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de Servicios</div>';
}
?>