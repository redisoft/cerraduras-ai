<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/nomina/empleados.js"></script>
<script>
$(document).ready(function()
{
	obtenerEmpleados();
});
</script>
<div class="derecha">
<div class="submenu">
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
     <table class="toolbar" width="100%">
     	<!--<tr>
        	<td class="seccion" colspan="2">
            	Empleados
            </td>
        </tr>-->
        <tr>
            <td style="border:none" width="20%">
				<?php
				echo'
				<a id="btnRegistrarEmpleado" onclick="formularioEmpleados()">
					<img src="'.base_url().'img/add.png" style="height:30px; width:30px" title="Registrar empleado"  /> <br />
					Registrar
				</a>';
				
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarEmpleado\');
					</script>';
				}
                ?>
            </td>
            <td>
            	<input onkeyup="obtenerEmpleados()" type="text"  name="txtBuscarEmpleado" id="txtBuscarEmpleado" class="busquedas" placeholder="Buscar empleados"  style="width:600px; "/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="obtenerEmpleados"></div>
<input type="hidden" id="txtAgregarEmpleados" value="0" />

<div id="ventanaRegistrarEmpleado" title="Registrar empleado">
<div id="registrandoEmpleado"></div>
<div id="formularioEmpleados"></div>
</div>

<div id="ventanaEditarEmpleado" title="Editar empleado">
<div id="editandoEmpleado"></div>
<div id="obtenerEmpleado"></div>
</div>

</div>
</div>
