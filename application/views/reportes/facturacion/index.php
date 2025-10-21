<script type="text/javascript" src="<?php echo base_url()?>js/reportes/facturacion/facturacion.js?v=<?php echo(rand());?>"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/reportes/facturacion/reportesFacturacion.js?v=<?php echo(rand());?>"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/reportes/facturacion/administracion.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js?v=<?php echo(rand());?>"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/facturacion/facturaGlobal.js?v=<?php echo(rand());?>"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/facturacion/pagos.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/clientes/direcciones/direccionesFiscales.js?v=<?php echo(rand());?>"></script>


<script src="<?php echo base_url()?>js/ventas/facturacion.js?v=<?php echo(rand());?>"></script>


<script type="text/javascript">
$(document).ready(function()
{
	$('#txtBuscarCliente,#txtBuscarFactura').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerFacturas();
		}
	});
	
	$('#txtIdCliente').val(0);
	$('#txtBuscarCliente').val('');
	
	$('#txtIdFactura').val(0);
	$('#txtBuscarFactura').val('');
	obtenerFacturas();
	
	$("#txtMes").monthpicker(
	{
		dateFormat: 'yy-mm',		
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'],
	});
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
Reporte de facturación
</div>
--> <table class="toolbar" width="100%">
    <tr>
    	<?php
		#if($tiendaLocal=='0')
		{
			echo '
			<td class="toolbar">
				<a onclick="accesoOpcionFactura(\'global\',1)">
					<img src="'.base_url().'img/xml.png" width="28" /><br />
					Factura global
				</a>
			</td>';
		}
		?>
        
    	<td>
        <input title="Seleccione mes fiscal" type="text" class="busquedas" placeholder="Mes fiscal" style="width:100px; cursor:pointer" id="txtMes" onchange="obtenerFacturas()" />
        
        
        	<input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Buscar por cliente"  style="width:300px;"/>
            <input type="hidden" id="txtIdCliente" value="0" />
            
            <input type="text"  name="txtBuscarFactura" id="txtBuscarFactura" class="busquedas" placeholder="Buscar por serie y folio"  style="width:200px;"/>
            <input type="hidden" id="txtIdFactura" value="0" />
            
            <input type="hidden" id="txtModuloFactura" value="1" />
            
            <select class="cajas" id="selectEmisoresBusqueda" onchange="obtenerFacturas()" style="width:350px">
            	<option value="0">Seleccione emisor</option>
                <?php
                foreach($emisores as $row)
				{
					echo '<option value="'.$row->idEmisor.'">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
				}
				?>
            </select>
            
            <select class="cajas" id="selectTipo" onchange="obtenerFacturas()" style="width:150px" >
            	<option value="0">Seleccione tipo</option>
                <option value="1">Factura</option>
                <option value="2">Recibo de nómina</option>
                <option value="3">Nota de crédito</option>
				<option value="4">Prefactura</option>
				<option value="5">Traslado</option>
            </select>
            
            <select class="cajas" id="selectCanceladas" onchange="obtenerFacturas()" style="width:150px" >
            	<option value="-1">Activas y canceladas</option>
                <option value="0">Activas</option>
                <option value="1">Canceladas</option>
            </select>
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="generandoReporte"></div>
    <div id="generandoZip"></div>
	<div id="obtenerFacturas">
		<input type="hidden"  name="selectEstaciones" id="selectEstaciones" value="0" />
	</div>
</div>


<div id="ventanaCancelarFactura" title="Cancelar CFDI">
<div style="width:99%;" id="cargandoCancelacion"></div>
<div id="ErrorCancelacion" class="ui-state-error" ></div>
<div id="cargarFolio"></div>
</div>

<div id="ventanaEnviarCorreoFactura" title="Enviar factura por correo electrónico">
    <div id="enviandoCorreoFactura"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioCorreoFactura"></div>
</div>

<div id="ventanaFacturaGlobal" title="Factura global">
    <div id="registrandoFacturaGlobal"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioFacturaGlobal"></div>
</div>

<div id="ventanaPagosCfdi" title="Recibo electrónico de pagos">
    <div id="registrandoPagoCfdi"></div>
    <div id="formularioPagosCfdi"></div>
</div>

<div id="ventanaFacturacion" title="Facturar venta">
    <div id="facturando"></div>
    <div id="errorFacturacion" class="ui-state-error" ></div>
    <div id="generandoZip"></div>
    <div id="obtenerDatosFactura"></div>
</div>

</div>
