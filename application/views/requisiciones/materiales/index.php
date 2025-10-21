<script src="<?php echo base_url()?>js/requisiciones/materiales/requisiciones.js"></script>

<div id="ventanaRequisiciones" title="Requisiciones">
	<div id="procesandoRequisiciones"></div>
	<table class="admintable" width="100%">
    	<tr>
        	<td align="center" width="15%" style="border-right:none">
            	<a onclick="formularioRequisiciones()" id="btnRegistro">
            	<img src="<?php echo base_url()?>img/add.png" width="22" />
                <br />
				Nueva requisición</a>
            </td>
            <td align="center" style="border-left:none">
            	<input type="text"  name="txtBusquedaRequisicion" id="txtBusquedaRequisicion" class="busquedas" placeholder="Buscar requisición"  style="width:0px; border: none" tabindex="1"/>
            
                Filtrar de 
                <input type="text"  name="txtInicioRequisicion" id="txtInicioRequisicion" 	class="busquedas" style="width:100px;" 	onchange="obtenerRequisiciones()" tabindex="2" value="<?php echo date('Y-01-01')?>"/>
                a 
                <input type="text"  name="txtFinRequisicion" 	id="txtFinRequisicion" 		class="busquedas" style="width:100px;" 	onchange="obtenerRequisiciones()" tabindex="3" value="<?php echo date('Y-m-d')?>"/>
            </td>
        </tr>
    </table>
    
    <div id="obtenerRequisiciones"></div>
</div>

<div id="ventanaFormularioRequisiciones" title="Registrar requisición">
    <div id="registrandoRequisicion"></div>
    <div id="formularioRequisiciones"> </div>
</div>

<div id="ventanaEditarRequisiciones" title="Editar requisición">
    <div id="editandoRequisicion"></div>
    <div id="obtenerRequisicion"> </div>
</div>

<div id="ventanaDetallesRequisiciones" title="Detalles requisición">
    <div id="obtenerDetallesRequisicion"> </div>
</div>