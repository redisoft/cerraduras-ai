<script src="<?php echo base_url()?>js/informacion.js"></script>
<script src="<?php echo base_url()?>js/reportes/ventasContadora.js"></script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
	Reporte de Ventas
</div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<td>

        	<input title="Fecha inicio" type="text" class="busquedas" placeholder="Fecha inicio" 	style="width:100px; cursor:pointer" id="FechaDia"  value="<?php echo date('Y-m-01')?>" />
            <input title="Fecha fin" 	type="text" class="busquedas" placeholder="Fecha fin" 		style="width:100px; cursor:pointer" id="FechaDia2"  value="<?php echo date('Y-m-d')?>" 	/>

        	<input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Buscar por venta, cliente"  style="width:500px; display:none"/>
            
            <input type="button"  class="btn" value="Buscar" onclick="obtenerVentasContadora()"/>

        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
<div id="generandoExcel"></div>
<div id="ticketVentas"></div>

<div id="obtenerVentasContadora">
	<input type="hidden"  name="selectZonas" id="selectZonas" value="0"/>
	<input type="hidden"  name="selectAgentes" id="selectAgentes" value="0"/>
</div>

<div id="ventanaVentasInformacion" title="Detalles de venta">
<div id="obtenerVentaInformacion"></div>
</div>

</div>
</div>
