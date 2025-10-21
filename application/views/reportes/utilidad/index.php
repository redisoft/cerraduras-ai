<script src="<?php echo base_url()?>js/reportes/utilidad.js"></script>	

<script>
$(document).ready(function()
{
	obtenerUtilidad();
	$('#txtFecha').monthpicker();
});

</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
	Utilidad
</div>-->
    <table class="toolbar" border="0" width="100%">
        <tr>
        	<td width="70%" align="left" valign="middle" style="border:none";>
               
               
        	</td>  
        </tr>
    </table> 
</div>       
</div>
       
<div class="listproyectos">
	<div id="generandoReporte"></div>
    
    <table class="admintable" width="100%">
    	<tr>
        	<th colspan="5" class="encabezadoPrincipal">
            	
                
                <img id="btnExportarPdfReporte" src="<?=base_url()?>img/pdf.png" 	width="22" onclick="reporteUtilidad()" title="PDF" />
				<img id="btnExportarExcelReporte" src="<?=base_url()?>img/excel.png" 	width="22" onclick="excelUtilidad()" title="Excel" />
                <br />
				PDF
                Excel
                <?php
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnExportarPdfReporte\');
						desactivarBotonSistema(\'btnExportarExcelReporte\');
					</script>';
				}
				?>
            </th>
        </tr>
        <tr>
            <th width="15%"><input type="text" class="cajas" id="txtFecha" name="txtFecha" value="<?php echo date('Y-m')?>" onchange="obtenerUtilidad()" /></th>
            <th width="40%">
            	<select class="cajas" id="selectEmisores" name="selectEmisores" style="width:300px" onchange="obtenerUtilidad()">
                	<option value="0">Seleccione emisor</option>
            	<?php
				foreach($emisores as $row)
				{
					echo '<option value="'.$row->idEmisor.'">'.$row->nombre.'</option>';
				}
				?>
                </select>
            </th>
            <th width="15%">Ingreso</th>
            <th width="15%">Gasto</th>
            <th width="15%">Utilidad</th>
        </tr>
    </table>
	<div id="obtenerUtilidad">
    	
    </div>
</div>

</div>

