<script type="text/javascript" src="<?php echo base_url()?>js/reportes/checador/checador.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/reportes/checador/importar.js"></script>

<script>
$(document).ready(function()
{
	obtenerChecador();
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb">Checador</div>
<div class="toolbar">

 <table class="toolbar" width="100%" <?php echo $permiso->escribir==0?'style="display:none"':''?>>
    <tr>
    	<?php
        echo '
		<td class="button" width="5%">
            <a id="btnImportar" onclick="accesoImportarChecador()">
                <img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Importar" alt="Importar" /><br />
                Importar  
            </a>      
        </td>';
		?>
        
        <td width="95%">
            <input onchange="obtenerChecador()" readonly="readonly" value="<?php echo date('Y-m-d')?>" type="text" title="Inicio" style="width:90px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
			&nbsp;
            <input onchange="obtenerChecador()" readonly="readonly" value="<?php echo date('Y-m-d')?>" type="text" title="Fin" id="FechaDia2" style="width:90px" class="busquedas" placeholder="Fecha fin" />

            <input type="text" id="txtCriterio" style="width:500px" class="busquedas" placeholder="Buscar por día, personal" />
        </td>
</tr>
</table>
</div>
</div>

<div class="listproyectos" style="margin-top:20px" >
	<div id="obtenerChecador"></div>
</div>



<div id="ventanaImportarChecador" title="Importar checador">
    <div id="importandoChecador"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioImportarChecador"></div>
</div>

</div>
