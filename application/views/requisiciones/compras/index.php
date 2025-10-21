<script src="<?php echo base_url()?>js/requisiciones/compras/requisiciones.js"></script>

<div id="ventanaRequisiciones" title="Requisiciones abiertas">
	<div id="procesandoRequisiciones"></div>
	<table class="admintable" width="100%">
    	<tr>
            <td align="center">
            
            	Filtrar orden de compra
                <input type="text"  name="txtFechaOrden" id="txtFechaOrden" 	class="busquedas" style="width:100px;" 	value="<?php echo date('Y-m-d H:i')?>"/>
                
            	<input type="text"  name="txtBusquedaRequisicion" id="txtBusquedaRequisicion" class="busquedas" placeholder="Buscar por folio, proveedor, materia prima"  style="width:500px;"/>
            
                &nbsp;&nbsp;&nbsp;&nbsp;
                
                Filtrar de
                <input type="text"  name="txtInicioRequisicion" id="txtInicioRequisicion" 	class="busquedas" style="width:100px;" 	onchange="obtenerRequisiciones()" value="<?php echo date('Y-01-01')?>"/>
                &nbsp;a
                <input type="text"  name="txtFinRequisicion" 	id="txtFinRequisicion" 		class="busquedas" style="width:100px;" 	onchange="obtenerRequisiciones()" value="<?php echo date('Y-m-d')?>"/>
            </td>
        </tr>
    </table>
    
    <div id="obtenerRequisiciones"></div>
</div>

<div id="ventanaRequisicionesProcesadas" title="Requisiciones procesadas">
	<div id="procesandoRequisicionesProcesadas"></div>
	<table class="admintable" width="100%">
    	<tr>
            <td align="center">
            	<input type="text"  name="txtBusquedaRequisicionProcesada" id="txtBusquedaRequisicionProcesada" class="busquedas" placeholder="Buscar por proveedor, requisición, compra"  style="width:500px"/>
            
                &nbsp;&nbsp;&nbsp;&nbsp;
                
                Filtrar de
                <input type="text"  name="txtInicioRequisicionProcesada" id="txtInicioRequisicionProcesada" 	class="busquedas" style="width:100px;" 	onchange="obtenerRequisicionesProcesadas()" value="<?php echo date('Y-01-01')?>"/>
                &nbsp;a
                <input type="text"  name="txtFinRequisicionProcesada" 	id="txtFinRequisicionProcesada" 		class="busquedas" style="width:100px;" 	onchange="obtenerRequisicionesProcesadas()" value="<?php echo date('Y-m-d')?>"/>
            </td>
        </tr>
    </table>
    
    <div id="obtenerRequisicionesProcesadas"></div>
</div>

