<script src="<?php echo base_url()?>js/administracion/niveles/nivel3/nivel3.js"></script>
<script>
$(document).ready(function()
{
	obtenerNiveles3();
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
                echo'<img src="'.base_url().'img/add.png" style="cursor:pointer" onclick="formularioNiveles3()" /> <br />
				Agregar';
                ?>
            </td>
            <td>
            	<input type="text"  name="txtBuscarNivel3" id="txtBuscarNivel3" class="busquedas" placeholder="Buscar registro"  style="width:400px; "/>
                <input type="hidden"  	name="txtRegistrosAfectados3" 	id="txtRegistrosAfectados3" value="0"/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="procesandoNivel3"></div>
<div id="obtenerNiveles3"></div>


<div id="ventanaRegistrarNivel3" title="Registrar">
<div id="registrandoNivel3"></div>
<div id="formularioNiveles3"></div>
</div>

<div id="ventanaEditarNivel3" title="Editar">
<div id="editandoNivel3"></div>
<div id="obtenerNivel3"></div>
</div>

</div>
