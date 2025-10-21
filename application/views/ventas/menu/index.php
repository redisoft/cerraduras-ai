<script src="<?php echo base_url()?>js/cotizaciones/cotizacionesClientes.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/cotizacionesAdministracion.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/clientesCotizaciones.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/motivos.js" ></script>
<script src="<?php echo base_url()?>js/cotizaciones/asignadas.js" ></script>
<script src="<?php echo base_url()?>js/cotizaciones/cancelar.js" ></script>
<script src="<?php echo base_url()?>js/cotizaciones/contactoClientes.js" ></script>
<script src="<?php echo base_url()?>js/ventas/ventasFacturacion.js"></script>
<script src="<?php echo base_url()?>js/ventas/sucursales.js"></script>
<script src="<?php echo base_url()?>js/informacion.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	/*obtenerCotizaciones();
	$('#txtInicio,#txtFin').datepicker({changeMonth: true, changeYear: true});*/
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar" >
<div class="seccionDiv">
	Cotizaciones
</div>
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        	<?php
            if($permiso[1]->activo==1)
			{
				echo '
				<a onclick="formularioCotizaciones(0)"  >
					<span class="icon-option"  title="Agregar cotización" style="cursor:pointer">
					<img src="'.base_url().'img/add.png" alt="a" border="0" title="Agregar cotización" /> 
					</span>Agregar
				</a>';
			}
			?>
        	
        </td>
        <td align="center">
        	<input type="text"  name="txtBuscarCotizacion" id="txtBuscarCotizacion" class="busquedas" placeholder="Buscar cotización"  style="width:500px;"/>
            
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text"  name="txtInicio" id="txtInicio" class="busquedas" style="width:100px;" onchange="obtenerCotizaciones()" value="<?php echo date('Y-01-01')?>"/>
            <input type="text"  name="txtFin" id="txtFin" class="busquedas" style="width:100px;" onchange="obtenerCotizaciones()" value="<?php echo date('Y-12-31')?>"/>
            <input type="hidden"  name="txtOrden" id="txtOrden" value="desc"/>
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
    
	<div id="obtenerCotizaciones"></div>
</div>

<!--<div id="ventanaCancelarFactura" title="Cancelar CFDI">
<div id="cargandoCancelacion"></div>
<div class="ui-state-error" ></div>
<div id="obtenerFacturaCancelar"></div>
</div>


<div id="ventanaEnviarCorreo" title="Enviar factura por correo electrónico">
<div id="enviandoCorreo"></div>
<div id="errorCorreo" class="ui-state-error" ></div>
<div id="formularioCorreo"></div>
</div>

<div id="ventanaInformacionFactura" title="Detalles de factura">
<div id="obtenerInformacionFactura"></div>
</div>-->


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

<div id="ventanaCorreo" title="Enviar cotización por correo:">
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


</div>
