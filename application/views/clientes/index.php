<script src="<?php echo base_url()?>js/clientes/seguimiento/seguimiento.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/clientes/clientes.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/crm/clientes/areas.js?v=<?php echo(rand());?>"></script>

<script src="<?php echo base_url()?>js/clientes/catalogo.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/clientes/importar.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/bancos/bancos.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/informacion.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/crm/clientes/bitacora.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/crm/clientes/editarSeguimientoClientes.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/ventas/faltantesTraspasos.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/ventas/ventas.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/ventas/ventasFacturas.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/ventas/sucursales.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/cotizaciones/cotizacionClientes.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/cotizaciones/descuentos.js?v=<?php echo(rand());?>" ></script>
<script src="<?php echo base_url()?>js/configuracion/zonas/catalogo.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/clientes/contactos/catalogo.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/informacion.js?v=<?php echo(rand());?>"></script>

<!-- CRM -->
<script src="<?php echo base_url()?>js/clientes/seguimiento/detalles.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/clientes/seguimiento/archivos.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/configuracion/status/catalogo.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/configuracion/servicios/catalogo.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/crm.js?v=<?php echo(rand());?>"></script>

<script src="<?php echo base_url()?>js/configuracion/estatus/catalogo.js?v=<?php echo(rand());?>"></script>

<script src="<?php echo base_url()?>js/configuracion/programas/catalogo.js?v=<?php echo(rand());?>"></script>

<!-- CONTABILIDAD -->
<script src="<?php echo base_url()?>js/contabilidad/asociarCuentas.js?v=<?php echo(rand());?>"></script>

<!-- ESTADO DE CUENTA -->
<script src="<?php echo base_url()?>js/clientes/estadoCuenta/estadoCuenta.js?v=<?php echo(rand());?>"></script>

<!-- DOCUMENTOS -->
<script src="<?php echo base_url()?>js/clientes/documentos/documentos.js?v=<?php echo(rand());?>"></script>
<link href="<?php echo base_url()?>css/pekeUpload/bootstrap/css/bootstrap.css?v=<?php echo(rand());?>" rel="stylesheet">   
<link href="<?php echo base_url()?>css/pekeUpload/custom.css?v=<?php echo(rand());?>" rel="stylesheet">   
<script src="<?php echo base_url()?>js/bibliotecas/pekeUpload/pekeUploadDocumentos.js?v=<?php echo(rand());?>"></script>

<script>
$(document).ready(function()
{
	window.setTimeout(function() 
	{
		$('#txtFechaMes').val('')
	}, 100);
	
	obtenerClientes()
});
</script>

<input type="hidden" name="txtOrden" 			id="txtOrden" value="asc" /> 

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
  <table class="toolbar" width="100%">
  	<!--<tr>
     	<td class="seccion">
    		Clientes 
   	    </td>
    </tr>-->
   <tr>
		<?php
			echo'
			<td class="button" width="5%">
				<a id="bntRegistrarCliente" onclick="formularioClientes(\'recargar\')">
					<img src="'.base_url().'img/clientes.png" width="30px;" height="30px;" title="Añadir nuevo prospecto" alt="Añadir nuevo cliente" /><br />
			   		Agregar nuevo cliente    
				</a>      
			</td>
			
			 <!--<td class="button" width="5%">
				<a id="btnPuntoVenta" onclick="formularioVentas()">
					<img src="'.base_url().'img/ventas.png" width="30px;" height="30px;" title="Registrar venta" alt="Registrar venta" /><br />
					
				   Punto de venta        
				</a>      
			</td>-->
			
			<td class="button" width="5%">
				<a id="btnPuntoVenta" href="'.base_url().'ventas/puntoVenta">
					<img src="'.base_url().'img/ventas.png" width="30px;" height="30px;" title="Registrar venta" alt="Registrar venta" /><br />
					
				   Punto de venta        
				</a>      
			</td>
			
			<td class="button" width="5%">
				<a id="btnImportar" onclick="accesoImportarClientes()">
					<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Importar" alt="Importar" /><br />
					Importar  
				</a>      
			</td>
			
			<td class="button" width="5%">
				<a id="btnExportar" onclick="accesoExportarClientes()">
					<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Exportar" alt="Exportar" /><br />
					Exportar  
				</a>      
			</td>';
			
			if(sistemaActivo=='IEXE')
			{
				echo '
				<td class="button" width="5%">
					<a id="btnPeriodos" onclick="obtenerCatalogoPeriodos()">
						<img src="'.base_url().'img/periodos.png" width="30px;" height="30px;" title="Periodos"  /><br />
						Periodos  
					</a>      
				</td>';
			}
			
			
			echo'
			<!--<td class="button" width="5%">
				<a class="toolbar" onclick="formularioCotizacionesClientes()">
				<span class="icon-option" title="Registrar cotización">
					<img src="'.base_url().'img/remision.png" width="30px;" height="30px;" title="Registrar cotización"/>
				</span>
			   Cotizaciones        
				</a>      
			</td>-->';
		
			if($permiso[1]->activo==0)
			{ 
				echo '
				<script>
					desactivarBotonSistema(\'bntRegistrarCliente\');
					desactivarBotonSistema(\'btnExportar\');
					desactivarBotonSistema(\'btnImportar\');
				</script>';
			}
			
			
			
			
			if($permisoVenta[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnPuntoVenta\');
				</script>';
			}
        ?>
         <td width="80%" align="left" valign="middle" >
			<input type="text"  name="txtBusquedas" id="txtBusquedas" class="busquedas" placeholder="<?php echo sistemaActivo=='IEXE'?'Buscar por alumno/cliente, teléfono, email, matrícula':'Buscar por cliente, teléfono, email, # cliente'?>" style="width:600px" />
         </td>  
         </tr>
         
         <tr>
         
             <td colspan="6">
                <input type="text"  name="txtCotizaciones" id="txtCotizaciones" class="busquedas" placeholder="Buscar cotización" style="width:140px"  />  
                <input type="text"  name="txtBuscarVenta" id="txtBuscarVenta" class="busquedas" placeholder="Buscar venta" style="width:140px"  />  
                <input type="text"  name="txtBuscarFactura" id="txtBuscarFactura" class="busquedas" placeholder="Buscar factura" style="width:140px"  />  
                
                <input type="hidden"  name="txtIdTienda" id="txtIdTienda" value="0"/>
                <input type="hidden"  name="txtPaginaActiva" id="txtPaginaActiva" value="clientes"/>
                

                <select id="selectServicioBusqueda" name="selectServicioBusqueda" onchange="obtenerClientes()" style="width:125px" class="cajas">
                    <option value="0">Seleccione servicio</option>
					<?php
                    foreach($servicios as $row)
                    {
                        echo '<option '.($idServicio==$row->idServicio?'selected="selected"':'').' value="'.$row->idServicio.'">'.$row->nombre.'</option>';
                    }
                    ?>
                </select>

                <input type="text" placeholder="Fecha" name="FechaDia2" id="FechaDia2" class="cajas" style="width:80px;" onchange="obtenerClientes()"  /> 

            	<input type="text" placeholder="Mes" name="txtFechaMes" id="txtFechaMes" class="cajas" style="width:80px;" /> 
            
                <select id="selectBusquedaTipo" name="selectBusquedaTipo" onchange="obtenerClientes()" style="width:130px;" class="cajas">
                    <option value="4">Registro</option>
                    
                 
                   <?php
				   
				   if(sistemaActivo=='IEXE')
				   {
					    foreach($grupos as $row)
					   {
						   echo '<option value="'.$row->prospecto.'">'.obtenerTipoAlumnoIxe($row->prospecto).' ('.$row->total.')</option>';
					   }
				   }
				   else
				   {
						echo '
						<option value="0">Cliente</option>
						<option value="1">Prospecto</option>';
				   }
				   
				  
				   
				   
                   #if(sistemaActivo=='IEXE') echo '<option value="2">Cliente</option>';
				   ?>
                </select>
                
                <input type="hidden"  name="txtGrupoActivo" id="txtGrupoActivo" value="Activo" /> 
                <input type="hidden"  name="txtTipoRegistro" id="txtTipoRegistro" value="clientes" />
            </td>
        </tr>
</table> 
</div>       
</div>
       
<div class="listproyectos">
	<div id="exportandoDatos"></div>
	
    <div id="obtenerClientes">
    	<input type="hidden" name="selectResponsableBusqueda" 	id="selectResponsableBusqueda" value="0" /> 
        <input type="hidden" name="selectStatusBusqueda" 		id="selectStatusBusqueda" value="0" /> 
        <input type="hidden" name="selectZonasBuscar" 			id="selectZonasBuscar" value="0" /> 
        <input type="hidden" name="selectProgramaBusqueda" 		id="selectProgramaBusqueda" value="0" /> 
        <input type="hidden" name="selectCampanasBusqueda" 		id="selectCampanasBusqueda" value="0" /> 
        <input type="hidden" name="selectDiaPago" 				id="selectDiaPago" value="0" /> 
        <input type="hidden" name="selectMatricula" 			id="selectMatricula" value="0" /> 
    </div>
</div>
 
<div id="ventanaClientes" title="Agregar <?php echo sistemaActivo=='IEXE'?'Alumno/Cliente':'Cliente' ?>">
    <div id="cargandoClientes"></div>
    <div id="ErrorClientes" class="ui-state-error" ></div>
    <div id="formularioClientes"></div>
</div>

<div id="ventanaFichaCliente" title="Ficha técnica del <?php echo sistemaActivo=='IEXE'?'Alumno/Cliente':'Cliente' ?>">
    <div class="ui-state-error" ></div>
    <div id="obtenerFichaCliente"></div>
</div>

<div id="ventanaEnviarFichaCliente" title="Enviar ficha técnica del <?php echo sistemaActivo=='IEXE'?'Alumno/Cliente':'Cliente' ?>">
    <div id="enviandoFichaCliente"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioCorreoFichaCliente"></div>
</div>

<div id="ventanaEditarClientes" title="Editar <?php echo sistemaActivo=='IEXE'?'Alumno/Cliente':'Cliente' ?>">
<div id="cargandoEditarClientes"></div>
<div id="ErrorEditarClientes" class="ui-state-error" ></div>
<div id="cargarClientes"></div>
</div>

<div id="ventanaSeguimientoClientes" title="Seguimiento <?php echo sistemaActivo=='IEXE'?'Alumno/Cliente':'Cliente' ?>">
<div id="siguiendoClientes"></div>
<div id="errorSeguimientoClientes" class="ui-state-error" ></div>
<table class="admintable" style="width:100%">
	<tr>
    	<th>Busqueda por fechas</th>
    </tr>
    <tr>
    	<td align="center">
        	<input type="text" style="width:0.1px" class="cajasTransparentes"/>
            
        	<input onchange="obtenerSeguimientoClienteFechas()" type="text" class="cajas" style="width:120px" value="<?php echo date('Y-m-01')?>" id="txtInicioSeguimiento"  />
            <input onchange="obtenerSeguimientoClienteFechas()" type="text" class="cajas" style="width:120px" value="<?php echo date('Y-m-'.$this->configuracion->obtenerUltimaDiaFecha(date('Y-m-d')))?>" id="txtFinSeguimiento" />
            <script>
				$('#txtInicioSeguimiento,#txtFinSeguimiento').datepicker();
			</script>
        </td>
    </tr>
</table>
<div id="cargarSeguimiento"></div>
</div>

<div id="ventanaDetallesSeguimiento" title="Detalles de seguimiento">
	
    <div id="errorDetallesSeguimiento" class="ui-state-error" ></div>
    <div id="detallesSeguimiento"></div>
</div>

<div id="ventanaFormularioSeguimiento" title="Seguimiento CRM">     <!-- Este es para el seguimiento CRM -->
    <div id="cargandoSeguimiento"></div>
    <div id="errorSeguimiento" class="ui-state-error" ></div>
    <div id="formularioSeguimiento"></div>
</div>




<div id="ventanaEditarSeguimiento" title="Editar CRM">
    <div id="editandoCrm"></div>
    <div id="errorCrm" class="ui-state-error" ></div>
    <div id="obtenerSeguimientoEditar"></div>
</div>

<div id="ventanaRegistrarNota" title="Registar nota">
<div id="registrandoNota"></div>
<div id="errorRegistrarNota" class="ui-state-error" ></div>
<div id="formularioRegistrarNota"></div>
</div>

<div id="ventanaEditarNota" title="Editar nota">
<div id="editandoNota"></div>
<div id="errorEditarNota" class="ui-state-error" ></div>
<div id="obtenerNota"></div>
</div>

<div id="ventanaNotas" title="Notas">
<div id="actualizandoNotas"></div>
<div id="errorNotas" class="ui-state-error" ></div>
<div id="obtenerNotas"></div>
</div>


<div id="ventanaProyectos" title="Proyectos">
<div id="actualizandoProyectos"></div>
<div id="obtenerProyectos"></div>
</div>

<div id="ventanaFormularioProyectos" title="Registrar proyecto">
<div id="registrandoProyecto"></div>
<div id="formularioProyectos"></div>
</div>

<div id="ventanaEditarProyecto" title="Editar proyecto">
<div id="editandoProyecto"></div>
<div id="obtenerProyecto"></div>
</div>




<div id="ventanaVentas" title="Punto de venta">
    <div id="realizandoVenta"></div>
    <div id="formularioVentas"></div>
</div>

<div id="ventanaMapaClientes" title="Mapa del <?php echo sistemaActivo=='IEXE'?'Alumno/Cliente':'Cliente' ?>">
<div class="ui-state-error" ></div>
<div id="obtenerMapa"></div>    
</div>

<div id="ventanaFuentesContacto" title="Contacto">
    <div class="ui-state-error" ></div>
    <div id="registrandoFuenteContacto"></div>
    <div id="formularioFuentesContacto"></div> 
</div>

<div id="ventanaBancos" title="Bancos">
<div id="registrandoBanco"></div>
<div class="ui-state-error" ></div>
<div id="formularioBancos"></div>
</div>

<div id="ventanaCuentas" title="Cuentas">
<div id="registrandoCuenta"></div>
<div id="errorCuenta" class="ui-state-error" ></div>
<div id="formularioCuentas"></div>
</div>

<div id="ventanaEditarCuenta" title="Editar cuenta">
<div id="editandoCuenta"></div>
<div id="errorEditarCuenta" class="ui-state-error" ></div>
<div id="obtenerCuenta"></div>
</div>

<div id="ventanaCotizaciones" title="Cotizaciones">
<div id="realizandoCotizacion"></div>
<div id="formularioCotizacionesClientes"></div>
</div>


<div id="ventanaCobrosVenta" title="Cobrar venta">
    <div id="registrandoCobroVenta"></div>
    <div id="formularioCobros"></div>
</div>

<div id="ventanaArchivosSeguimiento" title="Archivos">
    <div id="registrandoArchivosSeguimiento"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerArchivosSeguimiento"></div>
</div>

<div id="ventanaStockSucursales" title="Stock sucursales">
	<div id="obtenerStockSucursales"></div>
</div>

<div id="ventanaImportarClientes" title="Importar <?php echo sistemaActivo=='IEXE'?'Alumno/Cliente':'Cliente' ?>">
    <div id="importandoClientes"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioImportarClientes"></div>
</div>

<div id="ventanaInventarioFaltante" title="Productos con inventario faltante">
    <div id="procesandoInventarioFaltante"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioInventarioFaltante"></div>
</div>

<div id="ventanaCatalogoZonas" title="Catálogo de <?php echo $this->session->userdata('identificador')?>">
	<div id="obtenerCatalogoZonas"></div>
</div>

<div id="ventanaInformacionTienda" title="Detalles de tienda">
	<div id="obtenerInformacionTienda"></div>
</div>

<div id="ventanaCatalogoServicios" title="Catálogo de servicios">
	<div id="obtenerCatalogoServicios"></div>
</div>

<div id="ventanaCatalogoStatus" title="Catálogo de CRM">
	<div id="obtenerCatalogoStatus"></div>
</div>


<div id="ventanaCatalogoEstatus" title="Catálogo de Estatus">
	<div id="obtenerCatalogoEstatus"></div>
</div>


<div id="ventanaCatalogoProgramas" title="Catálogo de programas">
	<div id="obtenerCatalogoProgramas"></div>
</div>

<div id="ventanaAsignarDescuento" title="Asignar descuento">
	<table class="admintable" width="100%">
    	<tr>
        	<td class="key">Descuento:</td>
            <td><input type="text" class="cajas" id="txtAsignarDescuento" value="0" onkeypress="return soloDecimales(event)" maxlength="6" /></td>
        </tr>
    </table>
</div>

<div id="ventanaFormularioAsociarCuenta" title="Cuentas contables">
    <div id="asociandoCuentas"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioAsociarCuenta"></div>
</div>

<div id="ventanaEstadoCuenta" title="Estado de cuenta">
	<div id="obtenerEstadoCuenta"></div>
</div>

<div id="ventanaFormularioSeguimientoDetalle" title="Seguimiento">
    <div class="ui-state-error" ></div>
	<div id="formularioSeguimientoDetalle"></div>
</div>

<?php
$this->load->view('clientes/ficheros/modalFicheros');
 
$this->load->view('clientes/direcciones/catalogo');
$this->load->view('clientes/sucursales/modales');

?>

</div>

