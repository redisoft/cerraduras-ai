<script type="text/javascript" src="<?php echo base_url()?>js/reportes/ingresos.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/informacion.js"></script>
<script src="<?php echo base_url()?>js/administracion/ingresos/cfdi.js"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js"></script>

<script type="text/javascript" src="<?php echo base_url()?>js/reportes/facturacion/administracion.js"></script>

<script>
$(document).ready(function()
{
	
	
	$("#txtBuscarFactura").autocomplete(
	{
		source:base_url+'configuracion/obtenerFacturasIngresos',
		
		select:function( event, ui)
		{
			$('#txtIdIngreso').val(ui.item.idIngreso);
			obtenerIngresos();
		}
	});
	
	$('#txtIdClienteBusqueda').val(0);
	$('#txtBuscarCliente').val('');
	
	$('#txtIdIngreso').val(0);
	$('#txtBuscarFactura').val('');
	
	obtenerIngresos();
});
</script>

<form id="frmCriterios">
<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">
<!--<div class="seccionDiv">
Reporte de Ingresos
</div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<?php
        if(sistemaActivo!='pinata')
		{
			?>
            <td style="display:none">
                <a id="btn" onclick="formularioGlobalIngresos()">
                    <img src="<?php echo base_url()?>img/cfdi.png" width="30px;" height="30px;" title="Factura global" /><br />
                    
                   Factura global
                </a>      
             </td>
            <?php
		}
		?>
    	 
        <td width="90%">
        	
            <input onchange="obtenerIngresos()" readonly="readonly" value="<?php echo date('Y-m-01')?>" type="text" title="Inicio" style="width:90px" id="FechaDia" name="txtFechaInicial" class="busquedas" placeholder="Fecha inicio" />
			&nbsp;
            <input onchange="obtenerIngresos()" readonly="readonly" value="<?php echo date('Y-m-'.$this->reportes->obtenerUltimaDiaFecha(date('Y-m-d')))?>" type="text" title="Fin" id="FechaDia2" name="txtFechaFinal"  style="width:90px" class="busquedas" placeholder="Fecha fin" />
       
         <select  id="selectCuentas" name="selectCuentas" class="busquedas" style="width:auto;"  onchange="obtenerIngresos()">
            <option value="0">Seleccione cuenta</option>
            <?php
			foreach($cuentas as $row)
			{
				echo '<option value="'.$row->idCuenta.'">'.$row->nombre.', '.$row->cuenta.'</option>';
			}
            ?>
         </select>
         
         <input type="text" class="busquedas" id="txtBuscarCliente" placeholder="Buscar por cliente<?=sistemaActivo=='IEXE'?', matrícula':''?> " style="width:300px"  />
         <input type="hidden" id="txtIdClienteBusqueda" name="txtIdClienteBusqueda" value="0"  />
         
         <input type="text"  name="txtBuscarFactura" id="txtBuscarFactura" class="busquedas" placeholder="Seleccionar factura"  style="width:150px;"/>
         <input type="hidden" id="txtIdIngreso" value="0" />
         
         <select  id="selectCriterio" name="selectCriterio" class="busquedas" style="width:125px"  onchange="obtenerIngresos()">
            <option value="0">Con iva y sin iva</option>
            <option value="1">Con iva</option>
            <option value="2">Sin iva</option>
         </select>
         
         
            
        </td>
</tr>
</table>
</div>
</div>

<div class="listproyectos" style="margin-top:20px" >
	<div id="obtenerIngresos"></div>
</div>

<div id="ventanaVentasInformacion" title="Detalles de venta">
<div id="obtenerVentaInformacion"></div>
</div>

<input type="hidden" id="txtModuloCfdi" value="ingresos" />
<div id="ventanaFacturaIngreso" title="Facturar ingreso">
    <div id="facturandoIngreso"></div>
    <div id="formularioFacturaIngreso"></div>
</div>

<div id="ventanaGlobalIngreso" title="Factura global">
    <div id="facturandoGlobal"></div>
    <div id="formularioGlobalIngresos"></div>
</div>

<div id="ventanaDatosFiscales" title="Datos fiscales">
    <div id="editandoFiscales"></div>
    <div id="obtenerDatosFiscales"></div>
</div>

<div id="ventanaEnviarCorreoFactura" title="Enviar factura por correo electrónico">
    <div id="enviandoCorreoFactura"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioCorreoFactura"></div>
</div>

</div>
</form>
