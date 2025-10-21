<script src="<?php echo base_url()?>js/proveedores/proveedores.js"></script>
<script src="<?php echo base_url()?>js/proveedores/porcentaje.js"></script>
<script src="<?php echo base_url()?>js/proveedores/catalogo.js"></script>
<script src="<?php echo base_url()?>js/proveedores/ficheros.js"></script>

<script src="<?php echo base_url()?>js/crm.js"></script>
<script src="<?php echo base_url()?>js/proveedores/importar.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/bitacora.js"></script>
<script src="<?php echo base_url()?>js/proveedores/seguimiento/proveedoresSeguimiento.js"></script>
<script src="<?php echo base_url()?>js/configuracion/servicios/catalogo.js"></script>
<script src="<?php echo base_url()?>js/configuracion/status/catalogo.js"></script>
<script src="<?php echo base_url()?>js/proveedores/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/proveedores/seguimiento/archivos.js"></script>

<!-- CONTABILIDAD -->
<script src="<?php echo base_url()?>js/contabilidad/asociarCuentas.js"></script>


<div class="derecha">
<div class="submenu"> 
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
 <table class="toolbar" width="100%;">
 	<!--<tr>
    	<td class="seccion">
    	Proveedores
   	    </td>
    </tr>-->
    <tr>
        <td width="8%">
        <?php
		 echo '
		 <a id="btnRegistrarProveedor" class="toolbar" onclick="formularioProveedores()">
			<img src="'.base_url().'img/proveedores.png" width="30px;" height="30px;" title="Añadir nuevo proveedor" /><br />
			Nuevo proveedor   
		</a>    
			
		 <td class="button" width="5%">
			<a id="btnExportar" class="toolbar" onclick="accesoImportarProveedores()">
				<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Importar" alt="Importar" /><br />
				Importar  
			</a>      
		</td>
		
		<td class="button" width="5%">
			<a id="btnImportar" class="toolbar" onclick="accesoExportarProveedores()">
				<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Exportar" alt="Exportar" /><br />
				Exportar  
			</a>      
		</td>';
		if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnRegistrarProveedor\');
				desactivarBotonSistema(\'btnExportar\');
				desactivarBotonSistema(\'btnImportar\');
			</script>';
		}
        ?>  
        </td>
      
        <td width="82%" align="left" valign="middle" style=" padding-right:100px">
            <input type="text"  	name="txtBusquedaProveedor" id="txtBusquedaProveedor" style="width:500px;" class="busquedas"  placeholder="Buscar por empresa/alias" />
            <input type="hidden"  	name="txtPaginaActivada" 	id="txtPaginaActivada"  value="proveedores"/>
            
    
            <?php
            if($idProveedor>0)
            {
                echo '<img onclick="window.location.href=\''.base_url().'proveedores\'" src="'.base_url().'img/quitar.png" title="Borrar busqueda" class="borrarBusqueda" />';
            }
          ?>         
      
         </td>  
       
    </tr>
</table>
  </div>
</div>

<div class="listproyectos">
<div id="exportandoDatos"></div>
<?php
if(!empty($proveedores))
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
    <table class="admintable" width="100%;">
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal" align="left">
			Empresa';
			 
			  if($this->session->userdata('criterioProveedores')=='a')
			  {
				echo '<a href="'.base_url().'proveedores/ordenamiento/z"><img src="'.base_url().'img/ocultar.png" width="17" /></a>';	
			  }
			  else
			  {
				  echo '<a href="'.base_url().'proveedores/ordenamiento/a"><img src="'.base_url().'img/mostrar.png" width="17" /></a>';
			  }
		  
			echo'
            </th>
			<th class="encabezadoPrincipal" align="left">Días de crédito</th>
			<th class="encabezadoPrincipal" align="left">Teléfono</th>
			<th class="encabezadoPrincipal" align="left">Comprado</th>
			<th class="encabezadoPrincipal" align="left">Saldo</th>
			<th class="encabezadoPrincipal" align="left">CRM</th>
			<th class="encabezadoPrincipal" style="width:43%">Acciones</th>
		</tr>';
	
	$i	=$inicio;
	foreach ($proveedores as $row)
	{
		$estilo			=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$pagadoCompras	=$this->proveedores->sumarPagadoProveedorCompras($row->idProveedor);
		$compras		=$this->proveedores->sumarComprasProveedor($row->idProveedor);
		#$pagado			=$this->proveedores->sumarPagadoProveedor($row->idProveedor);
	
		?>
		<tr <?php echo $estilo?>>
			<td align="center"> <?php print($i); ?> </td>
			<td align="left" 
			<?php 
				if($materiales[0]->activo==1 or $productos[0]->activo==1 or $inventarios[0]->activo==1 or $servicios[0]->activo==1)
				{
					echo 'onclick="window.location.href=\''.base_url().'compras/index/'.$row->idProveedor.'\'"';
				}
				?>
                
            > 
			 <a><?php echo $row->empresa?></a>
			</td>
            <td align="center"> <?php echo $row->diasCredito?> </td>
			<td align="left"> <?php print($row->telefono); ?> </td>
            <td align="right"> $<?php echo number_format($compras,2)?> </td>
            <td align="right"> $<?php echo number_format($compras-$pagadoCompras,2) ?> </td>
            
            <td align="center"> 
            	<?php

				$seguimiento	= $this->proveedores->obtenerUltimoSeguimiento($row->idProveedor);

				if($seguimiento!=null)
				{
					if($permisoCrm[0]->activo==1)
					{
						echo'
						<span onclick="detallesSeguimiento('.$seguimiento->idSeguimiento.')"><div style="background-color: '.$seguimiento->color.'" class="circuloStatus"></div>
						<i style="font-weight:100">'.$seguimiento->status.'<br />'.obtenerFechaMesCortoHora($seguimiento->fecha).'</i></span>';
					}
				}
				?>
            </td>
            
			<td align="left" valign="middle">
			<?php
			
			echo '
			&nbsp;
			<img src="'.base_url().'img/fichaTecnica.png" width="22" height="22" title="Ficha tecnica" style="cursor:pointer" onclick="obtenerFichaTecnicaProveedor('.$row->idProveedor.')" />
             
          	&nbsp;&nbsp;
            <img onclick="obtenerMapa('.$row->idProveedor.')" src="'.base_url().'img/mapa.png" alt=" " width="22" height="22"  title="Ver mapa" />
			
			&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnFicheros'.$i.'" src="'.base_url().'img/fichero.png" onclick="obtenerFicheros('.$row->idProveedor.')" width="22" height="22"  title="Archivos" style="cursor:pointer"  />
			&nbsp;&nbsp;
			<img id="btnCrm'.$i.'" src="'.base_url().'img/crm.png" onclick="seguimientoProveedores('.$row->idProveedor.')" width="22" height="22"  title="Crm" style="cursor:pointer"  />
			
			
			
			&nbsp;&nbsp;
			<img id="btnEditar'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22"  title="Editar" style="cursor:pointer" onclick="accesoEditarProveedor('.$row->idProveedor.')" />
			
			&nbsp;&nbsp;&nbsp;
			<a id="btnCompras'.$i.'"  href="'.base_url().'compras/index/'.$row->idProveedor.'">
				<img src="'.base_url().'img/compras.png" width="22" height="22"  title="Compras" />
			</a>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a id="btnContactos'.$i.'"  href="'.base_url().'proveedores/contactos/'.$row->idProveedor.'">
				<img src="'.base_url().'img/contactos.png" width="22" height="22"  title="Ver contactos" />
			</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnBorrar'.$i.'" onclick="borrarProveedor('.$row->idProveedor.',\'¿Realmente desea borrar el registro del proveedor?\')" src="'.base_url().'img/borrar.png" width="22" height="22"  title="Borrar proveedor" />
			
			&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnAsignarPorcentaje'.$i.'" onclick="formularioPorcentaje('.$row->idProveedor.')" src="'.base_url().'img/descuento.png" width="22" height="22"  title="Actualizar" />
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnMarcas'.$i.'" onclick="obtenerMarcasProveedor('.$row->idProveedor.')" src="'.base_url().'img/promotores.png" width="22" height="22"  title="Marcas" />
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnBorrarInventario'.$i.'" onclick="accesoBorrarInventarioProveedor('.$row->idProveedor.')" src="'.base_url().'img/mermas.png" width="22" height="22"  title="Borrar inventario" />
			
			<br />
			<a>Ficha</a> &nbsp;
            <a>Mapa</a>
			<a id="a-btnFicheros'.$i.'">Archivos</a>
			<a id="a-btnCrm'.$i.'">CRM</a>
			<a id="a-btnEditar'.$i.'">Editar</a>
			<a id="a-btnCompras'.$i.'">Compras</a>
			<a id="a-btnContactos'.$i.'">Contactos</a>
			<a id="a-btnBorrar'.$i.'">Borrar</a>
			<a id="a-btnAsignarPorcentaje'.$i.'">Actualizar</a>
			<a id="a-btnMarcas'.$i.'">Marcas</a>
			<a id="a-btnBorrarInventario'.$i.'">Borrar inventario</a>';
		
			
			if($permiso[1]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnFicheros'.$i.'\');
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
					desactivarBotonSistema(\'btnAsignarPorcentaje'.$i.'\');
				</script>';
			}
			
			if($permisoContacto[0]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnContactos'.$i.'\');
				</script>';
			}

			if($permiso[3]->activo==0 or $row->idProveedor==1)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnBorrar'.$i.'\');
				</script>';
			}
		
			if($permiso[3]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnBorrarInventario'.$i.'\');
				</script>';
			}
			
			if($materiales[0]->activo==0 and $productos[0]->activo==0 and $inventarios[0]->activo==0 and $servicios[0]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnCompras'.$i.'\');
				</script>';
			}
			?>
			
			
			</td>
		</tr>
	
		<?php
		$i++;
	}

	echo'
	</table>
	
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar" style="margin-top:2px; margin-bottom: 5px; width:95%">No hay registros de proveedores</div>';
}
?>


<div id="ventanaProveedores" title="Registrar proveedor">
<div id="cargandoProveedores"></div>
<div class="ui-state-error" ></div>
<div id="formularioProveedores"></div>    
</div>

<div id="ventanaEditarProveedores" title="Editar proveedor">
<div style="width:99%;" id="cargandoEditarProveedores"></div>
<div id="ErrorEditarProveedores" class="ui-state-error" ></div>
<div id="cargarProveedores"></div>
</div>


<div id="ventanaSeguimientoProveedores" title="Seguimiento">
<div id="cargandoSeguimiento"></div>
<table class="admintable" style="width:100%">
	<tr>
    	<th>Busqueda por fechas</th>
    </tr>
    <tr>
    	<td align="center">
        	<input onchange="obtenerSeguimientoProveedorFechas()" type="text" class="cajas" style="width:100px" value="<?php echo date('Y-m-01')?>" id="txtInicioSeguimiento"  />
            <input onchange="obtenerSeguimientoProveedorFechas()" type="text" class="cajas" style="width:100px" value="<?php echo date('Y-m-'.$this->configuracion->obtenerUltimaDiaFecha(date('Y-m-d')))?>" id="txtFinSeguimiento" />
            <script>
				$('#txtInicioSeguimiento,#txtFinSeguimiento').datepicker();
			</script>
        </td>
    </tr>
</table>

<div id="seguimientoProveedores"></div>
</div>

<div id="ventanaFormularioSeguimiento" title="Registrar seguimiento">
<div id="registrandoSeguimiento"></div>
<div id="formularioSeguimiento"></div>
</div>

<div id="ventanaEditarSeguimiento" title="Editar seguimiento">
<div id="editandoSeguimientoProveedor"></div>
<div id="obtenerSeguimientoEditar"></div>
</div>

<div id="ventanaDetallesSeguimiento" title="Detalles de seguimiento">
    <div id="errorDetallesSeguimiento" class="ui-state-error" ></div>
    <div id="detallesSeguimiento"></div>
</div>

<div id="ventanaFormularioCuentas" title="Cuentas">
<div id="registrandoCuenta"></div>
<div id="formularioCuentas"></div>
</div>

<div id="ventanaArchivosSeguimiento" title="Archivos">
    <div id="registrandoArchivosSeguimiento"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerArchivosSeguimiento"></div>
</div>

<div id="ventanaEditarCuenta" title="Editar cuenta">
<div id="editandoCuenta"></div>
<div id="obtenerCuenta"></div>
</div>

<div id="ventanaMapaProveedor" title="Mapa del proveedor">
<div class="ui-state-error" ></div>
<div id="obtenerMapa"></div>    
</div>

<div id="ventanaImportarProveedores" title="Importar proveedores">
    <div id="importandoProveedores"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioImportarProveedores"></div>
</div>

<div id="ventanaCatalogoServicios" title="Catálogo de servicios">
	<div id="obtenerCatalogoServicios"></div>
</div>

<div id="ventanaCatalogoStatus" title="Catálogo de CRM">
	<div id="obtenerCatalogoStatus"></div>
</div>

<div id="ventanaFicheros" title="Ficheros">
    <div id="registrandoFicheros"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerFicheros"></div>
</div>

<input type="hidden"  name="txtGrupoActivo" id="txtGrupoActivo" value="Pasivo" />
<div id="ventanaFormularioAsociarCuenta" title="Cuentas contables">
    <div id="asociandoCuentas"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioAsociarCuenta"></div>
</div>
	
<div id="ventanaPorcentaje" title="Porcentaje">
    <div id="registrandoPorcentaje"></div>
    <div id="formularioPorcentaje"></div>
</div>
	
<?php
$this->load->view('proveedores/marcas/modales');	
?>

</div>
</div>
