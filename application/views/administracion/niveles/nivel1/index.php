<script src="<?php echo base_url()?>js/administracion/niveles/nivel1/nivel1.js"></script>
<script>
$(document).ready(function()
{
	obtenerNiveles1();
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
                echo'<img src="'.base_url().'img/add.png" style="cursor:pointer" onclick="formularioNiveles1()" /> <br />
				Agregar';
                ?>
            </td>
            <td>
            	<input type="text"  	name="txtBuscarNivel1" 			id="txtBuscarNivel1" class="busquedas" placeholder="Buscar registro"  style="width:400px; "/>
                <input type="hidden"  	name="txtRegistrosAfectados1" 	id="txtRegistrosAfectados1" value="0"/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="procesandoNivel1"></div>
<div id="obtenerNiveles1"></div>


<div id="ventanaRegistrarNivel1" title="Registrar">
<div id="registrandoNivel1"></div>
<div id="formularioNiveles1"></div>
</div>

<div id="ventanaEditarNivel1" title="Editar">
<div id="editandoNivel1"></div>
<div id="obtenerNivel1"></div>
</div>

</div>
