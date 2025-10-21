<script type="text/javascript" src="<?php echo base_url()?>js/reportes/pagosCredito.js"></script>

<form id="frmCriterios">
<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">
<!--<div class="seccionDiv">
Reporte de Ingresos
</div>-->
 <table class="toolbar" width="100%">
    <tr>
        <td width="90%">
        	
			<input onchange="obtenerReporte()" readonly="readonly" value="<?php echo date('Y-m-d')?>" type="text" title="Inicio" style="width:90px" id="FechaDia" name="txtFechaInicial" class="busquedas" placeholder="Fecha inicio" />
			&nbsp;
			<input onchange="obtenerReporte()" readonly="readonly" value="<?php echo date('Y-m-d')?>" type="text" title="Fin" id="FechaDia2" name="txtFechaFinal"  style="width:90px" class="busquedas" placeholder="Fecha fin" />

			<input type="text" class="busquedas" id="txtBuscarCliente" placeholder="Buscar por cliente" style="width:500px"  />

			<!--<input type="text"  name="txtBuscarFactura" id="txtBuscarFactura" class="busquedas" placeholder="Seleccionar factura"  style="width:150px;"/>-->

			<!--<select  id="selectCriterio" name="selectCriterio" class="busquedas" style="width:125px"  onchange="obtenerIngresos()">
				<option value="0">Con iva y sin iva</option>
				<option value="1">Con iva</option>
				<option value="2">Sin iva</option>
			</select>-->
        </td>
</tr>
</table>
</div>
</div>

<div class="listproyectos" style="margin-top:20px" >
	<div id="generandoReporte"></div>
	<div id="obtenerReporte"></div>
</div>


</div>
</form>
