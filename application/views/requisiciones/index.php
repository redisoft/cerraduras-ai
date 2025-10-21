<script src="<?php echo base_url()?>js/requisiciones/materiales/requisiciones.js"></script>

<script>
$(document).ready(function()
{
	obtenerRequisiciones()
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
 <table class="toolbar" width="100%" >
    <tr>
    	<?php
		
		echo '
		<td align="center" valign="middle" width="12%">
			<a onclick="formularioRequisiciones()" id="btnRegistro">
				<img src="'.base_url().'img/add.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Registrar '.(sistemaActivo=='IEXE'?'Insumos':'materia prima').' ">
				<br />
				Nueva requisición
			</a>
		</td>';
		
		if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnRegistro\');
			</script>';
		}
        ?>
			<td width="88%" align="left" valign="middle" style="padding-right:80px" >
                <input type="text"  name="txtBusquedaRequisicion" id="txtBusquedaRequisicion" class="busquedas" placeholder="Buscar requisición"  style="width:0px; border: none" tabindex="1"/>
            
                Filtrar de 
                <input type="text"  name="txtInicioRequisicion" id="txtInicioRequisicion" 	class="busquedas" style="width:100px;" 	onchange="obtenerRequisiciones()" tabindex="2" value="<?php echo date('Y-01-01')?>"/>
                a 
                <input type="text"  name="txtFinRequisicion" 	id="txtFinRequisicion" 		class="busquedas" style="width:100px;" 	onchange="obtenerRequisiciones()" tabindex="3" value="<?php echo date('Y-m-d')?>"/>
        	</td>
        
		</tr>
 	</table>
 </div>
</div>
<div class="listproyectos">
	<div id="obtenerRequisiciones"></div>

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

</div>
</div>

