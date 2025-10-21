<script src="<?php echo base_url()?>js/configuracion/periodos/periodos.js"></script>
<script>
$(document).ready(function()
{
	obtenerPeriodos();
	
	$('#txtBuscarPeriodo').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerPeriodos();
		}
	});
});
</script>

<div class="">


<div class="listproyectos" >
	
    <input type="hidden" id="txtPeriodosEditado" name="txtPeriodosEditado" value="0" />
    <table class="toolbar" width="100%">
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
                <a id="btnRegistrarPeriodos" onclick="formularioPeriodos()" title="Agregar campaña" style="cursor:pointer">
                    <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Agregar campaña" /> <br />
                    Agregar
                </a>
                
                <?php
                if($permiso[5]->activo==0)
                {
					echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarPeriodos\');
					</script>';
                }
                ?>
        	</td>
            
            <td>
            	<input type="text"  name="txtBuscarPeriodo" id="txtBuscarPeriodo" class="busquedas" placeholder="Buscar registro" style="width:400px" />
            </td>
        </tr>
    </table>
	
    <div id="procesandoPeriodos"></div>
	<div id="obtenerPeriodos"></div>

<div id="ventanaEditarPeriodos" title="Editar periodo" style="background-color: #FF0000">
    <div id="editandoPeriodos"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerPeriodosEditar"></div>
</div>

<div id="ventanaPeriodos" title="Periodos">
    <div id="registrandoPeriodos"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioPeriodos"></div>
</div>

</div>
</div>




