<script type="text/javascript" src="<?php echo base_url()?>js/reportes/cajaChica.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	$("#txtBuscarCliente").autocomplete(
	{
		source:base_url+'configuracion/obtenerClientes',
		
		select:function( event, ui)
		{
			$('#txtIdCliente').val(ui.item.idCliente);
		}
	});
	
	$('#txtIdCliente').val(0);
	$('#txtBuscarCliente').val('');
	
	window.setTimeout('obtenerCajaChica()',200);
	
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
Reporte de caja chica
</div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        <input title="Seleccione mes" type="text" class="busquedas" placeholder="Mes" value="<?=date('Y-m')?>" 
        	style="width:150px; cursor:pointer" id="txtMes" onchange="obtenerCajaChica()" />
        </td> 
        <!--td align="center">
        	<input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Seleccionar cliente"  style="width:300px;"/>
            <input type="hidden" id="txtIdCliente" value="0" />
        </td-->
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="generandoReporte"></div>
	<div id="obtenerCajaChica"></div>
</div>

</div>
