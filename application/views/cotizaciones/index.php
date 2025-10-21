<script src="<?php echo base_url()?>js/cotizaciones/cotizacionesClientes.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/cotizaciones/cotizacionesAdministracion.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/cotizaciones/clientesCotizaciones.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/cotizaciones/motivos.js?v=<?=rand()?>" ></script>
<script src="<?php echo base_url()?>js/cotizaciones/asignadas.js?v=<?=rand()?>" ></script>
<script src="<?php echo base_url()?>js/cotizaciones/cancelar.js?v=<?=rand()?>" ></script>
<script src="<?php echo base_url()?>js/cotizaciones/descuentos.js?v=<?=rand()?>" ></script>
<script src="<?php echo base_url()?>js/cotizaciones/contactoClientes.js?v=<?=rand()?>" ></script>
<script src="<?php echo base_url()?>js/ventas/ventasFacturacion.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/ventas/sucursales.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/informacion.js?v=<?=rand()?>"></script>

<!--CRM DE SERVICIOS-->
<script src="<?php echo base_url()?>js/clientes/seguimiento/detalles.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/clientes/seguimiento/archivos.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/crm/clientes/servicios/servicios.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/crm.js?v=<?=rand()?>"></script>

<script type="text/javascript">
$(document).ready(function()
{
	obtenerCotizaciones();
	$('#txtInicio,#txtFin').datepicker({changeMonth: true, changeYear: true});

	$('#txtBusquedaCotizacion').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerCotizaciones();
		}
	});
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
	Cotizaciones
</div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        	<?php
            if($permiso[1]->activo==1)
			{
				/*echo '
				<a onclick="formularioCotizaciones(0)"  >
					<span class="icon-option"  title="Agregar cotización" style="cursor:pointer">
					<img src="'.base_url().'img/add.png" alt="a" border="0" title="Agregar cotización" /> 
					</span>Agregar
				</a>';*/
			}
			?>
        	
        </td>
        <td align="center">
        	<input type="text"  name="txtBusquedaCotizacion" id="txtBusquedaCotizacion" class="busquedas" placeholder="Buscar cotización"  style="width:500px;"/>
            
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text"  	name="txtInicio" id="txtInicio" class="busquedas" style="width:100px;" onchange="obtenerCotizaciones()" value="<?php echo date('Y-01-01')?>"/>
            <input type="text"  	name="txtFin" id="txtFin" class="busquedas" style="width:100px;" onchange="obtenerCotizaciones()" value="<?php echo date('Y-12-31')?>"/>
            <input type="hidden"  	name="txtOrden" id="txtOrden" value="desc"/>
            <input type="hidden"  	name="txtIdServicioCrm" id="txtIdServicioCrm" value="1"/>
            <input type="hidden"  	name="txtIdClienteCrm" id="txtIdClienteCrm" value="0"/>

			<select id="selectDeglose" name="selectDeglose" class="cajas" onchange="obtenerCotizaciones()" style="width:150px;">
				<option value="0">Con desglose</option>
				<option value="1">Sin desglose</option>
			</select>
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="procesandoInformacion"></div>
    <div id="cancelandoCotizacion"></div>
    
    <ul class="menuTabs">
        <li class="activado">Cotizaciones</li>
        <li onclick="window.location.href='<?php echo base_url()?>cotizaciones/desasignadas'">No asignadas</li>
        <li onclick="window.location.href='<?php echo base_url()?>cotizaciones/procesadas'">Procesadas</li>
    </ul>
    
	<div id="obtenerCotizaciones">
		 <input type="hidden"  	name="selectEstaciones" id="selectEstaciones" value="0"/>
	</div>
</div>

<div id="ventanaCotizacionesInformacion" title="Detalles de cotización">
<div id="obtenerCotizacionInformacion"></div>
</div>

<div id="ventanaMargenCotización" title="Margen cotización">
<div id="formularioMargenCotizacion"></div>
</div>

<div id="ventanaConvertirVenta" title="Convertir cotización a venta">
<div id="conviertiendoVenta"></div>
<div id="obtenerDetallesCotizacion"></div>
</div>

<div id="ventanaEditarCotizacion" title="Editar cotización">
<input type="hidden" id="txtRecargar" value="0" />
<div id="procesandoC"></div>
<div id="obtenerCotizacion"></div>
</div>

<div id="ventanaCorreo" title="Enviar cotización por correo">
<div id="enviandoCorreo"></div>
<div id="formularioCorreo"></div>
</div>

<div id="ventanaFormularioEditarCotizacion" title="Editar cotización">
    <div id="editandoCotizacion"></div>
    <div id="formularioEditarCotizacion"></div>
</div>

<div id="ventanaCotizaciones" title="Cotizaciones">
    <div id="realizandoCotizacion"></div>
    <div id="formularioCotizaciones"></div>
</div>

<div id="ventanaProcesarCotizacion" title="Registrar cotización">
    <div id="registrandoCotizacion"></div>
    <div id="formularioProcesarCotizacion"></div>
</div>

<div id="ventanaStockSucursales" title="Stock sucursales">
	<div id="obtenerStockSucursales"></div>
</div>

<div id="ventanaCotizacionAsignada" title="Detalles de cotización">
    <div id="desasignandoCotizacion"></div>
    <div id="obtenerCotizacionAsignada"></div>
</div>

<div id="ventanaMotivos" title="Motivos" >
    <div id="registrandoMotivo"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerListaMotivos"></div>
</div>

<div id="ventanaEditarMotivo" title="Editar motivo" >
    <div id="editandoMotivo"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerMotivo"></div>
</div>

<div id="ventanaAsignarDescuento" title="Asignar descuento">
	<table class="admintable" width="100%">
    	<tr>
        	<td class="key">Descuento:</td>
            <td><input type="text" class="cajas" id="txtAsignarDescuento" value="0" onkeypress="return soloDecimales(event)" maxlength="6" /></td>
        </tr>
    </table>
</div>

<?php $this->load->view('clientes/seguimiento/crmServicios/modalesSeguimientoServicios');?>

</div>
