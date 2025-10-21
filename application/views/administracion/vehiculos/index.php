<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/administracion/vehiculos.js"></script>
<script>
$(document).ready(function()
{
	obtenerVehiculos();
});
</script>
<div class="derecha">
<div class="submenu">
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
     <table class="toolbar" width="100%">
     	
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
				<?php
				echo'
				<a id="btnRegistrarVehiculo" onclick="formularioVehiculos()">
					<img src="'.base_url().'img/add.png" title="Registrar vehículo" /> <br />
					Registrar
				</a>';
				
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarVehiculo\');
					</script>';
				}
                ?>
            </td>
            <td>
            	<input type="text"  name="txtBuscarVehiculo" id="txtBuscarVehiculo" class="busquedas" placeholder="Buscar vehículo"  style="width:400px; "/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="obtenerVehiculos" style="margin-top:20px"></div>


<div id="ventanaRegistrarVehiculo" title="Registrar vehículo">
	<div id="registrandoVehiculo"></div>
	<div id="formularioVehiculos"></div>
</div>

<div id="ventanaEditarVehiculo" title="Editar vehículo">
	<div id="editandoVehiculo"></div>
	<div id="obtenerVehiculo"></div>
</div>

</div>
</div>