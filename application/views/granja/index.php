
<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        	<?php
          
			?>
        	
        </td>
        <td align="center">
        	<!--<input type="text"  name="txtBusquedaCotizacion" id="txtBusquedaCotizacion" class="busquedas" placeholder="Buscar cotización"  style="width:500px;"/>
            
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text"  name="txtInicio" id="txtInicio" class="busquedas" style="width:100px;" onchange="obtenerCotizaciones()" value="<?php echo date('Y-01-01')?>"/>
            <input type="text"  name="txtFin" id="txtFin" class="busquedas" style="width:100px;" onchange="obtenerCotizaciones()" value="<?php echo date('Y-12-31')?>"/>
            <input type="hidden"  name="txtOrden" id="txtOrden" value="desc"/>
            <input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="1"/>
            <input type="hidden"  name="txtIdClienteCrm" id="txtIdClienteCrm" value="0"/>-->
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="procesandoInformacion"></div>
    <div id="cancelandoCotizacion"></div>

	<div id="obtenerCotizaciones" class="Error_validar">Sin detalles</div>
</div>

</div>
