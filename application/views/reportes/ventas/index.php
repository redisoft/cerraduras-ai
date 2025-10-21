<script src="<?php echo base_url()?>js/informacion.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/reportes/ventas.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/reportes/ventasLineas.js?v=<?php echo(rand());?>"></script>

<!--CRM DE SERVICIOS-->
<script src="<?php echo base_url()?>js/clientes/seguimiento/detalles.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/clientes/seguimiento/archivos.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/crm/clientes/servicios/servicios.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/crm.js?v=<?php echo(rand());?>"></script>



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
        	<input title="Fecha inicio" type="text" class="busquedas" placeholder="Fecha inicio" 	style="width:120px; cursor:pointer" id="FechaDia"  value="<?php echo date('Y-m-01')?>" onchange="obtenerVentas()" />
            <input title="Fecha fin" 	type="text" class="busquedas" placeholder="Fecha fin" 		style="width:120px; cursor:pointer" id="FechaDia2"  value="<?php echo date('Y-m-d')?>" 	onchange="obtenerVentas()"/>
            
           
            
            <input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="2"/>
			<input type="hidden"  name="txtIdClienteCrm" id="txtIdClienteCrm" value="0"/>
        </td> 
        <td align="center">
        	<input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Buscar por venta, cliente"  style="width:500px;"/>
             <?php
			
			?>        
            
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
<div id="generandoExcel"></div>

<div id="obtenerVentas">
	<input type="hidden"  name="selectZonas" id="selectZonas" value="0"/>
	<input type="hidden"  name="selectAgentes" id="selectAgentes" value="0"/>
	<input type="hidden"  name="selectEstaciones" id="selectEstaciones" value="0" />
	<input type="hidden"  name="selectFormas" id="selectFormas" value="0" />
</div>

<div id="ventanaVentasInformacion" title="Detalles de venta">
<div id="obtenerVentaInformacion"></div>
</div>

<?php $this->load->view('clientes/seguimiento/crmServicios/modalesSeguimientoServicios');?>

</div>
</div>
