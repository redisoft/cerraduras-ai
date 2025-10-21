

<?php

if(!empty($servicios))
{
	echo'
	<div style="width:90%; margin-top:2%;">
		<ul id="pagination-digg" class="ajax-pagServicios">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>

	<input type="hidden" id="pagina" value="<?php echo $inicio?>" />
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" style="width:2%; border-bottom-right-radius: 0px; border-top-right-radius: 0px;">#</th>
			<th class="encabezadoPrincipal" style="border-radius: 0px" >Código interno</th>
			<th class="encabezadoPrincipal" style="border-radius: 0px" width="40%">Servicio</th>
            <th class="encabezadoPrincipal">Unidad</th>
            <th class="encabezadoPrincipal"  width="10%">Precio antes de IVA</th>
			<th class="encabezadoPrincipal"  width="10%">Precio venta</th>
            <th class="encabezadoPrincipal" >Periodicidad</th>
            <th class="encabezadoPrincipal" >Plazo</th>
			<th class="encabezadoPrincipal" style="width:10%;">Acciones </th>
		</tr>
	<?php
	$i=1;
	foreach($servicios as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';

		$iva=$row->precioA*($this->session->userdata('iva')/100);
		$precioVenta=$row->precioA*(($this->session->userdata('iva')/100)+1);
	
		?>
		<tr <?php echo $estilo?>>
			<td align="left" valign="middle"> <?php print($i); ?> </td>
			<td align="center" valign="middle"><?php print($row->codigoInterno); ?></td>
			<td align="left" valign="middle"> <?php echo $row->nombre; ?> </td>
            <td><?php echo $row->unidad?></td>
            <td align="center" valign="middle">$<?php echo number_format($row->precioA,decimales); ?></td>
			<td align="center" valign="middle">$<?php echo number_format($row->precioImpuestos,decimales); ?></td>
            <td><?php echo $row->periodo?></td>
            <td align="center"><?php echo $row->plazo?></td>
			<td align="center"   valign="middle"> 
            	
                <img id="btnEditarServicio<?php echo $i?>" src="<?php echo base_url()?>img/edit.png" width="22" style="cursor:pointer" onclick="accesoEditarServicio('<?php echo $row->idProducto ?>')" />
                &nbsp;&nbsp;&nbsp;
				<img id="btnBorrarServicio<?php echo $i?>" src="<?php echo base_url()."img/borrar.png"?>" width="22" height="22" hspace="3" title="Borrar servicio" onclick="borrarServicioProducto('<?php echo $row->idProducto ?>','¿Realmente desea borrar el servicio?')" />
                
                <br />
                <a id="a-btnEditarServicio<?php echo $i?>">Editar</a>
                <a id="a-btnBorrarServicio<?php echo $i?>">Borrar </a>
                
				<?php
            
                
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
	<div style="width:90%; margin-top:2%;">
		<ul id="pagination-digg" class="ajax-pagServicios">'.$this->pagination->create_links().'</ul>
	</div>';
	
}
else
{
	echo '<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de servicios</div>';
}
?>

