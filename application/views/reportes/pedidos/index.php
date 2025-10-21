<script src="<?php echo base_url()?>js/informacion.js"></script>
<script src="<?php echo base_url()?>js/reportes/pedidos.js"></script>


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
        	<input title="Fecha inicio" type="text" class="busquedas" style="width:120px; cursor:pointer" id="txtFechaInicial"  value="<?php echo date('Y-m-01')?>" onchange="obtenerPedidos()" />
            <input title="Fecha fin" 	type="text" class="busquedas" style="width:120px; cursor:pointer" id="txtFechaFinal"  value="<?php echo date('Y-m-d')?>" 	onchange="obtenerPedidos()"/>
        </td> 
        <td align="center">
        	<input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Buscar por folio, cliente"  style="width:500px;"/>
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
<div id="generandoExcel"></div>

<div id="obtenerPedidos">
	<input type="hidden"  name="selectZonas" id="selectZonas" value="0"/>
	<input type="hidden"  name="selectAgentes" id="selectAgentes" value="0"/>
</div>

<div id="ventanaRepartidores" title="Repartidores">
	<div id="editandoRepartidor"></div>
    <div id="formularioRepartidores"></div>
</div>


</div>
</div>
