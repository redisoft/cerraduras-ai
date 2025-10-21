<script src="<?php echo base_url()?>js/administracion/niveles/nivel2/nivel2.js"></script>
<script>
$(document).ready(function()
{
	obtenerNiveles2();
});
</script>
<div class="submenu">
     <table class="toolbar" width="100%">
     	<tr>
       	 	<td class="seccion" colspan="2">
            
            </td>
        </tr>
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
				<?php
                echo'<img src="'.base_url().'img/add.png" style="cursor:pointer" onclick="formularioNiveles2()" /> <br />
				Agregar';
                ?>
            </td>
            <td>
            	<input type="text"  name="txtBuscarNivel2" id="txtBuscarNivel2" class="busquedas" placeholder="Buscar registro"  style="width:400px; "/>
                <input type="hidden"  	name="txtRegistrosAfectados2" 	id="txtRegistrosAfectados2" value="0"/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="procesandoNivel2"></div>
<div id="obtenerNiveles2"></div>


<div id="ventanaRegistrarNivel2" title="Registrar">
<div id="registrandoNivel2"></div>
<div id="formularioNiveles2"></div>
</div>

<div id="ventanaEditarNivel2" title="Editar">
<div id="editandoNivel2"></div>
<div id="obtenerNivel2"></div>
</div>

</div>
