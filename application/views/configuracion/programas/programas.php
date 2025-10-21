<script src="<?php echo base_url()?>js/configuracion/programas/programas.js"></script>
<script>
$(document).ready(function()
{
	obtenerProgramas();
	
	$('#txtBuscarPrograma').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerProgramas();
		}
	});
});
</script>

<div class="">


<div class="listproyectos" >
	
    <input type="hidden" id="txtProgramasEditado" name="txtProgramasEditado" value="0" />
    <table class="toolbar" width="100%">
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
                <a id="btnRegistrarProgramas" onclick="formularioProgramas()" title="Agregar programas" style="cursor:pointer">
                    <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Agregar programas" /> <br />
                    Agregar
                </a>
                
                <?php
                if($permiso[1]->activo==0)
                {
					echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarProgramas\');
					</script>';
                }
                ?>
        	</td>
            
            <td>
            	<input type="text"  name="txtBuscarPrograma" id="txtBuscarPrograma" class="busquedas" placeholder="Buscar programa" style="width:400px" />
            </td>
        </tr>
    </table>
	
    <div id="procesandoProgramas"></div>
	<div id="obtenerProgramas"></div>

<div id="ventanaEditarProgramas" title="Editar programas">
    <div id="editandoProgramas"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerProgramasEditar"></div>
</div>

<div id="ventanaProgramas" title="Programas">
    <div id="registrandoProgramas"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioProgramas"></div>
</div>

</div>
</div>




