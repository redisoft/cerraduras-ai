<?php 
if(!empty($pedidos))
{
	echo'
	
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagPedidos">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" style="width:3%;">#</th>
			<th class="encabezadoPrincipal" align="center" width="8%">
            	Fecha
                <img src="<?php echo base_url()?>img/<?php echo $orden=='desc'?'mostrar.png':'ocultar.png'?>" width="18" onclick="ordenPedidos('<?php echo $orden=='desc'?'asc':'desc'?>')" />
            </th>
            <th class="encabezadoPrincipal">Orden de producción</th>
            <th class="encabezadoPrincipal">Línea</th>
            <th class="encabezadoPrincipal">Usuario</th>
			<th class="encabezadoPrincipal">Tienda</th>
            <th class="encabezadoPrincipal">Comentarios</th>
			<th class="encabezadoPrincipal" style="width:26%;">Acciones</th>               
		</tr>
	
	<?php
	$i	= $inicio;
	foreach($pedidos as $row)
	{
		?>
		<tr <?php echo $i%2>0?'class="sinSombra"':'class="sombreado"'?>>
			<td align="right" valign="middle"><?php echo $i ?> </td>	
			<td align="center" valign="middle"><?php echo obtenerFechaMesCorto($row->fechaPedido)?></td>
			<td align="center" valign="middle">
				<?php 
                	#echo ($row->idLinea==2?frances:bizcocho); 
					
					if($row->idLinea==2) echo frances;
					if($row->idLinea==3) echo bizcocho;
					
					echo $row->folio;
					
					$domingo	= obtenerDiaActual($row->fechaPedido);
					
					if($domingo=='domingo')
					{
						echo '<br /><i>Domingo</i>';
					}
					
				?>
            </td>
            <td align="center" valign="middle"> <?php echo $row->linea ?> </td>
            <td align="center" valign="middle"> <?php echo $row->usuario ?> </td>
			<td align="left" valign="middle"> <?php echo $row->tienda ?> </td>
            <td align="left" valign="middle"> <?php echo nl2br($row->comentarios) ?> </td>
			<td align="left"   valign="middle">
                &nbsp;&nbsp;&nbsp;
                <img id="btnProducido<?php echo $i?>"  onclick="obtenerProducidoPedido('<?php echo $row->idPedido?>')" src="<?php echo base_url()?>img/producido.png" style="width:22px;" title="Producido" />
                
                <!--&nbsp;&nbsp;&nbsp;
                <img id="btnImprimir<?php echo $i?>"  onclick="window.location.href='<?php echo base_url().'reportes/pedido/'.$row->idPedido?>'" src="<?php echo base_url()?>img/pdf.png" style="width:22px; height:22px;" title="PDF" />-->
                
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnReporte<?php echo $i?>"  onclick="formularioReporte(<?php echo $row->idPedido?>)" src="<?php echo base_url()?>img/pdf.png" style="width:22px; height:22px;" title="PDF" />
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnEditarPedido<?php echo $i?>" onclick="accesoEditarPedido('<?php echo $row->idPedido?>')" src="<?php echo base_url()."img/edit.png"?>" width="22" height="22" title="Editar"  /></a>
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnCancelarPedido<?php echo $i?>" onclick="accesoCancelarPedido(<?php echo $row->idPedido?>)" src="<?php echo base_url()."img/cancelar.png"?>" width="22" height="22" title="Cancelar" />
                
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnBorrarPedido<?php echo $i?>" onclick="accesoBorrarPedido(<?php echo $row->idPedido?>)" src="<?php echo base_url()."img/borrar.png"?>" width="22" height="22" title="Borrar" />

                <br /> 
                
			<?php
			echo'
			<a id="a-btnProducido'.$i.'">Producido</a> 
			<!--<a id="a-btnImprimir'.$i.'">PDF</a> -->
			<a id="a-btnReporte'.$i.'">Reporte</a> 
			&nbsp;
			<a id="a-btnEditarPedido'.$i.'">Editar</a>
			&nbsp;<a id="a-btnCancelarPedido'.$i.'">Cancelar</a>
			&nbsp;<a id="a-btnBorrarPedido'.$i.'">Borrar</a>';
			
			if($idRol==5 )
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnReporte'.$i.'\');
				</script>';	
			}
			
			if($permiso[1]->activo==0 or $row->cancelado=='1'  )
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnProducido'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0 or $row->cancelado=='1' or $row->producido>0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarPedido'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0 or $row->cancelado=='1')
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
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de pedidos</div>';
}
?>
