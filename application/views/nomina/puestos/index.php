<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/nomina/puestos.js"></script>
<script>
$(document).ready(function()
{
	obtenerPuestos();
});
</script>
<div class="derecha">
<div class="submenu">
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
     <table class="toolbar" width="100%">
     	<tr>
       	 	<td class="seccion" colspan="2">
            Puestos
            </td>
        </tr>
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
				<?php
				echo'
				<a id="btnRegistrarPuesto" onclick="formularioPuestos()">
					<img src="'.base_url().'img/add.png" title="Registrar puesto" /> <br />
					Registrar
				</a>';
				
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarPuesto\');
					</script>';
				}
                ?>
            </td>
            <td>
            	<input onkeyup="obtenerPuestos()" type="text"  name="txtBuscarPuesto" id="txtBuscarPuesto" class="busquedas" placeholder="Buscar puesto"  style="width:400px; "/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="obtenerPuestos" style="margin-top:20px"></div>


<div id="ventanaRegistrarPuesto" title="Registrar puesto">
<div id="registrandoPuesto"></div>
<div id="formularioPuestos"></div>
</div>

<div id="ventanaEditarPuesto" title="Editar puesto">
<div id="editandoPuesto"></div>
<div id="obtenerPuesto"></div>
</div>

</div>
</div>