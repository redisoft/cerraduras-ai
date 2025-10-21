<script src="<?php echo base_url()?>js/clientes/seguimiento/seguimiento.js"></script>
<script src="<?php echo base_url()?>js/clientes/clientes.js"></script>
<script src="<?php echo base_url()?>js/clientes/preinscritos/preinscritos.js"></script>


<script src="<?php echo base_url()?>js/clientes/preinscritos/cobros.js"></script>
<script src="<?php echo base_url()?>js/administracion/calculos.js"></script>

<script src="<?php echo base_url()?>js/clientes/catalogo.js"></script>
<script src="<?php echo base_url()?>js/clientes/importar.js"></script>
<script src="<?php echo base_url()?>js/bancos/bancos.js"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js"></script>
<script src="<?php echo base_url()?>js/informacion.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/bitacora.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/editarSeguimientoClientes.js"></script>
<script src="<?php echo base_url()?>js/ventas/faltantesTraspasos.js"></script>
<script src="<?php echo base_url()?>js/ventas/ventas.js"></script>
<script src="<?php echo base_url()?>js/ventas/ventasFacturas.js"></script>
<script src="<?php echo base_url()?>js/ventas/sucursales.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/cotizacionClientes.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/descuentos.js" ></script>
<script src="<?php echo base_url()?>js/configuracion/zonas/catalogo.js"></script>
<script src="<?php echo base_url()?>js/clientes/contactos/catalogo.js"></script>
<script src="<?php echo base_url()?>js/informacion.js"></script>

<!-- CRM -->
<script src="<?php echo base_url()?>js/clientes/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/clientes/seguimiento/archivos.js"></script>
<script src="<?php echo base_url()?>js/configuracion/status/catalogo.js"></script>
<script src="<?php echo base_url()?>js/configuracion/servicios/catalogo.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>

<script src="<?php echo base_url()?>js/configuracion/estatus/catalogo.js"></script>

<script src="<?php echo base_url()?>js/configuracion/programas/catalogo.js"></script>

<!-- CONTABILIDAD -->
<script src="<?php echo base_url()?>js/contabilidad/asociarCuentas.js"></script>

<!-- ESTADO DE CUENTA -->
<script src="<?php echo base_url()?>js/clientes/estadoCuenta/estadoCuenta.js"></script>

<!-- DOCUMENTOS -->
<script src="<?php echo base_url()?>js/clientes/documentos/documentos.js"></script>
<link href="<?php echo base_url()?>css/pekeUpload/bootstrap/css/bootstrap.css" rel="stylesheet">   
<link href="<?php echo base_url()?>css/pekeUpload/custom.css" rel="stylesheet">   
<script src="<?php echo base_url()?>js/bibliotecas/pekeUpload/pekeUploadDocumentos.js"></script>

<script>
$(document).ready(function()
{
	window.setTimeout(function() 
	{
		$('#txtFechaMes').val('')
	}, 100);
	
	$('#txtFechaInicio,#txtFechaFin').datepicker();
});
</script>


<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
  <table class="toolbar" width="100%">
    <tr>
    	<?php
        echo '
		<td class="button" width="5%">
			<a id="btnExportar" onclick="excelPreinscritosClientes()">
				<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Exportar" alt="Exportar" /><br />
				Exportar  
			</a>      
		</td>';
		?>
        <td width="80%" align="left" valign="middle" >
        
        	<input type="text"  name="txtFechaInicio" id="txtFechaInicio" class="busquedas" value="<?=date('Y-m-01')?>" style="width:100px" readonly="readonly" onchange="obtenerPreinscritos()" />
            <input type="text"  name="txtFechaFin" id="txtFechaFin" class="busquedas" value="<?=date('Y-m-d')?>" style="width:100px" readonly="readonly"  onchange="obtenerPreinscritos()"/>
            
        	<input type="text"  name="txtBusquedasPreinscritos" id="txtBusquedasPreinscritos" class="busquedas" placeholder="Buscar por nombre, teléfono, email" style="width:600px" />
            
            <input type="hidden"  name="txtGrupoActivo" id="txtGrupoActivo" value="Activo" /> 
            <input type="hidden"  name="txtTipoRegistro" id="txtTipoRegistro" value="clientes" />
        </td>  
    </tr>
</table> 
</div>       
</div>
       
<div class="listproyectos">
	<div id="exportandoReporte"></div>
	
    <div id="obtenerPreinscritos">
    	<input type="hidden" name="selectResponsableBusqueda" 	id="selectResponsableBusqueda" 	value="0" /> 
        <input type="hidden" name="selectStatusBusqueda" 		id="selectStatusBusqueda" 		value="0" /> 
        <input type="hidden" name="selectZonasBuscar" 			id="selectZonasBuscar" 			value="0" /> 
        <input type="hidden" name="selectProgramaBusqueda" 		id="selectProgramaBusqueda" 	value="0" /> 
        <input type="hidden" name="selectCampanasBusqueda" 		id="selectCampanasBusqueda" 	value="0" /> 
        <input type="hidden" name="selectDiaPago" 				id="selectDiaPago" 				value="0" /> 
        <input type="hidden" name="selectMatriculaBusqueda" 	id="selectMatriculaBusqueda" 	value="0" /> 
        <input type="hidden" name="selectMesBusqueda" 			id="selectMesBusqueda" 			value="" /> 
        <input type="hidden" name="selectPeriodosBusqueda" 		id="selectPeriodosBusqueda" 	value="0" /> 
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


<div id="ventanaArchivosSeguimiento" title="Archivos">
    <div id="registrandoArchivosSeguimiento"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerArchivosSeguimiento"></div>
</div>



<div id="ventanaCatalogoZonas" title="Catálogo de <?php echo $this->session->userdata('identificador')?>">
	<div id="obtenerCatalogoZonas"></div>
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

<div id="ventanaMatricula" title="Matrícula">
    <div id="registrandoMatricula"></div>
	<div id="formularioMatricula"></div>
</div>

<div id="ventanaFormularioIngresos" title="Matrícula">
    <div id="agregandoIngresos"></div>
	<div id="formularioCobrosPreinscritos"></div>
</div>

<?php
$this->load->view('clientes/ficheros/modalFicheros');
?>

</div>

