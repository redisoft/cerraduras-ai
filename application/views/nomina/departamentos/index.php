<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/nomina/departamentos.js"></script>
<script>
$(document).ready(function()
{
	obtenerDepartamentos();
});
</script>
<div class="derecha">
<div class="submenu">
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
     <table class="toolbar" width="100%">
     	<tr>
       	 	<td class="seccion" colspan="2">
            Departamentos
            </td>
        </tr>
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
				<?php
                echo'
				<a id="btnRegistrarDepartamento" onclick="formularioDepartamentos()">
					<img src="'.base_url().'img/add.png" title="Registrar departamento"  /> <br />
					Registrar
				</a>';
				
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarDepartamento\');
					</script>';
				}
                ?>
            </td>
            <td>
            	<input onkeyup="obtenerDepartamentos()" type="text"  name="txtBuscarDepartamento" id="txtBuscarDepartamento" class="busquedas" placeholder="Buscar departamento"  style="width:400px; "/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="obtenerDepartamentos" style="margin-top:20px"></div>


<div id="ventanaRegistrarDepartamento" title="Registrar departamento">
<div id="registrandoDepartamento"></div>
<div id="formularioDepartamentos"></div>
</div>

<div id="ventanaEditarDepartamento" title="Editar departamento">
<div id="editandoDepartamento"></div>
<div id="obtenerDepartamento"></div>
</div>

</div>
</div>