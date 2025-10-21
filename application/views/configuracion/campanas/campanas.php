<script src="<?php echo base_url()?>js/configuracion/campanas/campanas.js"></script>
<script>
$(document).ready(function()
{
	obtenerCampanas();
	
	$('#txtBuscarCampana').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerCampanas();
		}
	});
});
</script>

<div class="">


<div class="listproyectos" >
	
    <input type="hidden" id="txtCampanasEditado" name="txtCampanasEditado" value="0" />
    <table class="toolbar" width="100%">
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
                <a id="btnRegistrarCampanas" onclick="formularioCampanas()" title="Agregar campaña" style="cursor:pointer">
                    <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Agregar campaña" /> <br />
                    Agregar
                </a>
                
                <?php
                if($permiso[1]->activo==0)
                {
					echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarCampanas\');
					</script>';
                }
                ?>
        	</td>
            
            <td>
            	<input type="text"  name="txtBuscarCampana" id="txtBuscarCampana" class="busquedas" placeholder="Buscar campaña" style="width:400px" />
            </td>
        </tr>
    </table>
	
    <div id="procesandoCampanas"></div>
	<div id="obtenerCampanas"></div>

<div id="ventanaEditarCampanas" title="Editar campañas">
    <div id="editandoCampanas"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerCampanasEditar"></div>
</div>

<div id="ventanaCampanas" title="Campañas">
    <div id="registrandoCampanas"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioCampanas"></div>
</div>

</div>
</div>




