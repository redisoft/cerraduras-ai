
<?php
/*if(!empty($clientes))
{
*/	echo'
	<div style="width:90%; margin-top:5%;">
		<ul id="pagination-digg" class="ajax-pagClientes">'.$this->pagination->create_links().'</ul>
	</div>';
	?>

	<table class="admintable" width="100%">
    	<?php
        echo '
		<tr>
			<td colspan="12" class="sinbordeTransparente">
				<ul class="menuTabs">
					<li class="activado" style="margin-top:10px; width: 150px">Alumnos</li> 
					<li style="margin-top:10px; width: 150px" onclick="window.location.href=\''.base_url().'clientes\'">Universo</li> 
					<li style="margin-top:10px; width: 150px" onclick="window.location.href=\''.base_url().'clientes/preinscritos\'">Pre-Inscritos</li> 
					
				</ul>
			</td>
		</tr>';
		?>
        
		<tr>
			<th class="encabezadoPrincipal" width="2%" align="center" >#<br /><?=$registros?></th>
			<th class="encabezadoPrincipal" align="left"><?php echo 'Alumno'; ?></th>
            <th class="encabezadoPrincipal" align="left">
				<select id="selectMatricula" name="selectMatricula" onchange="obtenerClientes()" style="width:100px" class="cajas">
                    <option value="0">Matrícula</option>
                    <option <?=$matricula=='1'?'selected="selected"':''?>  value="1">Con matrícula</option>
                    <option <?=$matricula=='2'?'selected="selected"':''?> value="2">Sin matrícula</option>
                </select>
                
            </th>
            <th class="encabezadoPrincipal" align="left">
            	
                <select id="selectProgramaBusqueda" name="selectProgramaBusqueda" onchange="obtenerClientes()" style="width:100px" class="cajas">
                    <option value="0">Programa</option>
					<?php
                    foreach($programas as $row)
                    {
                        echo '<option '.($idPrograma==$row->idPrograma?'selected="selected"':'').' value="'.$row->idPrograma.'">'.$row->nombre.'</option>';
                    }
                    ?>
                </select>
                
            </th>
            
            <th class="encabezadoPrincipal" align="left">
            	
                <select id="selectCampanasBusqueda" name="selectCampanasBusqueda" onchange="obtenerClientes()" style="width:100px" class="cajas">
                    <option value="0">Campaña</option>
					<?php
                    foreach($campanas as $row)
                    {
                        echo '<option '.($idCampana==$row->idCampana?'selected="selected"':'').' value="'.$row->idCampana.'">'.$row->nombre.'</option>';
                    }
                    ?>
                </select>
                
            </th>
            
            <th class="encabezadoPrincipal" align="left" style="display:none">Cursando</th>
            
            
            <th class="encabezadoPrincipal" >
            	 <?php
				 	$totalRegistros=0;
                    foreach($zonas as $row)
					{
						$totalRegistros+=$row['numeroClientes'];
					}
					?>
                    
            	<select id="selectZonasBuscar" name="selectZonasBuscar" class="cajas" style="display:none">
                	<option value="1">Activos</option>
                </select>
                
                Activos
            </th>
            
            <th class="encabezadoPrincipal" align="left" style="display:none">
            	
                <select id="selectDiaPago" name="selectDiaPago" onchange="obtenerClientes()" style="width:80px" class="cajas">
                    <option value="0">Día pago</option>
					<?php
                    foreach($diasPago as $row)
                    {
                        echo '<option '.($diaPago==$row->diaPago?'selected="selected"':'').'>'.$row->diaPago.'</option>';
                    }
                    ?>
                </select>
            
            </th>
            <th class="encabezadoPrincipal" style="display:none" align="left">Colegiatura <br /> $<?=number_format($colegiaturas,decimales)?></th>
			<th class="encabezadoPrincipal" style="display:none" >Teléfono</th>
			<th class="encabezadoPrincipal" >
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
			<th class="encabezadoPrincipal" >
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
            <th class="encabezadoPrincipal" >
            
            	Último acceso
                
                <?php 
				echo '<img src="'.base_url().'img/'.($orden=='asc'?'ocultar':'mostrar').'.png" onclick="ordenClientes('.($orden=='asc'?'\'desc\'':'\'asc\'').')" width="20" />';
				?>
            
            </th>
			<th class="encabezadoPrincipal" width="35%">Acciones</th>
		</tr>
	
	<?php
	$i=1;
	$c=$inicio;
	foreach ($clientes as $row)
	{
		$estilo			= $i%2>0?'class="sinSombra"':'class="sombreado"';

		$cobradoVentas	= $this->clientes->sumarCobradoClientesVentas($row->idCliente);
		$ventas			= $this->clientes->sumarVentasCliente($row->idCliente);
		$academico		= $this->clientes->obtenerAcademicoCliente($row->idCliente);


		?>
			<tr <?php echo $estilo?>>
				<td align="right"> <?php echo $c?> </td>
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
                <td align="left">  <?php echo $row->matricula ?> </td>
                <td align="left">  <?php echo $academico!=null?$academico->programa:'' ?> </td>
                <td align="left">  <?php echo $row->campana ?> </td>
                <td align="center" style="display:none">  <?php echo $academico!=null?$academico->periodoActual:'' ?> </td>
                
				<td align="left">  <?php echo $row->variable ?> </td>
                <td align="center" style="display:none">  <?php echo $academico!=null?$academico->diaPago:'' ?> </td>
                <td align="center" style="display:none">  $<?php echo $academico!=null?number_format($academico->colegiatura,decimales):'' ?> </td>
                
				<td align="left" style="display:none">  
				<?php 
					echo $row->lada.''.$row->telefono;
					echo '<br />'.$row->ladaMovil.''.$row->movil;
				?> 
                </td>
                
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
			 	<td align="left">  <?php echo $responsable ?> </td>
				<td align="center"> <?php if($permisoCrm[0]->activo==1)echo $estatus ?> </td>
				
                 <td align="center">  
				 	<?php 
						echo strlen($row->ultimaConexion)>2? obtenerFechaMesCortoHora($row->ultimaConexion):''; 
						
						
					?> 
                    
                   
                 </td>
				
				<td align="center" valign="middle">
					<?php
                    
						if(sistemaActivo=='IEXE')
						{
							echo '
							<img src="'.base_url().'img/estadocuenta.png" id="btnEstadoCuenta'.$i.'" width="22" height="22" title="Estado de cuenta" style="cursor:pointer" onclick="obtenerEstadoCuenta('.$row->idCliente.')" />
							&nbsp;&nbsp;';
						}
						
						echo'
						<img src="'.base_url().'img/fichaTecnica.png" id="fichaTecnica'.$i.'" width="22" height="22" title="Ficha tecnica" style="cursor:pointer" onclick="fichaTecnicaCliente('.$row->idCliente.')" />
						
						&nbsp;
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
						&nbsp;
						
						&nbsp;&nbsp;
						<img onclick="obtenerMapa('.$row->idCliente.')" src="'.base_url().'img/mapa.png" alt=" " width="22" height="22" border="0" title="Ver mapa" />
					   
						&nbsp;&nbsp;&nbsp;
						<img id="btnBorrar'.$i.'" onclick="accesoBorrarCliente('.$row->idCliente.')" src="'.base_url().'img/borrar.png" width="22" height="22" border="0" title="Borrar Cliente" alt="Borrar Cliente" />
						
						<br />';
						
						if(sistemaActivo=='IEXE')
						{
							echo'<a>Edo. cta.</a>';
						}
						
						echo'
						<a>Ficha</a>
						<a id="a-btnCrm'.$i.'">CRM</a>
						<!-- <a class="a-btnProyectos'.$i.'">Proyectos</a>-->
						<a id="a-btnFicheros'.$i.'">Ficheros</a>
						
						<a id="a-btnEditar'.$i.'">Editar</a>
						
						<a id="a-btnFacturas'.$i.'">Facturas</a>
						<a>Mapa</a>
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