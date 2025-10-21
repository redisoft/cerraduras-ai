<script src="<?php echo base_url()?>js/ordenes/ordenes.js"></script>
<script src="<?php echo base_url()?>js/ordenes/procesos.js"></script>

<script>
$(document).ready(function()
{
	obtenerOrdenes();
});
</script>
<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">
   <!-- <div class="seccionDiv">
    	Ordenes de producción
    </div>-->
 <table class="toolbar" style="width:100%">
    <tr>
        
        <?php 
        echo'
		<td align="center" valign="middle" style="width:10%" >
			<a id="agregarOrdenProduccion" onclick="obtenerFormularioProduccion()">
				<img src="'.base_url().'img/engranes.png" width="30px;" height="30px;" style="cursor:pointer;" title="Ordenes de produccion">
				<br />
				Nueva orden 
			</a>
        </td>';
        
	
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'agregarOrdenProduccion\');
			</script>';
		}
      ?>
       <td align="left" valign="middle" style="width:90%; padding-right:80px">
			<input type="text" class="busquedas" style="width:120px" id="FechaDia" onchange="busquedaOrdenesFecha()"  placeholder="Fecha" />  
            
            <input type="text" class="busquedas" style="width:450px" id="txtBuscarOrden"  placeholder="Seleccione orden" />  

        
        </td>
    </tr>
 </table>
 </div>
</div>

<div class="listproyectos">
	<div id="procesandoOrden"></div>
	<div id="obtenerOrdenes"></div>

<div id="ventanaProcesosProduccion" title="Procesos de producción">
<div style="width:99%;" id="cargandoProcesosProduccion"></div>
<div id="errorProcesosProduccion" class="ui-state-error" ></div>
<div id="cargarProcesosProduccion"></div>
</div>

<div id="ventanaOrdenProduccion" title="Nueva orden de producción">
<div style="width:100%;" id="agregandoOrdenProduccion"></div>
<div id="errorAgregarOrden" class="ui-state-error" ></div>
<div id="cargarFormularioOrdenProduccion"></div>
</div>


<!-- Productos con sus materiales-->

<div id="ventanaProductoProducido" title="Producto terminado">
<div id="cargandoProducido"></div>
<div id="ErrorOrden" class="ui-state-error" ></div>
<div id="cargarOrdenProduccion"></div>
</div>

<div id="ventanaCancelarOrden" title="Cancelar orden">
<div id="cancelandoOrden"></div>
<div id="obtenerDetallesOrden"></div>
</div>

<div id="ventanaEditarProducido" title="Editar producto terminado">
<div id="editandoProducido"></div>
<div class="ui-state-error" ></div>
<div id="obtenerProductoTerminado"></div>
</div>


<div id="ventanaAgregarProceso" title="Agregar proceso">
<div id="agregandoProceso"></div>
<div id="formularioAgregarProceso"></div>
</div>

</div>
</div>
