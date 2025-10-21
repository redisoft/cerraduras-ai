<?php
if(!empty($ordenes))
{
	echo'
	<div style="width:90%; margin-top:5%;">
		<ul id="pagination-digg" class="ajax-pagii">'.$this->pagination->create_links().'</ul>
	</div>';

	 ?>
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" width="2%">#</th>
			<th class="encabezadoPrincipal" width="8%">Fecha</th>
			<th class="encabezadoPrincipal" width="8%">Orden de producción</th>
			<th class="encabezadoPrincipal" width="12%" align="center">Autorizo</th>
			<th class="encabezadoPrincipal" width="20%">Productos</th>
			<th class="encabezadoPrincipal" align="center">Cantidad</th>
			<th class="encabezadoPrincipal" width="27%">Procesos </th>
			<th class="encabezadoPrincipal" width="12%" style="-moz-border-radius-bottomleft: 0px;-moz-border-radius-topleft: 0px;">Acciones</th>             
		</tr>
	<?php
	
	$i=1;
	$p=1;
	foreach ($ordenes as $orden)
	{
		echo' 
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="left" valign="middle">'.$i.'</td>
			<td align="center" valign="middle">'.obtenerFechaMesCortoHora($orden->fechaRegistro).'</td>
			<td align="center" valign="middle">'.folioOrdenes.$orden->orden.'</td>
			<td align="center" valign="middle">'.$orden->autorizo.'</td>
			<td align="center" valign="middle">'.$orden->descripcion;
			
			if($orden->materiaPrima==1)
			{
				echo ' (Materia prima)';
			}
			
			echo'
			</td>
			<td align="center" valign="middle">'.round($orden->cantidad,4).'</td>
			<td align="center" valign="middle" style="background-color:#FFF">  ';
				
				$procesoPasado	= 0;
					
				if($orden->cancelada=='0')
				{
					echo '
					<div align="center">
						'.($orden->producidos==0?'<img id="btnRegistrarProceso'.$i.'" src="'.base_url().'img/add.png" width="20" height="20" title="Agregar proceso" onclick="formularioAgregarProceso('.$orden->idOrden.')" />
						<br />
						<a id="a-btnRegistrarProceso'.$i.'">Agregar proceso</a>':'').'
					</div>';
					$prioridad		= 1; //Es para identificar la prioridad del proceso
				
					
					$procesos		= $this->ordenes->obtenerProcesosProduccion($orden->idOrden);
					
					if($procesos!=null)
					{
						if($orden->producidos!=$orden->cantidad)
						{
							$n=1;
							
							foreach($procesos as $row)
							{
								echo '<div style="width:150px; float: left; border:1px solid #d5d5d5; font-weight:bold; margin-top: 1px; margin-left: 1px; ">';
								echo '<div style="background-color:#f6f6f6; height: 22px; line-height:18px;"  id="filaProceso'.$row->idRelacion.'">#'.$n.' '.$row->nombre.' <img id="btnBorrarProcesoOrden'.$p.'" src="'.base_url().'img/borrar.png" width="16" title="Borrar proceso" onclick="confirmarBorrarProcesoOrden('.$row->idRelacion.')" /></div>';
								#--------------------------------------------------------------------#
				
								$cantidadProceso		= $this->ordenes->obtenerCantidadProceso($row->idRelacion);
								#--------------------------------------------------------------------#
			
								$cantidadProcesoSalida	= $this->ordenes->obtenerCantidadProcesoSalida($row->idRelacion);
								#--------------------------------------------------------------------#
								
								$cantidadProceso		= $cantidadProceso-$cantidadProcesoSalida;
								$completo				= "";
								
								if($cantidadProcesoSalida==$orden->cantidad)
								{
									$completo='<a style="font-weight:100">Completado</a>';
								}
								else
								{
									$completo='<a style="font-weight:100"><span id="spnCantidad">Cantidad: '.number_format($cantidadProceso,2).'</span></a>';
								}
								
								echo'
								<div align="center">
									<img id="btnProducidoProceso'.$p.'" src="'.base_url().'img/producido.png" onclick="obtenerDetallesProceso('.$orden->idOrden.','.$row->idRelacion.','.$prioridad.','.$procesoPasado.')" width="22" height="22" title="'.$row->nombre.'" style="cursor:pointer;"/>
									<br />
									'.$completo.'
								</div>';
								
								
								
								echo '</div>';
								
								if($permiso[1]->activo==0)
								{
									 echo '
									<script>
										desactivarBotonSistema(\'btnProducidoProceso'.$p.'\');
									</script>';
								}
								
								if($permiso[3]->activo==0)
								{
									 echo '
									<script>
										desactivarBotonSistema(\'btnBorrarProcesoOrden'.$p.'\');
									</script>';
								}
								
								$procesoPasado=$row->idRelacion;
								$prioridad++;
								$p++;
								
								$n++;
							}
						}
						else
						{
							echo '<a>Producido</a>';
						}
					}
					else
					{
						if($orden->producidos==$orden->cantidad)
						{
							echo '<a>Producido</a>';
						}
						else
						{
							echo '<div><a>Sin procesos de producción</a></div>';
						}
					}
				}
				?>
			</td>
			
			<td align="center" valign="middle"> 
			   <?php
			   	echo '
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="produccionProducido'.$i.'" src="'.base_url().'img/producido.png" width="22" height="22"  title="Producido"  onclick="obtenerProducido('.$orden->idOrden.','.$procesoPasado.')" style="cursor:pointer;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
				<img id="btnCancelarOrden'.$i.'" onclick="accesoCancelarOrden('.$orden->idOrden.')" src="'.base_url().'img/borrar.png" width="22" height="22"  title="Borrar Orden" />
				&nbsp;&nbsp;&nbsp;
				<br />

				<a id="a-produccionProducido'.$i.'">Terminados </a>
				&nbsp;
				<a id="a-btnCancelarOrden'.$i.'">Cancelar</a>';
				
				if($permiso[1]->activo==0 or $orden->cancelada=='1')
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarProceso'.$i.'\');
						desactivarBotonSistema(\'produccionProducido'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0 or $orden->cancelada=='1')
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnCancelarOrden'.$i.'\');
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
		<div style="width:90%; margin-top:5%;">
			<ul id="pagination-digg" class="ajax-pagii">'.$this->pagination->create_links().'</ul>
		</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de ordenes de producción</div>';
}
?>