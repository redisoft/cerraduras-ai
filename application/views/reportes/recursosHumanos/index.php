<script src="<?php echo base_url()?>js/reportes/recursoHumanos.js"></script>


<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        	<!--<input title="Fecha inicio" type="text" class="busquedas" style="width:120px; cursor:pointer" id="txtFechaInicial"  value="<?php echo date('Y-m-01')?>" onchange="obtenerPedidos()" />
            <input title="Fecha fin" 	type="text" class="busquedas" style="width:120px; cursor:pointer" id="txtFechaFinal"  value="<?php echo date('Y-m-d')?>" 	onchange="obtenerPedidos()"/>-->
        </td> 
        <td align="center">
        	<!--<input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Buscar por folio, cliente"  style="width:500px;"/>-->
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
    <div id="generandoExcel"></div>
    <div id="obtenerRecursosHumanos">
</div>



</div>
</div>
