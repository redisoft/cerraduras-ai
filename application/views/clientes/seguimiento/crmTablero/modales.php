<div id="ventanaFormularioCrmCliente" title="CRM">
	<div class="ui-state-error" ></div>
    <div id="registrandoCrmCliente"></div>
    
    <table class="admintable" width="100%">
    	<tr>
        	<td class="key">Tipo de seguimiento:</td>
            <td>
            	<select class="cajas" id="selectTipoSeguimiento" style="width:140px" onchange="tipoSeguimientoCrm()">
                	<option>Clientes</option>
                    <option>Proveedores</option>
                </select>
            </td>
        </tr>
    </table>
	<div id="formularioCrmClientes"></div>
</div>

<div id="ventanaEditarSeguimiento" title="Editar CRM">
<div id="editandoCrm"></div>
<div id="errorCrm" class="ui-state-error" ></div>
<div id="obtenerSeguimientoEditar"></div>
</div>