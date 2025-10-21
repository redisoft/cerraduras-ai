<?php 
if(!empty($salidas))
{
	echo'
	
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagSalidasControl">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" style="width:3%;">#</th>
			<th class="encabezadoPrincipal" align="center" width="8%" >Fecha</th>
            <th class="encabezadoPrincipal">Folio</th>
            <th class="encabezadoPrincipal">Usuario</th>
			<th class="encabezadoPrincipal">Tienda</th>
            <th class="encabezadoPrincipal">Comentarios</th>
			<th class="encabezadoPrincipal" style="width:22%;">Acciones</th>               
		</tr>
	
	<?php
	$i	= $inicio;
	foreach($salidas as $row)
	{
		?>
		<tr <?php echo $i%2>0?'class="sinSombra"':'class="sombreado"'?>>
			<td align="right" valign="middle"><?php echo $i ?> </td>	
			<td align="center" valign="middle"><?php echo obtenerFechaMesCorto($row->fechaSalida)?></td>
			<td align="center" valign="middle"><?php echo salidas.$row->folio?></td>
            <td align="center" valign="middle"> <?php echo $row->usuario ?> </td>
			<td align="left" valign="middle"> <?php echo $row->tienda ?> </td>
            <td align="left" valign="middle"> <?php echo nl2br($row->comentarios) ?> </td>
			<td align="center"   valign="middle">
                &nbsp;&nbsp;&nbsp;
                <img id="btnDevuelto<?php echo $i?>"  onclick="obtenerDevueltosControl('<?php echo $row->idSalida?>')" src="<?php echo base_url()?>img/proveedores.png" style="width:22px; height:22px;" title="Devueltos" />
                
                &nbsp;&nbsp;&nbsp;
                <img id="btnImprimir<?php echo $i?>"  onclick="window.location.href='<?php echo base_url().'reportes/salidaControl/'.$row->idSalida?>'" src="<?php echo base_url()?>img/pdf.png" style="width:22px; height:22px;" title="PDF" />
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnEditarSalida<?php echo $i?>" onclick="accesoEditarSalida('<?php echo $row->idSalida?>')" src="<?php echo base_url()."img/edit.png"?>" width="22" height="22" title="Editar"  /></a>
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnBorrarSalida<?php echo $i?>" onclick="accesoBorrarSalida(<?php echo $row->idSalida?>)" src="<?php echo base_url()."img/borrar.png"?>" width="22" height="22" title="Borrar" />

                <br /> 
                
			<?php
			echo'
			<a id="a-btnDevuelto'.$i.'">Devuelto</a> 
			<a id="a-btnDevuelto'.$i.'">PDF</a> 
			&nbsp;
			<a id="a-btnEditarSalida'.$i.'">Editar</a>
			&nbsp;<a id="a-btnBorrarSalida'.$i.'">Borrar</a>';
			
			if($permiso[1]->activo==0 )
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnDevuelto'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0 or $row->devueltos=='1')
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarSalida'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarSalida'.$i.'\');
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
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagSalidasControl">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de control</div>';
}
?>
