<script type="text/javascript" src="<?php echo base_url()?>js/informacion.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/reportes/materiaPrima.js"></script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
Inventario productos
</div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<td>
            <input type="text"  name="txtCriterio" id="txtCriterio" class="busquedas" placeholder="Buscar por código, nombre, proveedor"  style="width:700px;"/>
            <input type="hidden" id="txtIdProducto" value="0" />
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="generandoReporte"></div>
	<div id="obtenerMateriaPrima"></div>
</div>

<div id="ventanaInformacionCompras" title="Detalles de inventario">
    <div id="obtenerInformacionCompras"></div>
</div>

<div id="ventanaInformacionMaterial" title="Detalles de materia prima">
    <div id="obtenerInformacionMaterial"></div>
</div>



</div>
