<script src="<?php echo base_url()?>js/reportes/precio1.js?v=<?php echo(rand());?>"></script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">

	<table class="toolbar" width="100%">
        <tr>
            <td>
				<input name="FechaDia" type="text" title="Inicio" style="width:150px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" value="<?=date('Y-m-01')?>" />
				<input name="FechaDia2" type="text" title="Fin" id="FechaDia2" style="width:150px" class="busquedas" placeholder="Fecha fin" value="<?=date('Y-m-d')?>" />
				<input type="button" class="btn" value="Buscar" onclick="obtenerReporte()"  />    

				<input type="text"  name="txtCriterioBusqueda" id="txtCriterioBusqueda" class="busquedas"  style="width:500px;" placeholder="Buscar por cliente, venta"/>
            </td>
        </tr>
    </table>
</div>
</div>

<div class="listproyectos" style="margin-top:25px">
	<div id="generandoReporte"></div>
	
	<div id="obtenerReporte">
		<input type="hidden" id="selectEstaciones" value="0" />
		<input type="hidden" id="selectAgentes" value="0" />
	</div>

	</div>
</div>


