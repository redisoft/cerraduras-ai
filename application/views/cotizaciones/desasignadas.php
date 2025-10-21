<script src="<?php echo base_url()?>js/cotizaciones/asignadas.js" ></script>
<script src="<?php echo base_url()?>js/informacion.js"></script>


<script >
$(document).ready(function()
{
	obtenerCotizacionesAsignadas();
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
   <!-- <div class="seccionDiv">
    	Cotizaciones
    </div>-->
 <table class="toolbar" width="100%">
    <tr>
        <td align="center">
        	<input type="text"  name="txtBuscarCotizacion" id="txtBuscarCotizacion" class="busquedas" placeholder="Buscar cotización"  style="width:500px;" />
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="procesandoInformacion"></div>
    <ul class="menuTabs">
        <li onclick="window.location.href='<?php echo base_url()?>cotizaciones'">Cotizaciones</li>
        <li class="activado">No asignadas</li>
        <li onclick="window.location.href='<?php echo base_url()?>cotizaciones/procesadas'">Procesadas</li>
    </ul>
	<div id="obtenerCotizacionesAsignadas"></div>
</div>

<div id="ventanaCotizacionesInformacion" title="Detalles de cotización">
<div id="obtenerCotizacionInformacion"></div>
</div>



</div>
