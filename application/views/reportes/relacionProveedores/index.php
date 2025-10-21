<script src="<?php echo base_url()?>js/reportes/relacionProveedores.js"></script>	

<script>
$(document).ready(function()
{
	obtenerRelacionProveedores();
});

</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
	Relación proveedores
</div>-->
    <table class="toolbar" border="0" width="100%">
        <tr>
        	<td width="70%" align="left" valign="middle" style="border:none";>
                <!--<input value="<?php echo date('Y-m')?>" onchange="obtenerIngresosFacturados()" placeholder="Mes" type="text"  name="txtMes" id="txtMes" class="busquedas" style="width:90px;" readonly="readonly"/>-->
              	<select class="cajas" id="selectAnio" style="width:120px" onchange="obtenerRelacionProveedores()">  
                	<option value="0">Seleccione año</option>
					<?php
                    for($i=2010;$i<=2100;$i++)
                    {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    }
                    ?>
                </select>
                
                <select class="cajas" id="selectEmisores" style="width:350px" onchange="obtenerRelacionProveedores()">  
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
	<div id="obtenerRelacionProveedores"></div>
</div>

</div>

