<?php 
if(!empty($conteos))
{
	echo'
	
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagPedidos">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" style="width:3%;">#</th>
            <th class="encabezadoPrincipal">Conteo</th>
            <th class="encabezadoPrincipal">Usuario</th>
            <th class="encabezadoPrincipal">Tienda</th>
            <th class="encabezadoPrincipal">Comentarios</th>
			<th class="encabezadoPrincipal" style="width:20%;">Acciones</th>               
		</tr>
	
	<?php
	$i	= $inicio;
	foreach($conteos as $row)
	{
		?>
		<tr <?php echo $i%2>0?'class="sinSombra"':'class="sombreado"'?>>
			<td align="right" valign="middle"><?php echo $i ?> </td>	
            <td align="center" valign="middle"> <?php echo conteos.$row->folio ?> </td>
            <td align="center" valign="middle"> <?php echo $row->usuario ?> </td>
			<td align="left" valign="middle"> <?php echo $row->tienda ?> </td>
            <td align="left" valign="middle"> <?php echo nl2br($row->comentarios) ?> </td>
			<td align="left"   valign="middle">
                &nbsp;&nbsp;
                <img id="btnProducido<?php echo $i?>"  onclick="detallesConteo('<?php echo $row->idConteo?>')" src="<?php echo base_url()?>img/pan.png" style="width:22px;" title="Conteo" />

                 &nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnReporte<?php echo $i?>"  onclick="reporteConteos(<?php echo $row->idConteo?>)" src="<?php echo base_url()?>img/pdf.png" style="width:22px; height:22px;" title="PDF" />
                
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnEditarPedido<?php echo $i?>" onclick="accesoEditarConteo('<?php echo $row->idConteo?>')" src="<?php echo base_url()."img/edit.png"?>" width="22" height="22" title="Editar"  /></a>
                
                &nbsp;&nbsp;&nbsp;
                <img id="btnBorrarConteo<?php echo $i?>" onclick="accesoBorrarConteo(<?php echo $row->idConteo?>)" src="<?php echo base_url()."img/borrar.png"?>" width="22" height="22" title="Borrar" />

                <br /> 
                
			<?php
			echo'
			<a id="a-btnProducido'.$i.'">Conteo</a> 
			<a id="a-btnReporte'.$i.'">Reporte</a> 
			&nbsp;
			<a id="a-btnEditarPedido'.$i.'">Editar</a>
			&nbsp;<a id="a-btnBorrarPedido'.$i.'">Borrar</a>';
			
			if($idRol==5 )
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnReporte'.$i.'\');
				</script>';	
			}
			
			if($permiso[1]->activo==0  )
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnProducido'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarPedido'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0 )
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnCancelarPedido'.$i.'\');
					desactivarBotonSistema(\'btnBorrarPedido'.$i.'\');
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
		<ul id="pagination-digg" class="ajax-pagPedidos">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de conteos</div>';
}
?>
