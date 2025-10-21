
<?php
if(!empty($compras))
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagin">'.$this->pagination->create_links().'</ul>
	</div>';
	?>

	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Fecha</th>
			<th class="encabezadoPrincipal">Proveeedor</th>
			<th class="encabezadoPrincipal">Orden de compra</th>
            <?php
            echo $idRol!=6?'
			<th class="encabezadoPrincipal">CRM</th>
			<th class="encabezadoPrincipal">Precio</th>
			<th class="encabezadoPrincipal">Pago</th>
			<th class="encabezadoPrincipal">Saldo</th>':'';
			?>
			<th class="encabezadoPrincipal" style="width:<?php echo $idRol!=6?47:10?>%">Acciones</th>             
		</tr>
	<?php
	$i=1;
	foreach ($compras as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
	
		$pagado		= $this->compras->obtenerPagado($row->idCompras);
		$saldo		= $row->total-$pagado;
		$onclick	= $idRol!=6?'onclick="obtenerComprita('.$row->idCompras.')" title="Click para ver el detalle"':'';
		
		?>
	
		<tr <?php echo $estilo?>>
			<td align="left" valign="middle" <?php echo $onclick?>> <?php print($i); ?> </td>
			<td align="center" valign="middle" <?php echo $onclick?>><?php echo obtenerFechaMesCorto($row->fechaCompra); ?></td>
			<td align="center" valign="middle" <?php echo $onclick?>>  <?php print($row->empresa); ?> </td>
			<td align="center" valign="middle" <?php echo $onclick?>> <a><?php echo $row->nombre .($row->cerrada=='1'?' (Cerrada)':'') ?> </a></td>
            
            <?php
			
			if($idRol!=6)
			{
				$seguimiento	= null;
				if(strlen($row->idSeguimiento)>0)
				{
					$seguimiento	= $this->crm->obtenerUltimoSeguimientoCompra($row->idCompras);
				}
				
				$mostrarSeguimiento=false;
				
				if($permisoCrm[0]->activo==1)
				{
					$mostrarSeguimiento=true;
				}
				
				 echo'
				<td align="center" title="Click para ver detallles de seguimiento" '.($mostrarSeguimiento?($seguimiento!=null?'onclick="obtenerSeguimientoServicio('.$row->idCompras.','.$seguimiento->idSeguimiento.')"':'onclick="obtenerSeguimientoServicio('.$row->idCompras.',0)"'):'').' >';
					
					if($mostrarSeguimiento and $seguimiento!=null)
					{
						echo'
						<span >
							<div style="background-color: '.$seguimiento->color.'" class="circuloStatus"></div>
							<i style="font-weight:100">'.$seguimiento->status.'<br />'.obtenerFechaMesCortoHora($seguimiento->fecha).'</i>
						</span>';
					}
					if($mostrarSeguimiento and $seguimiento==null)
					{
						echo '<img src="'.base_url().'img/crm.png" width="22" height="22" />';
					}
					
				echo'
				</td>
				<td align="right" valign="middle" '.$onclick.'>  $'.number_format($row->total,decimales).'</td>
				<td id="tdPagado'.$row->idCompras.'" align="right" valign="middle" '.$onclick.'>$'.number_format($pagado,decimales).'</td>
				<td id="tdSaldo'.$row->idCompras.'" align="right" valign="middle" '.$onclick.'>$'.number_format($saldo,decimales).'</td>';
			}

			?>
			            
			
			<td align="left"   valign="middle"> 
			<?php
				if($idRol!=6)
				{
					echo '
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img id="btnRecepcionesAnden'.$i.'" src="'.base_url().'img/anden.png" width="22" height="22"  title="Recepción anden" onclick="recepcionesAnden('.$row->idCompras.')"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					&nbsp;&nbsp;&nbsp;
					<img id="btnRecibirProductos'.$i.'" src="'.base_url().'img/'.($row->recibidos==$row->comprados?'success.png':'Cerrar.png').'" width="22" height="22"  title="Recibido" onclick="obtenerProductosComprados('.$row->idCompras.');" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<img id="btnCerrarCompra'.$i.'" src="'.base_url().'img/cerrado.png" width="22" height="22"  title="Cerrar" onclick="cerrarCompra('.$row->idCompras.')"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<img id="btnPagosProveedor'.$i.'" onclick="obtenerPagosComprasProveedor('.$row->idCompras.')" src="'.base_url().'img/pagos.png" width="22" height="20" title="Pagos a proveedor" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					 
					<img id="btnComprobantesCompras'.$i.'" src="'.base_url().'img/subir.png" width="22"  onclick="obtenerComprobantesCompras('.$row->idCompras.',0)"  title="Comprobantes" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<a id="btnPdfCompras'.$i.'" onclick="window.open(\''.base_url().'compras/comprasPDF/'.$row->idCompras.'/'.$this->session->userdata('idLicencia').'\')" >
						<img src="'.base_url().'img/pdf.png" width="22" height="22" title="PDF" />
					</a>
					
					&nbsp;&nbsp;&nbsp;&nbsp;
					<img id="btnEnviarCompra'.$i.'" onclick="formularioEnviarCompra('.$row->idCompras.')" src="'.base_url().'img/correo.png" width="20" height="20"  title="Enviar" />
					
					&nbsp;&nbsp;&nbsp;&nbsp;
					<img id="btnCancelarCompra'.$i.'" onclick="cancelarCompra('.$row->idCompras.',\'Cancelar la compra borrara tambien sus pagos y el inventario ingresado, ¿Desea continuar?\',\'compras\')" src="'.base_url().'img/cancelame.png" width="22" height="22"  title="Cancelar compra" />
					
					&nbsp;&nbsp;&nbsp;
					<img id="btnBorrarCompra'.$i.'" onclick="borrarCompra('.$row->idCompras.',\'Borrar la compra borrara tambien sus pagos y el inventario ingresado, ¿Desea continuar?\',\'compras\')" src="'.base_url().'img/quitar.png" width="22" height="22"  title="Borrar compra" />
					
					<br />
					
					&nbsp;&nbsp; 
					<a id="a-btnRecepcionesAnden'.$i.'">Recep. anden</a>
					
					&nbsp;&nbsp; 
					<a id="a-btnRecibirProductos'.$i.'">Recibido</a>
					&nbsp;&nbsp; 
					<a id="a-btnCerrarCompra'.$i.'">Cerrar</a>
					&nbsp;&nbsp; 
					<a id="a-btnPagosProveedor'.$i.'">Pagos</a>&nbsp;
					<a id="a-btnComprobantesCompras'.$i.'">Comprobantes</a>
					&nbsp;
					<a id="a-btnPdfCompras'.$i.'">PDF</a>
					 &nbsp;&nbsp;
					<a id="a-btnEnviarCompra'.$i.'">Enviar</a>
					<a id="a-btnCancelarCompra'.$i.'">Cancelar </a>
					<a id="a-btnBorrarCompra'.$i.'">Borrar </a>';
					
					
					
					if($row->cancelada=='1')
					{
						echo '
						<script>
							desactivarBotonSistema(\'btnEnviarCompra'.$i.'\');
							desactivarBotonSistema(\'btnPdfCompras'.$i.'\');
						</script>';
					}
					
					if($permiso[2]->activo==0 or $row->cancelada=='1')
					{
						echo '
						<script>
							desactivarBotonSistema(\'btnRecibirProductos'.$i.'\');
							desactivarBotonSistema(\'btnPagosProveedor'.$i.'\');
							desactivarBotonSistema(\'btnComprobantesCompras'.$i.'\');
							desactivarBotonSistema(\'btnRecepcionesAnden'.$i.'\');
						</script>';
					}
					
					if($permiso[3]->activo==0 or $row->cancelada=='1' or $row->cerrada=='1' or $row->recibidos>0)
					{
						echo '
						<script>
							desactivarBotonSistema(\'btnCancelarCompra'.$i.'\');
							desactivarBotonSistema(\'btnBorrarCompra'.$i.'\');
							desactivarBotonSistema(\'btnCerrarCompra'.$i.'\');
							desactivarBotonSistema(\'btnRecepcionesAnden'.$i.'\');
						</script>';
					}
				}
				
				if($idRol==6)
				{
					echo '
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img id="btnRecepcionesAnden'.$i.'" src="'.base_url().'img/anden.png" width="22" height="22"  title="Recepción anden" onclick="recepcionesAnden('.$row->idCompras.')"/>
					<br />
					<a id="a-btnRecepcionesAnden'.$i.'">Recepción anden</a>';
					
					if($row->cancelada=='1' or $row->cerrada=='1' or $row->recibidos>0)
					{
						echo '
						<script>
							desactivarBotonSistema(\'btnRecepcionesAnden'.$i.'\');
						</script>';
					}
				}
				

			?>
			
			</td>
		</tr>
		<?php
		$i++;
	 }//Foreach del Cliente
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
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de compras</div>';
}
?>

