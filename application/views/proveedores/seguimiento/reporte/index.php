<script src="<?php echo base_url()?>js/proveedores/seguimiento/reporteSeguimiento.js"></script>

<script src="<?php echo base_url()?>js/crm/proveedores/registrar.js"></script>
<script src="<?php echo base_url()?>js/crm/proveedores/contactos.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>
<script src="<?php echo base_url()?>js/configuracion/servicios/catalogo.js"></script>
<script src="<?php echo base_url()?>js/configuracion/status/catalogo.js"></script>
<script src="<?php echo base_url()?>js/proveedores/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/proveedores/seguimiento/archivos.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	obtenerSeguimientos();
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
	Seguimientos
</div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        	<a id="btnRegistrarProveedor" onclick="formularioCrmProveedores('<?php echo date('Y-m-d')?>','<?php echo date('H')?>')" title="Registrar">
                <img src="<?php echo base_url()?>img/crm.png" width="30" />
                <br />
                Registrar
            </a>
            <?php
			if($permiso[1]->activo==0)
			{ 
				echo '
				<script>
					desactivarBotonSistema(\'btnRegistrarProveedor\');
				</script>';
			}
			?>
        </td>
        
        <td align="center">
        	De
        	<input type="text"  name="FechaDia" id="FechaDia" class="busquedas" value="<?php echo date('Y-m-01')?>" style="width:100px;" onchange="obtenerSeguimientos()"/>
            A
            <input type="text"  name="FechaDia2" id="FechaDia2" class="busquedas" value="<?php echo date('Y-m-'.$this->configuracion->obtenerUltimaDiaFecha(date('Y-m-d')))?>" style="width:100px;" onchange="obtenerSeguimientos()"/>
            
            
        	<input type="text"  name="txtBuscarLlamada" id="txtBuscarLlamada" class="busquedas" placeholder="Buscar por empresa, responsable"  style="width:500px;" />

        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="procesandoInformacion"></div>
	<div id="obtenerSeguimientos">
    	
    	<input type="hidden" value="0" id="selectStatusBusqueda" name="selectStatusBusqueda" />
        <input type="hidden" value="0" id="selectServiciosBusqueda" name="selectServiciosBusqueda" />
    </div>
</div>

<div id="ventanaCatalogoServicios" title="Catálogo de servicios">
	<div id="obtenerCatalogoServicios"></div>
</div>

<div id="ventanaCatalogoStatus" title="Catálogo de CRM">
	<div id="obtenerCatalogoStatus"></div>
</div>

<div id="ventanaArchivosSeguimiento" title="Archivos">
    <div id="registrandoArchivosSeguimiento"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerArchivosSeguimiento"></div>
</div>

<div id="ventanaDetallesSeguimiento" title="Detalles de seguimiento">
    <div id="errorDetallesSeguimiento" class="ui-state-error" ></div>
    <div id="detallesSeguimiento"></div>
</div>

<div id="ventanaFormularioCrmCliente" title="CRM">
	<div class="ui-state-error" ></div>
    <div id="registrandoCrmCliente"></div>
	<div id="formularioCrmClientes"></div>
</div>

</div>
