<script src="<?php echo base_url()?>js/informacion.js"></script>
<script src="<?php echo base_url()?>js/reportes/ventasReporte.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/barcode.js"></script>

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
        	<label>Límite ventas $</label>
            <input type="text"  name="txtLimiteVentas" id="txtLimiteVentas" class="busquedas" value="<?php echo round($limiteVentas,decimales)?>" style="width:90px" onkeypress="return soloDecimales(event)" onchange="actualizarLimiteVentas()" />
        	
        	<input title="Fecha inicio" type="text" class="busquedas" placeholder="Fecha inicio" 	style="width:100px; cursor:pointer" id="FechaDia"  value="<?php echo date('Y-m-01')?>" onchange="obtenerVentas()" />
            <input title="Fecha fin" 	type="text" class="busquedas" placeholder="Fecha fin" 		style="width:100px; cursor:pointer" id="FechaDia2"  value="<?php echo date('Y-m-d')?>" 	onchange="obtenerVentas()"/>
            
           
            
            <input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="2"/>
			<input type="hidden"  name="txtIdClienteCrm" id="txtIdClienteCrm" value="0"/>
       
       
        	<input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Buscar por venta, cliente"  style="width:500px;"/>
              
            <select id="selectTipoVenta" name="selectTipoVenta" class="cajas" onchange="obtenerVentas()" style="width:170px">
            	<option value="0">Ventas F3 y F4</option>
                <option value="1">Ventas F3</option>
                <option value="2">Ventas F4</option>
            </select>
            
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
<div id="generandoExcel"></div>
<div id="ticketVentas"></div>

<div id="obtenerVentas">
	<input type="hidden"  name="selectZonas" id="selectZonas" value="0"/>
	<input type="hidden"  name="selectAgentes" id="selectAgentes" value="0"/>
</div>

<div id="ventanaVentasInformacion" title="Detalles de venta">
<div id="obtenerVentaInformacion"></div>
</div>

</div>
</div>
