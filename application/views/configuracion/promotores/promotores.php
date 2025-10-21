<script src="<?php echo base_url()?>js/configuracion/promotores/promotores.js"></script>
<script>
$(document).ready(function()
{
	obtenerPromotores();
	
	$('#txtBuscarPromotor').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerPromotores();
		}
	});
});
</script>

<div class="derecha">
    <div class="submenu" style="height:10px">
    <div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
</div>

<div class="listproyectos" >
	
    <input type="hidden" id="txtPromotoresEditado" name="txtPromotoresEditado" value="0" />
    <table class="toolbar" width="100%">
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
                <a id="btnRegistrarPromotores" onclick="formularioPromotores()" title="Agregar promotores" style="cursor:pointer">
                    <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Agregar promotores" /> <br />
                    Agregar
                </a>
                
                <?php
                if($permiso[1]->activo==0)
                {
					echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarPromotores\');
					</script>';
                }
                ?>
        	</td>
            
            <td>
            	<form action="javascript:fo()">
            		<input type="text"  name="txtBuscarPromotor" id="txtBuscarPromotor" class="busquedas" placeholder="Buscar por promotor" style="width:400px" />
                </form>
            </td>
        </tr>
    </table>
	
    <div id="procesandoPromotores"></div>
	<div id="obtenerPromotores">
    	<input type="hidden"  name="selectPromotoresBusqueda" 	id="selectPromotoresBusqueda" 	value="0"/>
        <input type="hidden"  name="selectCampanasBusqueda" 	id="selectCampanasBusqueda" 	value="0"/>
    </div>

<div id="ventanaEditarPromotores" title="Editar promotores">
    <div id="editandoPromotores"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerPromotoresEditar"></div>
</div>

<div id="ventanaPromotores" title="Promotores">
    <div id="registrandoPromotores"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioPromotores"></div>
</div>

</div>
</div>




