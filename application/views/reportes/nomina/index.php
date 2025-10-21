<script src="<?php echo base_url()?>js/reportes.js"></script>
<script src="<?php echo base_url()?>js/administracion.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	$('#txtBuscarPersonal').val('');
	$('#txtPersonal').val(0);
	
	$("#txtBuscarPersonal").autocomplete(
	{
		source:base_url+'configuracion/obtenerPersonal',
		
		select:function( event, ui)
		{
			//busquedaCliente(ui.item.idCliente)
			$('#txtPersonal').val(ui.item.idPersonal);
			obtenerNomina();
		}
	});
	
	$("#txtProductos").autocomplete(
	{
		source:base_url+'configuracion/obtenerProductosInventario',
		
		select:function( event, ui)
		{
			location.href=base_url+"reportes/busquedaProductosVentas/"+ui.item.idProducto;
		}
	});
	
	obtenerNomina();
	
	
});

function obtenerCuentas()
{
	$("#filaCuenta").load(base_url+"produccion/obtenerCuentas/"+$('#selectBancos').val());
}
	

</script>

<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar" >
<div class="seccionDiv">
Reporte de nómina
</div>
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        	<input value="<?php echo date('Y-m-01')?>" title="Inicio" type="text" class="busquedas" style="width:120px; cursor:pointer" id="FechaDia" onchange="obtenerNomina()" />
            a
            <input value="<?php echo date('Y-m-'.$this->reportes->obtenerUltimaDiaFecha(date('Y-m-d')))?>" title="Fin" type="text" class="busquedas" style="width:120px; cursor:pointer" id="FechaDia2" onchange="obtenerNomina()" />
        </td> 
        <td align="center">
        <input type="text"  id="txtBuscarPersonal" class="busquedas" placeholder="Seleccionar personal"  style="width:300px;"/>
        <input type="hidden" id="txtPersonal" value="0" />
        </td>
        <!--td>
        <input type="text"  name="txtDepartamento" id="txtDepartamento" class="busquedas" placeholder="Seleccionar departamento" style="width:300px;"/>
         
         
         
        <?php
        if($this->session->userdata('idClienteVenta')!="")
        {
			echo 
			'<br />
			<a href="'.base_url().'reportes/busquedaFechaVentas/todas" class="toolbar" style="margin-left:100px">
				<img src="'.base_url().'img/quitar.png" width="22px;" height="22px;" title="Borrar busqueda" />
			</a>';
        }
        ?>        
        
        </td-->
        
     
	</tr>
  </table>
</div>
</div>

<div id="obtenerNomina"></div>

<div id="ventanaNomina" title="Pago de nómina">
<div id="pagandoNomina"></div>
<div id="formularioNomina"></div>
</div>

</div>
</div>
