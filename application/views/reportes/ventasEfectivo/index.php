<script src="<?php echo base_url()?>js/reportes/retiros.js"></script>	
<script>
$(document).ready(function()
{
	obtenerRetiros();
	
	$("#txtMes").monthpicker();
	
});

</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
Retiros
</div>-->
    <table class="toolbar" border="0" width="100%">
        <tr>
        	<td width="70%" align="left" valign="middle" style="border:none";>
                <input value="<?php echo date('Y-m')?>" onchange="obtenerRetiros()" placeholder="Mes" type="text"  name="txtMes" id="txtMes" class="busquedas" style="width:90px;" readonly="readonly"/>
                
                <select class="cajas" id="selectCuentas" name="selectCuentas" style="width:200px" onchange="obtenerRetiros()">
                	<option value="0">Seleccione cuenta</option>
                    
                    <?php
                    foreach($cuentas as $row)
					{
						echo '<option value="'.$row->idCuenta.'">'.$row->cuenta.'('.$row->nombre.')</option>';
					}
					?>
                </select>
                
                <select class="cajas" id="selectEmisores" name="selectEmisores" style="width:400px" onchange="obtenerRetiros()">
                	<option value="0">Seleccione emisor</option>
                    <?php
                    foreach($emisores as $row)
					{
						echo '<option value="'.$row->idEmisor.'">('.$row->rfc.')'.$row->nombre.'</option>';
					}
					?>
                </select>
        	</td>  
        </tr>
    </table> 
</div>       
</div>
       
<div class="listproyectos">
	<div id="generandoReporte"></div>
	<div id="obtenerRetiros"></div>
</div>

</div>

