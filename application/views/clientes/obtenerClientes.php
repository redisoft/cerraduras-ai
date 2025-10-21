
<?php
/*if(!empty($clientes))
{
*/	echo'
	<div style="width:90%; margin-top:5%;">
		<ul id="pagination-digg" class="ajax-pagClientes">'.$this->pagination->create_links().'</ul>
	</div>';
	?>

	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" width="2%" align="right" >#</th>
			<th class="encabezadoPrincipal" align="left">Contacto</th>
			<th class="encabezadoPrincipal" align="left">
			
			<?php
				echo sistemaActivo=='IEXE'?'Alumno':'Cliente';
				if($this->session->userdata('criterioClientes')=='a')
				{
					#echo '<a href="'.base_url().'clientes/ordenamiento/z"><img src="'.base_url().'img/ocultar.png" width="17" /></a>';	
				}
				else
				{
					#echo '<a href="'.base_url().'clientes/ordenamiento/a"><img src="'.base_url().'img/mostrar.png" width="17" /></a>';
				}
		  ?>
			</th>
			
			<th class="encabezadoPrincipal" align="left"># Cliente</th>
            <th class="encabezadoPrincipal" style="display: none" >
            	 <?php
				 	$totalRegistros=0;
                    foreach($zonas as $row)
					{
						$totalRegistros+=$row['numeroClientes'];
					}
					?>
                    
            	<select id="selectZonasBuscar" name="selectZonasBuscar" class="cajas" style="width:120px" onchange="obtenerClientes()">
                	<option value="0"><?php echo $this->session->userdata('identificador').' ('.$totalRegistros.')'?></option>
                    
                    <?php
                    foreach($zonas as $row)
					{
						echo '<option '.($row['idZona']==$idZona?'selected="selected"':'').' value="'.$row['idZona'].'">'.$row['descripcion'].' ('.$row['numeroClientes'].')</option>';
					}
					?>
                </select>
            </th>
			<th class="encabezadoPrincipal" >Teléfono</th>
            <th class="encabezadoPrincipal" >Venta</th>
            <th class="encabezadoPrincipal" >Saldo</th>
			<th class="encabezadoPrincipal" style="display: none" >
            	<select id="selectResponsableBusqueda" name="selectResponsableBusqueda" onchange="obtenerClientes()" style="width:95px" class="cajas">
                    <option value="0">Responsable</option>
					<?php
                    foreach($responsables as $row)
                    {
                        echo '<option '.($idResponsable==$row->idResponsable?'selected="selected"':'').' value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
                    }
                    ?>
                </select>
            </th>
			<th class="encabezadoPrincipal" style="display: none">
            	<select id="selectStatusBusqueda" name="selectStatusBusqueda" onchange="obtenerClientes()" style="width:90px" class="cajas">
                    <option value="0">CRM</option>
					<?php
                    foreach($status as $row)
                    {
                        echo '<option '.($idStatus==$row->idStatus?'selected="selected"':'').' value="'.$row->idStatus.'">'.$row->nombre.'</option>';
                    }
                    ?>
                </select>
            </th>
			<th class="encabezadoPrincipal" width="37%">Acciones</th>
		</tr>
	
	<?php
	$i=1;
	$c=$inicio;
	foreach ($clientes as $row)
	{
		$estilo			= $i%2>0?'class="sinSombra"':'class="sombreado"';
		
		$contacto		= $this->clientes->obtenerContactoCliente($row->idCliente);
		$contacto		= $contacto!=null?$contacto->nombre:'Sin contactos';
		
		$cobradoVentas	= $this->clientes->sumarCobradoClientesVentas($row->idCliente);
		#$cobrado		=$this->clientes->sumarCobradoClientes($row->idCliente);
		$ventas			= $this->clientes->sumarVentasCliente($row->idCliente);


		?>
			<tr <?php echo $estilo?>>
				<td align="right"> <?php echo $c?> </td>
				<td align="left">  <?php echo $contacto ?> </td>
				<td align="left" <?php echo $permisoVenta[0]->activo==1?'onclick="window.location.href=\''.base_url().'clientes/ventas/'.$row->idCliente.'\'"':''?>>  
				<?php 
					if(sistemaActivo=='IEXE')
					{
						echo '<a>'.$row->nombre.' '.$row->paterno.' '.$row->materno.'</a><br />';
					}
					else
					{
						echo '<a>'.$row->empresa.'</a><br />';
					}
					
					
					if($row->prospecto=="1") echo 'Prospecto';

					if($row->prospecto=="0") echo sistemaActivo=='IEXE'?'Alumno':'Cliente';
				
				
					if($row->prospecto=="2")
					{
						echo 'Cliente';
					}
					if($row->prospecto=="3")
					{
						#echo 'Potencial';
					}
					
					if(sistemaActivo=='IEXE')
					{
						if($row->idTipo==1)	
						{
							echo '<br />Normal';
						}
						if($row->idTipo==2)	
						{
							echo '
							<div align="center">
								<div style="background-color: red" class="circuloStatus"></div>								
								VIP
							</div>';
						}
					}
				?> </td>
				<td align="left"  >  <?php echo $row->alias ?> </td>
				<td align="left" style="display: none">  <?php echo $row->variable ?> </td>
				<td align="left">  <?php echo $row->telefono ?> </td>
                <td align="right">$<?php echo number_format($ventas,2) ?> </td>
                <td align="right">$<?php echo number_format($ventas-$cobradoVentas,2) ?> </td>
				
				<?php
				$estatus		= "";
				$fecha			= "";
				$servicio		= "";
				$responsable	= "";
				
				$seguimiento	= $this->clientes->obtenerUltimoSeguimiento($row->idCliente,$permisoCrm[4]->activo);
				
				if($idStatus>0)
				{
					$seguimiento	= $this->clientes->obtenerUltimoSeguimientoStatus($idStatus,$row->idCliente,$permisoCrm[4]->activo);
				}
				
				if($seguimiento!=null)
				{
					$fecha			= $seguimiento->fecha;
					$servicio		= $seguimiento->servicio;
					$responsable	= $seguimiento->responsable;

					$estatus='<span onclick="detallesSeguimiento('.$seguimiento->idSeguimiento.')"><div style="background-color: '.$seguimiento->color.'" class="circuloStatus"></div>
					<i style="font-weight:100">'.$seguimiento->status.'<br />'.obtenerFechaMesCortoHora($seguimiento->fecha).'</i></span>';
				}
				?>
			 	<td align="left" style="display: none">  <?php echo $responsable ?> </td>
				<td style="display: none" align="center"> <?php if($permisoCrm[0]->activo==1)echo $estatus ?> </td>
				
				
				<td align="center" valign="middle">
					<?php
                    
						echo'
						
						<img src="'.base_url().'img/banco.png" id="fichaTecnica'.$i.'" width="22" height="22" title="Ficha tecnica" style="cursor:pointer" onclick="obtenerSucursalesCliente('.$row->idCliente.')" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="'.base_url().'img/fichaTecnica.png" id="fichaTecnica'.$i.'" width="22" height="22" title="Ficha tecnica" style="cursor:pointer" onclick="fichaTecnicaCliente('.$row->idCliente.')" />
						
						&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="'.base_url().'img/rastreo.png" id="btnDirecciones'.$i.'" width="22" height="22" title="Direcciones fiscales y de envío" style="cursor:pointer" onclick="obtenerCatalogoDirecciones('.$row->idCliente.')" />
						
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img id="btnCrm'.$i.'" src="'.base_url().'img/crm.png" width="22" height="22" border="0" title="Seguimiento" style="cursor:pointer" onclick="obtenerSeguimientoCliente('.$row->idCliente.')" />';

				   		//DE MOMENTO LOS PROYECTOS QUEDARAN PENDIENTES
                        echo '
						<!--&nbsp;&nbsp;&nbsp;&nbsp;
                        <img id="btnProyectos'.$i.'" src="'.base_url().'img/notas.png" width="22" height="22" title="Proyectos" style="cursor:pointer" onclick="obtenerProyectos('.$row->idCliente.')" />-->
                    
                         &nbsp;&nbsp;&nbsp;
                        <img id="btnFicheros'.$i.'" src="'.base_url().'img/fichero.png" width="22" height="22" title="Ficheros" style="cursor:pointer" onclick="obtenerFicheros('.$row->idCliente.')" />
						
						&nbsp;&nbsp;
						<img id="btnEditar'.$i.'" style="cursor:pointer" src="'.base_url().'img/editar.png" width="22" height="22" title="Editar cliente" alt="Editar cliente" onclick="accesoEditarCliente('.$row->idCliente.')" />
						
						&nbsp;&nbsp;&nbsp;
						<a id="btnFacturas'.$i.'" href="'.base_url().'facturacion/facturasCliente/'.$row->idCliente.'"><img src="'.base_url().'img/pdf.png" width="22" height="22" border="0" title="Facturas"/></a>
						&nbsp;&nbsp;
						
						<!--&nbsp;&nbsp;
						<img onclick="obtenerMapa('.$row->idCliente.')" src="'.base_url().'img/mapa.png" alt=" " width="22" height="22" border="0" title="Ver mapa" />-->
					   
						
						<img id="btnBorrar'.$i.'" onclick="accesoBorrarCliente('.$row->idCliente.')" src="'.base_url().'img/borrar.png" width="22" height="22" border="0" title="Borrar Cliente" alt="Borrar Cliente" />
						
						<br />
						
						<a>Sucursales</a>
						<a>Ficha</a>
						<a id="a-btnCrm'.$i.'">Direcciones</a>
						<a id="a-btnDirecciones'.$i.'">CRM</a>
						<!-- <a class="a-btnProyectos'.$i.'">Proyectos</a>-->
						<a id="a-btnFicheros'.$i.'">Ficheros</a>
						
						<a id="a-btnEditar'.$i.'">Editar</a>
						
						<a id="a-btnFacturas'.$i.'">Facturas</a>
						<!--<a>Mapa</a>-->
						<a id="a-btnBorrar'.$i.'">Borrar</a>';
						
						if($permiso[1]->activo==0)
						{
							echo '
							<script>
								desactivarBotonSistema(\'btnFicheros'.$i.'\');
							</script>';
						}
						
						if($permisoFactura[0]->activo==0)
						{
							echo '
							<script>
								desactivarBotonSistema(\'btnFacturas'.$i.'\');
							</script>';
						}
						
						if($permisoCrm[0]->activo==0)
						{
							echo '
							<script>
								desactivarBotonSistema(\'btnCrm'.$i.'\');
							</script>';
						}
						
						if($permiso[2]->activo==0)
						{ 
							echo '
							<script>
								desactivarBotonSistema(\'btnEditar'.$i.'\');
							</script>';
						}
						
						if($permiso[3]->activo==0 or $row->publico==1)
						{ 
							echo '
							<script>
								desactivarBotonSistema(\'btnBorrar'.$i.'\');
							</script>';
						}

                    ?>
				</td>
			</tr>
		
		<?php
		$i++;
		$c++;
	 }
	?>
	
	</table>
	
	
	<?php
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagClientes">'.$this->pagination->create_links().'</ul>
	</div>';
/*}
else
{
	echo'<div class="Error_validar" style=" width:95%; margin-top:5%">No hay registros de clientes</div>';
}*/
?>