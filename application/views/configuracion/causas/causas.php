<script src="<?php echo base_url()?>js/configuracion/causas/causas.js"></script>
<script>
$(document).ready(function()
{
	obtenerCausas();
	
	$('#txtBuscarCausa').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerCausas();
		}
	});
});
</script>

<div class="">


<div class="listproyectos" >
	
    <input type="hidden" id="txtCausasEditado" name="txtCausasEditado" value="0" />
    <table class="toolbar" width="100%">
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
                <a id="btnRegistrarCausas" onclick="formularioCausas()" title="Agregar causa" style="cursor:pointer">
                    <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Agregar causa" /> <br />
                    Agregar
                </a>
                
                <?php
                if($permiso[1]->activo==0)
                {
					echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarCausas\');
					</script>';
                }
                ?>
        	</td>
            
            <td>
            	<input type="text"  name="txtBuscarCausa" id="txtBuscarCausa" class="busquedas" placeholder="Buscar causa" style="width:400px" />
            </td>
        </tr>
    </table>
	
    <div id="procesandoCausas"></div>
	<div id="obtenerCausas"></div>

<div id="ventanaEditarCausas" title="Editar causa">
    <div id="editandoCausas"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerCausasEditar"></div>
</div>

<div id="ventanaCausas" title="Causas">
    <div id="registrandoCausas"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioCausas"></div>
</div>

</div>
</div>




