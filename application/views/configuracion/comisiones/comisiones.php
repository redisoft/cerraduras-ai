<script src="<?php echo base_url()?>js/configuracion/comisiones/comisiones.js"></script>
<script>
$(document).ready(function()
{
	obtenerComisiones();
	
	$('#txtBuscarComision').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerComisiones();
		}
	});
});
</script>

<div class="">


<div class="listproyectos" >
    <table class="toolbar" width="100%" >
        <tr>
            <!--<td style="border:none" width="20%" align="center" valign="middle" class="button">
                <a id="btnRegistrarComisiones" onclick="formularioComisiones()" title="Agregar comisiones" style="cursor:pointer">
                    <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Agregar comisiones" /> <br />
                    Agregar
                </a>
                
                <?php
                if($permiso[1]->activo==0)
                {
					echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarComisiones\');
					</script>';
                }
                ?>
        	</td>-->
            
            <td>
            	<input type="text"  name="txtBuscarComision" id="txtBuscarComision" class="cajas" placeholder="Buscar alumno" style="width:400px" />
            </td>
        </tr>
    </table>
	
    <div id="procesandoComisiones"></div>
	<div id="obtenerComisiones">
    	<input type="hidden"  name="selectPromotoresComisiones" id="selectPromotoresComisiones" value="0"/>
        <input type="hidden"  name="selectCampanasComisiones" 	id="selectCampanasComisiones" 	value="0"/>
        <input type="hidden"  name="selectProgramasComisiones" 	id="selectProgramasComisiones" 	value="0"/>
    </div>
    
    

<div id="ventanaEditarComision" title="Editar comisiones">
    <div id="editandoComisiones"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioEditarComision"></div>
</div>



</div>
</div>




